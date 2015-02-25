$(document).ready(function(){
    var countries = Datamap.prototype.worldTopo.objects.world.geometries;
    var map = new Datamap({
        element: document.getElementById('container'),
        fills: {
            defaultFill: "#000000"
        }
    });

    $('#overview').click(function(){
        getOverview(updateMap);

    });

    var getOverview = function (callback){
        var countryCodes = [];
        $.getJSON("../src/index.php/migration/migrations")
            .done(function(json) {
                $.each( json, function(index, code ) {
                    var countryCode = { code: code};
                    countryCodes.push(countryCode);
                });
                callback(countryCodes);
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