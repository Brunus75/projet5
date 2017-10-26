
/* -------------------------- Commentaires -------------------------------------*/
function commente(parentValue) {
    document.getElementById('parent').value = parentValue;
    document.getElementById('popup-fond').className = 'flex';
}

function fermer(id) { /* fonction qui ferme la fenetre modale */

    document.getElementById(id).setAttribute("itemprop", "true"); /* iniatilisation de la variable itemprop pour savoir la cible du clique*/
    var dialog = document.getElementById("popup").getAttribute("itemprop"); /* récuperation de la valeur de la variable itemprop */
    var div = document.getElementById("popup-fond"); /* crÃ©ation du fond noir */

    if (id !== "popup") { /* si le clique est en dehors de la fenetre utile */
        if (document.getElementById("popup").getAttribute("itemprop") === "false") {
            div.className = "hidden"; /* on ferme la fenetre modale */
        } else {
            document.getElementById("popup").setAttribute("itemprop", "false"); /* on ne fait rien */
        }
    }

}

/* -------------------------- Signalement --------------------------------------*/

function dejaSignale(){
    alert("Vous l'avez déjà signalé");
}
