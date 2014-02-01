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

$fichierXML = "carte_vigilance_meteo.xml";

require("VigilanceMeteo.class.php");
$fichier = false;

// Choix entre affichage ou sauvegarde
$_GET_lower = array_change_key_case($_GET, CASE_LOWER);
if (isset ($_GET_lower['json']))
{
	$format = "json";
	header('Content-Type: application/json; charset=utf-8');
}
elseif (isset ($_GET_lower['xml']))
	$format = "xml";
else
	$fichier = $fichierXML;

$meteo = new VigilanceMeteo($format,"Etats de vigilance météorologique des départements (métropole et outre-mer) et territoires d'outre-mer français");
$meteo->DonneesVigilance($fichier);

// Et c'est tout !

?>
