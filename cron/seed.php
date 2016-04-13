<?php
    
    require_once('config.php');
    require_once('tests/common.php');
    require_once('inc/UriDataCache.php');
    
    var_dump($argv);
    
    if(!isset($argv[1])){
        echo "You must pass an input file consisting of one URI per line\n";
        exit(1);
    }
    
    $lines = file($argv[1]);
    
    $line_count = 0;
    foreach($lines as $uri){
        $uri = trim($uri);
        if($uri){
            echo $line_count . " " . (new DateTime())->format(DATE_ATOM) . $uri . ' ... ';
            $cache = new UriDataCache($uri);
            $cache->fetch_data();
            $cache->load_data();
            echo "done\n";
        }
        $line_count++;
    }
    
?>