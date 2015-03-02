/**
 * Created by chris on 01.03.2015.
 */
$(document).ready(function(){

    var map = new Datamap({
        element: document.getElementById('container'),
        fills: {
            defaultFill: "#000000"
        }
    });

    var color;

    var colorfunction;

    //assign a specific color Value to a Country by using d3 function
    //maybe different colors for different types of questions
    //see chloropleth article on Wikipedia for mor details
    //needs to be loaded when array is up


    $('#overview').click(function(){
        getOverview(updateMap);

    });

    $('#distribution').click(function(){
        getDistribution(updateMap);
    });

    var getOverview = function (callback){
        var countryCodes = [];
        $.getJSON("../src/index.php/migration/migrations")
            .done(function(json) {
                //console.log(d3.min(json, function(d) { return parseInt(d.amount); }));
                $.each( json, function(index, code ) {
                    var countryCode = { code: code};
                    countryCodes.push(countryCode);
                });
                callback(countryCodes);
            });
    };

    var updateMap = function (data) {
        map.updateChoropleth(data);
    };

    function getDistribution(callback) {
           $.getJSON("../src/index.php/migration/migrations?filter=distributionByCountries&year=1933")
                .done(function (json) {
                    calculateMap(json, callback)
                });
        }

    //function for calculating the color values and assigning it to the map
    function calculateMap(json, callback){
        var countryMap = {};
        color = d3.scale.quantize()
            .range(['rgb(254,240,217)','rgb(253,212,158)','rgb(253,187,132)','rgb(252,141,89)','rgb(227,74,51)','rgb(179,0,0)'])
            .domain([d3.min(json, function(d) { return parseInt(d.amount); }),
                d3.max(json, function(d) { return parseInt(d.amount); })
            ]);
        colorfunction = function (value){
            return color(value);
        }
        for (var country in json) {
            var currentCountry = json[country];
            countryMap[currentCountry['country']] = colorfunction(currentCountry.amount);
        }
        console.log(countryMap);
        callback(countryMap);

    }


        //get all countries
        var countries = Datamap.prototype.worldTopo.objects.world.geometries;
        //defining new constructor for datamap


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
        ], {strokeWidth: 5, arcSharpness: 1.2, animationSpeed: 3400});
    })

