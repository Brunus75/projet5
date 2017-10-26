$(function() {
    var $incre = 0;
    var $window = $(window);
    //var $moreBtn = $('#moreObservation');

    $window.on('scroll', function () {
        //$moreBtn.on('click', function () {
        if (($(window).scrollTop() > $(document).height() - $(window).height() - $('footer').height() - 20) && ($(window).scrollTop() < $(document).height() - $(window).height() - $('footer').height() + 20)) {
            $incre = $incre + 1;
            var submit = function () {
                var $moreObservationUrl = "/admin/more/" + $incre;
                return $.ajax({
                    url: $moreObservationUrl,
                    method: 'GET'
                }).done(function (response) {
                    $.each(response.observations, function (key, value) {
 //Obtenir la date
                        var date = new Date(value.date.date);
                        date = (date.getDate() + '/' + (date.getMonth() + 1) + '/' + date.getFullYear());
 //Obtenir les rôles d'utilisateur
                        var $role = value.user.roles;
                        var $roleString = "";
                        if ($.inArray("ROLE_ADMINISTRATEUR", $role)) {
                            if ($.inArray("ROLE_ORNITHOLOGUE", $role)) {
                                if (value.statut === 'untreated'){
                                    $roleString = '<p class="role">Utilisateur</p><p class="role">En attente</p>';
                                } else {
                                    $roleString = '<p class="role">Utilisateur</p><p class="role">Publié</p>';
                                }
                            } else {
                                $roleString = 'Ornithologue';
                            }
                        } else {
                            $roleString = 'Aadministrateur';
                        }
//Afficher la fiche d'observation
                        if (value.image !== null) {
                            $('#moreObservation').before(
                                '<div class="col-md-5 col-xs-12 observationContainer ajaxContainer">' +
                                '<div class="row layer">' +
                                '<div class="sheet">' +
                                '<div class="col-xs-4">' +
                                '</div>' +
                                '<div class="col-xs-8">' +
                                '<p class="user">' + value.user.username + '</p>' +
                                '<p class="role">' + $roleString + '</p>' +
                                $xpHtml +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '<div class="row contain ajax">' +
                                '<div class="col-xs-6">' +
                                '<p class="link"><a href="' + value.oiseau.url + '">Lien fiche INPN</a></p>' +
                                '<a href="/uploads/images/' + value.image.id + '.' + value.image.ext + '" class="thumbnail" target="_blank" title="Ouvrir l\'image dans un nouvel onglet"><img class="imageObservation" src="/uploads/images/' + value.image.id + '.' + value.image.ext + '" alt="' + value.image.alt + '" /></a>' +
                                '</div>' +
                                '<div class="col-xs-6">' +
                                '<p class="nomOiseau">' + value.oiseau.nomVern + '<br><span class="date">le ' + date + '</span></p>' +
                                '<p class="lat">Latitude : ' + value.latitude + '</p>' +
                                '<p class="lon">Longitude : ' + value.longitude + '</p>' +
                                '</div>' +
                                '</div>' +
                                '</div>'
                            );
                        } else {
                            $('#moreObservation').before(
                                '<div class="col-md-5 col-xs-12 observationContainer ajaxContainer">' +
                                '<div class="row layer">' +
                                '<div class="sheet">' +
                                '<div class="col-xs-4">' +
                                '</div>' +
                                '<div class="col-xs-8">' +
                                '<p class="user">' + value.user.username + '</p>' +
                                '<p class="role">' + $roleString + '</p>' +
                                $xpHtml +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '<div class="row contain ajax">' +
                                '<div class="col-xs-6">' +
                                '<p class="link"><a href="' + value.oiseau.url + '">Lien fiche INPN</a></p>' +
                                '<img class="imageObservation" src="/bundles/nao/images/logo.png" alt="no-picture" />' +
                                '</div>' +
                                '<div class="col-xs-6">' +
                                '<p class="nomOiseau">' + value.oiseau.nomVern + '<br><span class="date">le ' + date + '</span></p>' +
                                '<p class="lat">Latitude : ' + value.latitude + '</p>' +
                                '<p class="lon">Longitude : ' + value.longitude + '</p>' +
                                '</div>' +
                                '</div>' +
                                '</div>'
                            );
                        }
                        FB.XFBML.parse();
                    })
                }).fail(function (response) {
                    $window.off();
                    var msg = '';
                    if (response.status === 422) {
                        msg = 'Vous n\'avez pas d\'autres observations.';
                    } else {
                        msg = 'Une erreur s\'est produite. Veuillez réessayer.';
                    }
                    $('#errorMsg').remove();
                    $('footer').before('<div id="errorMsg" class="alert alert-warning">' + msg + '</div>');
                })
            };
            submit();
        }
    });
});