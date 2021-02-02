<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);
date_default_timezone_set('UTC');

// script run hourly to check CETAF identifiers


require_once('../vendor/autoload.php');

// use some of the common functions from the tester
include_once('../config.php');
include_once('../tests/common.php');

// get a list of the watches 
$watch_files = glob('conf/*.txt');

// let's prepare a statement as we will use it each time
$stmt = $mysqli->prepare("INSERT INTO monitor (`email`,`cetaf_id`, `html_response_code`, `html_uri`, `html_uri_response_code`, `rdf_response_code`, `rdf_uri`, `rdf_uri_response_code`, `rdf_triplets` ) VALUES (?,?,?,?,?,?,?,?,?)");

// git test

// test a random id from each watch
foreach($watch_files as $file){
	$lines = file($file);	
	$email = trim(array_shift($lines));
	$id = trim($lines[array_rand($lines, 1)]);
	test_id($email, $id, $stmt);
}

$stmt->close();

/**
*	
*
*/
function test_id($email, $id, $stmt){

	$results = array();
	
	$results['email'] = $email;
	$results['cetaf_id'] = $id;
	
	// call for HTML
	$curl = get_curl_handle($id);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array( "Accept: text/html"));
    $response = run_curl_request($curl);
		
	if($response->error == 0){
	
		$results['html_response_code'] = $response->info['http_code'];

		if($results['html_response_code'] == 303 || $results['html_response_code'] == 302){
			$results['html_uri'] = $response->info['redirect_url'];
			$results['html_uri_response_code'] = get_response_code($response->info['redirect_url']);
				
		}else{
			$results['html_uri'] = '-';
			$results['html_uri_response_code'] = 0;
		}
	
	}else{
		$results['html_response_code'] = $response->error;
		$results['html_uri'] = $response->error_message;
		$results['html_uri_response_code'] = 0;
	}
	

	
	// call for RDF
	$results['rdf_triplets'] = 0;
	$curl = get_curl_handle($id);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array( "Accept: application/rdf+xml" ));
    $response = run_curl_request($curl);
		
	if($response->error == 0){
		
		$results['rdf_response_code'] = $response->info['http_code'];

		if($results['rdf_response_code'] == 303 || $results['rdf_response_code'] == 302){
			
			$results['rdf_uri'] = $response->info['redirect_url'];
			
			// call for the actual RDF
			$curl2 = get_curl_handle($results['rdf_uri']);
			curl_setopt($curl2, CURLOPT_HTTPHEADER, array( "Accept: application/rdf+xml" ));
			curl_setopt($curl2, CURLOPT_FOLLOWLOCATION, true); // extra redirects 
		    $response2 = run_curl_request($curl2);
			$results['rdf_uri_response_code'] = $response2->info['http_code'];
                        
            // if we have a 200 OK for the RDF lets check if it is valid
			if($results['rdf_uri_response_code'] == 200){				
				try{
					$doc = new EasyRdf\Graph($results['rdf_uri']);
					$triplets = $doc->load($results['rdf_uri'],'rdfxml');
					if($triplets){
						$results['rdf_triplets'] = $triplets;
					}
				}catch (Exception $e){
					$results['rdf_triplets'] = 0;
				}
			}
		
		}else{
			$results['rdf_uri'] = '-';
			$results['rdf_uri_response_code'] = 0;
		}
	}else{
		$results['rdf_response_code'] = $response->error;
		$results['rdf_uri'] = $response->error_message;
		$results['rdf_uri_response_code'] = 0;
	}
	
	
	// write the results to the db
	$stmt->bind_param("ssisiisii", 
		$results['email'],
		$results['cetaf_id'],
		$results['html_response_code'],
		$results['html_uri'],
		$results['html_uri_response_code'],
		$results['rdf_response_code'],
		$results['rdf_uri'],
		$results['rdf_uri_response_code'],
		$results['rdf_triplets']
	);
	
	if (!$stmt->execute()) {
	    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	
	print_r($results);
	
}

function get_response_code($uri){
	$curl = get_curl_handle($uri);
    $response = run_curl_request($curl);
	if($response->error == 0){
		return $response->info['http_code'];
	}else{
		return $response['error'];
	}
}


	
	
?>
