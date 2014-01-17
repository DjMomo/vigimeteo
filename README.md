VigilanceMeteo
=================

Une classe PHP pour obtenir les niveaux de vigilance météo en France métropolitaine, Antilles et Andorre.

https://github.com/DjMomo/vigimeteo

==========

2014-01-17 - V2.4 - Ajout des niveaux de vigilance crue pour chaque département de métropole concerné. Balises <crues> </crues> du fichier XML. Niveau de 1 = Vert à 4 = Rouge.

2014-01-01 - V2.3 - Ajout des risques (pluie-inondation, orages, canicule, avalanches, etc...) aux départements en vigilance orange ou rouge de métropole.

2013-12-21 - V2.2 - Evolution liée à PHP 5.3

2013-12-08 - V2.1 - Etats de vigilance pour les côtes métropolitaines désormais disponibles :
Pour les départements côtiers métropolitains, disponibilité des états de vigilance "Vagues/submersion". Accessibles dans le JSON et/ou le XML via la balise "cote_XX" ou XX représente le numéro du département côtier concerné (2A, 06, 40, 59, etc...)

2013-12-08 - V2.0 - Evolution majeure
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
