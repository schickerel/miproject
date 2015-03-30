$(document).ready(function(){

    var isDrawing = false;

    $('#filter').click(function(){
        var denominationIds = "";
        var professionalCategoryIds = "";
        var returnMigration = "";

        $('#denomination input').each( function(){
            if($(this).prop('checked')){
                denominationIds += $(this).val() + ";";
            }
        });

        $('#professional-category input').each( function(){
            if($(this).prop('checked')){
                professionalCategoryIds += $(this).val() + ";";
            }
        });

        $('#return-migration input').each( function(){
            if($(this).prop('checked')){
                returnMigration = $(this).val();
            }
        });
        var url = buildUrl(denominationIds, professionalCategoryIds, returnMigration);
        filterPersonList(url);
    });

    var buildUrl = function (denominationIds, professionalCategoryIds, returnMigration) {
        var url = "?";
        var and = "";
        if(denominationIds !== ""){
            url += "denomination=" +denominationIds;
            and = "&";
        }
        if(professionalCategoryIds !== "") {
            url += and + "professionalCategory=" + professionalCategoryIds;
            and = "&";
        }
        if(returnMigration !== "") {
            url += and + "returnMigration=" + returnMigration;
        }
        return url;
    };

    var filterPersonList = function(url) {
        $('#person-list').empty();
        $.getJSON("../src/index.php/person/persons" + url)
            .done(function(json) {
                $.each(json, function(index, person){
                    $('#person-list').append("<li value='" + person['Id'] + "'>" + person['FirstName'] + " " + person['LastName'] + "</li>");
                });
            });
    };

    $('#person-list').on('click', 'li', function(){
        if(isDrawing) {
            $('#person-list').addClass('not-clickable');
        } else {
            $('#person-list').removeClass('not-clickable');
            $('#person-information').empty();
            $('#person-migrations').empty();
            $('#map-wrapper').empty();
            var personId = $(this).val();
            var personName = $(this).html();
            $('#person-information').html("<h2>" + personName + "</h2>");
            $.getJSON("../src/index.php/migration/migrations?personId=" + personId)
                .done(function (migrations) {
                    getCountries(migrations, showPersonMigrations);
                });
        }
    });

    var getCountries = function(migrations, callback){
        $.getJSON("../src/index.php/country/countries")
            .done(function(countries) {
                callback(migrations, countries);
            });
    };

    var showPersonMigrations = function(migrations, countries){
        isDrawing = true;
        var migrationInfo = [];
        migrationInfo[0] = {};
        var migrationData = {};
        var arcs = [];
        var colorCountries = {};
        $.each(migrations, function(index, migration){
            $.each(countries, function(index, country){
                if(country['Id'] === 7){
                    migrationInfo[0] = {code: country['Code'], country: country['Country'], year: migration['Year'], longitude: country['Longitude'], latitude: country['Latitude']};
                }
                if(migration['CountryId'] === country['Id']) {
                    if(country['Code'] in migrationData) {
                        var migrationYears = migrationData[country['Code']];
                        var times =  migrationData[country['Code']].times;
                        times++;
                        migrationYears['times'] = times;
                        migrationYears['year' + times] = migration['Year'];
                        migrationData[country['Code']] = migrationYears;
                        migrationInfo.push({code: country['Code'], country: country['Country'], year: migration['Year'], longitude: country['Longitude'], latitude: country['Latitude'], times: times});
                    } else {
                        migrationData[country['Code']] = {year: migration['Year'], times: 1}
                        migrationInfo.push({code: country['Code'], country: country['Country'], year: migration['Year'], longitude: country['Longitude'], latitude: country['Latitude'], times: 1});
                    }
                }
            });
        });
        var map = drawMap(migrationData);

        var index = 0;
        loop();
        $('#person-migrations').append("<p>Migrationen:</p>")
        function loop () {
            setTimeout(function () {
                var arc = {
                    origin: {
                        latitude: migrationInfo[index]['latitude'],
                        longitude: migrationInfo[index]['longitude']
                    },
                    destination: {
                        latitude: migrationInfo[index + 1]['latitude'],
                        longitude: migrationInfo[index + 1]['longitude']
                    }
                }
                arcs.push(arc);
                map.arc(arcs, {
                    strokeWidth: 2,
                    arcSharpness: migrationInfo[index + 1]['times'],
                    strokeColor: 'rgba(61, 127, 184, 0.9)'}
                );
                colorCountries[migrationInfo[0]['code']] = "#C0392B";

                var color = d3.scale.linear()
                    .domain([1, 5])
                    .range(["#F39C12", "#32025B"]);

                colorCountries[migrationInfo[index + 1]['code']] = color(migrationInfo[index + 1]['times']);
                map.updateChoropleth(colorCountries);
                $('#person-migrations').append("<p>" + migrationInfo[index + 1]['year'] + ": " + migrationInfo[index]['country'] + " &#10137; " + migrationInfo[index+1]['country'] + "</p>");
                index++;
                if(index < migrationInfo.length - 1) {
                    loop();
                } else if (index === migrationInfo.length - 1) {
                    $('#person-list').removeClass('not-clickable');
                    isDrawing = false;
                }
            }, 2000)
        }
    }

    var drawMap = function (migrationData) {
        var map = new Datamap({
            element: document.getElementById('map-wrapper'),
            geographyConfig: {
                highlightOnHover: false,
                popupTemplate: function(geography, data) {
                    if ( !data ) {
                        return;
                    } else {
                        var hoverDiv = ["<div class='hoverinfo'><strong>Migration to " + geography.properties.name + " in " + data['year']];
                        var times = 1;
                        while(data.times > 1 && times !== data.times) {
                            times++;
                            hoverDiv.push("<br/> and in " + data['year' + times]);
                        }
                    }
                    return hoverDiv.join("");
                }
            },
            fills: {
                defaultFill: "#000000"
            },
            data: migrationData
        });
        return map;
    }
});