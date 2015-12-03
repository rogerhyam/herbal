<?php include_once('inc/header.php'); ?>

    <h1>Herbal: CETAF Specimen URI Tester</h1>
    
    <p>Enter a URI in the box below and click 'Check Now'.</p>
    
    <form>
      <input id="check-uri-input" type="text" size="100" name="uri" value="http://data.rbge.org.uk/herb/E00421509" />
      <input id="check-now-button" type="button" value="Check Now"/>
    </form>
    
    <div id="uri-format-results"   class="herbal-results" >Loading ... </div>
    <div id="request-html-results" class="herbal-results" >Loading ... </div>
    <div id="request-rdf-results"  class="herbal-results" >Loading ... </div>
    <div id="parse-rdf-results"    class="herbal-results" >Loading ... </div>
    
    <hr/>
    <a href="http://tdwg.github.io/dwc/terms/guides/rdf/index.htm">TDWG guide to construction of RDF for Darwin Core.</a>
    
<?php include_once('inc/footer.php'); ?>