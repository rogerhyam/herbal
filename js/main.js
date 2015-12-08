
var herbalTest = {}; // let's have a namespace object

$( document ).ready(function() {
    
    console.log("page loaded");
    
    var uri_in_query = herbalTest.getUrlVars()['uri'];
    if(uri_in_query){
        $('#check-uri-input').val(uri_in_query);
        herbalTest.runTest();
    }

    $('#check-now-button').on('click', herbalTest.runTest);
    
});

herbalTest.runTest = function(evt){
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
}

// Read a page's GET URL variables and return them as an associative array.
herbalTest.getUrlVars = function(){
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}