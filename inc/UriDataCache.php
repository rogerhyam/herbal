<?php


/*
    Bunch of functions to store and retrieve data
*/

class UriDataCache{
    
    public $uri = null;
    private $uri_regexes = array();
    private $data = null;
    
    public function __construct($uri){
        
        global $uri_regexes;
        global $mysqli;
        
        $this->uri = $uri;
        $this->uri_regexes = $uri_regexes;
        $this->mysqli = $mysqli;
        
        
    }
    
    public function is_recognised_uri(){
        
        foreach($this->uri_regexes as $regex){
            if(preg_match($regex, $this->uri)) return true;
        }
        
        return false;
    }

    /*
        called from outside 
        will either return stored in db or 
        will fetch it and store it first
    */
    public function get_data(){
        
        // if we have already loaded it return it
        if($this->data) return $data;
        
        // check we have a good uri
        if(!$this->is_recognised_uri()){
          echo "Not recognised";
          return null;  
        } 
        
        // try and load it from the db
        $this->load_data();
        if($this->data) return $this->data;
        
        // try and fetch it
        $this->fetch_data();
        $this->load_data();
        return $this->data; // may still be null if it failed.
        
    }
    
    /*
        Loads data from database
    */
    public function load_data(){
        
        $sql = "SELECT * FROM uri_data WHERE uri = '$this->uri'";
        $result = $this->mysqli->query($sql);
        $this->data = $result->fetch_assoc();
        
        // update the staleness of the record
        $sql = "UPDATE uri_data SET stale = stale + 1 WHERE uri = '$this->uri'";
        $this->mysqli->query($sql);
        
    }
    
    /*
        Fetches data for URI and stores it in the database
    */
    public function fetch_data(){
        
        // initialise it
        $template = array(
              'uri' => $this->uri,
              'html_raw' => '',
              'rdf_raw' => '',
              'words' => '',
              'log' => ''
        );
        
        $template['log'] = 'Start: ' . (new DateTime())->format(DATE_ATOM) ."\n";
        
        $this->fetch_html_data($template);
        $this->fetch_rdf_data($template);
        
       // var_dump($template);
        
        $this->create_index_vals($template);
        $this->save_data($template);
      
    }
    
    private function create_index_vals(&$template){
        
        // make sure we start the words with the actual URI and its parts
        $template['words'] .= $this->uri;
        $template['words'] .= ' ' . str_replace('/', ' ', $this->uri) . ' ';
        
        // add the two data fields
        $template['words'] .= $template['html_raw'] . ' ' . $template['rdf_raw'];
        
        // remove all the html & rdf tags
        $template['words'] = strip_tags($template['words']);

        // convert any entities into real things        
        $template['words'] = html_entity_decode($template['words']);
        
        // remove some of the white space - just to be nice
        $template['words'] = preg_replace( '/\s+/', ' ', $template['words']);

    }
    
    private function save_data($template){
        
        // we refuse to save things that neither have rdf nor html
        // presumably the call failed
        if(!$template['html_raw'] && !$template['rdf_raw']) return;
        
        $stmt = $this->mysqli->prepare("INSERT INTO uri_data 
            (uri,html_raw,rdf_raw, words, log,stale, created)
            VALUES
            (?,?,?,?,?,-1, now())
            ON DUPLICATE KEY UPDATE 
            html_raw = ?, rdf_raw = ?, words = ?, log = ?, stale = -1
            ");
        
        $stmt->bind_param(
            "sssssssss", 
            $this->uri,
            $template['html_raw'],
            $template['rdf_raw'],
            $template['words'],
            $template['log'],
            $template['html_raw'],
            $template['rdf_raw'],
            $template['words'],
            $template['log']
        );

        /* execute query */
        $stmt->execute();
        
        /* close statement */
        $stmt->close();
        
        
    }
    
    private function fetch_html_data(&$template){
        
        $template['log'] .= "\nRequesting HTML by passing 'Accept: text/html' header.\n";
        $template['log'] .= "Calling: ".  $this->uri .".\n";    
        
        $curl = get_curl_handle($this->uri);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array( "Accept: text/html"));
        $response = run_curl_request($curl);
        
        $response_code = $response->info['http_code'];
        $template['log'] .= "Response code: $response_code .\n";
        
        if($response_code == 200){
            $template['html_raw'] = $response->body;
            return;
        }
        
        // follow the redirect to get the data
        if($response_code == 303 || $response_code == 302){
             
             $template['log'] .= "Calling: ".  $response->info['redirect_url'] .".\n";
             
             $curl = get_curl_handle($response->info['redirect_url']);
             curl_setopt($curl, CURLOPT_HTTPHEADER, array( "Accept: text/html"));
             curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); // extra redirects for Paris!
             $response = run_curl_request($curl);
             if($response->info['http_code'] == 200){
                $template['log'] .= "Response code: 200.\n";
                $template['html_raw'] = $response->body;
                return;
             }
             
        }
        
        // not recognised the response code
        $template['log'] .= "Failed to get HTML for uri.\n";

    }
    
    private function fetch_rdf_data(&$template){
        
        $template['log'] .= "\nRequesting RDF by passing 'Accept: application/rdf+xml' header.\n";
        $template['log'] .= "Calling: ".  $this->uri .".\n";    
        
        $curl = get_curl_handle($this->uri);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array( "Accept: application/rdf+xml"));
        $response = run_curl_request($curl);
       
        //var_dump($response);
        
        $response_code = $response->info['http_code'];
        $template['log'] .= "Response code: $response_code .\n";
        
        if($response_code == 200){
            $template['rdf_raw'] = $response->body;
            return;
        }
        
        // follow the redirect to get the data
        if($response_code == 303 || $response_code == 302){
             
             $template['log'] .= "Calling: ".  $response->info['redirect_url'] .".\n";
             
             $curl = get_curl_handle($response->info['redirect_url']);
             curl_setopt($curl, CURLOPT_HTTPHEADER, array( "Accept: application/rdf+xml"));
             curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); // extra redirects for Paris!
             $response = run_curl_request($curl);
             
             //var_dump($response);
             
             if($response->info['http_code'] == 200){
                $template['log'] .= "Response code: 200.\n";
                $template['rdf_raw'] = $response->body;
                return;
             }
             
        }
        
        // not recognised the response code
        $template['log'] .= "Failed to get RDF for uri.\n";

    }
    

} // end class

?>