/**
 * @category  Webkul
 * @package   Webkul_AjaxContactForm
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
 
 /*jshint jquery:true*/
 define([
    "jquery",
    "jquery/ui"
], function ($) {
    "use strict";
    $.widget('ajaxcontactform.autofilladdress', {
        _create: function () {
            var options = this.options;
            var apiKey = options.googleApiKey;
            if (apiKey == '') {
                apiKey = $('#ajaxcontactform_general_appendChild').val();
            }
            $.getScript('https://maps.googleapis.com/maps/api/js?key=AIzaSyD2LWqs1-VHErfBbJ8aXFS5lUfcjmhh8r0&libraries=places', function () {
                var placeSearch, autocomplete;
                autocomplete = new google.maps.places.Autocomplete(
                    /** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
                    {types: ['geocode']}
                );
                autocomplete.addListener('place_changed', fillInAddress);
                $('#autocomplete').val(options.savedAddress);

                function fillInAddress()
                {
                // Get the place details from the autocomplete object.
                    var place = autocomplete.getPlace();
                    $('#ajaxcontactform_general_map_coordinates').val(place.geometry.location.lat()+", "+place.geometry.location.lng());
                }
            });
        }
    });
    return $.ajaxcontactform.autofilladdress;
});
