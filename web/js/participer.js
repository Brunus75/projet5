/*Auto-complétion des espèces sur la nouvelle observation (c'est la caractéristique principale) */
(function () {
    var options = {
        url_list: $('#url-list').attr('href'),
        url_get: $('#url-get').attr('href'),
        otherOptions: {
            minimumInputLength: 1,
            theme: 'boostrap',
            formatNoMatches: 'aucune référence trouvée.',
            formatSearching: 'Recherche ...',
            formatInputTooShort: 'Entrez au moins un caractère pour avoir des suggestions'
        }
    };

    $('#observation_oiseau').autocompleter(options);


});

/*
{{ form_row(form.categories) }}



    $(document).ready(function() {
        // On récupère la balise <div> en question qui contient l'attribut « data-prototype » qui nous intéresse.
        var $container = $('div#oc_platformbundle_advert_categories');

        // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
        var index = $container.find(':input').length;

        // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
        $('#add_category').click(function(e) {
            addCategory($container);

            e.preventDefault(); // évite qu'un # apparaisse dans l'URL
            return false;
        });

        // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
        if (index === 0) {
            addCategory($container);
        } else {
            // S'il existe déjà des catégories, on ajoute un lien de suppression pour chacune d'entre elles
            $container.children('div').each(function() {
                addDeleteLink($(this));
            });
        }

        // La fonction qui ajoute un formulaire CategoryType
        function addCategory($container) {
            // Dans le contenu de l'attribut « data-prototype », on remplace :
            // - le texte "__name__label__" qu'il contient par le label du champ
            // - le texte "__name__" qu'il contient par le numéro du champ
            var template = $container.attr('data-prototype')
                .replace(/__name__label__/g, 'Catégorie n°' + (index+1))
                .replace(/__name__/g,        index)
            ;

            // On crée un objet jquery qui contient ce template
            var $prototype = $(template);

            // On ajoute au prototype un lien pour pouvoir supprimer la catégorie
            addDeleteLink($prototype);

            // On ajoute le prototype modifié à la fin de la balise <div>
            $container.append($prototype);

            // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
            index++;
        }

        // La fonction qui ajoute un lien de suppression d'une catégorie
        function addDeleteLink($prototype) {
            // Création du lien
            var $deleteLink = $('<a href="#" class="btn btn-danger">Supprimer</a>');

            // Ajout du lien
            $prototype.append($deleteLink);

            // Ajout du listener sur le clic du lien pour effectivement supprimer la catégorie
            $deleteLink.click(function(e) {
                $prototype.remove();

                e.preventDefault(); // évite qu'un # apparaisse dans l'URL
                return false;
            });
        }
    });
*/