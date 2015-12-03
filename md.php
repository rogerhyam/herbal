<div class="herbal-md-chunk">
<?php
    include_once('inc/header.php'); 
    $Parsedown = new Parsedown();
    $file_path = 'md/' . $_GET['q'] . '.md';
    echo $Parsedown->text(file_get_contents($file_path));
    include_once('inc/footer.php');
?>
</div>