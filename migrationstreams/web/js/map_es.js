var dataset = [

    {name: 'USA', count: 2000},
    {name: 'AFG', count: 2900}

];

var buildCountryMap = function (dataset) {
    var countryMap = {};
    for(var countries in dataset) {
        var country = dataset[countries];
        countryMap[country['name']] = '#ce2834';
    }
    return countryMap;
};

//get all countries
var countries = Datamap.prototype.worldTopo.objects.world.geometries;
//defining new constructor for datamap
var map = new Datamap({
    element: document.getElementById('container'),
    fills: {
        defaultFill: "#000000"
    }
});

map.updateChoropleth(buildCountryMap(dataset));