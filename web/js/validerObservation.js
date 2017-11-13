(function(){

    var $oiseauField = $('#oiseauField');

    //fonction pour effacer
    $('#clear').click(function (e) {
        e.preventDefault();
        location.reload();
    });

    //fonction pour obtenir toutes les observations pour un oiseau
    $oiseauField.on('change', function(e){
        e.preventDefault();
        //Obtenir l'identifiant de l'oiseau
        var $input = $oiseauField.val();
        var $datalist = $('#oiseaux');
        var $val = $($datalist).find('option[value="'+$input+'"]');
        var $endval = $val.attr('id');
        //Appelez ajax
        var submit = function(){
//            var $oiseauFieldUrl = '/recherche/oiseau/attente/'+$endval;
            return $.ajax({
//                url: $oiseauFieldUrl,
                method: 'POST',
                url : rechercherOiseauAValider,
                data : {'oiseauField' : $endval}

            }).done(function(response){
                $('#errorMsg').remove();
                $('#results').empty();
                $.each(response.observations, function(key, value){
                    var date = new Date(value.date.date);
                    date = (date.getDate() + '/' + (date.getMonth()+1) + '/' + date.getFullYear());


                    //obtenir la description
                    var $description = "";
                    if (value.description !== null){
                        $description = "Description donnée : "+value.description;
                    } else {
                        $description = "L'utilisateur n'a pas donné de description pour l'espèce observée."
                    }
                    if(value.image !== null){
                        $('#results').append(
                            '<p id="observationDescription">"'+value.user.username+'" a observé <strong>"'+value.oiseau.nomVern+'"</strong> le '+date+' aux coordonnées suivantes : <a title="Cliquez pour accéder à la carte" href="/recherche/gps/'+value.latitude+'/'+value.longitude+'">'+value.latitude+', '+value.longitude+'</a></p>' +
                            '<p class="link"><a href="value.oiseau.url">Lien vers la fiche INPN</a></p> ' +
                            '<a href="/uploads/images/'+value.image.id+'.'+value.image.ext+'" class="thumbnail" target="_blank" title="Ouvrir l\'image dans un nouvel onglet"><img src="/uploads/imgages/'+value.image.id+'.'+value.image.ext+'" alt="'+value.image.alt+'" height="200"/></a>' +
                            '<p class="description">'+$description+'</p> '
                        );
                    } else {
                        $('#results').append(
                            '<p id="observationDescription">"'+value.user.username+'" a observé <strong>"'+value.oiseau.nomVern+'"</strong> le '+date+' aux coordonnées suivantes : <a title="Cliquez pour accéder à la carte" href="/recherche/gps/'+value.latitude+'/'+value.longitude+'">'+value.latitude+', '+value.longitude+'</a></p>' +
                            '<p class="link"><a href="value.oiseau.url">Lien vers la fiche INPN</a></p> ' +
                            '<p class="imgMsg">L\'utilisateur n\'a pas pris de photo de l\'espèce observée.</p>' +
                            '<p class="description">'+$description+'</p> '
                        );
                    }
                    $('#results').append('<form method="post" action="/admin/valider/observations/confirme/'+value.id+'">' +
                        '<input type="submit" class="btn btn-primary accept" value="Valider et Publier"> ' +
                        '</form>' +
                        '<form method="post" action="/admin/valider/observations/refuse/'+value.id+'">' +
                        '<input type="submit" class="btn btn-danger refuse" value="Supprimer" ' +
                        '</form> ');
                })
            }).fail(function(jqXHR, exception){
                var msg = '';
                if (jqXHR.status === 404) {
                    msg = 'Espèce non présente dans le fichier de l\'INPN. (Base TAXREF)';
                } else if (jqXHR.status === 500) {
                    msg = 'Internal Server Error [500].';
                } else if (jqXHR.status === 422) {
                    msg = 'Cette espèce n\'a pas d\'observation en attente de validation';
                } else {
                    msg = 'Une erreur s\'est produite. Veuillez réessayer.';
                }
                $('#errorMsg').remove();
                $('#clear').after('<div id="errorMsg" class="alert alert-warning">'+msg+'</div>');
            })
        };
        submit();
    });
});