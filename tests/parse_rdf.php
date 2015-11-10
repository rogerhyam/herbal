<?php
    require_once('common.php');
    require_once('../vendor/autoload.php');
    
    $specimen_uri = $_GET['specimen_uri'];
    $doc = new EasyRdf_Graph($_GET['rdf_uri']);
    $doc->load();
    
    /*
  $foaf = 
  $foaf->load();
  $me = $foaf->primaryTopic();
  echo "My name is: ".$me->get('foaf:name')."\n"; 
*/
    
?>
<h2>Parsing RDF</h2>

<h3>CETAF Specimen Preview Profile</h3>
<p>This is a tentative list.</p>

<?php

    // GOT TO HERE -- add table of CETAF fields..

    echo "<strong>dc:title</strong> ";
    echo $doc->get($specimen_uri, '<http://purl.org/dc/terms/title>');
    echo '<hr/>';
    echo "<strong>test</strong> ";
    echo $doc->get($specimen_uri, '<http://purl.org/dc/terms/relation>/dc:description');
    
    //var_dump($doc);
?>

<h3>Complete RDF Graph</h3>
<?php
    echo $doc->dump('html');
?> 