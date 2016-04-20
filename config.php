<?php

date_default_timezone_set('UTC');

// uri's must match one of these regexes to be handled
// also we will try and add a popup box to them when we render then as links
$uri_regexes = array(
        '/^http:\/\/data.rbge.org.uk\//',
        '/^http:\/\/herbarium.bgbm.org\/object\//',
        '/^http:\/\/coldb.mnhn.fr\/catalognumber\//',
        '/^http:\/\/specimens.kew.org\//',
        '/^http:\/\/www.antweb.org\/specimen\//',
        '/^http:\/\/id.luomus.fi\//',
        '/^http:\/\/purl.oclc.org\/net\/edu.harvard.huh\//',
        '/^http:\/\/mczbase.mcz.harvard.edu\/guid\//',
        '/^http:\/\/coll.mfn-berlin.de\//',
        '/^http:\/\/data.nhm.ac.uk\/object\//',
        '/^http:\/\/data.biodiversitydata.nl\/naturalis\/specimen\//',
        '/^http:\/\/col.smns-bw.org\/object\//',
        '/^http:\/\/id.zfmk.de\//',
        '/^http:\/\/ibot.sav.sk\/herbarium\//'
);

$db_host = 'localhost';
$db_database = 'herbal';
$db_user = 'herbal';
$db_password = 'herbaljuice';

// create and initialise the database connection
$mysqli = new mysqli($db_host, $db_user, $db_password, $db_database);    

// connect to the database
if ($mysqli->connect_error) {
  echo $mysqli->connect_error;
}

if (!$mysqli->set_charset("utf8")) {
  echo printf("Error loading character set utf8: %s\n", $mysqli->error);
}

?>