
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
$( ".button" ).button();
});

$(function(){
$( "#slider" ).slider({
    range: false,
    min: 1933,
    max: 1970,
    create: function(event, ui){
        setSliderTicks(event.target);
    },
    change: function(event, ui) {
        alert(ui.value);
    }

});
    function setSliderTicks(el) {
        var $slider = $(el);
        var max = $slider.slider("option", "max");
        var min = $slider.slider("option", "min");
        var spacing = 100 / (max - min);

        $slider.find('.ui-slider-tick-mark').remove();
        for (var i = 0; i < max - min; i++) {
            $('<span class="ui-slider-tick-mark"></span>').css('left', (spacing * i) + '%').appendTo($slider);
        }
    }
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
    height: 600
 /*   buttons: [
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
    ]*/
});
});

// Link to open the dialog
$(function(){
$( "#dialog-link" ).click(function( event ) {
    $( ".dialog" ).dialog( "open")

    event.preventDefault();
});
});

$(function(){
    $( ".selectmenu" ).selectmenu();
});

$(function(){
    $( "#distribution" ).click(function(){
        $("#slider").css("visibility", "visible");
    });
});

$(function(){
    $( "#destination" ).click(function(){
        $("#slider").css("visibility", "hidden");
    });
});

$(function(){
    $( "#overview" ).click(function(){
        $("#slider").css("visibility", "hidden");
    });
});

$(function(){
    $( "#firstMigration" ).click(function(){
        $("#slider").css("visibility", "hidden");
    });
});
