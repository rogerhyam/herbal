<?php
	
// emails a summary of last 24 hrs for each watch

error_reporting(E_ALL);
ini_set("display_errors", 1);
date_default_timezone_set('UTC');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once('../vendor/autoload.php');

// get a list of the watches 
$watch_files = glob('conf/*.txt');

foreach($watch_files as $file){
	$lines = file($file);	
	$email = trim(array_shift($lines));
	send_report($email);
}

function send_report($email){
	
	include_once('../config.php');
		
	// get a list of all the calls in the last 24 hrs
	$sql = "SELECT * FROM herbal.monitor WHERE email = '$email' AND `created` > DATE_SUB(now(), INTERVAL 24 hour)";
	$result = $mysqli->query($sql);
	$rows = $result->fetch_all(MYSQLI_ASSOC);
	
	
	$OK = true;
	foreach($rows as $row){
		if($row['html_response_code'] != 303) $OK = false;
		if(!$row['rdf_triplets']) $OK = false;
	}
	
	if($OK){
		$subject = "OK: CETAF IDs Called " . count($rows) . " times without errors.";
	}else{
		$subject = "FAULTS: CETAF IDs Called " . count($rows) . " times, errors were encountered.";
	}
	
	if(count($rows) > 0){
		$body = "<table>";
		$body .= "<tr>";
		foreach(array_keys($rows[0]) as $key){
			$body .= "<td style=\"color:white; background-color:black; padding: 0.5em;\" >$key</td>";
		}
		$body .= "</tr>";
	
		foreach($rows as $row){
		
			if($row['html_response_code'] != 303 || !$row['rdf_triplets']){
				$bgcolour = 'pink'; 
			}else{
				$bgcolour = 'white';
			}
			
			$body .= "<tr>";
			
			foreach($row as $cell => $val){
				$body .= "<td style=\"color:black; background-color:$bgcolour; padding: 0.5em;\" >";
				if(substr($val, 0, 4) === "http"){
					$body .= "<a href=\"$val\">$val</a>";
				}else{
					$body .= $val;
				}
				$body .= "</td>";
			}
			
			$body .= "</tr>";
		}
	
	
		$body .= "</table>";
		
		
	}else{
		$body .= "<p>No IDs called</p>";
	}
	
	$body .= "<p>To make changes to this service please reply to the email with your request.</p>";
	

	// send the email

	
	//echo "<h1>$subject</h1>";
	//echo $body;
	

	$mail = new PHPMailer;
	
//	$mail->SMTPDebug = 3;                               

	//Set PHPMailer to use SMTP.
	$mail->isSMTP();            
	//Set SMTP host name                          	
	$mail->Host = $smtp_host;
	//Set this to true if SMTP host requires authentication to send email
	$mail->SMTPAuth = true;                          
	//Provide username and password     
	$mail->Username = $smtp_user;                 
	$mail->Password = $smtp_password;         

	//From email address and name
	$mail->From = "r.hyam@rbge.org.uk";
	$mail->FromName = "CETAF ID Monitor";

	//To address and name
	$mail->addAddress($email, "CETAF ID Admin");

	//Address to which recipient will reply
	$mail->addReplyTo("r.hyam@rbge.org.uk", "CETAF ID Monitor");

	//Send HTML or Plain Text email
	$mail->isHTML(true);

	$mail->Subject = $subject;
	$mail->Body = $body;
	$mail->AltBody = print_r($rows, true);

	if(!$mail->send()) 
	{
	    echo "Mailer Error: " . $mail->ErrorInfo;
	} 
	else 
	{
	    echo "Message has been sent successfully";
	}
	
	
	
}	
?>
