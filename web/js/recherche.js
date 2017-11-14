$(function(){

    //Leaflet : bibliothèque Javascript pour afficher les carte interactives
    var mymap = L.map('mapid').setView([48.857482, 2.346372], 5);
    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
        attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
        maxZoom: 18,
        id: 'mapbox.streets',
        accessToken: 'pk.eyJ1IjoiYnJ1bnVzNzUiLCJhIjoiY2o4dHcyOGVvMG45MjJ3b2Jva2lvbmZ3bSJ9.wkXdBZ6uuO5NuuilrT-DaA'

    }).addTo(mymap);

    mymap.scrollWheelZoom.disable();
    mymap.on('click', function() {
        if (mymap.scrollWheelZoom.enabled()) {
            mymap.scrollWheelZoom.disable();
        }
        else {
            mymap.scrollWheelZoom.enable();
        }
    });


    var $orderField = $('select#ordres');
    var $familyField = $('select#familles');
    var $oiseauField = $('#oiseauField');
    var i=0;

    //fonction pour effacer la carte
    $('#clearMap').click(function (e) {
        e.preventDefault();
        location.reload();
    });


    //fonction pour trier les familles (families) par ordre (order)

    var filterOrder = function(){
        $orderField.on('change', function (e) {
            e.preventDefault();

            var order = $orderField.val();

            $('#errorMsg').remove();
            var submit = function(){

                //renvoie un appel ajax
                return $.ajax({
                    type: 'POST',
                    url: rechercheOrdre,

                    data : { 'order': order  }

                }).done(function (response) {
                    //Si c'est un succès, nous nettoyons le Field famille avant d'y ajouter des résultats
                    $('#familles').empty().append('<option></option>');
                    $('#oiseauField').val('');
                    $.each(response.families, function(key, value){
                        var $toAdd="<option value='"+value.famille+"'>"+value.famille+"</option>";
                        $('#familles').append($toAdd);
                    })
                }).fail(function(jqXHR, exception){
                    var msg = '';
                    if (jqXHR.status === 404) {
                        msg = 'Veuillez sélectionner un ordre';
                    } else if (jqXHR.status === 500) {
                        msg = 'Internal Server Error [500].';
                    } else {
                        msg = 'Une erreur s\'est produite. Veuillez réessayer.';
                    }
                    $('#errorMsg').remove();
                    $('form').append('<div id="errorMsg" class="alert alert-warning">'+msg+'</div>');
                })
            };
            submit();
        });
    };
    filterOrder();

   // fonction qui fait exactement la même chose que précédemment, mais pour les oiseaux (trier par famille)
    var filterFamily = function(){
        $familyField.on('change', function (e) {
            e.preventDefault();

            var family = $familyField.val();

            $('#errorMsg').remove();
            var submit = function(){

                //renvoie un appel ajax
                return $.ajax({
                    type: 'POST',
                    url: rechercheFamille,

                    data : { 'family': family }

                }).done(function (response) {
                    $('#oiseaux').empty();
                    $('#oiseauField').val('');
                    var $nomOiseaux = [];
                    $.each(response.oiseaux, function(key, value){
                        if(value.nomVern !== ''){
                            if(jQuery.inArray(value.nomVern, $nomOiseaux) !== -1){
                                console.log('Cet oiseau est déjà dans la liste');
                            } else {
                                $nomOiseaux.push(value.nomVern);
                                $('#oiseaux').append(
                                    '<option class="oiseauOption" value="'+value.nomVern+'"  id="'+value.id+'">'
                                );
                                $('#'+value.id).append(
                                    '<input id="getUrl-'+value.id+'" type="hidden" value="'+value.url+'"/>'
                                )
                            }
                        }
                    });
                }).fail(function(jqXHR, exception){
                    var msg = '';
                    if (jqXHR.status === 404) {
                        msg = 'Veuillez sélectionner une famille';
                    } else if (jqXHR.status === 500) {
                        msg = 'Internal Server Error [500].';
                    } else {
                        msg = 'Une erreur s\'est produite. Veuillez réessayer.';
                    }
                    $('#errorMsg').remove();
                    $('form').append('<div id="errorMsg" class="alert alert-warning">'+msg+'</div>');
                })
            };
            submit();
        });
    };
    filterFamily();

    //fonction pour obtenir toutes les observations pour un oiseau,
    var filterOiseaux = function(){
        $oiseauField.on('change', function(e){
            e.preventDefault();

            //Obtenir l'identifiant "id" de l'oiseau
            var $input = $oiseauField.val();
            var $datalist = $('#oiseaux');
            var $val = $($datalist).find('option[value="'+$input+'"]');
            var $endval = $val.attr('id');
            //Appelez ajax
            var submit = function(){
                //renvoie un appel ajax
                return $.ajax({
                    method: 'POST',

                    url : rechercherOiseau,
                    data : {'oiseauField' : $endval}

                }).done(function(response){
                    $('#errorMsg').remove();
                    $.each(response.observations, function(key, value){
                        var date = new Date(value.date.date);
                        date = (date.getDate() + '/' + (date.getMonth()+1) + '/' + date.getFullYear());
                        if($('#isLogged').val() === "true") {
                            //Obtenir les rôles d'utilisateur
                            var $role = value.user.roles;
                            var $roleString = "";
                            if ($.inArray("ROLE_ADMINISTRATEUR", $role)) {
                                if ($.inArray("ROLE_ORNITHOLOGUE", $role)) {
                                    $roleString = 'Utilisateur';
                                } else {
                                    $roleString = 'Ornithologue';
                                }
                            } else {
                                $roleString = 'Administrateur';
                            }

                            if (i === 0){
                            $('#colMap').append(
                                '<div class="col-md-5 col-xs-12 observationContainer ajaxContainer">' +
                                '<div class="row layer">' +
                                '<div class="sheet">' +
                                '<div class="col-xs-4">' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '<div class="row contain ajax">' +
                                '<div class="col-xs-6">' +
                                '<p class="link"><a href="' + value.oiseau.url + '">Consulter la fiche INPN de l\'oiseau</a></p>' +
                                '</div>' +
                                '</div>' +
                                i++
                            );
                            }

                            var marker = L.marker([value.latitude, value.longitude]).addTo(mymap);
                            marker.bindPopup('<b><a href="../uploads/images/' + value.image.id + '.' + value.image.ext + '" class="thumbnail" target="_blank" title="Ouvrir l\'image dans un nouvel onglet"><img class="imageObservation" src="../uploads/images/' + value.image.id + '.' + value.image.ext + '" alt="' + value.image.alt + '" /></a>' +value.oiseau.nomVern+"<br>"+"observé par "+ value.user.username+"<br>"+value.latitude+", "+value.longitude+"<br>à "+value.ville+"<br> le "+date+"</b>");

                        } else {
                            var circle = L.circle([value.latitude, value.longitude], {
                                color: 'red',
                                fillColor: '#f03',
                                fillOpacity: 0.5,
                                radius: 5000
                                }).addTo(mymap);
                        }

                    });

                    var markers = L.markerClusterGroup();
                    markers.addLayer(L.marker(getRandomLatLng(map)));

                    map.addLayer(markers);
            })
            };
            submit();
        });

    };

    filterOiseaux();

    var champ = Document.getElementById('input');
    if( champ.length < 3 ) {
        window.alert("Saisir 3 caractères minimum");
    }

    //Get gps coordinates from controller to display marker for an untreated observation
    var $latGPS = $('.alert-success_lat').html();
    var $lonGPS = $('.alert-success_lon').html();
    if ($latGPS !== false && $lonGPS !== false){
 //       var marker = L.marker([46.52, 2.43]).addTo(mymap);
    var marker = L.marker([$latGPS, $lonGPS]).addTo(mymap);
    }
});

