$(document).ready(function(){
    var map = new Datamap({
        element: document.getElementById('map-wrapper'),
        fills: {
            defaultFill: "#000000"
        }
    });

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
        var personId = $(this).val();
        $.getJSON("../src/index.php/migration/migrations?personId=" + personId)
            .done(function(migrations) {
                getCountries(migrations, showPersonMigrations);
            });
    });

        var getCountries = function(migrations, callback){
        $.getJSON("../src/index.php/country/countries")
            .done(function(countries) {
                callback(migrations, countries);
            });
    };

    var showPersonMigrations = function(migrations, countries){
        var longitudeStart = 0;
        var latitudeStart = 0;
        var longsLats = [];
        var arcs = [];
        $.each(migrations, function(index, migration){
            $.each(countries, function(index, country){
                if(country['Id'] === 7){
                    longitudeStart = country['Longitude'];
                    latitudeStart = country['Latitude'];
                }
                if(migration['CountryId'] === country['Id']) {
                    var longLat = {longitude: country['Longitude'], latitude: country['Latitude']};
                    longsLats.push(longLat);
                }
            });
        });
        var arc = {
            origin: {
                latitude: latitudeStart,
                longitude: longitudeStart
            },
            destination: {
                latitude: longsLats[0]['latitude'],
                longitude: longsLats[0]['longitude']
            }
        };
        arcs.push(arc);
        map.arc(arcs);

        var index = 0;
        loop();

        function loop () {
            setTimeout(function () {
                arc = {
                    origin: {
                        latitude: longsLats[index]['latitude'],
                        longitude: longsLats[index]['longitude']
                    },
                    destination: {
                        latitude: longsLats[index + 1]['latitude'],
                        longitude: longsLats[index + 1]['longitude']
                    }
                }
                arcs.push(arc);
                map.arc(arcs);
                index++;
                if(index < longsLats.length - 1) {
                    loop();
                }
            }, 3000)
        }
    }
});