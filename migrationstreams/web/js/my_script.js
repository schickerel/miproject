

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
        autoOpen: true,
        draggable: false,
        resizable: false,
        width: 750,
        height: 130,
        open: function(event, ui) {
            $(event.target).parent().css('position', 'fixed');
            $(event.target).parent().css('top', '200px');
            $(event.target).parent().css('left', '10px');
        }
        //dialogClass: "positionDialogStart"
        //position: { my: "top", at: "left", of: $("#test3") }
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

$(function(){
    $("#closeDialog").click(function(event){
        $(".dialog").dialog("close")
        event.preventDefault();
    });
});

// Link to open the dialog
/*$(function(){
    $( "#overview" ).click(function( event ) {
        $( ".dialog2" ).dialog( "open")
        $(".dialog2").css("visibility","visible");

        event.preventDefault();
    });
});*/

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
        $("#dialog-link").css("visibility","visible");
    });
});

$(function(){
    $( "#DistributionRefined" ).click(function(){
        $("#slider2").css("visibility", "visible");
    });
});

$(function(){
    $( "#firstMigrationRefined" ).click(function(){
        $("#slider2").css("visibility", "hidden");
    });
});

$(function(){
    $( "#destinationRefined" ).click(function(){
        $("#slider2").css("visibility", "hidden");
    });
});

$(function(){
$( "#helpButtonStart" ).click(function() {
    //$(".helpDialogStart").fadeIn( "slow");
    $( ".helpDialogStart" ).toggle( "drop" );
});
});

/*$(function(){
$("#helpButtonStart").click (function () {
    if ($(".helpDialogStart").css("visibility")== "hidden") {
        $(".helpDialogStart").fadeIn("slow");
        //$(".helpDialogStart").css("visibility", "visible");
    } else {
        $( ".helpDialogStart" ).toggle( "explode" );
    }
});
});*/

$(function(){
    $("#helpButtonDetail").click(function() {
        $( ".helpDialogDetail" ).toggle( "drop" );
    });
});

$(function(){
    $("#helpButtonPerson").click(function() {
        $( ".helpDialogPerson" ).toggle( "drop" );
    });
});


/*$(function(){
    $("#helpButtonDetail").click (function () {
        if ($(".helpDialogDetail").css("visibility")== "hidden") {
            $(".helpDialogDetail").css("visibility", "visible");
        } else {
            $(".helpDialogDetail").css("visibility", "hidden");
        }
    });
});

$(function(){
    $("#helpButtonPerson").click (function () {
        if ($(".helpDialogPerson").css("visibility")== "hidden") {
            $(".helpDialogPerson").css("visibility", "visible");
        } else {
            $(".helpDialogPerson").css("visibility", "hidden");
        }
    });
});*/

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



