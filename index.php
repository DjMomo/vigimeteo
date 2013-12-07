<?php

/*************************************************************************************
**												
** Script vigilance météo pour systèmes domotique
**												
** V1 - DjMaboul - http://www.touteladomotique.com/forum/viewtopic.php?f=72&t=1724
**						
** Version ultérieures - DjMomo - Voir Readme.md
**
**************************************************************************************/

require("VigilanceMeteo.class.php");

if (isset ($_GET['json']))
	$format = "json";
else
	$format = "xml";

$meteo = new VigilanceMeteo($format,"Etats de vigilance météorologique des départements (métropole et outre-mer) et territoires d'outre-mer français");
// Affiche les données au format choisi (JSON ou XML)
$meteo->DonneesVigilance();
// Pour sauvegarder dans un fichier XML :
// $meteo->DonneesVigilance("nom_du_fichier.xml");

?>
