$(document).ready(function(){

    $( ".button" ).button();

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

    $( "#spinner" ).spinner();

    $( "#accordion" ).accordion();

    $( ".dialogDetail" ).dialog({
        autoOpen: false,
        draggable: false,
        resizable: false,
        width: 1000,
        height: 650
    });

    $( "#dialogDetail" ).click(function( event ) {
        $( ".dialogDetail" ).dialog( "open")
        event.preventDefault();
    });

    $( ".dialogStart" ).dialog({
        autoOpen: true,
        draggable: false,
        resizable: false,
        width: 750,
        height: 130,
        open: function(event, ui) {
            $(event.target).parent().css('top', '175px');
            $(event.target).parent().css('left', '30px');
        }
    });

    $("#closeDialog").click(function(event){
        $(".dialogDetail").dialog("close")
        d3.select("#refinedChart").remove()
        event.preventDefault();
    });



    $( ".dialogPerson" ).dialog({
        autoOpen: false,
        draggable: false,
        resizable: false,
        width: 200,
        height: 450,
        open: function(event, ui) {
            $(event.target).parent().css('top', '500px');
            $(event.target).parent().css('left', '20px');
        }
    });

    $( "#filter" ).click(function( event ) {
        $( ".dialogPerson" ).dialog( "open")
        event.preventDefault();
    });

    $( ".selectmenu" ).selectmenu();

    $( "#distribution" ).click(function(){
        $("#slider").css("visibility", "visible");
        $("#dialogDetail").css("visibility","visible");
        $(".dialogStart").css("visibility","visible");
        $("#sliderLabel").css("visibility","visible")
    });

    $( "#destination" ).click(function(){
        $("#slider").css("visibility", "hidden");
        $("#dialogDetail").css("visibility","visible");
        $(".dialogStart").css("visibility","visible");
        $("#sliderLabel").css("visibility","hidden")
    });

    $( "#overview" ).click(function(){
        $("#slider").css("visibility", "hidden");
        $("#dialogDetail").css("visibility","hidden");
        $("#sliderLabel").css("visibility","hidden")
    });

    $( "#firstMigration" ).click(function(){
        $("#slider").css("visibility", "hidden");
        $("#dialogDetail").css("visibility","visible");
        $("#sliderLabel").css("visibility","hidden")
    });

    $( "#DistributionRefined" ).click(function(){
        $("#slider2").css("visibility", "visible");
        $("#sliderLabel2").css("visibility","visible");
    });

    $( "#firstMigrationRefined" ).click(function(){
        $("#slider2").css("visibility", "hidden");
        $("#sliderLabel2").css("visibility","hidden");
    });

    $( "#destinationRefined" ).click(function(){
        $("#slider2").css("visibility", "hidden");
        $("#sliderLabel2").css("visibility","hidden");
    });

    $( "#helpButtonStart" ).click(function() {
        $( ".helpDialogStart" ).toggle( "drop", { direction: "right" });
        $( ".helpDialogStart2" ).toggle( "drop");
    });

    $("#helpButtonDetail").click(function() {
        $( ".helpDialogDetail" ).toggle( "drop" );
    });

    $("#helpButtonPerson").click(function() {
        $( ".helpDialogPerson" ).toggle( "drop" );
    });

});