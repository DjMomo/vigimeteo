eedomus-vigimeteo
=================

A PHP class to retrieve vigilance meteo levels in France

https://github.com/DjMomo/vigimeteo

==========

2013-05-23 - V1.0 - Initial version on Github
Refonte du script initial, correction de bugs, int�gration de la cr�ation du fichier XML.
2013-05-23 - V1.1 - Possibilit� de choisir l'affichage ou la g�n�ration d'un fichier XML. 
Pour afficher le XML, ajouter le param�tre d � 1 � l'URL (index.php?d=1)
2013-06-26 - V1.1 - Externalisation des donn�es dans un fichier s�par�. Ajout des d�partements des Antilles (971,972,973,977,978)
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
