<?php
    require_once('common.php');
    require_once('../cpss.php');
    require_once('../vendor/autoload.php');
    $specimen_uri = $_GET['specimen_uri'];	
	$doc = new EasyRdf\Graph($_GET['rdf_uri']);
    $doc->load($_GET['rdf_uri'],'rdfxml');

	// now then...
	// The specimen_uri is the one passed in.
	// That doesn't mean it is the one the assertions are made about.
	// They may be made about a different version of the URI that is joined to this one by an owl:sameAs.
	$sames = $doc->resourcesMatching('owl:sameAs', $doc->resource($specimen_uri));
	if(count($sames) > 0){
		$specimen_uri = $sames[0]->getUri();
	}
	
?>
<h2>Parsing RDF</h2>

<h3>CETAF Specimen Preview Profile</h3>
<p>Preferred URI's are listed first followed by any deprecated URIs that may contain the required values.</p>
<table>
    <tr>
        <th>CSPP Element</th>
        <th>Mandatory</th>
        <th>RDF resource URI</th>
        <th>Resource Expected</th>
        <th>Value</th>
    </tr>    
<?php
    
    foreach($cpss as $prop){
        
        echo '<tr>';
        echo '<td>' . $prop->display_name . '</td>';
        echo '<td>' . ($prop->required ? 'Yes':'No') . '</td>';

        // work out which uri we will use
        echo '<td>';
        $is_first = true;
        $is_resource = false;
        foreach($prop->qnames as $uri){
            
            if($is_first) $prefered_class = "herbal-prefered-uri";
            else $prefered_class = "herbal-not-prefered-uri";
            
            if($prop->resource_expected){
                $r =  $doc->getResource($specimen_uri, '<' . $uri . '>');
                if($r){
                  $val = $r->getUri();
                  $is_resource = true;  
                }else{
                  $val = $doc->getLiteral($specimen_uri, '<' . $uri . '>');
                }
            }else{
                $val = $doc->getLiteral($specimen_uri, '<' . $uri . '>');
            }
            
            
            
            if($val){
                echo '<span class="herbal-used-uri '. $prefered_class .'">' . $uri . "</span><br/>";
                break;
            }else{
                echo '<span class="herbal-not-used-uri '. $prefered_class .'" >' . $uri . "</span><br/>";
                $is_first = false;
                continue;
            }
            
        }
        echo '</td>';
        
        if($val){
            if(
                ($prop->resource_expected && !$is_resource)
                ||
                (!$prop->resource_expected && $is_resource)
            ){
                $is_resource_class = 'herbal-wrong-kind-of-object';
            }else{
                $is_resource_class = 'herbal-right-kind-of-object';
            }
       
        }else{
            $is_resource_class = 'herbal-null-kind-of-object';
        }
        
        echo "<td class=\"$is_resource_class\">";
        echo $prop->resource_expected ? 'True': 'False';
        echo '</td>';
        
        // write out the value
        if($is_first) $val_class = "herbal-value-by-prefered";
        else $val_class = "herbal-value-not-by-prefered";
        
        if($val){
            // make it a link
            if($is_resource) $val = "<a href=\"$val\">$val<a>"; 
            echo '<td class="'. $val_class .'" >'. $val . '</td>';
        }else{
            echo '<td class="herbal-value-not-found" >NOT FOUND</td>';
        }
        
        
        echo '</tr>';
    }
    
?>
</table>



<h3>Complete RDF Graph</h3>
<?php
    echo $doc->dump('html');
?> 