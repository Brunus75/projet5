# NOA - Nos amis les oiseaux
## OpenClassRooms Parcours Chef de Projet Multimedia :
### Projet N°5 - Création d'une application participative permettant l'observation des espèces d'oiseaux. 


Installation
------------

1) Download et dézipper le fichier P5-Team5.zip <a href="https://1drv.ms/f/s!AptwH26DXwQsgf8kNAYFqoOjYJkqZQ">ICI</a>

2) A la racine du projet, mettre à jour le composer : php composer.phar update

3) Compléter le fichier parameters.yml sur l'exemple de parameters.yml.dist
  - Connexion à une boîte mail d'envois
  - Connexion à la base de donnée
 
4) Créer la base de donnée sur le serveur local : php bin/console doctrine:database:create

5) Charger les tables doctrines dans la base de donnée : php bin/console doctrine:schema:update --force

6) Importer les données de la BDD, table "especes" <a href="https://1drv.ms/u/s!AptwH26DXwQsgf8oOFTF3eq_RnnJfg">BDD - P5 format SQL</a>

7) Création d'un "User" avec son "Role"

    - php bin/console fos:user:create "pseudo email pass"

    - php bin/console fos:user:promote "pseudo role"
      ( "role" => ORNITHOLOGUE )
      
8) C'est prêt!

==============
