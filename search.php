<?php 
    include_once('inc/header.php'); 
    require_once('config.php');
    require_once('tests/common.php');
    require_once('inc/UriDataCache.php');
    include_once('inc/SpecimenRenderer.php');
?>
<div class="herbal-search-wrap">
    <div class="herbal-search-chunk">

        <h1>Search</h1>
        <form method="GET" action="search.php">
            <input type="text" size="100" value="<?php echo @$_GET['q'] ?>" name="q" />
            <input type="submit"  value="Search" />
            [<a href="https://herbal-rogerhyam-1.c9.io/md.php?q=documentation#search">Help</a>]
        </form>
        <div class="cetaf-search-results">
<?php
         $q = @$_GET['q'];
         
         // if the query matches one of our possible URI's then we force indexing first
         $cache = new UriDataCache(trim($q));
         if($cache->is_recognised_uri()){
             $cache->fetch_data();
             echo "<p>Refreshed data for $q</p>";
         }
         
         
         $stmt = $mysqli->prepare("SELECT uri FROM uri_data WHERE MATCH (words) AGAINST (? IN NATURAL LANGUAGE MODE) ORDER BY MATCH(words) AGAINST(?) DESC LIMIT 30;");
         // printf("Errormessage: %s\n", $this->mysqli->error);
         $stmt->bind_param("ss",  $q, $q);
         $stmt->bind_result($uri);
         $stmt->execute();
         $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
         foreach($rows as $row){
            $cache = new UriDataCache($row['uri']);
            $sr = new SpecimenRenderer($cache);
            $sr->render();
         }
    
?>
        </div>
        <div class="cetaf-search-footer">
            <strong>Cached URI Count: 
<?php

    $result = $mysqli->query("SELECT count(*) as n FROM uri_data;");
    $row = $result->fetch_assoc();
    echo $row['n'];
?>
            </strong>
        </div>
  
    </div>
</div>
<?php include_once('inc/footer.php'); ?>