
/*$(function(){
$("#slider").dateRangeSlider();
});*/

/*$(function() {
    $( "#show-option" ).tooltip({
      show: {
        effect: "slideDown",
        delay: 250

      }
    });
});*/

/*zweite Zeile reicht nur wenn direkt in html-Datei als Skript geschrieben wird, bei auslagerung in js datei muss außen rum nochmal funktion
*/
$(function(){
$( ".selectmenu" ).selectmenu();
});

$(function(){
$( ".button" ).button();
});

$(function(){
$( "#slider" ).slider({
    range: true,
    values: [ 17, 67 ]
});
});

$(function(){
$( "#spinner" ).spinner();
});

$(function(){
$( "#accordion" ).accordion();
});

$(function(){
$( ".dialog" ).dialog({
    autoOpen: false,
    width: 1000,
    height: 700,
    buttons: [
        {
            text: "Ok",
            click: function() {
                $( this ).dialog( "close" );
            }
        },
        {
            text: "Cancel",
            click: function() {
                $( this ).dialog( "close" );
            }
        }
    ]
});
});

// Link to open the dialog
$(function(){
$( "#dialog-link" ).click(function( event ) {
    $( ".dialog" ).dialog( "open")

    event.preventDefault();
});
});
