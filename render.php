<?php

/*
    This will:
    
    Look in the DB for this URI.
    
        Checks it is a recognised URI from the regex list
    
        If it exists it will return it and flag the entry for re-caching
    
        If it doesn't exist it:
            
            fetches the URI RDF and HTML and caches it then returns rendering

*/

include_once('config.php');
require_once('tests/common.php');
include_once('inc/UriDataCache.php');

$uri = @$_GET['uri'];

// do nothing if the pass nothing
if(!$uri){
    echo "You need to specify a 'uri' parameter for this script";
    exit;
}

// we will need a cache to interact with
$cache = new UriDataCache($uri);

// check it is a recognised URI
if(!$cache->is_recognised_uri()){
    echo "The URI supplied is not recognised.";
}

// get the data from the cache
$data = $cache->get_data($uri);

// if we have some then do some nice rendering...
if(!$data){
    echo "No data available for $uri";
    exit();
}


//var_dump($data);

// wrap the thing is a div to isolate it
echo "<div class=\"cetaf-specimen-preview\">";

if($data['rdf_raw']){
    
    $body = get_body_from_curl_response($data['rdf_raw']);
    require_once('vendor/autoload.php');
    $rdf_doc = new EasyRdf_Graph($uri, $body);
    
    // easier to work with it as a php array
    $rdf_array = $rdf_doc->toRdfPhp();
    $rdf_array = $rdf_array[$uri];
    
    // image - comes first to make floating easy
    if(isset($rdf_array['http://rs.tdwg.org/dwc/terms/associatedMedia'][0]['value'])){
        $image_uri = $rdf_array['http://rs.tdwg.org/dwc/terms/associatedMedia'][0]['value'];
         echo "<img class=\"cetaf-image\" src=\"$image_uri\" />";
    }
    
    // title
    if(isset($rdf_array['http://purl.org/dc/terms/title'][0]['value'])){
         echo '<p class="cetaf-title">'. $rdf_array['http://purl.org/dc/terms/title'][0]['value'] . '</p>';
    }
    
    echo '<div class="cetaf-taxonomy">';
    
    // scientificName
    if(isset($rdf_array['http://rs.tdwg.org/dwc/terms/scientificName'][0]['value'])){
         echo '<p class="cetaf-scientificName">'. $rdf_array['http://rs.tdwg.org/dwc/terms/scientificName'][0]['value'] . '</p>';
    }
    
    // family
    if(isset($rdf_array['http://rs.tdwg.org/dwc/terms/family'][0]['value'])){
        $family = $rdf_array['http://rs.tdwg.org/dwc/terms/family'][0]['value'];
        $family = ucfirst(strtolower($family));
         echo '<p class="cetaf-family"><a href="https://en.wikipedia.org/wiki/'. $family . '" >'. $family .'</a></p>';
    }
    
    echo '</div>';
    
    echo '<div class="cetaf-collection">';
    
    // recordedBy
    if(isset($rdf_array['http://rs.tdwg.org/dwc/terms/recordedBy'][0]['value'])){
        echo '<p class="cetaf-recordedBy">'. $rdf_array['http://rs.tdwg.org/dwc/terms/recordedBy'][0]['value'] . '</p>';
    }
    
    // recordedBy
    if(isset($rdf_array['http://rs.tdwg.org/dwc/terms/recordNumber'][0]['value'])){
        echo '<p class="cetaf-recordNumber">'. $rdf_array['http://rs.tdwg.org/dwc/terms/recordNumber'][0]['value'] . '</p>';
    }
    
    // recorded date
    if(isset($rdf_array['http://purl.org/dc/terms/created'][0]['value'])){
        echo '<p class="cetaf-created">'. $rdf_array['http://purl.org/dc/terms/created'][0]['value'] . '</p>';
    }
    
    echo '</div>';
    
    echo '<div class="cetaf-geography">';
    
    // country
    if(isset($rdf_array['http://rs.tdwg.org/dwc/terms/countryCode'][0]['value'])){
        
        $country_iso = $rdf_array['http://rs.tdwg.org/dwc/terms/countryCode'][0]['value'];
        
        require_once('inc/iso_countries.php');
        
        if(array_key_exists($country_iso, $iso_countries)){
            $country_name = $iso_countries[$country_iso];
            echo "<p class=\"cetaf-country cetaf-country-$country_iso\"><a href=\"https://en.wikipedia.org/wiki/$country_name\">$country_name</a></p>";
        }else{
            $country_name = $country_iso;
            echo "<p class=\"cetaf-country cetaf-country-$country_iso\">$country_iso</p>";
        }
        
    }
    
    // lon lat
    if(
        isset($rdf_array['http://rs.tdwg.org/dwc/terms/decimalLongitude'][0]['value'])
        &&
        isset($rdf_array['http://rs.tdwg.org/dwc/terms/decimalLatitude'][0]['value'])
     
     ){
        $lat = $rdf_array['http://rs.tdwg.org/dwc/terms/decimalLatitude'][0]['value'];
        $lon = $rdf_array['http://rs.tdwg.org/dwc/terms/decimalLongitude'][0]['value'];
        $lat_lon = "$lat,$lon";
         
        echo '<p class="cetaf-lat-lon"><a href="http://maps.google.com?q='. $lat_lon .'">'. $lat_lon . '</a></p>';
    }
    

    
    echo '</div>';
    
    echo '<div class="cetaf-meta">';
    
    // source
    if(isset($rdf_array['http://purl.org/dc/terms/publisher'][0]['value'])){
        
        $publisher = $rdf_array['http://purl.org/dc/terms/publisher'][0]['value'];
        
        if(filter_var($publisher, FILTER_VALIDATE_URL)){
            // fixme - is this a known publisher? if so render their logo
            echo "<p class=\"cetaf-publisher\"><a href=\"$publisher\">$publisher</a></p>";
        }else{
            echo '<p class="cetaf-publisher">'. $publisher . '</p>';
        }
        
    }
    
    echo '<p class="cetaf-cache-timestamp">Cached: '. $data['modified']   .' UTC</p>';
    
    echo '</div>';
    
    echo "</div>"; // end of specimen preview

    exit();
}


if($data['html_raw']){
    
    // wrap the thing is a div to isolate it
    echo "<div class=\"cetaf-specimen-preview\">";
    
    $body = get_body_from_curl_response($data['html_raw']);
    
    // extract the title
    $res = preg_match("/<title>(.*)<\/title>/siU", $body, $title_matches);
    if (!$res){
        $title = 'No title';
    }else{
        $title = preg_replace('/\s+/', ' ', $title_matches[1]);
        $title = trim($title);
    } 
    
    echo '<p class="cetaf-title">'. $title . '</p>';
    echo '<p class="cetaf-note">Only HTMl available for this specimen.</p>';
    
    echo '<div class="cetaf-meta">';
    echo '<p class="cetaf-cache-timestamp">Cached: '. $data['modified']   .' UTC</p>';
    echo '</div>';
    
    echo "</div>"; // end of specimen preview
    exit(); 
}


?>