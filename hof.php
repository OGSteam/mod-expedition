<?php

if (!defined('IN_SPYOGAME')) die("Hacking attempt");

require_once('functions.php');

// on charge les valeurs de la base :
$hofArray = readDBHOF();

include('header.php');

//définition de la page
$pageHOF = <<<HEREHOF
<br />
<br />
<br />


<!-- DEBUT Insertion mod eXpedition : HOF -->


Classement meilleur cumul (resources + vaisseaux) :

<table style="text-align: left; height: 150px;" border="0" cellpadding="2" cellspacing="2">
	<tbody>
		<tr>
			<td class="c" style="width: 20px;">#</td>
			<td class="c" style="width: 100px;"><big>Pseudo</big></td>
			<td class="c" style="width: 100px;"><big>Points</big></td>
		</tr>	
HEREHOF;

for($i = 0 ; $i < 10 ; $i++)
{
	$indice = $i + 1;
	$pageHOF .= '
		<tr>
			<td class="c" style="width: 20px;">'.$indice.'</td>
			<th style="width: 100px;"><big>'.$hofArray['cumul'][$i]['pseudo'].'</big></th>
			<th style="width: 75px;"><big>'.number_format((int)$hofArray['cumul'][$i]['cumul'], 0, ',', ' ').'</big></th>
		</tr>';
}

$pageHOF .= <<<HEREHOF
	</tbody>
</table>
<br />
<br />
<br />
<br />



Classement total ressource :

<table style="text-align: left; height: 150px;" border="0" cellpadding="2" cellspacing="2">
	<tbody>
		<tr>
			<td class="c" style="width: 20px;">#</td>
			<td class="c" style="width: 100px;"><big>Pseudo</big></td>
			<td class="c" style="width: 75px;"><big>Métal</big></td>
			<td style="width: 20px;"></td>
			<td class="c" style="width: 90px;"><big>Pseudo</big></td>
			<td class="c" style="width: 75px;"><big>Cristal</big></td>
			<td style="width: 20px;"></td>
			<td class="c" style="width: 90px;"><big>Pseudo</big></td>
			<td class="c" style="width: 75px;"><big>Deutérium</big></td>
			<td style="width: 20px;"></td>
			<td class="c" style="width: 90px;"><big>Pseudo</big></td>
			<td class="c" style="width: 75px;"><big>Antimatière</big></td>

		</tr>
HEREHOF;
for($i = 0 ; $i < 10 ; $i++)
{
	$indice = $i + 1;
	$pageHOF .= '
		<tr>
			<td class="c" style="width: 20px;">'.$indice.'</td>
			<th style="width: 100px;"><big>'.$hofArray['cumulRess'][$i]['M']['pseudo'].'</big></th>
			<th style="width: 75px;"><big>'.number_format((int)$hofArray['cumulRess'][$i]['M']['cumul'], 0, ',', ' ').'</big></th>
			<td style="width: 20px;"></td>
			<th style="width: 90px;"><big>'.$hofArray['cumulRess'][$i]['C']['pseudo'].'</big></th>
			<th style="width: 75px;"><big>'.number_format((int)$hofArray['cumulRess'][$i]['C']['cumul'], 0, ',', ' ').'</big></th>
			<td style="width: 20px;"></td>
			<th style="width: 90px;"><big>'.$hofArray['cumulRess'][$i]['D']['pseudo'].'</big></th>
			<th style="width: 75px;"><big>'.number_format((int)$hofArray['cumulRess'][$i]['D']['cumul'], 0, ',', ' ').'</big></th>
			<td style="width: 20px;"></td>
			<th style="width: 90px;"><big>'.$hofArray['cumulRess'][$i]['A']['pseudo'].'</big></th>
			<th style="width: 75px;"><big>'.number_format((int)$hofArray['cumulRess'][$i]['A']['cumul'], 0, ',', ' ').'</big></th>
		</tr>';
}

$pageHOF .= <<<HEREHOF
	</tbody>
</table>
<br />
<br />
<br />
<br />

Classement total vaisseau :

<table style="text-align: left; height: 150px;" border="0" cellpadding="2" cellspacing="2">
	<tbody>
		<tr>
			<td class="c" style="width: 20px;">#</td>
			<td class="c" style="width: 100px;"><big>Pseudo</big></td>
			<td class="c" style="width: 100px;"><big>Points</big></td>
		</tr>	
