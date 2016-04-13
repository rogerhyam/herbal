<?php

class SpecimenRenderer{
    
    private $uriCache = null;

    public function __construct($uriCache){
        $this->uriCache = $uriCache;   
    }
    
    public function render(){
            // check it is a recognised URI
        if(!$this->uriCache->is_recognised_uri()){
            echo "The URI supplied is not recognised.";
        }
    
        // get the data from the cache
        $data = $this->uriCache->get_data();
    
        // if we have some then do some nice rendering...
        if(!$data){
            echo "No data available for uri";
            exit();
        }
        
        // wrap the thing is a div to isolate it
        echo "<div class=\"cetaf-specimen-preview\">";
    
        //render the type of data we have
        $rendered = false;
        
        if($data['rdf_raw']){
            $rendered = $this->render_rdf($data['rdf_raw']);
        }
    
        if(!$rendered && $data['html_raw']){
            $rendered = $this->render_html($data['html_raw']);
        }
        
        if(!$rendered){
            echo "Failed to render RDF or HTML";
        }
    
        echo '<p class="cetaf-cache-timestamp">Cached: '. $data['modified']   .' UTC</p>';
    
        echo "</div>"; // end of specimen preview
    
    }
    
    private function render_rdf($body){

        global $iso_countries;

        require_once('vendor/autoload.php');
        
        try{
            $rdf_doc = new EasyRdf_Graph($this->uriCache->uri, $body);
        } catch (Exception $e) {
            return false;
        }
        
        // the RDF may be rubbish 
        if($rdf_doc->countTriples() == 0){
            return false;
        }
        
        // easier to work with it as a php array
        $rdf_array = $rdf_doc->toRdfPhp();
        
        // we can get RDF that isn't about the specimen!
        if(!array_key_exists($this->uriCache->uri, $rdf_array)){
            return false;
        }

        $rdf_array = $rdf_array[$this->uriCache->uri];

        // image - comes first to make floating easy
        if(isset($rdf_array['http://rs.tdwg.org/dwc/terms/associatedMedia'][0]['value'])){
            $image_uri = $rdf_array['http://rs.tdwg.org/dwc/terms/associatedMedia'][0]['value'];
             echo "<a href=\"$image_uri\"><img class=\"cetaf-image\" src=\"$image_uri\" /></a>";
        }
        
        // title
        if(isset($rdf_array['http://purl.org/dc/terms/title'][0]['value'])){
             echo '<p class="cetaf-title"><a href="'. $this->uriCache->uri .'">'. $rdf_array['http://purl.org/dc/terms/title'][0]['value'] . '</a></p>';
        }
        
        echo '<div class="cetaf-taxonomy">';
        
        // scientificName
        if(isset($rdf_array['http://rs.tdwg.org/dwc/terms/scientificName'][0]['value'])){
             echo '<p class="cetaf-scientificName">'. $rdf_array['http://rs.tdwg.org/dwc/terms/scientificName'][0]['value'] . '</p>';
        }
        
        // family
        if(isset($rdf_array['http://rs.tdwg.org/dwc/terms/family'][0]['value'])){
            $family = $rdf_array['http://rs.tdwg.org/dwc/terms/family'][0]['value'];
            $family = ucfirst(strtolower($family));
             echo '<p class="cetaf-family"><a href="https://en.wikipedia.org/wiki/'. $family . '" >'. $family .'</a></p>';
        }
        
        echo '</div>';
        
        echo '<div class="cetaf-collection">';
        
        // recordedBy
        if(isset($rdf_array['http://rs.tdwg.org/dwc/terms/recordedBy'][0]['value'])){
            echo '<p class="cetaf-recordedBy">'. $rdf_array['http://rs.tdwg.org/dwc/terms/recordedBy'][0]['value'] . '</p>';
        }
        
        // recordedBy
        if(isset($rdf_array['http://rs.tdwg.org/dwc/terms/recordNumber'][0]['value'])){
            echo '<p class="cetaf-recordNumber">'. $rdf_array['http://rs.tdwg.org/dwc/terms/recordNumber'][0]['value'] . '</p>';
        }
        
        // recorded date
        if(isset($rdf_array['http://purl.org/dc/terms/created'][0]['value'])){
            echo '<p class="cetaf-created">'. $rdf_array['http://purl.org/dc/terms/created'][0]['value'] . '</p>';
        }
        
        echo '</div>';
        
        echo '<div class="cetaf-geography">';
        
        // country
        if(isset($rdf_array['http://rs.tdwg.org/dwc/terms/countryCode'][0]['value'])){
            
            $country_iso = strtoupper($rdf_array['http://rs.tdwg.org/dwc/terms/countryCode'][0]['value']);
            
            require_once('inc/iso_countries.php');
            
            if(array_key_exists($country_iso, $iso_countries)){
                $country_name = $iso_countries[$country_iso];
                echo "<p class=\"cetaf-country cetaf-country-$country_iso\"><a href=\"https://en.wikipedia.org/wiki/$country_name\">$country_name</a></p>";
            }else{
                $country_name = $country_iso;
                echo "<p class=\"cetaf-country cetaf-country-$country_iso\">$country_iso</p>";
            }
            
        }
        
        // lon lat
        if(
            isset($rdf_array['http://rs.tdwg.org/dwc/terms/decimalLongitude'][0]['value'])
            &&
            isset($rdf_array['http://rs.tdwg.org/dwc/terms/decimalLatitude'][0]['value'])
         
         ){
            $lat = $rdf_array['http://rs.tdwg.org/dwc/terms/decimalLatitude'][0]['value'];
            $lon = $rdf_array['http://rs.tdwg.org/dwc/terms/decimalLongitude'][0]['value'];
            $lat_lon = "$lat,$lon";
             
            echo '<p class="cetaf-lat-lon"><a href="http://maps.google.com?q='. $lat_lon .'">'. $lat_lon . '</a></p>';
        }
        
    
        
        echo '</div>';
        
        echo '<div class="cetaf-meta">';
        
        // source
        if(isset($rdf_array['http://purl.org/dc/terms/publisher'][0]['value'])){
            
            $publisher = $rdf_array['http://purl.org/dc/terms/publisher'][0]['value'];
            
            if(filter_var($publisher, FILTER_VALIDATE_URL)){
                // fixme - is this a known publisher? if so render their logo
                echo "<p class=\"cetaf-publisher\"><a href=\"$publisher\">$publisher</a></p>";
            }else{
                echo '<p class="cetaf-publisher">'. $publisher . '</p>';
            }
            
        }
        
        echo '</div>';
        
        return true;
        
    }
    
    private function render_html($body){
        
        // extract the title
        $res = preg_match("/<title>(.*)<\/title>/siU", $body, $title_matches);
        if (!$res){
            return false;
        }else{
            $title = preg_replace('/\s+/', ' ', $title_matches[1]);
            $title = trim($title);
        } 
        
        echo "<p class=\"cetaf-title\"><a href=\"{$this->uriCache->uri}\">$title</a></p>";
        echo '<p class="cetaf-note">Only HTMl available for this specimen.</p>';
        
        return true;
    }
    
    
    
    
    
}

?>