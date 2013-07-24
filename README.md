eedomus-vigimeteo
=================

A PHP class to retrieve vigilance meteo levels in France

https://github.com/DjMomo/vigimeteo

==========

2013-05-23 - V1.0 - Initial version on Github
Refonte du script initial, correction de bugs, intégration de la création du fichier XML.
2013-05-23 - V1.1 - Possibilité de choisir l'affichage ou la génération d'un fichier XML. 
Pour afficher le XML, ajouter le paramètre d à 1 à l'URL (index.php?d=1)
2013-06-26 - V1.1 - Externalisation des données dans un fichier séparé. Ajout des départements des Antilles (971,972,973,977,978)
2013-07-24 - V1.2 - Correction d'un bug qui retournait par moments la mauvaise couleur de la carte de vigilance

==========
Configuration :

-- None --

==========
How to use :

Generate an XML file "carte_vigilance_meteo.xml" :
> http://IP/index.php

Display all datas in XML format :
> http://IP/index.php?d=1
