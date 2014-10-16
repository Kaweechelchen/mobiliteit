$(function() {
    var timer;
    $('.searchString').keyup(function() {
        clearTimeout(timer);
        var ms = 400;
        var val = this.value;
        timer = setTimeout(function() {

            var request = $.ajax({
                type: 'get',
                url: 'http://www.mobiliteit.lu/hafassuggest.php?q=' + $('.searchString').val(),
                complete: function( response ) {

                    stationsJSON = JSON.parse( response.responseText );

                    var stations = '';

                    $.each(stationsJSON, function(nr, station) {

                        stations += '<a href="/api/1/' + station.extId + '"><div class="col-xs-12 floating-label-form-group "><label>.</label><p>' + station.value + ' <span class="label label-mobiliteit">' + station.extId + '</span></label></p></div></a>';

                    });

                    $('.stations').html( stations );

                    console.log( $('.searchString').val() );

                }

            });

        }, ms);
    });

});

/*!
 * Start Bootstrap - Freelancer Bootstrap Theme (http://startbootstrap.com)
 * Code licensed under the Apache License v2.0.
 * For details, see http://www.apache.org/licenses/LICENSE-2.0.
 */
// Floating label headings for the contact form
$(function() {
    $("body").on("input propertychange", ".floating-label-form-group", function(e) {
        $(this).toggleClass("floating-label-form-group-with-value", !! $(e.target).val());
    }).on("focus", ".floating-label-form-group", function() {
        $(this).addClass("floating-label-form-group-with-focus");
    }).on("blur", ".floating-label-form-group", function() {
        $(this).removeClass("floating-label-form-group-with-focus");
    });
});