<?php 
    include_once('inc/header.php'); 
    include_once('config.php');
?>
<div class="herbal-md-wrap">
    <div class="herbal-md-chunk">
<?php
    $Parsedown = new Parsedown();
    $file_path = 'md/' . $_GET['q'] . '.md';
    $html = $Parsedown->text(file_get_contents($file_path));
    
    // add class to those of known collections
    $hrefs = array();
    $offset = 0;
    while(preg_match('/href="(.+)"/', $html, $hrefs, PREG_OFFSET_CAPTURE, $offset)){
        
        foreach($uri_regexes as $regex){
            if(preg_match($regex, $hrefs[1][0])){
                $class = ' class="cetaf-specimen-link" ';
                $html = substr_replace($html, $class, $hrefs[0][1], 0);
                $offset = $hrefs[1][1] + strlen($class) + 1;
                break;
            }else{
                $offset = $hrefs[1][1] + 1;
            }
        }   
        
        
        $hrefs = array();

    }
    
    echo $html;
    

    
?>
    </div>
</div>
<?php include_once('inc/footer.php'); ?>