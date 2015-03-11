/**
 * Created by chris on 01.03.2015.
 */
$(document).ready(function(){

    var first = true;

    var map;
    map = new Datamap({
        element: document.getElementById('container'),
        fills: {
            defaultFill: "#000000"
        },
        geographyConfig: {
            dataUrl: null, //if not null, datamaps will fetch the map JSON (currently only supports topojson)
            hideAntarctica: true,
            borderWidth: 1,
            borderColor: '#FDFDFD',
            popupTemplate: function(geography, data) { //this function should just return a string
                return '<div class="test"><strong>' + geography.properties.name + '</strong></div>';
            },
            popupOnHover: true, //disable the popup while hovering
            highlightOnHover: true,
            highlightFillColor: '#FC8D59',
            highlightBorderColor: 'rgba(250, 15, 160, 0.2)',
            highlightBorderWidth: 5
        }
        });



    var xScale = d3.scale.ordinal();

    var yScale = d3.scale.linear();

    var color;

    var colorfunction;

    var colorScale = d3.scale.linear();


    var colormap;

    var dataset;

    var svg;

    var key;

    //assign a specific color Value to a Country by using d3 function
    //maybe different colors for different types of questions
    //see chloropleth article on Wikipedia for mor details
    //needs to be loaded when array is up

    var updateMap = function (data) {
        map.updateChoropleth(data);
    };

    $('#overview').click(function(){
        getOverview(updateMap);

    });

    $('#distribution').click(function(){
        getDistribution(updateMap);
    });

    $('#firstMigration').click(function(){
        getfirstMigrations(updateMap);
    });

    $('#destination').click(function(){
        getDestinations(updateMap);
    });


    var getOverview = function (callback){
        var countryMap = {};
        $.getJSON("../src/index.php/migration/migrations")
            .done(function(json) {
                //console.log(d3.min(json, function(d) { return parseInt(d.amount); }));
                for (var country in json)  {
                    var currentCountry = json[country];
                    countryMap[currentCountry] = '#ce2834';
                }
                callback(countryMap);})
                $.getJSON("../src/index.php/migration/migrations?filter=overview")
                    .done(function(json) {

                key = function (d) {
                    return d.year;
                };
                if (first){
                    calculateBarchart(json);
                    first = false;}
                else{
                    recalculateBarchart(json);
                }

            });
    };

    function getfirstMigrations(callback) {
        $.getJSON("../src/index.php/migration/migrations?filter=firstMigration")
            .done(function (json) {
                calculateMap(json, callback)
                key = function (d) {
                    return d.country;
                };
                if (first){
                calculateBarchart(json);
                first = false;}
                else{
                recalculateBarchart(json);
                }

            });
    }

    function getDestinations(callback) {
        $.getJSON("../src/index.php/migration/migrations?filter=targetCountryMigration")
            .done(function (json) {
                calculateMap(json, callback);
                key = function (d) {
                    return d.country;
                };
                if (first){
                    calculateBarchart(json);
                    first = false;}
                else{
                    recalculateBarchart(json);
                }
            });
    }


    function getDistribution(callback) {
           $.getJSON("../src/index.php/migration/migrations?filter=distributionByCountries&year=1933&month=3")
                .done(function (json) {
                    calculateMap(json, callback);
                   key = function (d) {
                       return d.country;
                   };
                   if (first){
                       calculateBarchart(json);
                       first = false;}
                   else{
                       recalculateBarchart(json);
                   }
                });
        }

    //function for calculating the color values and assigning it to the map
    function calculateMap(json, callback){
        var countryMap ={};
        invalidateMap();
        color = d3.scale.quantize()
            .range(['rgb(255,245,240)','rgb(254,224,210)','rgb(252,187,161)','rgb(252,146,114)','rgb(251,106,74)','rgb(239,59,44)','rgb(203,24,29)','rgb(165,15,21)','rgb(103,0,13)'])
            .domain([d3.min(json, function(d) { return parseInt(d.amount); }),
                d3.max(json, function(d) { return parseInt(d.amount); })
            ]);
        colorfunction = function (value){
            return color(value);
        };
        for (var country in json) {
            var currentCountry = json[country];
            countryMap[currentCountry['country']] = colorfunction(currentCountry.amount);
        }
        console.log(countryMap);
        callback(countryMap);

    }
    //Funktion zur Invalidierung??
    function invalidateMap(){
        var countryMap = {};
        var countries = Datamap.prototype.worldTopo.objects.world.geometries;

        $.each(countries, function(index){
            var id = countries[index].id;
            if (id != -99){
                countryMap[id] = "#000000";
            }
        })

        updateMap(countryMap);
    }

    function calculateBarchart(json) {
        dataset = json;
        var w = 250;
        var h = 150;
        svg = d3.select("#barcontainer")
            .append("svg")
            .attr("width", w)
            .attr("height", h);

        xScale.domain(d3.range(dataset.length))
         .rangeRoundBands([0, w], 0.05);

        yScale.domain([0, d3.max(dataset, function (d) {
                return parseInt(d.amount);
            })])
            .range([0, h]);


        colorScale.domain([0, d3.max(dataset, function (d) {
                return parseInt(d.amount);
            })])
            .range([0, 255]);



        svg.selectAll("rect")
            .data(dataset, key)    //Bind data with custom key function
            .enter()
            .append("rect")
            .attr("x", function (d, i) {
                return xScale(i);
            })
            .attr("y", function (d) {
                return h - yScale(parseInt(d.amount));
            })
            .attr("width", xScale.rangeBand())
            .attr("height", function (d) {
                return yScale(parseInt(d.amount));
            })
            .attr("fill", function (d) {
                return "rgb(0, 0, " + Math.round(colorScale(parseInt(d.amount))) + ")";
            })

            .on("mouseout", function () {
                //# steht für IDs
                //d3.select("#tooltip").remove();
            });
    }
        function recalculateBarchart(json) {
            dataset = json;
            console.log(dataset);
            var w = 250;
            var h = 150;
            xScale.domain(d3.range(dataset.length));

            yScale.domain([0, d3.max(dataset, function (d) {
                return d.amount;
            })]);

            colorScale.domain([0, d3.max(dataset, function (d) {
                return d.amount;
            })]);


            var datas = svg.selectAll("rect")
                .data(dataset, key);

            datas.enter()

                .append("rect")
                .attr("x", function (d, i) {
                    return xScale(i);
                })
                .attr("y", function (d) {
                    return h - yScale(d.amount);
                })
                .attr("width", xScale.rangeBand())
                .attr("height", function (d) {
                    return yScale(d.amount);
                })
                .attr("fill", function (d) {
                    return "rgb(0, 0, " + Math.round(colorScale(d.amount)) + ")";
                })

                .on("mouseout", function () {
                    //# steht für IDs
                    //d3.select("#tooltip").remove()
                });


            //nur die Dinge neu schreiben, die sich durch die neu hinzugekommenen Werte ändern
            datas.transition()
                .duration(1000)
                .attr("x", function (d, i) {
                    return xScale(i);
                })
                .attr("y", function (d) {
                    return h - yScale(d.amount);
                })
                .attr("width", xScale.rangeBand())
                .attr("height", function (d) {
                    return yScale(d.amount);
                })
                .attr("fill", function (d) {
                    return "rgb(0, 0, " + Math.round(colorScale(d.amount)) + ")";
                });


            // .attr("fill", function(d) {
            // return "rgb(0, 0, " + Math.round(colorScale(d.count)) + ")";
            // });


            //. attrTween("d", tweenPie);
            datas.exit()
                .transition()
                .duration(500)
                .remove();

        }});


        //get all countries
       // var countries = Datamap.prototype.worldTopo.objects.world.geometries;
        //defining new constructor for datamap

/*
        map.arc([
            {
                origin: {
                    latitude: 40.639722,
                    longitude: -73.778889
                },
                destination: {
                    latitude: 37.618889,
                    longitude: -122.375
                }
            },
            {
                origin: {
                    latitude: 30.194444,
                    longitude: -97.67
                },
                destination: {
                    latitude: 25.793333,
                    longitude: -80.290556
                },
                options: {
                    strokeWidth: 2,
                    strokeColor: 'rgba(100, 10, 200, 0.4)'
                }
            },
            {
                origin: {
                    latitude: 39.861667,
                    longitude: -104.673056
                },
                destination: {
                    latitude: 35.877778,
                    longitude: -78.7875
                }
            }
        ], {strokeWidth: 5, arcSharpness: 1.2, animationSpeed: 3400});*/


