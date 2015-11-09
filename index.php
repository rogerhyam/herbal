<?php

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Herbal URI Tester</title>
    <script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="js/main.js"></script>
  </head>
  <body>
    <title>Herbal: CETAF Specimen URI Tester</title>
    
    <form>
      <input id="check-uri-input" type="text" size="100" name="uri" value="http://data.rbge.org.uk/herb/E00421509" />
      <input id="check-now-button" type="button" value="Check Now"/>
    </form>
    
    <div id="uri-format-results"   class="herbal-results" >Loading ... </div>
    <div id="request-html-results" class="herbal-results" >Loading ... </div>
    <div id="request-rdf-results"  class="herbal-results" >Loading ... </div>
    <div id="parse-rdf-results"    class="herbal-results" >Loading ... </div>
    
  </body>
    
  
</html>