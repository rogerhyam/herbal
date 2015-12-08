<?php
    require_once('common.php');
?>

<h2>Checking URI format</h2>

<?php
    
    $uri = @$_GET['uri'];
    $proceed = true;
    if(!$uri){
        echo_error('No URI given.');
        $proceed = false;
    }else{
        
        // append it to the log file
        $log = date(DATE_ATOM) . "\t" .  $_SERVER['REMOTE_ADDR'] . "\t" . $uri . "\n"; 
        file_put_contents('../data/uri.log', $log, FILE_APPEND);
        
        $parts = parse_url($uri);
        
        // we don't support non http uris
        if(!isset($parts['scheme'])){
            echo_error("There is no schema in the URI");
            $proceed = false;
        }elseif(strtolower($parts['scheme']) != 'http'){
            echo_error("The scheme '". $parts['scheme'] ."' is not supported. http only please.");
            $proceed = false;
        }else{
            echo_ok("The scheme '". $parts['scheme'] ."' is correct");
        }
        
        // check it isn't a ip address
        if(!isset($parts['host'])){
            echo_error("There is no host in the URI");
            $proceed = false;
        }elseif(filter_var($parts['host'], FILTER_VALIDATE_IP)){
            echo_error("The host '". $parts['host'] ."' appears to be an IP address. These are not considered persistent. You must use a domain name.");
            $proceed = false;
        }else{
            echo_ok("The host has the domain name of '". $parts['host']  ."'");
        }
        
        // warn if we have no path info. There isn't a requirement to have one but 
        // it is unlikely people would do it all with subdomains.
        if(isset($parts['path'])){
            echo_ok("The path component is '".  $parts['path'] ."'");
        }else{
            echo_warning("The URI lacks a path component. Are you sure this is what you intended?");
        }
        
        // they shouldn't have a query string
        // this stops the use of db queries
        if(isset($parts['query'])){
            echo_error("The URI contains the query string: '". $parts['query'] ."'. This is not permitted.");
            $proceed = false;
        }else{
            echo_ok("The URI lacks a query string component which is a good thing.");
        }
        
        //var_dump($parts);
    }
    
    if($proceed){
        echo_ok("Format of URI appears OK. Continuing test.");
?>
<script type="text/javascript">
    $('#request-html-results').html('Loading ...');
    $('#request-html-results').show('slow');
    $('#request-html-results').load('tests/request_html.php', 'uri=' + '<?php echo $uri ?>' );
</script>
<?php
    }else{
        echo_error("Errors prevent further checking of URI.");
    }
?>