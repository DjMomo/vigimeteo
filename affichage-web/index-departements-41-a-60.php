<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="refresh" content="3600" /> <!-- Actualisation de la page toutes les heures -->
		<title>Vigilance m&eacute;t&eacute;o en France</title>
		<link rel="stylesheet" type="text/css" href="inc/style.css" />
	</head>
	
	<body>
		<div id="menu">
			<ul id="onglets">
				<li><a href="index-cote.php"> Côte départements </a></li>
				<li><a href="index-departements-1-a-2B.php"> Départements 1 à 2B </a></li>
				<li><a href="index-departements-21-a-40.php"> Départements 21 à 40 </a></li>
				<li class="active"><a href="index-departements-41-a-60.php"> Départements 41 à 60 </a></li>
				<li><a href="index-departements-61-a-80.php"> Départements 61 à 80 </a></li>
				<li><a href="index-departements-81-a-99.php"> Départements 81 à 99 </a></li>
			</ul>
		</div>
		
	<?php
		/* $xml = simplexml_load_file("http://127.0.0.1/vigimeteo-master/carte_vigilance_meteo.xml"); */
		$xml = simplexml_load_file("http://www2.fmaurel.fr/data/carte_vigilance_meteo.xml");
		foreach($xml->bulletin_metropole as $meteo) {
			echo 'Dernière mise à jour de la vigilance : '. $meteo->mise_a_jour .'<br /><br />';
		}
		
		echo '<table style="width: 30%; border-collapse: collapse; text-align: center;" border="1" bordercolor="#000">
				<tr>
					<td style="width: 15%; font-weight: bold; background: #CECECE;">Le département</td>
					<td style="width: 15%; font-weight: bold; background: #CECECE;">Est en vigilance</td>
				</tr>';
		// Vigilance département
		include ("inc/vigilance-departement-41-a-60.php");
		echo '</table>';
		include ("inc/pied.php");
	?>
	</body>
</html>