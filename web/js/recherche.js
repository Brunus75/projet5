$(function(){

    //Leaflet : bibliothèque Javascript pour afficher les carte interactives
    var mymap = L.map('mapid').setView([48.857482, 2.346372], 5);
    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
        attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
        maxZoom: 18,
        id: 'mapbox.streets',
        accessToken: 'pk.eyJ1IjoiYnJ1bnVzNzUiLCJhIjoiY2o4dHcyOGVvMG45MjJ3b2Jva2lvbmZ3bSJ9.wkXdBZ6uuO5NuuilrT-DaA'

 //       https://api.mapbox.com/styles/v1/mapbox/streets-v9/tiles/256/{z}/{x}/{y}?access_token=pk.eyJ1IjoiYnJ1bnVzNzUiLCJhIjoiY2o4dHcyOGVvMG45MjJ3b2Jva2lvbmZ3bSJ9.wkXdBZ6uuO5NuuilrT-DaA



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

    var $familyField = $('select#familles');
    var $orderField = $('select#ordres');
    var $oiseauField = $('#oiseauField');

    //fonction pour effacer la carte
    $('#clearMap').click(function (e) {
        e.preventDefault();
        location.reload();
    });

    //fonction pour trier les familles (families) par ordre (order)

    var filterOrder = function(){
        $orderField.on('change', function (e) {
            e.preventDefault();
            $('#errorMsg').remove();
            var submit = function(){
                var $orderFieldUrl = '/recherche/order/'+$orderField.val();
                //renvoie un appel ajax
                return $.ajax({
                    url: $orderFieldUrl,
                    method: 'GET'
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
            $('#errorMsg').remove();
            var submit = function(){
                var $familyFieldUrl = '/recherche/family/'+$familyField.val();
                return $.ajax({
                    url: $familyFieldUrl,
                    method: 'GET'
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

    //fonctionner pour obtenir toutes les observations pour un oiseau,
    var filterOiseaux = function(){
        $oiseauField.on('change', function(e){
            e.preventDefault();
            //Obtenir l'identifiant de l'oiseau
            var $input = $oiseauField.val();
            var $datalist = $('#oiseaux');
            var $val = $($datalist).find('option[value="'+$input+'"]');
            var $endval = $val.attr('id');
            //Appelez ajax
            var submit = function(){
                var $oiseauFieldUrl = '/recherche/oiseau/accepte/'+$endval;
                return $.ajax({
                    url: $oiseauFieldUrl,
                    method: 'GET'
                }).done(function(response){
                    $('#errorMsg').remove();
                    $.each(response.observations, function(key, value){
                        var date = new Date(value.date.date);
                        date = (date.getDate() + '/' + (date.getMonth()+1) + '/' + date.getFullYear());
                        if($('#isLogged').val() === "true"){

                            //Obtenir les rôles d'utilisateur
                            var $role = value.user.roles;
                            var $roleString = "";
                            if($.inArray("ROLE_ADMINISTRATEUR", $role)){
                                if ($.inArray("ROLE_ORNITHOLOGUE", $role)){
                                    $roleString = 'Utilisateur';
                                } else {
                                    $roleString = 'Ornithologue';
                                }
                            } else{
                                $roleString = 'Administrateur';
                            }

                            //Afficher la fiche d'observation
                            if(value.image !== null) {
                                $('#colMap').append(
                                    '<div class="col-md-5 col-xs-12 observationContainer">'+ 
                                    '<div class="row layer">' +
                                    '<div class="sheet">' +
                                    '<div class="col-xs-4">' +
                                    '</div>' +
                                    '<div class="col-xs-8">' +
                                    '<p class="user">'+value.user.username+'</p>' +
                                    '<p class="role">'+$roleString+'</p>' +
                                    $xpHtml +
                                    '</div>'+
                                    '</div>' +
                                    '</div>' +
                                    '<div class="row contain ajax">' +
                                    '<div class="col-xs-6">' +
                                    '<p class="link"><a href="'+value.oiseau.url+'">Lien fiche INPN</a></p>' +
                                    '<a href="/uploads/images/'+value.image.id+'.'+value.image.ext+'" class="thumbnail" target="_blank" title="Ouvrir l\'image dans un nouvel onglet"><images class="imageObservation" src="/uploads/images/' + value.image.id + '.' + value.image.ext + '" alt="' + value.image.alt + '" /></a>' +
                                    '</div>' +
                                    '<div class="col-xs-6">' +
                                    '<p class="nomOiseau">'+value.oiseau.nomVern +'<br><span class="date">le ' + date + '</span></p>' +
                                    '<p class="lat">Latitude : ' + value.latitude + '</p>' +
                                    '<p class="lon">Longitude : ' + value.longitude + '</p>'+
                                    '</div>' +
                                    '</div>' +
                                    '</div>'
                                );
                            } else {
                                $('#colMap').append(
                                    '<div class="col-md-5 col-xs-12 observationContainer">' +
                                    '<div class="row layer">' +
                                    '<div class="sheet">' +
                                    '<div class="col-xs-4">' +
                                    '<img class="profileImage" src="'+$userUrl+'" alt="profileImage"/> ' +
                                    '</div>' +
                                    '<div class="col-xs-8">' +
                                    '<p class="user">'+value.user.username+'</p>'+
                                    '<p class="role">'+$roleString+'</p>' +
                                    $xpHtml +
                                    '</div>'+
                                    '</div>' +
                                    '</div>' +
                                    '<div class="row contain ajax">' +
                                    '<div class="col-xs-6">' +
                                    '<p class="link"><a href="'+value.oiseau.url+'">Lien fiche INPN</a></p>' +
                                    '<img class="imageObservation" src="images/logo.png" alt="no-picture" />' +
                                    '</div>' +
                                    '<div class="col-xs-6">' +
                                    '<p class="nomOiseau">'+value.oiseau.nomVern +'<br><span class="date">le ' + date + '</span></p>' +
                                    '<p class="lat">Latitude : ' + value.latitude + '</p>' +
                                    '<p class="lon">Longitude : ' + value.longitude + '</p>'+
                                    '</div>' +
                                    '</div>' +
                                    '</div>'
                                );
                            }
                            FB.XFBML.parse();
                            //Ajouter un marqueur sur la carte
                            var marker = L.marker([value.latitude, value.longitude]).addTo(mymap);
                            marker.bindPopup("<b>"+value.oiseau.nomVern+" observé le "+date+" par "+value.user.username+"</b>");
                        } else {
                            var circle = L.circle([value.latitude, value.longitude], {
                                color: 'red',
                                fillColor: '#f03',
                                fillOpacity: 0.5,
                                radius: 5000
                            }).addTo(mymap);
                        }
                    })
                }).fail(function(jqXHR, exception){
                    var msg = '';
                    if (jqXHR.status === 404) {
                        msg = 'Espèce non présente dans la basse de l\'INPN. (Base TAXREF)';
                    } else if (jqXHR.status === 500) {
                        msg = 'Internal Server Error [500].';
                    } else if (jqXHR.status === 422) {
                        var $url = $('#getUrl-'+$endval).val();
                        msg = 'Cette espèce n\'a pas encore été observée. <a href="'+$url+'">Consultez sa fiche INPN</a>';
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
    filterOiseaux();

    //Get gps coordinates from controller to display marker for an untreated observation
    var $latGPS = $('.alert-success_lat').html();
    var $lonGPS = $('.alert-success_lon').html();
    if ($latGPS !== false && $lonGPS !== false){
       // var marker = L.marker([46.52, 2.43]).addTo(mymap);
        var marker = L.marker([$latGPS, $lonGPS]).addTo(mymap);
    } alert('test');






});