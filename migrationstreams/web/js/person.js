$(document).ready(function(){
    var map = new Datamap({
        element: document.getElementById('map-wrapper'),
        geographyConfig: {
            popupOnHover: false,
            highlightOnHover: false
        },
        fills: {
            defaultFill: "#000000"
        }
    });

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
            invalidateMap();
            $('#person-list').removeClass('not-clickable');
            $('#error-message').hide();
            var personId = $(this).val();
            $.getJSON("../src/index.php/migration/migrations?personId=" + personId)
                .done(function (migrations) {
                    getCountries(migrations, showPersonMigrations);
                });
        }
    });

    var invalidateMap = function (){
        var countryMap = {};
        var countries = Datamap.prototype.worldTopo.objects.world.geometries;

        $.each(countries, function(index){
            var id = countries[index].id;
            if (id != -99){
                countryMap[id] = "#000000";
            }
        })
        map.updateChoropleth(countryMap);
    }

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
        var arcs = [];
        var colorCountries = {};
        $.each(migrations, function(index, migration){
            $.each(countries, function(index, country){
                if(country['Id'] === 7){
                    longitudeStart = country['Longitude'];
                    latitudeStart = country['Latitude'];
                    codeStart = country['Code'];
                }
                if(migration['CountryId'] === country['Id']) {
                    migrationInfo.push({code: country['Code'], longitude: country['Longitude'], latitude: country['Latitude'], year: migration['Year']});
                }
            });
        });
        var arc = {
            origin: {
                latitude: latitudeStart,
                longitude: longitudeStart
            },
            destination: {
                latitude: migrationInfo[0]['latitude'],
                longitude: migrationInfo[0]['longitude']
            }
        };
        arcs.push(arc);
        map.arc(arcs,{strokeWidth: 2, strokeColor: 'rgba(61, 127, 184, 0.9)'});
        colorCountries[codeStart] = "#FF4D4D";
        colorCountries[migrationInfo[0]['code']] = "#99796B";
        map.updateChoropleth(colorCountries);

        if(migrationInfo.length > 1) {
            var index = 0;
            loop();
        } else {
            $('#person-list').removeClass('not-clickable');
            isDrawing = false;
        }
        function loop () {
            setTimeout(function () {
                arc = {
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
                colorCountries[migrationInfo[index + 1]['code']] = "#99796B";
                map.updateChoropleth(colorCountries);
                index++;
                if(index < migrationInfo.length - 1) {
                    loop();
                } else if (index === migrationInfo.length - 1) {
                    $('#person-list').removeClass('not-clickable');
                    isDrawing = false;
                }
            }, 3000)
        }
    }
});