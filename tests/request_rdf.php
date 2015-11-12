<?php
    require_once('common.php');
?>
<h2>Requesting RDF Format Data</h2>
<?php
    
    $uri = $_GET['uri'];

    // get default curl handle
    $curl = get_curl_handle($uri);
    
    // set other things here
    curl_setopt($curl, CURLOPT_HTTPHEADER, array( "Accept: application/rdf+xml"));
    $response = run_curl_request($curl);
    echo_info("Requesting RDF by passing 'Accept: application/rdf+xml' header.");
    if($response->info['http_code'] != 303){
        echo_error("Unexpected response code: '". $response->info['http_code'] ."'. Expecting 303 Redirect to RDF.");
    }else{
        echo_ok("Recieved 303 Redirect HTTP code.");
        echo_ok("Redirect to URI: <a target=\"_new\" href=\"". $response->info['redirect_url'] ."\">" . $response->info['redirect_url']  . "</a>");
        echo_info("Had 303 redirect so will request RDF data for parsing.");
        $rdfUri = $response->info['redirect_url'];
?>
<script type="text/javascript">
    $('#parse-rdf-results').html('Loading ...');
    $('#parse-rdf-results').show('slow');
    $('#parse-rdf-results').load('tests/parse_rdf.php', 'rdf_uri=' + '<?php echo $rdfUri ?>' + '&specimen_uri=' + '<?php echo $uri ?>' );
</script>
<?php

    } // end got 303 uri
    
?>