HEREHOF;
for($i = 0 ; $i < 10 ; $i++)
{
	$indice = $i + 1;
	$pageHOF .= '
		<tr>
			<td class="c" style="width: 20px;">'.$indice.'</td>
			<th style="width: 100px;"><big>'.$hofArray['cumulFleet'][$i]['pseudo'].'</big></th>
			<th style="width: 75px;"><big>'.number_format((int)$hofArray['cumulFleet'][$i]['cumul'], 0, ',', ' ').'</big></th>
		</tr>';
}

$pageHOF .= <<<HEREHOF
	</tbody>
</table>
<br />
<br />
<br />
<br />

Classement meilleure eXpedition ressource :

<table style="text-align: left; height: 150px;" border="0" cellpadding="2" cellspacing="2">
	<tbody>
		<tr>
			<td class="c" style="width: 20px;">#</td>
			<td class="c" style="width: 100px;"><big>Pseudo</big></td>
			<td class="c" style="width: 75px;"><big>Métal</big></td>
			<td style="width: 20px;"></td>
			<td class="c" style="width: 90px;"><big>Pseudo</big></td>
			<td class="c" style="width: 75px;"><big>Cristal</big></td>
			<td style="width: 20px;"></td>
			<td class="c" style="width: 90px;"><big>Pseudo</big></td>
			<td class="c" style="width: 75px;"><big>Deutérium</big></td>
			<td style="width: 20px;"></td>
			<td class="c" style="width: 90px;"><big>Pseudo</big></td>
			<td class="c" style="width: 75px;"><big>Antimatière</big></td>

		</tr>
HEREHOF;
for($i = 0 ; $i < 10 ; $i++)
{
	$indice = $i + 1;
	$pageHOF .= '
		<tr>
			<td class="c" style="width: 20px;">'.$indice.'</td>
			<th style="width: 100px;"><big>'.$hofArray['ress'][$i]['M']['pseudo'].'</big></th>
			<th style="width: 75px;"><big>'.number_format((int)$hofArray['ress'][$i]['M']['quantite'], 0, ',', ' ').'</big></th>
			<td style="width: 20px;"></td>
			<th style="width: 90px;"><big>'.$hofArray['ress'][$i]['C']['pseudo'].'</big></th>
			<th style="width: 75px;"><big>'.number_format((int)$hofArray['ress'][$i]['C']['quantite'], 0, ',', ' ').'</big></th>
			<td style="width: 20px;"></td>
			<th style="width: 90px;"><big>'.$hofArray['ress'][$i]['D']['pseudo'].'</big></th>
			<th style="width: 75px;"><big>'.number_format((int)$hofArray['ress'][$i]['D']['quantite'], 0, ',', ' ').'</big></th>
			<td style="width: 20px;"></td>
			<th style="width: 90px;"><big>'.$hofArray['ress'][$i]['A']['pseudo'].'</big></th>
			<th style="width: 75px;"><big>'.number_format((int)$hofArray['ress'][$i]['A']['quantite'], 0, ',', ' ').'</big></th>
		</tr>';
}

$pageHOF .= <<<HEREHOF
	</tbody>
</table>
<br />
<br />
<br />
<br />

Classement meilleure eXpedition vaisseau :

<table style="text-align: left; height: 150px;" border="0" cellpadding="2" cellspacing="2">
	<tbody>
		<tr>
			<td class="c" style="width: 20px;">#</td>
			<td class="c" style="width: 100px;"><big>Pseudo</big></td>
			<td class="c" style="width: 100px;"><big>Points</big></td>
		</tr>	
HEREHOF;

for($i = 0 ; $i < 10 ; $i++)
{
	$indice = $i + 1;
	$pageHOF .= '
		<tr>
			<td class="c" style="width: 20px;">'.$indice.'</td>
			<th style="width: 100px;"><big>'.$hofArray['fleet'][$i]['pseudo'].'</big></th>
			<th style="width: 75px;"><big>'.number_format((int)$hofArray['fleet'][$i]['cumul'], 0, ',', ' ').'</big></th>
		</tr>';
}

$pageHOF .= <<<HEREHOF
	</tbody>
</table>
<br />



<!-- FIN Insertion mod eXpedition : HOF -->



HEREHOF;

//affichage de la page
echo($pageHOF);

?>
