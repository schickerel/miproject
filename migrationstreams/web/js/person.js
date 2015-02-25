$(document).ready(function(){
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
                    $('#person-list').append("<li>" + person['FirstName'] + " " + person['LastName'] + "</li>");
                });
            });
    };

});