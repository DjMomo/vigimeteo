eedomus-vigimeteo
=================

Une classe PHP pour obtenir les niveaux de vigilance météo en France

https://github.com/DjMomo/vigimeteo

==========

2013-08-12 - V2.0 - Evolution majeure
- Nouveau mode de traitement des données météo (traitement de données texte plutôt que de parser une image). Entraine un temps d'exécution divisé par 2 par rapport à la précédente version,
- Réécriture de toute la classe PHP,
- Possibilité de récupérer les données au format JSON (XML par défaut),
- Script plus léger (plus de fichier donnees.php désormais obsolète).

2013-11-21 - V1.4 - Correction d'un bug lié à un changement d'URL

2013-07-24 - V1.3 - Correction d'un bug qui retournait par moments la mauvaise couleur de la carte de vigilance

2013-06-26 - V1.2 - Externalisation des données dans un fichier séparé. Ajout des départements des Antilles (971,972,973,977,978)

2013-05-23 - V1.1 - Possibilité de choisir l'affichage ou la génération d'un fichier XML. 
Pour afficher le XML, ajouter le paramètre d=1 à l'URL (index.php?d=1)

2013-05-23 - V1.0 - Initial version on Github
Refonte du script initial, correction de bugs, intégration de la création du fichier XML.

==========
Configuration :

-- Aucune --

==========
Utilisation :

Afficher les données au format XML :
> http://IP/index.php?xml

Afficher les données au format JSON :
> http://IP/index.php?json

Sauvegarder les données au format XML dans le fichier "carte_vigilance_meteo.xml" :
> http://IP/index.php
