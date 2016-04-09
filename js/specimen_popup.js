

var specimenPop = {}; // let's have a namespace object

$( document ).ready(function() {
    // add a listener to the anchors
    $("a.cetaf-specimen-link").hover( specimenPop.linkMouseIn, specimenPop.linkMouseOut );
    $('#cetaf-specimen-link-pop').hover(specimenPop.divMouseIn, specimenPop.divMouseOut );
});

specimenPop.linkMouseIn = function(){
   console.log("Mouse In");
   console.log($(this).attr('href'));
   
   $('#cetaf-specimen-link-pop').html('Loading ... ');
   $('#cetaf-specimen-link-pop').load('render.php?uri=' + $(this).attr('href') );

   $('#cetaf-specimen-link-pop').show('slow');

}

specimenPop.linkMouseOut = function(){
    setTimeout(specimenPop.hidePop, 1000);
}

specimenPop.divMouseIn = function(){

}

specimenPop.divMouseOut = function(){
    setTimeout(specimenPop.hidePop, 1000);
}

specimenPop.hidePop = function(){
    // check the mouse isn't over the div or a link before closing
    if (
        $('#cetaf-specimen-link-pop:hover').length == 0
        &&
        $('a.cetaf-specimen-link:hover').length == 0
        )
    {
        $('#cetaf-specimen-link-pop').hide('slow');
    }
}

