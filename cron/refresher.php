<?php
    
    require_once('config.php');
    require_once('tests/common.php');
    require_once('inc/UriDataCache.php');
    
    echo "Hello I'm fresh!\n";

    
    // connect to the db and get a list of most stale
    $sql = "SELECT uri FROM uri_data WHERE stale > 0 ORDER BY stale DESC LIMIT 1000 ";
    
    $response = $mysqli->query($sql);
    
    if($mysqli->error){
      echo $mysqli->error;
      exit(1);
    } 
    
    while($row = $response->fetch_assoc()){
        
        $uri = $row['uri'];
        
        echo (new DateTime())->format(DATE_ATOM) . $uri . ' ... ';
        
        $cache = new UriDataCache($uri);
        $cache->fetch_data();
        $cache->load_data();
        
        echo "done\n";
    
        
    }


?>