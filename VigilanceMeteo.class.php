<?php

/*************************************************************************************
**												
** Classe PHP VigilanceMeteo
**												
** Toutes versions - DjMomo - Voir Readme.md
**
**************************************************************************************/
  
class VigilanceMeteo 
{
	private $OUTPUT_FORMAT;
	private $XML_COMMENT;
	private $DATA = array();
	private $HEADER = array();
	private $UPDATE;
	private $METEO_XML_DATA_URL;	// M�tropole
	private $METEO_TXT_UPDATE_URL;	// Antilles
	private $METEO_TXT_DATA_URL;	// Antilles
	private $DOM;
	private $RISQUE = array("","vent","pluie-inondation","orages","inondations","neige-verglas","canicule","grand-froid","avalanches","vagues-submersion","crues");
	private $DEP;
		
	public function __construct($output_format = "XML", $entete_XML = "")
	{
		spl_autoload_register(array($this,"autoloader"));

		$this->OUTPUT_FORMAT = $output_format;
		$this->XML_COMMENT = $entete_XML;
		$this->METEO_XML_DATA_URL = "http://vigilance.meteofrance.com/data/NXFR34_LFPW_.xml";
		$this->METEO_TXT_UPDATE_URL = "http://www.meteo.gp/donnees/pics/date_vigi.txt";
		$this->METEO_TXT_DATA_URL = "http://www.meteo.gp/donnees/pics/RSS_couleurs.txt";
		$this->METEO_XML_DETAILS_URL = "WXML%DEPT%_LFPW_.xml";//"http://www.vigimeteo.com/data/WXML%DEPT%_LFPW_.xml";
		
		$update = $this->ToUTF8("update");
		$updateval = $this->ToUTF8(date("d-m-Y H:i"));
		$this->UPDATE[$update] = $updateval;
	}
	
	function autoloader ($pClassName) 
	{
        include(__DIR__ . "/" . $pClassName . ".class.php");
    }
	
    private function GetData($url)
	{
		// R�cup�re le contenu du fichier source des donn�es
		return file_get_contents($url);
	}
	
	public function DonneesVigilance($file = false)
	{
		//
		// Donn�es de m�tropole
		//
		$this->MetropoleDataFormat();
		
		//
		// Donn�es des Antilles
		//
		$this->AntillesDataFormat();
				
		// Fusion des tableaux d'entete et de donnees
		$this->SortAndMergeHeaderAndData();
		$root = $this->ToUTF8("vigilance");
		$arr = $this->DATA;
		$this->DATA = array();
		$this->DATA[$root] = $arr;
		
		if ($file === false)
			echo $this->OuputEncode();
		else
			$this->OutputEncodeXML($file);
	}
	
	private function SortAndMergeHeaderAndData()
	{
		// Fusion des tableaux entete et donn�es apr�s tri du tableau des donn�es
		$this->DataSort();
		$this->DATA = (array_merge($this->UPDATE,$this->HEADER,$this->DATA));
	}
	
	private function CreateHeader($location,$data)
	{
		if (strcasecmp($location, "metropole") == 0)
			$this->CreateMetropoleHeader($data);
		if (strcasecmp($location, "antilles") == 0)
			$this->CreateAntillesHeader($data);
	}
	
	private function CreateMetropoleHeader($array_data)
	{
		$label = $this->ToUTF8("bulletin_metropole");
		$this->HEADER[$label] = array(
									$this->ToUTF8("creation") => $this->ToUTF8($this->ConvertLongDateToFRDate($array_data['dateinsert'])),
									$this->ToUTF8("mise_a_jour") => $this->ToUTF8($this->ConvertLongDateToFRDate($array_data['daterun'])),
									$this->ToUTF8("validite") => $this->ToUTF8($this->ConvertLongDateToFRDate($array_data['dateprevue'])),
									$this->ToUTF8("version") => $this->ToUTF8($array_data['noversion'])
									);
	}
	
	private function CreateAntillesHeader($str)
	{
		$label = $this->ToUTF8("bulletin_antilles");
		$this->HEADER[$label] = array(
									$this->ToUTF8("creation") => $this->ToUTF8($this->ConvertLongDateToFRDate($str)),
									);
	}
	
	private function ConvertLongDateToFRDate($str)
	{
		// Date au format YYYYMMDDHHMMSS
		$year = substr($str,0,4);
		$month = substr($str,4,2);
		$day = substr($str,6,2);
		$hour = substr($str,8,2);
		$min = substr($str,10,2);
				
		return $day."-".$month."-".$year." ".$hour.":".$min;
	}
	
	private function MetropoleDataFormat()
	{
		// Lit et met en forme les donn�es pour le format de sortie
		$xml = new SimpleXMLElement($this->GetData($this->METEO_XML_DATA_URL));
		
		foreach ($xml->datavigilance as $line)
		{	
			$type = $this->Filter($line);
			if (strcasecmp($type,"dep") == 0)
			{
				$this->DEP = $this->ToUTF8("dep_".$line['dep']);
				$this->AddData($line);
			}
			if (strcasecmp($type,"cote") == 0)
			{
				$this->DEP = $this->ToUTF8("cote_".substr($line['dep'],0,2));
				$this->AddData($line);
			}
		}
		$this->CreateHeader("metropole",$xml->entetevigilance);
	}
	
