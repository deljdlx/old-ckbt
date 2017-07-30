# Détecteur de doublons de phrases dans différents fichier

## Présentation
Ce projet permet de trouver les phrases apparaissant dans différents fichiers.

Le concept de phrase est configurable par regexp. Par défaut une phrase est considérée comme
étant une chaine de caractères terminée par l'un des caractères suivants: .?! ou retour à la ligne.
Une phrase ne prend pas en compte le signe de "ponctuation" de fin. Ainsi "Bonjour", "Boujour !" ou "Bonjour..." sont considérés comme étant la même phrase



## Installation
git clone https://github.com/ElBiniou/ckbt.git


## Test Web

cd ckbt/www

php -S localhost:8080
 
 Avec un navigateur http://localhost:8080
 
 Passer la souris sur les phrases en gras pour surligner ses autres occurrences.
 
 Glisser déposer des fichier texte dans la zone grise pour ajouter un fichier à comparer
 
 Cliquer sur la croix rouge pour retirer un fichier de la comparaison
 
 
 
## Test console
 
cd ckbt
 
php bin/compare.php [file1 file2 file3....]

(Le format de sortie n'est peut être pas forcément le plus pratique ; mais facile à modifier) 
  


## Stratégies
L'architecture du projet se base sur des stratégie de détection de doublons. Dans la démonstration trois stratégies sont disponibles (Namespace \CKBT\ComparatorStrategy\LaStrategie). La stratégie par défaut est l'utilisation de Hash

- Hash (Compléxité linéaire, consommation de ram linéaire): Une stratégie se voulant être un compromis entre consommation Ram et temps de calcul.  

- SQLite (Compléxité linéaire, consomation ram dépendant du nombre de doublons) C'est la stratégie la plus scalable. Cependant elle est n'est adaptée que pour du traitement de volumes important car le temps de provisionning de la bdd est coûteux comparé à stratégie par hash.

- DumbAndCheap (Compléxité polynomiale, consommation ram dépendant du nombre de doublons) C'est la solution la plus économe en terme de ram, mais en terme d'optimisation du temps de calcul c'est une solution "brute force"
  

Pour changer les stratégies de détection de doublons, aller dans les fichiers bin/compare.php ou www/controller.php et chercher le bloc de commentaire "CHANGER LES STRATEGIES ICI"



## Remarques

- Pas de couche MVC ou autre car inutlie dans le cadre de la démo
- La partie front web est fonctionnelle mais loin d'être un exemple d'expérience utilisateur 
- L'aspect sécurité et gestion des erreurs a été quasiment ignoré pour la démonstration
- Pas de couche routeur/application/configuration/... ; car inutile dans le cadre de la démo
- Algorithmes non optimaux pour du traitement de logs
- Mis à part jQuery et Fontawesome tout le code source est "personnel"

## Vers l'infini et au delà

- Chargement dynamique des stratégies par configuration
- Sécurité à améliorer
- Déclarer les interfaces adéquates pour le choix des stratégies 
- Nettoyage et code hinting
- Parallélisation des stratégies
- Réelle UX
- Fuzzy détection des doublons
- ...





 
 



  

