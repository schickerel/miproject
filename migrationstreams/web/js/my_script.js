

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
    $( "#slider2" ).slider({
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
    draggable: false,
    resizable: false,
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
    $( ".dialog2" ).dialog({
        autoOpen: false,
        draggable: false,
        resizable: false,
        width: 750,
        height: 130,
        position: { my: "top", at: "left", of: $("#test3") }
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
    });;
});

$(function(){
    $("#closeDialog").click(function(event){
        $(".dialog").dialog("close")
        event.preventDefault();
    });
});

// Link to open the dialog
$(function(){
    $( "#overview" ).click(function( event ) {
        $( ".dialog2" ).dialog( "open")
        $(".dialog2").css("visibility","visible");

        event.preventDefault();
    });
});

$(function(){
    $( ".dialog3" ).dialog({
        autoOpen: false,
        draggable: false,
        resizable: false,
        width: 50,
        height: 600,
        position: { my: "bottom", at: "bottom", of: $(".buttonMenu") }
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
    $( "#filter" ).click(function( event ) {
        $( ".dialog3" ).dialog( "open")

        event.preventDefault();
    });
});

$(function(){
    $( ".selectmenu" ).selectmenu();
});

$(function(){
    $( "#distribution" ).click(function(){
        $("#slider").css("visibility", "visible");
        $("#dialog-link").css("visibility","visible");
        $(".dialog2").css("visibility","visible");
    });
});

$(function(){
    $( "#destination" ).click(function(){
        $("#slider").css("visibility", "hidden");
        $("#dialog-link").css("visibility","visible");
        $(".dialog2").css("visibility","visible");
    });
});

$(function(){
    $( "#overview" ).click(function(){
        $("#slider").css("visibility", "hidden");
        $("#dialog-link").css("visibility","hidden");
    });
});

$(function(){
    $( "#firstMigration" ).click(function(){
        $("#slider").css("visibility", "hidden");
    });
});

$(function(){
    $( "#distributionrefined" ).click(function(){
        $("#slider2").css("visibility", "visible");
    });
});

/*$(function(){
    $( "#help" ).click(function(){
        $(".info").css("visibility", "visible");
    });
});*/

$(document).on('click', '#help', function () {
    if ($(".info").css ("visibility", "hidden")) {
        $('.info').css("visibility", "visible");
    } else {
        $('.info').css("visibility", "hidden");
    }
});

$(function(){
 $( "#help2" ).click(function(){
 $(".info2").css("visibility", "visible");
 });
 });

/*$(function() {
    $( "#button2" ).click(function() {
        $( "#effect" ).toggleClass( "newClass", 1000 );
    });
});*/

/*$(function() {

        $( "#barcontainer" ).css("visibility","hidden");

    });*/

/*
$(function() {
$("#test").click(function() {
    $( ".dialog2" ).effect( "size", {
        to: { width: 1200, height: 80 }
    }, 1000).css("visibility","visible");
});
});

$(function() {
    $("#test2").click(function() {
        $( ".blubb" ).effect( "size", {
            to: { width: 1200, height: 400 }
        }, 1000 );
    });
});*/

/*$(function() {
    $("#test").click(function() {
$("#barcontainer").each(function () { $("svg")[0].setAttribute('viewBox', '0 0 100 100') });
});
});*/


/*$(document).ready(function(){
    $("#test").click(function(){
        $("#barcontainer").slideUp(1000);
    });
    $("#test2").click(function(){
        $("#barcontainer").slideDown(1000);
    });
});*/



