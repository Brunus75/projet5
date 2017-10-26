
function getPage(P_url, P_fonction_callback) {
    var requete = new XMLHttpRequest();
    requete.open("GET", P_url);

    requete.addEventListener("load", function () {
        if (requete.status >= 200 && requete.status < 400) {
            P_fonction_callback(requete.responseText);
        } else {
            console.error('erreur ' + requete.status + ": " + requete.statusText);
        }
    });
    requete.addEventListener("error", function () {
        console.error('Erreur réseau');
    });
    requete.send(null);
}
function postPage(P_url, P_data, P_fonction_callback, P_isJson) {
    var requete = new XMLHttpRequest();
    requete.open("POST", P_url);
    requete.addEventListener("load", function () {
        if (requete.status >= 200 && requete.status < 400) {
            // Appelle la fonction callback en lui passant la réponse de la requête
            P_fonction_callback(requete.responseText);
        } else {
            console.error(requete.status + " " + requete.statusText + " " + P_url);
        }
    });
    requete.addEventListener("error", function () {
        console.error("Erreur réseau avec l'URL " + P_url);
    });
    if (P_isJson) {
        // Définit le contenu de la requête comme étant du JSON
        requete.setRequestHeader("Content-Type", "application/json");
        // Transforme la donnée du format JSON vers le format texte avant l'envoi
        P_data = JSON.stringify(P_data);
    }
    requete.send(P_data);
}
function afficherConsole(P_reponse) {
    console.log(P_reponse);
}
function afficherJSONConsole(P_reponse) {
    P_reponse = JSON.parse(P_reponse);
    console.log(P_reponse);
}
function creerTitreTableau(obj, P_Tab, P_tabTitre) {
    obj = obj[0];

    var ligneTitre = document.createElement("tr");
    P_Tab.appendChild(ligneTitre);
    var count = 0;
    var index = 0;
    for (var prop in obj) {
        if (obj.hasOwnProperty(prop)) {
            ligneTitre.innerHTML += "<th>" + prop + "</th>";
            P_tabTitre[index] = prop;
            console.log(prop);
            ++count;
            index++;
        }
    }
    return count;
}
function creerJSONTableau(P_reponse) {
    P_reponse = JSON.parse(P_reponse);
    console.log(P_reponse);
    var tableau = document.getElementById("tabLivres");
    var ligneTitre = document.createElement("tr");
    var tabNomTitre = [];
    var nombreColonne = creerTitreTableau(P_reponse, tableau, tabNomTitre);

    for (var indexLignes = 0; indexLignes < P_reponse.length; indexLignes++) {

        var nouvelleLigne = document.createElement("tr");
        tableau.appendChild(nouvelleLigne);
        for (var indexColonne = 0; indexColonne < nombreColonne; indexColonne++) {
            nouvelleLigne.innerHTML += "<td>" + P_reponse[indexLignes][tabNomTitre[indexColonne]] + "</td>";
        }

    }
}
function envoiMail(adresseTO, sujet, message, adresseFrom) {

    parametre = new FormData();
// Ajout d'information dans l'objet
    parametre.append("adresse", adresseTO);
    parametre.append("sujet", sujet);
    parametre.append("message", message);
    parametre.append("from", adresseFrom);

    postPage("http://calvi.kaim.fr/mailReservation.php", parametre, console.log, false);
}