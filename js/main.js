$( document ).ready(function() {
    
    $('#check-now-button').on('click', function(evt){
     
       // clear and hide the existing results divs
       $('.herbal-results').hide();
       $('.herbal-results').empty();
       
       // what are they testing
       var uri = $('#check-uri-input').val().trim();
       console.log(uri);
       
       // load the first test - others will cascade.
       $('#uri-format-results').html('Loading ...');
       $('#uri-format-results').show('slow');
       $('#uri-format-results').load('tests/uri_format.php', 'uri=' + uri);
       
    });
    
});