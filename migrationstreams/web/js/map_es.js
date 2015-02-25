$(document).ready(function(){
    var countries = Datamap.prototype.worldTopo.objects.world.geometries;
    var map = new Datamap({
        element: document.getElementById('container'),
        fills: {
            defaultFill: "#000000"
        }
    });

    $('#overview').click(function(){
        getOverview();

    });

    var getOverview = function(){
        var countryIds = [];
        $.getJSON("http://localhost/miproject/migrationstreams/src/index.php/migration/migrations")
            .done(function(json) {
                $.each( json, function(index, countryId ) {
                    countryIds.push(countryId);
                });
                getCountryCodes(countryIds);
            });
    };

    var getCountryCodes = function(countryIds){
        var countryCodes = [];
        $.getJSON("http://localhost/miproject/migrationstreams/src/index.php/country/countries")
            .done(function(json){
                $.each( json, function(id, country) {
                    for(var i = 0; i < countryIds.length; i++){
                        if(country['Id'] === countryIds[i]){
                            var countryCode = { code: country['Code']};
                            countryCodes.push(countryCode);
                        }
                    }
                });
                updateMap(countryCodes);
            });
    };

    var updateMap = function (data) {
        map.updateChoropleth(buildCountryMap(data));
    };

    var buildCountryMap = function (dataset) {
        var countryMap = {};
        for(var countries in dataset) {
            var country = dataset[countries];
            countryMap[country['code']] = '#ce2834';
        }
        return countryMap;
    };

});