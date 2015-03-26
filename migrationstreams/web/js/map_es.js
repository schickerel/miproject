$(document).ready(function(){

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

        var map = new Datamap({
            element: document.getElementById('container'),
            fills: {
                defaultFill: "#000000"
            },
            data: countryData,
            geographyConfig: {
                highlightBorderWidth: 1,
                highlightFillColor: function(geography) {
                    for (var countryCode in countryData) {
                        if(countryCode === geography.id) {
                            return '#FC8D59';
                        }
                    }
                },
                highlightBorderColor: function(geography) {
                    for (var countryCode in countryData) {
                        if(countryCode === geography.id) {
                            return 'rgba(250, 15, 160, 0.2)';
                        }
                    }
                }

            },
            done: function(datamap) {
                datamap.svg.selectAll('.datamaps-subunit').on('click', function(geography) {
                    for (var countryCode in countryData) {
                        if(countryCode === geography.id) {
                            $.getJSON("../src/index.php/migration/migrations?countryId=" + countryData[countryCode])
                                .done(function (migrations) {
                                    var country = geography.properties.name;
                                    openDialog(migrations, country);
                                });
                        }
                    }
                });
            }
        });
    };


    var openDialog = function (migrations, countryName) {
        $('#country-chart').html('');
        drawChart(migrations);
        $('#country-chart').dialog({
            title: countryName
        });
    }


    var drawChart = function (migrations) {

        var emigrations = migrations['emigrations'];
        var immigrations = migrations['immigrations'];

        var width = 650;
        var height = 180;
        var margin = 10;
        var barHeight = 150;

        var emigrationsMax = d3.max(emigrations, function(d) { return d.amount; });
        var immigrationsMax = d3.max(immigrations, function(d) { return d.amount; });

        var maxAmount = 0;
        if(emigrationsMax > immigrationsMax) {
            maxAmount = emigrationsMax;
        } else {
            maxAmount = immigrationsMax;
        }

        var domain = emigrations.map(function(d) {return d.year}).concat(immigrations.map(function(d) {return d.year}));
        for(var i=0; i<domain .length; ++i) {
            for(var j=i+1; j<domain .length; ++j) {
                if(domain [i] === domain [j])
                    domain .splice(j--, 1);
            }
        }

        var x = d3.scale.ordinal().rangeRoundBands([0, width], .05);
        var y = d3.scale.linear().range([barHeight, 0]);

        var xAxis = d3.svg.axis()
            .scale(x)
            .outerTickSize(0)
            .orient("bottom");

        var svg = d3.select("#country-chart")
            .append("svg")
            .attr("width", width)
            .attr("height", height);

        x.domain(domain.map(function(d) {return d}).sort());
        y.domain([0, maxAmount]);

        svg.append("g")
            .attr("class", "axis")
            .attr("transform", "translate(0," + (barHeight) + ")")
            .call(xAxis);

        svg.selectAll("rect.emigrations")
            .data(emigrations)
            .enter()
            .append("rect")
            .attr("class", "emigrations")
            .attr("x", function(d) { return x(d.year) + margin;})
            .attr("width", x.rangeBand() / 2 - margin)
            .attr("y",  function(d) { return y(d.amount);})
            .attr("height", function(d) { return barHeight - y(d.amount);}) ;

        svg.selectAll("rect.immigrations")
            .data(immigrations)
            .enter()
            .append("rect")
            .attr("class", "immigrations")
            .attr("x", function(d) { return x(d.year) + (x.rangeBand()/2);})
            .attr("width", (x.rangeBand() / 2) - margin)
            .attr("y",  function(d) { return y(d.amount); })
            .attr("height", function(d) { return barHeight - y(d.amount);}) ;

    };
});
