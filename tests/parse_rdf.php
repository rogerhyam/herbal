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


<?php
    
    echo $doc->get($specimen_uri, '<http://purl.org/dc/terms/title>');
    echo '<hr/>';

    var_dump($doc);
?>