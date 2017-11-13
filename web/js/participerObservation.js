/*Auto-complétion des espèces sur la nouvelle observation */
(function () {
    var options = {
        url_list: $('#url-list').attr('href'),
        url_get: $('#url-get').attr('href'),
        otherOptions: {
            minimumInputLength: 3,
            theme: 'boostrap',
            formatNoMatches: 'aucune référence trouvée.',
            formatSearching: 'Merci de patienter, nous recherchons ...',
            formatInputTooShort: 'Entrez au moins trois caractère'
        }
    };

    $('#observation_oiseau').autocompleter(options);


}());

