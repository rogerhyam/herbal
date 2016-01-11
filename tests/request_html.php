<?php
    require_once('common.php');
?>
<h2>Requesting HTML Format Data</h2>
<?php

    $uri = $_GET['uri'];

    // get default curl handle
    $curl = get_curl_handle($uri);
    
    // set other things here
    curl_setopt($curl, CURLOPT_HTTPHEADER, array( "Accept: text/html"));
    echo_info("Requesting HTML by passing 'Accept: text/html' header.");
    $response = run_curl_request($curl);
    
    // we either got a 303 redirect or we got a 200 or something else!
    $iFrameUri = false;
    $requestRdf = false;
    if($response->info['http_code'] == 303){
        echo_ok("Recieved 303 Redirect HTTP code.");
        echo_ok("Redirect to URI: <a target=\"_new\" href=\"". $response->info['redirect_url'] ."\">" . $response->info['redirect_url']  . "</a>");
        $iFrameUri =  $response->info['redirect_url'];
        $requestRdf = true;
    }elseif($response->info['http_code'] == 302){
        echo_warning("Recieved 302 Redirect HTTP code. This should be a 303 as we are assuming support for HTTP1.1 ~ 302 is so last century :)");
        echo_ok("Redirect to URI: <a target=\"_new\" href=\"". $response->info['redirect_url'] ."\">" . $response->info['redirect_url']  . "</a>");
        $iFrameUri =  $response->info['redirect_url'];
        $requestRdf = true;
    }elseif($response->info['http_code'] == 200){
        echo_ok("Recieved 200 OK HTTP code.");
        $iFrameUri = $uri;
    }elseif($response->info['http_code'] == 404){
        echo_error("Got HTTP response code of 404 Not Found.");
        $iFrameUri = false;
    }else{
        echo_error("Unexpected response code: '". $response->info['http_code'] ."'. Expecting 303 Redirect or 200 OK.");
        $iFrameUri = false;
    }
    
    if($iFrameUri){
        echo "<iframe id=\"herbal-html-response\" src=\"$iFrameUri\"></iframe>";
    }else{
        echo_error("Errors prevent retrieval of HTML.");
    }

    if(!$requestRdf){
        echo_info("No 303 redirect so stopping here.");
    }else{
        echo_info("Had 303 redirect when asking for HTML so will request RDF format.");
?>
<script type="text/javascript">
    $('#request-rdf-results').html('Loading ...');
    $('#request-rdf-results').show('slow');
    $('#request-rdf-results').load('tests/request_rdf.php', 'uri=' + '<?php echo $uri ?>' );
</script>
<?php
    } // end if requestRdf

    // var_dump($response);


?>