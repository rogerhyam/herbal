<?php

error_reporting(E_ALL & ~E_DEPRECATED);
ini_set("display_errors", 1);
date_default_timezone_set('UTC');

/**
 * Sets up a curl handle with any common params in it
 */
function get_curl_handle($uri){
    $ch = curl_init($uri);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Herbal URI Tester');
    curl_setopt($ch, CURLOPT_HEADER, 1);
    return $ch;
}

/**
 * Run the cURL requests in one place 
 * so we can catch errors etc
 */
function run_curl_request($curl){
   
   $out['response'] = curl_exec($curl);
   
   $out['error'] = curl_errno($curl);

    $out['info'] = curl_getinfo($curl);
   
    if(!$out['error']){
        // no error
        $out['headers'] = get_headers_from_curl_response($out);
        $out['body'] = trim(substr($out['response'], $out['info']["header_size"]));

    }else{
        // we are in error
        $out['error_message'] = curl_error($curl);
    }
    
    // we close it down after it has been run
    curl_close($curl);
    
    return (object)$out;
    
}

/**
 * cURL returns headers as sting so we need to chop them into
 * a useable array - even though the info is in the 
 */
function get_headers_from_curl_response($out){
    
    $headers = array();
    
    // may be multiple header blocks - we want the last
    $headers_block = substr($out['response'], 0, $out['info']["header_size"]-1);
    $blocks = explode("\r\n\r\n", $headers_block);
    $header_text = trim($blocks[count($blocks) -1]);

    foreach (explode("\r\n", $header_text) as $i => $line){
        if ($i === 0){
            $headers['http_code'] = $line;
        }else{
            list ($key, $value) = explode(': ', $line);
            $headers[$key] = $value;
        }
    }

    return $headers;
}

function get_body_from_curl_response($response){
    return trim(substr($response, strpos($response, "\r\n\r\n")));
}


function echo_error($message){
    echo_message($message, 'herbal-error');
}

function echo_warning($message){
    echo_message($message, 'herbal-warning');
}

function echo_ok($message){
    echo_message($message, 'herbal-ok');
}

function echo_info($message){
    echo_message($message, 'herbal-info');
}

function echo_message($message, $class){
    echo '<div class="herbal-message '. $class .'">';
    echo '<span class="herbal-message-icon">&nbsp;</span>';
    echo $message;
    echo '</div>';
}



?>