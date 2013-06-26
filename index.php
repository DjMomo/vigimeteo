<?php

/*************************************************************************************
**												
** Script vigilance m�t�o pour syst�mes domotique
**												
** V1 - DjMaboul - http://www.touteladomotique.com/forum/viewtopic.php?f=72&t=1724
**						
** V2.x - DjMomo - http://www.planete-domotique.com/blog/2013/05/11/la-vigilance-meteo-dans-votre-eedomus/
** V2.0 - 2013-05-10 - 	Refonte du script initial, correction de bugs, int�gration de la cr�ation du fichier XML.
** V2.1 - 2013-05-23 - 	Possibilit� de choisir l'affichage ou la g�n�ration d'un fichier XML. 
**						Pour afficher le XML, ajouter le param�tre d � 1 � l'URL (index.php?d=1)
** V2.2 - 2013-06-26 - Externalisation des donn�es dans un fichier s�par�. Ajout des DOM et TOM
**************************************************************************************/

// Indiquer ici le chemin relatif ainsi que le nom du fichier du XML
$fichierXML = "./carte_vigilance_meteo.xml";

// ******** Ne rien modifier ci-dessous ********
require_once('vigilancemeteo_class.php');
require_once('donnees.php');

$couleur = array('Bleu','Vert','Jaune','Orange','Rouge','Violet','Gris');
							
if ((isset ($_GET['d'])) && (($_GET['d'] == 1) || ($_GET['d'] == 0)))
	$display = $_GET['d'];

// Cr�ation fichier XML avec les donn�es
// Instance de la class DomDocument
$doc = new DOMDocument();

// Definition de la version et de l'encodage
$doc->version = '1.0';
$doc->encoding = 'UTF-8';
$doc->formatOutput = true;

// Ajout d'un commentaire a la racine
$comment_elt = $doc->createComment(utf8_encode('Carte de vigilance des d�partements (m�tropole et outre-mer) et territoires d\'outre-mer fran�ais'));
$doc->appendChild($comment_elt);

$racine = $doc->createElement('vigilance');

// Ajout la balise 'update' a la racine
$version_elt = $doc->createElement('update',date("Y-m-d H:i"));
$racine->appendChild($version_elt);

foreach ($donnees_vigilance as $region)
{
	//Nouvelle instance de la classe vigilancemeteo
	$meteo = new VigilanceMeteo($region);

	//On r�cup�re les diff�rentes couleurs possibles pour cette carte
	$couleurs = $meteo->getColours($region["colours"]);
	
	//Boucle sur tous les d�partements de la liste
	foreach ($region["depts"] as $dept)
	{
		//Ajout du 0 significatif aux num�ros de d�partements < 10
		$dept = str_pad($dept, 2, "0", STR_PAD_LEFT);
		
		$niveau = $meteo->alertMe($dept);

		//Nouvel essai
		if ($niveau == false)
			$niveau = $meteo->alertMe($dept);
		
		if ($niveau == false)
			$niveau = 0;

		$dept_elt = $doc->createElement('dep_'.$dept);
		$dept_niveau_elt = $doc->createElement('niveau', $niveau);
		$dept_couleur_elt = $doc->createElement('alerte', utf8_encode($couleur[$niveau]));
					
		$dept_elt->appendChild($dept_niveau_elt);
		$dept_elt->appendChild($dept_couleur_elt);
		$racine->appendChild($dept_elt);
	}
	
	// On efface les images dans le cache
	$meteo->effaceimages();
}

$doc->appendChild($racine);
if ($display == 1)
	echo $doc->saveXML();
else
	$doc->save($fichierXML);

?>
