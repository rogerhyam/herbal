<?php

$uri_regexes = array(
        '/^http:\/\/data.rbge.org.uk\//'
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