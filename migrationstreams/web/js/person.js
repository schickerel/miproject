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
            $('#map-wrapper').empty();
            $('#error-message').hide();
            var personId = $(this).val();
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
        var longitudeStart = 0;
        var latitudeStart = 0;
        var codeStart = "";
        var migrationInfo = [];
        migrationInfo[0] = {};
        var migrationData = {};
        var arcs = [];
        var colorCountries = {};
        $.each(migrations, function(index, migration){
            $.each(countries, function(index, country){
                if(country['Id'] === 7){
                    migrationInfo[0] = {code: country['Code'], longitude: country['Longitude'], latitude: country['Latitude']};
                }
                if(migration['CountryId'] === country['Id']) {
                    migrationInfo.push({code: country['Code'], longitude: country['Longitude'], latitude: country['Latitude']});
                    migrationData[country['Code']] = {year: migration['Year']}
                }
            });
        });
        var map = drawMap(migrationData);

        var index = 0;
        loop();

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
                map.arc(arcs, {strokeWidth: 2, strokeColor: 'rgba(61, 127, 184, 0.9)'});
                colorCountries[migrationInfo[0]['code']] = "#FF4D4D";
                colorCountries[migrationInfo[index + 1]['code']] = "#99796B";
                map.updateChoropleth(colorCountries);
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
                    if ( !data ) return;
                    return ['<div class="hoverinfo"><strong>',
                        'Migration to ' + geography.properties.name,
                        ' in ' + data.year,
                        '</strong></div>'].join('')
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