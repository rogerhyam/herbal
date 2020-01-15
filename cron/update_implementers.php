<?php
	
/*
	This script is run by cron each day and pulls a list of implementers from the
	spreadsheet here
	https://docs.google.com/spreadsheets/d/1vHl2xDghffm6HfQhVeruHV6ZAWAnrc-2LPasq0fOyF4/edit#gid=83189964
	to rebuild the md/implementers.md page

	git test

*/

require_once('../config.php');

require_once('../vendor/autoload.php');
		
// Get the API client and construct the service object.
$client = getClient();
$service = new Google_Service_Sheets($client);

// Prints the names and majors of students in a sample spreadsheet:
// https://docs.google.com/spreadsheets/d/1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms/edit
// $spreadsheetId = '1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms'; // test sheet 

$spreadsheetId = '1vHl2xDghffm6HfQhVeruHV6ZAWAnrc-2LPasq0fOyF4'; // cetaf sheet

$range = 'CETAF ID Registry!A1:K';
$response = $service->spreadsheets_values->get($spreadsheetId, $range);
$values = $response->getValues();

$out = get_header_text();

if (empty($values)) {
    print "No data found.\n";
} else {
	$headers = $values[0];
    for($r = 1; $r < count($values); $r++) {
		$row = $values[$r];
		$out .= "## " . $row[0] . "\n";
		for ($c=1; $c < count($row); $c++) {
			if(strlen($row[$c]) < 1) continue;
			$out .= "* __" . str_replace('_', ' ', $headers[$c]) . ":__ ";
			
			// if the value is a URL make it a link
			if (filter_var($row[$c], FILTER_VALIDATE_URL)) {
				$out .= '[' . $row[$c] .'](' . $row[$c] . ')';
			} else {
			    $out .= $row[$c];
			}
			
			$out .=  "\n";
		}
		
		// finish entry
		$out .= "\n";
		
		print_r($row);
    }
}

$out .= get_footer_text();

file_put_contents('../md/implementers.md', $out);

echo $out;


function getClient()
{
    $client = new Google_Client();
    $client->setApplicationName('Google Sheets API PHP Quickstart');
    $client->setScopes(Google_Service_Sheets::SPREADSHEETS_READONLY);
    $client->setAuthConfig('../../herbal_google_client.json');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');

    // Load previously authorized token from a file, if it exists.
    // The file token.json stores the user's access and refresh tokens, and is
    // created automatically when the authorization flow completes for the first
    // time.
    $tokenPath = 'token.json';
    if (file_exists($tokenPath)) {
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);
    }

    // If there is no previous token or it's expired.
    if ($client->isAccessTokenExpired()) {
        // Refresh the token if possible, else fetch a new one.
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            printf("Open the following link in your browser:\n%s\n", $authUrl);
            print 'Enter verification code: ';
            $authCode = trim(fgets(STDIN));

            // Exchange authorization code for an access token.
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
            $client->setAccessToken($accessToken);

            // Check to see if there was an error.
            if (array_key_exists('error', $accessToken)) {
                throw new Exception(join(', ', $accessToken));
            }
        }
        // Save the token to a file.
        if (!file_exists(dirname($tokenPath))) {
            mkdir(dirname($tokenPath), 0700, true);
        }
        file_put_contents($tokenPath, json_encode($client->getAccessToken()));
    }
    return $client;
}

function get_header_text(){

	return "# Implementers

Any natural history collection is free to implement persistant semantic web compatible
URIs for its specimens so it can be difficult to keep track of who has implemented them and 
to what level they are implemented. Below is a list of some we know about that 
may be useful to look at. Some of these may have been created totally separately
from any of the things mentioned in the
<a href=\"md.php?q=documentation\">documentation</a>.

For a list of institutions who are participating in the CETAF Stable Identifiers initiative 
[see the list on the CETAF website](http://cetaf.org/cetaf-stable-identifiers).

The list below is not intended to be exhaustive but if an institution is missing 
and you would like it to be included please [get in contact](/md.php?q=contact)
so we can add it.\n\n";
	
	
}

function get_footer_text(){
	
	$out = "The data on this page is updated approximately hourly from a [collaboratively maintained spreadsheet](https://docs.google.com/spreadsheets/d/1vHl2xDghffm6HfQhVeruHV6ZAWAnrc-2LPasq0fOyF4).
If it hasn't updated or looks mangled please [contact Roger Hyam](/md.php?q=contact) and ask him to fix it.\n\n";

	$out .= "Last synchronised: " . date("Y/m/d - h:i e");
	
	$out .= "\n\n";

	return $out;
}
?>