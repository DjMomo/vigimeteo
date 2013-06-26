<?php

  // vigilancemeteo_class.php
  
class vigilanceMeteo {

	var $img;
	var $legend;
	var $couleurs;

	function VigilanceMeteo($region) 
	{
		$this->legend = $this->createimage($region["colours"]["URL"]);
		$this->img = $this->createimage($region["URL"]);
	}
	
	function effaceimages()
	{
		$this->destroyimage($this->img);
		$this->destroyimage($this->legend);
	}
	
	function createimage($imgURL) {
		// Crée une ressource à partir d'une URL d'image
		$this->img_ok = true;
		$img_type = $this->imagetype($imgURL);
		if (strcasecmp($img_type,"png") == 0)
			$img = @imagecreatefrompng($imgURL);
		if (strcasecmp($img_type,"gif") == 0)
			$img = @imagecreatefromgif($imgURL);
		if ((strcasecmp($img_type,"jpg") == 0) || (strcasecmp($img_type,"jpeg") == 0))
			$img = @imagecreatefromjpeg($imgURL);
		if (!$img) {
			$this->img_ok = false;
		}
		return $img;
	}

	function destroyimage($img)
	{
		imagedestroy($img);
	}
	
	function imagetype($imgURL)
	{
		$URL = explode('.',$imgURL);
		$position = count($URL) - 1;
		return $URL[$position];
	}
	
	function compareColors ($col1, $col2, $tolerance=35) 
	{
		$col1Rgb = array(
			"r" => ($col1 >> 16) & 0xFF,
			"g" => ($col1 >> 8) & 0xFF,
			"b" => $col1 & 0xFF
			);
		$col2Rgb = array(
			"r" => ($col2 >> 16) & 0xFF,
			"g" => ($col2 >> 8) & 0xFF,
			"b" => $col2 & 0xFF
			);

		return ((abs($col1Rgb['r'] - $col2Rgb['r']) <= $tolerance) && (abs($col1Rgb['g'] - $col2Rgb['g']) <= $tolerance) && (abs($col1Rgb['b'] - $col2Rgb['b']) <= $tolerance));
	}
	
	function getColours($colours)
	{
		// Recuperation des index des couleurs de la légende de la carte
		$bleu = imagecolorat($this->legend, $colours["bleu"]["x"], $colours["bleu"]["y"]); 
		$vert = imagecolorat($this->legend, $colours["vert"]["x"], $colours["vert"]["y"]); 
		$jaune = imagecolorat($this->legend, $colours["jaune"]["x"], $colours["jaune"]["y"]);
		$orange = imagecolorat($this->legend, $colours["orange"]["x"], $colours["orange"]["y"]);
		$rouge = imagecolorat($this->legend, $colours["rouge"]["x"], $colours["rouge"]["y"]);
		$violet = imagecolorat($this->legend, $colours["violet"]["x"], $colours["violet"]["y"]);
		$gris = imagecolorat($this->legend, $colours["gris"]["x"], $colours["gris"]["y"]);
		
		$this->couleurs = array($bleu,$vert,$jaune,$orange,$rouge,$violet,$gris);
	}
	
	function getPolygone($dept) 
	{
		global $coordonnees;
		
		// Tableau des sommets x,y dans l'ordre du polygone qui encadre le département $dept 
		$tab = explode(',',$coordonnees[$dept]);

		return $tab;
	}

	function getRectangle($dept) {
		$tab = $this->getPolygone($dept);
	
		// Recherche xmin et xmax
		$xmax = $tab[0];
		$xmin = $tab[0];
		for ($i = 0; $i < count($tab)-1 ; $i+=2) {
			if ($tab[$i]>$xmax) $xmax = $tab[$i];
			if ($tab[$i]<$xmin) $xmin = $tab[$i];
		}
		// Recherche ymin et ymax
		$ymax = $tab[1];
		$ymin = $tab[1];
		for ($i = 1; $i < count($tab) ; $i+=2) {
			if ($tab[$i]>$ymax) $ymax = $tab[$i];
			if ($tab[$i]<$ymin) $ymin = $tab[$i];
		}
		$rect = array($xmin,$ymin,$xmax,$ymax);
		return $rect;
	}

	function alertMe($dept) 
	{
		$alertMe = false;
	
		if ($this->img_ok == true) 
		{
			list($xmin,$ymin,$xmax,$ymax) = $this->getRectangle($dept);
			
			// On recherche une des couleurs prédéfinies
			// Sens horizontal de recherche
			$x = $xmin + ($xmax-$xmin) / 2;
			$y = $ymin + ($ymax-$ymin) / 2;
			
			while (($x <= $xmax) && ($alertMe == false))
			{
				$color_index = imagecolorat($this->img,$x,$y);
				$colors = imagecolorsforindex($this->img, $color_index);
				foreach ($this->couleurs as $index_couleur)
				{
					if (($this->compareColors ($color_index, $index_couleur,15) == true) && ($alertMe == false))
					{
						$position = array_keys($this->couleurs, $index_couleur);
						$alertMe = $position[0];
					}
				}
				$x++;
			}
			
			// Sens vertical de recherche
			$x = $xmin + ($xmax-$xmin) / 2;
			$y = $ymin + ($ymax-$ymin) / 2;
			
			while (($y <= $ymax) && ($alertMe == false))
			{
				$color_index = imagecolorat($this->img,$x,$y);
				$colors = imagecolorsforindex($this->img, $color_index);
				foreach ($this->couleurs as $index_couleur)
				{
					if (($this->compareColors ($color_index, $index_couleur,15) == true) && ($alertMe == false))
					{
						$position = array_keys($this->couleurs, $index_couleur);
						$alertMe = $position[0];
					}
				}
				$y++;
			}
			
			if ($alertMe == false)
				$alertMe = 0;
						
		}
		else
			echo "Impossible de charger l'image";
		
		return $alertMe;
	}
}

?>
