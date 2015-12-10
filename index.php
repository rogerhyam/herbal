<?php include_once('inc/header.php'); ?>

<div class="herbal-tester-wrap">
    
    <h1>CETAF Specimen URI Tester</h1>
    
    <p>Enter a URI in the box below and click 'Check Now' or <a href="index.php?uri=http://data.rbge.org.uk/herb/E00421509">click for example</a>.</p>
    
    <p>
      <form>
        <input id="check-uri-input" type="text" size="100" name="uri" value="" />
        <input id="check-now-button" type="button" value="Check Now"/>
      </form>
    </p>

    <hr/>
    
    <div id="uri-format-results"   class="herbal-results" >Loading ... </div>
    <div id="request-html-results" class="herbal-results" >Loading ... </div>
    <div id="request-rdf-results"  class="herbal-results" >Loading ... </div>
    <div id="parse-rdf-results"    class="herbal-results" >Loading ... </div>

</div>
    
<?php include_once('inc/footer.php'); ?>