	private function AddData($data)
	{
		$NiveauMax = $this->NiveauMax($data);
		if ($NiveauMax > 2) 
		{
			$risque = $this->RisqueConcat($data->risque["valeur"], $this->DATA[$this->DEP]["risque"]);
			if (isset ($data->crue["valeur"]))
				$risque = $this->RisqueConcat((int)$data->crue["valeur"]+10, $risque);
		}
		else		
			$risque = "RAS"; 
			
		$this->DATA[$this->DEP] = array (
							$this->ToUTF8("niveau") => $this->ToUTF8($NiveauMax), 
							$this->ToUTF8("alerte") => $this->ToUTF8($this->ConvertLevelToColor($NiveauMax)),
							$this->ToUTF8("risque") => $this->ToUTF8($risque),
									);
	}
	
	private function NiveauMax($data)
	{
		((int)$data['couleur'] >= (int)$data->crue['valeur']) ? $niveau = $data['couleur'] : $niveau = $data->crue['valeur'];
		return $niveau;
	}
	
	private function RisqueConcat($risque,$risque_text = "")
	{
		if ($this->RisqueConvert($risque) != "")
		{
			if (strlen($risque_text) > 0)
				$risque_text .= ", ".ucfirst($this->RisqueConvert($risque));
			else
				$risque_text = ucfirst($this->RisqueConvert($risque));
		}
		return $risque_text;
	}
	
	private function RisqueConvert($risque)
	{
		$risque = (int)$risque;
		return $this->RISQUE[$risque];
	}
	
	private function AntillesDataFormat()
	{
		// Lit et met en forme les donn�es des Antilles pour le format de sortie
		$txt = $this->GetData($this->METEO_TXT_DATA_URL);
		$txt = preg_replace("#( +)#", " ", trim($txt)); // Suppression des espaces inutiles
		
		$data = explode(PHP_EOL,$txt);
		
		foreach ($data as $line)
		{	
			if (strlen(trim($line)) != 0)
			{
				$data = explode(" ", trim($line));
				
				$this->DEP = $this->ToUTF8("dep_".$this->ConvertDepartmentToNumber($data[0]));
				$this->DATA[$this->DEP] = array (
										$this->ToUTF8("niveau") => $this->ToUTF8($this->ConvertColorToLevel($data[1])), 
										$this->ToUTF8("alerte") => $this->ToUTF8($this->ConvertLevelToColor($this->ConvertColorToLevel($data[1])))
										);
			}
		}
	
		// Recopie du 978 car m�mes donn�es que le 977
		$this->DEP = $this->ToUTF8("dep_978");
		$dep_ini = $this->ToUTF8("dep_977");
		$arr = $this->DATA[$dep_ini];
		$this->DATA[$this->DEP] = $this->DATA[$dep_ini];
		
		$this->CreateHeader("antilles",$this->GetData($this->METEO_TXT_UPDATE_URL));
	}
	
	private function ToUTF8($str)
	{
		return utf8_encode($str);
	}
	
	private function ConvertDepartmentToNumber($dep)
	{
		$dep = strtolower($dep);
		$DepNumber = array("guadeloupe" => 971, "martinique" => 972, "guyane" => 973, "idn" => 977);
		return $DepNumber[$dep];
	}
	
	private function ConvertLevelToColor($level)
	{
		$level = (int)$level;
		$colors = array('Bleu','Vert','Jaune','Orange','Rouge','Violet','Gris');
		return $colors[$level];
	}
	
	private function ConvertColorToLevel($color)
	{
		$color = strtolower($color);
		$level = array('bleu' => 0,'vert' => 1,'jaune' => 2,'orange' => 3,'rouge' => 4,'violet' => 5,'gris' => 6);
		return $level[$color];
	}
	
	private function Filter($data)
	{
		// Filtrage des donn�es (depts 99, 2A10, 4010, 3310, etc..) du fichier source de m�tropole
		if (((strlen ($data['dep']) == 2) && ($data['dep'] < 96)) || ($data['dep'] == 99)) 
			return 'dep';
		if ((strlen($data['dep']) == 4) && (strcasecmp(substr($data['dep'],-2),"10") == 0))
			return 'cote';
		return false;
	}
	
	private function DataSort()
	{
		ksort(&$this->DATA);
	}
	
	private function OuputEncode()
	{
		// Fonction qui retourne les donn�es au format choisi
		if (strcasecmp($this->OUTPUT_FORMAT,"XML") == 0)
			return $this->OutputEncodeXML();		// Format XML
		
		if (strcasecmp($this->OUTPUT_FORMAT,"JSON") == 0)
			return $this->OutputEncodeJSON(); 	// Format JSON
		
		return false;
	}
	
	private function OutputEncodeXML($file = false)
	{
		// Fonction d'encodage en XML � partir de la classe XmlDomConstruct
		$this->DOM = new XmlDomConstruct('1.0', 'utf-8');
		$this->DOM->formatOutput = true;
		$comment_elt = $this->DOM->createComment($this->ToUTF8($this->XML_COMMENT));
		$this->DOM->appendChild($comment_elt);
		$this->DOM->fromMixed($this->DATA);
 
		if ($file === false)
			return $this->DOM->saveXML();
		else
			$this->DOM->save($file);
	}
	
	private function OutputEncodeJSON()
	{
		// Fonction d'encodage en JSON
		return json_encode($this->DATA);
	}
}

?>
