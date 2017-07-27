$(function () {
    'use strict';

    // var countriesArray = $.map(countries, function (value, key) { return { value: value, data: key }; });
    // var _token =  token;

    // Initialize ajax autocomplete:
    $('#autocomplete-referral').autocomplete({
        serviceUrl: baseUrl+'/admin/autosuggest/referral',
        //lookup: countriesArray,
        lookupFilter: function(suggestion, originalQuery, queryLowerCase) {
            var re = new RegExp('\\b' + $.Autocomplete.utils.escapeRegExChars(queryLowerCase), 'gi');
            return re.test(suggestion.value);
        },
        onSelect: function(suggestion) {
            $('#selction-ajax').html('You selected: ' + suggestion.value + ', ' + suggestion.data);
        },
        onHint: function (hint) {
            $('#autocomplete-ajax-x').val(hint);
        },
        onInvalidateSelection: function() {
            $('#selction-ajax').html('You selected: none');
        }
    });
});