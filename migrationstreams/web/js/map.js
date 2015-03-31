/**
 * Created by chris on 01.03.2015.
 */
$(document).ready(function () {

    var first = true;
    var mainFilter;
    var bars;
    var state;
    var x1;
    var x0;
    var y;
    var legend;
    var myText;
    var firstRefined = true;
    var xScale;
    var yScale;
    var color;
    var colorfunction;
    var colorScale;
    var dataset;
    var refinedDatasets;
    var currentURL;
    var currentLegend;
    var svg;
    var bigSvg;
    var key;
    var map;

    $.getJSON("../src/index.php/country/countries")
        .done(function (countries) {
            buildMap(countries);
        });

    var buildMap = function (countries) {
        var countryData = new Array();
        for (var countryKey in countries) {
            var country = countries[countryKey];
            countryData[country['Code']] = country['Id'];
        }
        map = new Datamap({
            element: document.getElementById('container'),
            fills: {
                defaultFill: "#000000"
            },
            geographyConfig: {
                dataUrl: null,
                hideAntarctica: true,
                borderWidth: 1,
                borderColor: '#FDFDFD',
                popupTemplate: function (geography, data) { //this function should just return a string
                    return '<div class="test"><strong>' + geography.properties.name + '</strong></div>';
                },
                popupOnHover: true,
                highlightOnHover: true,
                highlightFillColor: '#FC8D59',
                highlightBorderColor: 'rgba(250, 15, 160, 0.2)',
                highlightBorderWidth: 5
            },
            done: function (datamap) {
                datamap.svg.selectAll('.datamaps-subunit').on('click', function (geography) {
                    for (var countryCode in countryData) {
                        if (countryCode === geography.id) {
                            $.getJSON("../src/index.php/migration/migrations?countryId=" + countryData[countryCode])
                                .done(function (migrations) {
                                    var country = geography.properties.name;
                                    drawChart(migrations, country);
                                });
                        }
                    }
                });
            }
        });
    }


    var updateMap = function (data) {
        map.updateChoropleth(data);
    };

    $('#overview').click(function () {
        d3.select("#chart")
            .remove();
        d3.select("#barchart")
            .remove();
        first = true;
        getOverview(updateMap);
    });

    $("#slider").slider({
        change: function (event, ui) {
            d3.select("#chart")
                .remove();
            d3.select("#yearchart")
                .remove();
            getDistribution(updateMap, ui.value);
            $("#sliderLabel").text(ui.value);
        }
    });

    $("#slider2").slider({
        change: function (event, ui) {
            d3.select("#chart")
                .remove();
            d3.select("#yearchart")
                .remove();
            firstRefined = true;
            mainFilter = "distributionByCountries";
            d3.select("#refinedChart")
                .remove();
            $("#sliderLabel2").text(ui.value);
            getDistribution(updateMap, ui.value);
            newChart(dataset);
        }
    });

    $('#firstMigration').click(function () {
        d3.select("#chart")
            .remove();
        d3.select("#yearchart")
            .remove();
        getfirstMigrations(updateMap);

    });

    $('#destination').click(function () {
        d3.select("#chart")
            .remove();
        d3.select("#yearchart")
            .remove();
        getDestinations(updateMap);

    });

    $('#firstMigrationRefined').click(function () {
        d3.select("#chart")
            .remove();
        d3.select("#yearchart")
            .remove();
        firstRefined = true;
        d3.select("#refinedChart")
            .remove();
        getfirstMigrations(updateMap);
        mainFilter = "firstMigration";
        newChart(dataset);


    });


    $('#destinationRefined').click(function () {
        d3.select("#chart")
            .remove();
        d3.select("#yearchart")
            .remove();
        firstRefined = true;
        d3.select("#refinedChart")
            .remove();
        getDestinations(updateMap);
        mainFilter = "targetCountryMigration";
        newChart(dataset);
    });

    //remove svg graphic on dialog close
    $('.dialog').bind('dialogclose', function (event) {
        d3.select("#refinedChart")
            .remove();
    });


    $('#selectdenom').selectmenu({
        //construct the URL for the json request
        change: function (event, ui) {

            if (mainFilter == 'distributionByCountries') {
                var year = $('#slider2').slider("value");
                var month = 5;
                currentURL = buildUrl(this.name, this.value, year, month);
            } else {
                currentURL = buildUrl(this.name, this.value);
            }
             currentLegend = $("#selectdenom :selected").text();
             var legend = true;
            if (firstRefined) {
                d3.select("#refinedChart")
                    .remove();
                filter(newChart, currentURL, currentLegend, legend);
                firstRefined = false;
            } else {
                filter(recalculateRefined, currentURL, currentLegend,legend);
            }
        }
    });

    $('#selectprof').selectmenu({
        //construct the URL for the json request
        change: function (event, ui) {
            if (mainFilter == 'distributionByCountries') {
                var year = $('#slider2').slider("value");
                var month = 5;
                currentURL = buildUrl(this.name, this.value, year, month);
            } else {
                currentURL = buildUrl(this.name, this.value);
            }
            //get the selected Text value; used for the diagram's legend
            currentLegend = $("#selectprof :selected").text();
            var legend = true;
            if (firstRefined) {
                d3.select("#refinedChart")
                    .remove();
                filter(newChart, currentURL, currentLegend,legend);
                firstRefined = false;
            } else {
                filter(recalculateRefined, currentURL, currentLegend, legend);
            }
        }
    });

    var getOverview = function (callback) {
        var countryMap = {};
        $.getJSON("../src/index.php/migration/migrations")
            .done(function (json) {
                for (var country in json) {
                    var currentCountry = json[country];
                    countryMap[currentCountry] = '#ce2834';
                }
                callback(countryMap);
            })

        $.getJSON("../src/index.php/migration/migrations?filter=overview")
            .done(function (json) {
                json = json.sort(function (a, b) {
                    return d3.ascending(a.year, b.year);
                });

                calculateYearChart(json);
            });
    };


    function calculateYearChart(json) {

        var width = 600
        var w = 300;
        var h = 110;
        var dataset = json;
        var barsvg = d3.select("#barcontainer")
            .append("svg")
            .attr("id", "yearchart")
            .attr("width", width)
            .attr("height", h);
        xScale = d3.scale.ordinal()
            .domain(d3.range(dataset.length))
            .rangeRoundBands([0, w], 0.1);
        yScale = d3.scale.linear()
            .domain([0, d3.max(dataset, function (d) {
                return parseInt(d.amount);
            })])
            .range([0, h]);
        var newcolorScale = d3.scale.quantize()
            .range(['rgb(255,245,240)', 'rgb(254,224,210)', 'rgb(252,187,161)', 'rgb(252,146,114)', 'rgb(251,106,74)', 'rgb(239,59,44)', 'rgb(203,24,29)', 'rgb(165,15,21)', 'rgb(103,0,13)'])
            .domain([d3.min(dataset, function (d) {
                return parseInt(d.amount);
            }),
                d3.max(dataset, function (d) {
                    return parseInt(d.amount);
                })
            ]);

        barsvg.selectAll("rect")
            .data(dataset)
            .enter()
            .append("rect")
            .attr("x", function (d, i) {
                return xScale(i);
            })
            .attr("y", function (d) {
                return h - yScale(d.amount) - 15;
            })
            .attr("width", xScale.rangeBand())
            .attr("height", function (d) {
                return yScale(d.amount);
            })
            .attr("fill", function (d) {
                return newcolorScale(d.amount);
            });

        barsvg.selectAll("text")
            .data(dataset)
            .enter()
            .append("text")
            .attr("id", "tooltip")
            .attr("x", function (d, i) {
                return xScale(i) + + xScale.rangeBand() / 2;
            })
            .attr("y", function (d, i) {
                return h - 3;
            })
            .text(function (d) {
                return d.year;
            })
            .attr("text-anchor", "middle")
            .attr("font-family", "sans-serif")
            .attr("font-size", "11px")
            .attr("font-weight", "bold")
            .attr("fill", "yellow");

        barsvg.append("text")
            .text("Auswanderung aus dem Deutschen Reich")
            .attr("x",355)
            .attr("y", 10)
            .attr("font-size", "12px")
            .attr("fill", "yellow");

        $.each(dataset, function(index, datum){
            barsvg.append("text")
                .text(datum.year + " " + datum.amount + " Personen" )
                .attr("x",380)
                .attr("y", (index*10) + 20)
                .attr("font-size", "11px")
                .attr("fill", "yellow");
        })
    }

    function getfirstMigrations(callback) {
        $.getJSON("../src/index.php/migration/migrations?filter=firstMigration")
            .done(function (json) {
                json = json.sort(function (a, b) {
                    return d3.ascending(a.country, b.country);
                });
                calculateMap(json, callback)
                key = function (d) {
                    return d.country;
                };
                recalculateBarchart(json);
                mainFilter = "firstMigration";
            });
    }

    function getDestinations(callback) {
        $.getJSON("../src/index.php/migration/migrations?filter=targetCountryMigration")
            .done(function (json) {
                json = json.sort(function (a, b) {
                    return d3.ascending(a.country, b.country);
                });
                calculateMap(json, callback);
                key = function (d) {
                    return d.country;
                };
                recalculateBarchart(json);
                mainFilter = "targetCountryMigration";
            })
    }

    function getDistribution(callback, value) {
        var url = "../src/index.php/migration/migrations?filter=distributionByCountries&";
        var year = value;
        var month = 5;
        url += "year=" + year + "&month=" + month;
        $.getJSON(url)
            .done(function (json) {
                json = json.sort(function (a, b) {
                    return d3.ascending(a.country, b.country);
                });
                calculateMap(json, callback);
                key = function (d) {
                    return d.country;
                };
                recalculateBarchart(json);
                mainFilter = "distributionByCountries";
            });
    }

    function drawChart(migrations, country) {

        var width = 650;
        var height = 110;
        var margin = 0;
        var barHeight = 110;

        var emigrations = migrations['emigrations'];
        var immigrations = migrations['immigrations'];

        var emigrationsMax = d3.max(emigrations, function (d) {
            return d.amount;
        });
        var immigrationsMax = d3.max(immigrations, function (d) {
            return d.amount;
        });
        var maxAmount = 0;
        if (emigrationsMax > immigrationsMax) {
            maxAmount = emigrationsMax;
        } else {
            maxAmount = immigrationsMax;
        }

        var domain = emigrations.map(function (d) {
            return d.year
        }).concat(immigrations.map(function (d) {
            return d.year
        }));
        for (var i = 0; i < domain.length; ++i) {
            for (var j = i + 1; j < domain.length; ++j) {
                if (domain [i] === domain [j])
                    domain.splice(j--, 1);
            }
        }
        var sortedDomain = domain.sort();

        var x = d3.scale.ordinal().rangeRoundBands([0, width], .05);
        var y = d3.scale.linear().range([barHeight, 0]);

        d3.select("#chart")
            .remove();
        d3.select("#barchart")
            .remove();
        d3.select("#yearchart")
            .remove();
        first = true;
        var svg = d3.select("#barcontainer")
            .append("svg")
            .attr("id", "chart")
            .attr("width", width)
            .attr("height", height);

        x.domain(domain.map(function (d) {
            return d
        }).sort());
        y.domain([0, maxAmount]);

        svg.selectAll("rect.emigrations")
            .data(emigrations)
            .enter()
            .append("rect")
            .attr("class", "emigrations")
            .attr("x", function (d) {
                return x(d.year) + margin;
            })
            .attr("width", x.rangeBand() / 2 - margin)
            .attr("y", function (d) {
                return y(d.amount);
            })
            .attr("height", function (d) {
                return barHeight - y(d.amount);
            })
            .style("fill", "white");

        svg.selectAll("rect.immigrations")
            .data(immigrations)
            .enter()
            .append("rect")
            .attr("class", "immigrations")
            .attr("x", function (d) {
                return x(d.year) + (x.rangeBand() / 2);
            })
            .attr("width", (x.rangeBand() / 2) - margin)
            .attr("y", function (d) {
                return y(d.amount);
            })
            .attr("height", function (d) {
                return barHeight - y(d.amount);
            })
            .style("fill", "blue");

        svg.selectAll("text")
            .data(sortedDomain)
            .enter()
            .append("text")
            .text(function (d) {
                return d;
            })
            .style("font-size", "8px")

            .attr("x", function (d) {
                return x(d) + (x.rangeBand() / 2);
            })
            .attr("y", height)
            .style("fill", "red");


        var migrations = ["Immigrations", "Emmigrations"];
        var colors = ["white", "blue"];

        var countryLegend = svg.selectAll(".legend")
            .data(migrations)
            .enter().append("g")
            .attr("class", "legend")
            .attr("transform", function (d, i) {
                return "translate(0,"+ i * 10 +")";
                //
            });

        countryLegend.append("rect")
            .attr("x", 18)
            .attr("y", 22)
            .attr("width", 10)
            .attr("height", 10)
            .style("fill", function(d,i){
                return colors[i];
            });

        countryLegend.append("text")
            .attr("x", 30)
            .attr("y", 26)
            .attr("dy", ".35em")
            .attr("fill", "yellow")
            .attr("font-size", "8px")
            .text(function (d, i) {
                return migrations[i];
            });



    };

    function filter(callback, url, category, legend) {
                $.getJSON(url)
                .done(function (json) {
                    refinedDatasets = [];
                    var refinedCategory = category;
                    json = json.sort(function (a, b) {
                        return d3.ascending(a.country, b.country);
                    });
                    $.each(dataset, function (i) {
                        var refinedDataset = {};
                        refinedDataset.country = dataset[i].country;
                        refinedDataset.totalAmount = dataset[i].amount;
                        for (data in json) {

                            if (dataset[i].country == json[data].country) {
                                refinedDataset[refinedCategory] = json[data].amount;
                            }
                        }
                        refinedDatasets.push(refinedDataset);
                    })
                    $.each(refinedDatasets, function (i) {
                        if (!refinedDatasets[i][refinedCategory]) {
                            refinedDatasets[i][refinedCategory] = 0;
                        }
                    })
                    callback(refinedDatasets, legend);

                })

    };

    function buildUrl(category, number, year, month) {
        var url = "  ../src/index.php/migration/migrations?filter=";
        var and = "";
        if (mainFilter == "distributionByCountries") {
            url += mainFilter + "&" + "year=" + year + "&month=" + month + "&" + category + "=" + number;
        } else {
            url += mainFilter + "&" + category + "=" + number;
        }
        return url;

    }


    var newChart = function (data, legend) {

       var margin = {top: 15, right: 0, bottom: 0, left: 0},
            width = 900 - margin.left - margin.right,
            height = 470 - margin.top - margin.bottom,
            barheight = 370;

        x0 = d3.scale.ordinal()
            .rangeRoundBands([0, width], .1);

        x1 = d3.scale.ordinal();

        y = d3.scale.linear()
            .range([barheight, 0]);

        var color = d3.scale.ordinal()
            .range(["#DFE2DB", "#DE1B1B"]);


        bigSvg = d3.select("#barcontainertwo").append("svg")
            .attr("id", "refinedChart")
            .attr("width", width)
            .attr("height", height)
            .append("g")
            .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

        var catNames = d3.keys(data[0]).filter(function (key) {
            return key !== "country";
        });

        data.forEach(function (d) {
            d.cat = catNames.map(function (name) {
                return {name: name, value: +d[name]};
            });
        });


        x0.domain(data.map(function (d) {
            return d.country;
        }));
        x1.domain(catNames).rangeRoundBands([0, x0.rangeBand()]);
        y.domain([0, d3.max(data, function (d) {
            return d3.max(d.cat, function (d) {
                return d.value;
            });
        })]);

        state = bigSvg.selectAll(".state")
            .data(data)
            .enter().append("g")
            .attr("class", "g")
            .attr("transform", function (d) {
                return "translate(" + x0(d.country) + ",0)";
            });


        bars = state.selectAll("rect")
            .data(function (d) {
                return d.cat;
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
                return barheight - y(d.value);
            })
            .style("fill", function (d) {
                return color(d.name);
            });
        if( legend == true){
            bars.on("mouseover", function(d, i){

                var xPosition = 10;
                var yPosition = 30;

                bigSvg.append("text")
                    .attr("id", "tooltip")
                    .attr("x", xPosition)
                    .attr("y", yPosition)
                    .attr("font-family", "sans-serif")
                    .attr("font-size", "13px")
                    .attr("font-weight", "bold")
                    .attr("fill", "yellow")
                    .text(d.value);

            })
                .on ("mouseout", function(){
                //# steht für IDs
                d3.select("#tooltip").remove();
            });}



        bigSvg.selectAll("text")
            .data(dataset) //Bind data with custom key function
            .enter()
            .append("text")
            .html(function (d) {
                return d.country + " " + d.amount;
            })
            .attr("text-anchor", "middle")
            .attr("x", function (d, i) {
                return x0(d.country) + +x0.rangeBand() / 2;
                ;
            })
            .attr("y", height - 70)
            .attr("font-family", "sans-serif")
            .attr("font-size", "8px")
            .attr("fill", "yellow");

        legend = bigSvg.selectAll(".legend")
            .data(catNames.slice().reverse())
            .enter().append("g")
            .attr("class", "legend")
            .attr("transform", function (d, i) {
                return "translate(" + i * 350 + ",400)";
                //
            });

        legend.append("rect")
            .attr("x", 18)
            .attr("y", 22)
            .attr("width", 18)
            .attr("height", 18)
            .style("fill", color);

        myText = legend.append("text")
            .attr("x", 70)
            .attr("y", 30)
            .attr("dy", ".35em")
            .attr("fill", "yellow")
            .text(function (d) {
                return d;
            });

        // })
    }

    var recalculateRefined = function (data) {

        var margin = {top: 15, right: 0, bottom: 0, left: 0},
            width = 900 - margin.left - margin.right,
            height = 470 - margin.top - margin.bottom,
            barheight = 370;
        x0 = d3.scale.ordinal()
            .rangeRoundBands([0, width], .1);

        x1 = d3.scale.ordinal();

        y = d3.scale.linear()
            .range([barheight, 0]);

        var color = d3.scale.ordinal()
            .range(["#DFE2DB", "#DE1B1B"]);



        var catNames = d3.keys(data[0]).filter(function (key) {
            return key !== "country";
        });

        data.forEach(function (d) {
            d.cat = catNames.map(function (name) {

                return {name: name, value: +d[name]};

            });

        });

        x0.domain(data.map(function (d) {
            return d.country;
        }));
        x1.domain(catNames).rangeRoundBands([0, x0.rangeBand()]);
        y.domain([0, d3.max(data, function (d) {
            return d3.max(d.cat, function (d) {
                return d.value;
            });
        })]);

        state = bigSvg.selectAll(".g")
            .data(data);


        bars = state.selectAll("rect")
            .data(function (d) {
                return d.cat;
            })

        bars.transition().duration(1000)
            .attr("width", x1.rangeBand())
            .attr("x", function (d) {
                return x1(d.name);
            })
            .attr("y", function (d) {
                return y(d.value);
            })
            .attr("height", function (d) {
                return barheight - y(d.value);
            })
            .style("fill", function (d) {
                return color(d.name);
            });

        redrawText()

        function redrawText() {
            myText
                .data(catNames.slice().reverse())
                .transition()
                .duration(1000)
                .text(function (d) {
                    return d;
                });
        }
    }


    function calculateMap(json, callback) {
        var countryMap = {};
        invalidateMap();
        color = d3.scale.quantize()
            .range(['rgb(255,245,240)', 'rgb(254,224,210)', 'rgb(252,187,161)', 'rgb(252,146,114)', 'rgb(251,106,74)', 'rgb(239,59,44)', 'rgb(203,24,29)', 'rgb(165,15,21)', 'rgb(103,0,13)'])
            .domain([d3.min(json, function (d) {
                return parseInt(d.amount);
            }),
                d3.max(json, function (d) {
                    return parseInt(d.amount);
                })
            ]);
        colorfunction = function (value) {
            return color(value);
        };
        for (var country in json) {
            var currentCountry = json[country];
            countryMap[currentCountry['country']] = colorfunction(currentCountry.amount);
        }

        callback(countryMap);

    }


    function invalidateMap() {
        var countryMap = {};
        var countries = Datamap.prototype.worldTopo.objects.world.geometries;

        $.each(countries, function (index) {
            var id = countries[index].id;
            if (id != -99) {
                countryMap[id] = "#000000";
            }
        })

        updateMap(countryMap);
    }

    function recalculateBarchart(json) {
        dataset = json;

        var w = 650;
        var h = 450;

        if (first) {
            svg = d3.select("#barcontainer")
                .append("svg")
                .attr("id", "barchart")
                .attr("width", w)
                .attr("height", h);
            first = false;
        }
        xScale = d3.scale.linear()
            .domain([0, d3.max(dataset, function (d) {
                return d.amount;
            })])
            .range([0, w]);

        yScale = d3.scale.ordinal()
            .domain(d3.range(dataset.length))
            .rangeBands([0, h]);

        colorScale = d3.scale.quantize()
            .range(['rgb(255,245,240)', 'rgb(254,224,210)', 'rgb(252,187,161)', 'rgb(252,146,114)', 'rgb(251,106,74)', 'rgb(239,59,44)', 'rgb(203,24,29)', 'rgb(165,15,21)', 'rgb(103,0,13)'])
            .domain([d3.min(json, function (d) {
                return parseInt(d.amount);
            }),
                d3.max(json, function (d) {
                    return parseInt(d.amount);
                })
            ]);


        var datas = svg.selectAll("rect")
            .data(dataset, key);

        datas.enter()

            .append("rect")
            .attr("x", 50)
            .attr("y", function (d, i) {
                return yScale(i);
            })
            .attr("width", function (d) {
                return xScale(d.amount);
            })
            .attr("height", function (d) {
                return yScale.rangeBand();
            })
            .attr("fill", function (d) {
                return colorScale(d.amount);
            })
            .on("mouseover", function (d) {
                var countryvalue = {};
                countryvalue[d.country] = 'rgb(0, 0, 150)';
                map.updateChoropleth(countryvalue);
            }).on("mouseout", function (d) {
                var countryvalue = {};
                countryvalue[d.country] = colorScale(d.amount)
                map.updateChoropleth(countryvalue);
            });

        datas.transition()
            .duration(1000)
            .attr("x", 50)
            .attr("y", function (d, i) {
                return yScale(i);
            })
            .attr("width", function (d) {
                return xScale(d.amount);
            })
            .attr("height", function (d) {
                return yScale.rangeBand();
            })
            .attr("fill", function (d) {
                return colorScale(d.amount);
            })

        var text = svg.selectAll("text")
            .data(dataset, key)

        text.transition()
            .duration(1000)
            .text(function (d) {

                return d.country + " " + d.amount;
            })
            .attr("text-anchor", "middle")
            .attr("x", 25)
            .attr("y", function (d, i) {
                return yScale(i) + yScale.rangeBand() / 2;
            })
            .attr("font-family", "sans-serif")
            .attr("font-size", "11px")
            .attr("fill", "yellow");



        text.enter()
            .append("text")
            .text(function (d) {
                return key(d) + " " + d.amount;
            })
            .attr("text-anchor", "middle")
            .attr("x", 25)
            .attr("y", function (d, i) {
                return yScale(i) + yScale.rangeBand() / 2;
            })
            .attr("font-family", "sans-serif")
            .attr("font-size", "11px")
            .attr("fill", "yellow");

        text.exit()
            .transition()
            .remove();


        datas.exit()
            .transition()
            .duration(200)
            .remove();

    }
});




