<?php

/*************************************************************************************
**												
** Script vigilance m�t�o pour syst�mes domotique
**												
** V1 - DjMaboul - http://www.touteladomotique.com/forum/viewtopic.php?f=72&t=1724
**						
** Version ult�rieures - DjMomo - Voir Readme.md
**
**************************************************************************************/

require("VigilanceMeteo.class.php");

if (isset ($_GET['json']))
	$format = "json";
else
	$format = "xml";

$meteo = new VigilanceMeteo($format,"Etats de vigilance m�t�orologique des d�partements (m�tropole et outre-mer) et territoires d'outre-mer fran�ais");
// Affiche les donn�es au format choisi (JSON ou XML)
$meteo->DonneesVigilance();
// Pour sauvegarder dans un fichier XML :
// $meteo->DonneesVigilance("nom_du_fichier.xml");

?>
