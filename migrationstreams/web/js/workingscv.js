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

    var keyValue;

    var colormap;

    var dataset;

    var refinedDataset;

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

    $('#professional').click(function(){
        filterProfessional();
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
                json = json.sort(function (a,b) {return d3.ascending(a.year, b.year); });

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
                json = json.sort(function (a,b) {return d3.ascending(a.country, b.country); });
                calculateMap(json, callback)
                key = function (d) {
                    return d.country;
                };
                if (first){
                    //calculateBarchart(json);
                    dataset = json;
                    first = false;}
                else{
                    recalculateBarchart(json);
                }

            });
    }

    function getDestinations(callback) {
        $.getJSON("../src/index.php/migration/migrations?filter=targetCountryMigration")
            .done(function (json) {
                json = json.sort(function (a,b) {return d3.ascending(a.country, b.country); });
                calculateMap(json, callback);
                drawRefinedChart();
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
                json = json.sort(function (a,b) {return d3.ascending(a.country, b.country); });
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

    //function for creating an new array with both data
    function filterProfessional(){
        $.getJSON("../src/index.php/migration/migrations?filter=firstMigration&denomination=1")
            .done(function (json) {
                var refinedDatasets = [];
                json = json.sort(function (a,b) {return d3.ascending(a.country, b.country); });
                $.each (dataset, function( i){
                    var refinedDataset = {};
                    refinedDataset.country = dataset[i].country;
                    refinedDataset.totalAmount = dataset[i].amount;
                    for (data in json) {
                        //console.log(json[data]);
                        if (dataset[i].country == json[data].country) {
                            refinedDataset.denomAmount = json[data].amount;
                        }
                    }
                    //console.log(refinedDataset);
                    refinedDatasets.push(refinedDataset);
                    key = function (d) {
                        return d.country;
                    };
                })

                console.log(refinedDatasets);
                //calculateGroupedBarchart(regroupedData);
            })


    };

    function drawRefinedChart() {

        //margin values zum Abstand zwischen den zwei den mehreren SVG-Grafiken, die gezeichnet werden;
        //jede Gruppe von Balken ist eine SVG-Grafik!
        var margin = {top: 20, right: 20, bottom: 30, left: 40},
            width = 960 - margin.left - margin.right,
            height = 500 - margin.top - margin.bottom;


        var x0 = d3.scale.ordinal()
            .rangeRoundBands([0, width], .1);

        var x1 = d3.scale.ordinal();

        var y = d3.scale.linear()
            .range([height, 0]);

        var color = d3.scale.ordinal()
            .range(["#98abc5", "#8a89a6", "#7b6888", "#6b486b", "#a05d56", "#d0743c", "#ff8c00"]);


        var svg = d3.select("body").append("svg")
            .attr("width", width)
            .attr("height", height)
            .append("g")
            .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

        d3.csv("pics/testcsv.csv", function (error, data) {

            //get an array of all key values (the ages) in the dataset; States are excluded
            //first line (title line is evaluated)
            var ageNames = d3.keys(data[0]).filter(function (key) {
                return key !== "State";
            });

            console.log(data);
            //map each datarow/Object to an key in the keymap;
            //each data has additional attribute ages; with
            //name and value
            data.forEach(function (d) {
                d.ages = ageNames.map(function (name) {
                    return {name: name, value: +d[name]};
                });
                //console.log(d.ages);
            });


            x0.domain(data.map(function (d) {
                return d.State;
            }));
            x1.domain(ageNames).rangeRoundBands([0, x0.rangeBand()]);
            y.domain([0, d3.max(data, function (d) {
                return d3.max(d.ages, function (d) {
                    return d.value;
                });
            })]);


            //append an group Element for every state, as dataholder for the age data
            var state = svg.selectAll(".state")
                .data(data)
                .enter().append("g")
                .attr("class", "g")
                .attr("transform", function(d) { return "translate(" + x0(d.State) + ",0)"; });


            state.selectAll("rect")
                .data(function (d) {
                    console.log (d.ages);
                    return d.ages;
                })
                .enter().append("rect")
                .attr("width", x1.rangeBand())
                .attr("x", function (d) {
                    return x1(d.name);
                })
                .attr("y", function (d) {
                    return y(d.value);
                })
                .attr("height", function (d) {
                    return height - y(d.value);
                })
                .style("fill", function (d) {
                    return color(d.name);
                });


            var legend = svg.selectAll(".legend")
                .data(ageNames.slice().reverse())
                .enter().append("g")
                .attr("class", "legend")
                .attr("transform", function (d, i) {
                    return "translate(0," + i * 20 + ")";
                });

            legend.append("rect")
                .attr("x", width - 18)
                .attr("width", 18)
                .attr("height", 18)
                .style("fill", color);

            legend.append("text")
                .attr("x", width - 24)
                .attr("y", 9)
                .attr("dy", ".35em")
                .style("text-anchor", "end")
                .text(function (d) {
                    return d;
                });

        })
    }


    /*    var regroup = function regroup(data){
     var dataArray = [];
     var subarrayOne = [];
     var subarrayTwo =[];
     $.each (data, function(index, value){
     var subObject ={};
     var subObjectTwo ={};
     subObject.country = data[index].country
     subObjectTwo.country = data[index].country
     if (data[index].denomAmount){
     subObject.amount = data[index].totalAmount - data[index].denomAmount;
     subObjectTwo.amount = data[index].denomAmount;
     }else{
     subObject.amount = data[index].totalAmount;
     subObjectTwo.amount = 0;
     }
     subarrayOne.push(subObject);
     subarrayTwo.push(subObjectTwo);

     })

     dataArray.push(subarrayOne);
     dataArray.push(subarrayTwo);
     return dataArray
     }*/






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
        dataset = dataset.sort(function (a,b) {return d3.ascending(a.value, b.value); });

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




