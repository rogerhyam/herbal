<?php

/*
    This will:
    
    Look in the DB for this URI.
    
        Checks it is a recognised URI from the regex list
    
        If it exists it will return it and flag the entry for re-caching
    
        If it doesn't exist it:
            
            fetches the URI RDF and HTML and caches it then returns rendering

*/

include_once('config.php');
require_once('tests/common.php');
include_once('inc/UriDataCache.php');
include_once('inc/SpecimenRenderer.php');

$uri = @$_GET['uri'];

// do nothing if the pass nothing
if(!$uri){
    echo "You need to specify a 'uri' parameter for this script";
    exit;
}

// we will need a cache to interact with
$cache = new UriDataCache($uri);
$sr = new SpecimenRenderer($cache);
$sr->render();
    


?>