<?php
    require_once('common.php');
    require_once('../cpss.php');
    require_once('../vendor/autoload.php');
    $specimen_uri = $_GET['specimen_uri'];
    $doc = new EasyRdf_Graph($_GET['rdf_uri']);
    $doc->load();
    
?>
<h2>Parsing RDF</h2>

<h3>CETAF Specimen Preview Profile</h3>
<p>Prefered URI's are listed first followed by any depricated URIs that may contain the required values.</p>
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