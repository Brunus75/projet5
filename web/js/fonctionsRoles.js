function recRole(P_donnee) {
    P_donnee = JSON.parse(P_donnee);
    if (P_donnee.length === 0) {
        console.error("probleme d'enregistrement du role");
    } else {
        console.log("Champs "+P_donnee.champs+" de "+P_donnee.entity+" mis à jour");
    }
}