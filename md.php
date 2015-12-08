<?php include_once('inc/header.php');  ?>
<div class="herbal-md-wrap">
    <div class="herbal-md-chunk">
<?php
    $Parsedown = new Parsedown();
    $file_path = 'md/' . $_GET['q'] . '.md';
    echo $Parsedown->text(file_get_contents($file_path));
?>
    </div>
</div>
<?php include_once('inc/footer.php'); ?>