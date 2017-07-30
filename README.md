# Détecteur de doublons de phrases dans différents fichier

## Présentation
Ce projet permet de trouver les phrases apparaissant dans différents fichiers.

Le concept de phrase est configurable par regexp. Par défaut une phrase est considérée comme
étant une chaine de caractères terminée par un des caractère suivants: .?! ou un retour à la ligne.


 




## Installation
Executer le fichier SQL source/Application/provision/mysql.all.sql.

Attention le fichier SQL crée automatique une base de donnée "dzr"

L'application n'a pas besoin configuration de virtual host 

## Configuration
Editer le fichier source/Application/Configuration/Datasource.php

## Provision
Si vous souhaitez compléter la base de donnée, éditer le fichier source/Application/bin/populate-database.php puis l'éxécuter.

## Test
Sans configuration serveur :

En ligne de commande se placer dans le dossier source/Application/www

Lancer php -S localhost:8000

Pour le player : http://localhost:8000

Pour l'API : http://localhost:8000/api.php


## Demo
API http://dzr.jlb.ninja/api.php

Client http://dzr.jlb.ninja/


## Considérations techniques

Le code n'utilise pas les spécificités de PHP7 afin de le rendre portable sur un maximum de versions de PHP.

La classe Application gère l'injection de dépendances ainsi que le routing. Elle n'a pas été découpée en sous classes car hors contexte dans le cadre de ce projet.

La couche de gestion des requêtes et réponses HTTP est minimaliste. Il serait aisé de changer les classes utilisées par un vendor plus solide.

La couche controleurs ne fait quasiment rien et le routeur aurait pu attaquer directement la couche modèle. Le choix de cette couche intermédiaire a été fait pour faciliter la compréhension de l'architecture en restant dans un modèle "standard"

La configuration se fait par classe PHP afin de pouvoir la débugguer facilement sans couche intermédiaire ainsi que pouvoir gérer potentiellement de l'héritage "natif" de configurations.

Le client JS a été codé rapidement et aurait besoin de consolidation.