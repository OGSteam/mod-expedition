<?php

// Hack protection
if (!defined('IN_SPYOGAME')) die("Hacking attempt");

require_once('functions.php');

if ( isset($pub_subaction) && $pub_subaction=="global" )
	$user_id = 0;
else
	$user_id = $user_data['user_id'];


$temp = $db->sql_query("SELECT min(date) FROM ".TABLE_EXPEDITION." WHERE user_id = $user_id");
list($minDate) = $db->sql_fetch_row($temp);

$expeditionDataTotal = readDB($user_id, 0 , time());
$expeditionDataMonth = readDB($user_id, mktime(0,0,0,date('m'),1,date('Y')), mktime(0,0,0,date('m')+1,1,date('Y')));
$currentMonth        = date('m/Y');
$firstExp            = date('d/m/Y', $minDate);

// Pas de valeur décimale pour les points
$expeditionDataTotal['totPtRess']  = (int)round($expeditionDataTotal['totPtRess']);
$expeditionDataMonth['totPtRess']  = (int)round($expeditionDataMonth['totPtRess']);
$expeditionDataTotal['totUFleet']  = (int)round($expeditionDataTotal['totUFleet']);
$expeditionDataMonth['totUFleet']  = (int)round($expeditionDataMonth['totUFleet']);
$expeditionDataTotal['moyUVFleet'] = (int)round($expeditionDataTotal['moyUVFleet']);
$expeditionDataMonth['moyUVFleet'] = (int)round($expeditionDataMonth['moyUVFleet']);
$expeditionDataTotal['moyUFleet']  = (int)round($expeditionDataTotal['moyUFleet']);
$expeditionDataMonth['moyUFleet']  = (int)round($expeditionDataMonth['moyUFleet']);

// Un peu de mise en forme
foreach ($expeditionDataTotal as &$value){
	if (is_float($value))
		$value = number_format($value, 2, ',', ' ');
	else
		$value = number_format($value, 0, ',', ' ');	
}
foreach ($expeditionDataMonth as &$value){
	if (is_float($value))
		$value = number_format($value, 2, ',', ' ');
	else
		$value = number_format($value, 0, ',', ' ');	
}

include('header.php');

$template = <<<HERESTAT
<br />
<br />
<br />

<!-- DEBUT Insertion mod eXpedition : Stat -->

<table style="text-align: left; height: 150px;" border="0" cellpadding="2" cellspacing="2">
	<tbody>
		<tr>
			<td style="width: 500px;"></td>
			<td class="c" style="width: 100px;"><big>Depuis le $firstExp</big></td>
			<td class="c" style="width: 100px;"><big>Pour le mois $currentMonth</big></td>
		</tr>
		<tr>
			<td class="c" style="width: 500px;"><big>Nombre total d'eXpedition :</big></td>
			<td class="c" style="width: 100px;"><big>$expeditionDataTotal[nbTotal]</big></td>
			<td class="c" style="width: 100px;"><big>$expeditionDataMonth[nbTotal]</big></td>
		</tr>
		<tr>
			<td class="c" style="width: 500px;"><big>Nombre d'eXpeditions ayant ramené des ressources :</big></td>
			<td class="c" style="width: 100px;"><big>$expeditionDataTotal[nbRess] ($expeditionDataTotal[prcRess] %)</big></td>
			<td class="c" style="width: 100px;"><big>$expeditionDataMonth[nbRess] ($expeditionDataMonth[prcRess] %)</big></td>
		</tr>
		<tr>
			<td class="c" style="width: 500px;"><big>Nombre d'eXpeditions ayant ramené des vaisseaux :</big></td>
			<td class="c" style="width: 100px;"><big>$expeditionDataTotal[nbFleet] ($expeditionDataTotal[prcFleet] %)</big></td>
			<td class="c" style="width: 100px;"><big>$expeditionDataMonth[nbFleet] ($expeditionDataMonth[prcFleet] %)</big></td>
		</tr>
		<tr>
			<td class="c" style="width: 500px;"><big>Nombre d'eXpeditions ayant ramené un marchand :</big></td>
			<td class="c" style="width: 100px;"><big>$expeditionDataTotal[nbMerch] ($expeditionDataTotal[prcMerch] %)</big></td>
			<td class="c" style="width: 100px;"><big>$expeditionDataMonth[nbMerch] ($expeditionDataMonth[prcMerch] %)</big></td>
		</tr>
		<tr>
			<td class="c" style="width: 500px;"><big>Nombre d'eXpeditions n'ayant rien ramené :</big></td>
			<td class="c" style="width: 100px;"><big>$expeditionDataTotal[nbNul] ($expeditionDataTotal[prcNul] %)</big></td>
			<td class="c" style="width: 100px;"><big>$expeditionDataMonth[nbNul] ($expeditionDataMonth[prcNul] %)</big></td>
		</tr>
	</tbody>
</table>
<br />
<br />
<br />
<span style="font-weight: bold;">
	<big>Total des ressources récoltées :</big>
</span>
<br />
<br />
<br />
<table style="text-align: left; " border="0" cellpadding="2" cellspacing="2">
	<tbody>
		<tr>
			<td class="c" style="width: 225px; font-weight: bold;">En tout depuis le $firstExp :</td>
			<td class="c" style="width: 125px;"></td>
			<td style="width: 50px;"></td>
			<td class="c" style="width: 225px; font-weight: bold;">Pour le mois $currentMonth :</td>
			<td class="c" style="width: 125px;"></td>
		</tr>
		<tr>
			<th style="width: 225px;">Métal</th>
			<th style="width: 125px;">$expeditionDataTotal[sumMetal]</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">Métal</th>
			<th style="width: 125px;">$expeditionDataMonth[sumMetal]</th>
		</tr>
		<tr>
			<th style="width: 225px;">Cristal </th>
			<th style="width: 125px;">$expeditionDataTotal[sumCristal]</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">Cristal</th>
			<th style="width: 125px;">$expeditionDataMonth[sumCristal]</th>
		</tr>
		<tr>
			<th style="width: 225px;">Deutérium</th>
			<th style="width: 125px;">$expeditionDataTotal[sumDeuterium]</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">Deutérium</th>
			<th style="width: 125px;">$expeditionDataMonth[sumDeuterium]</th>
		</tr>
		<tr>
			<th style="width: 225px;">Antimatière</th>
			<th style="width: 125px;">$expeditionDataTotal[sumAntimatiere]</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">Antimatière</th>
			<th style="width: 125px;">$expeditionDataMonth[sumAntimatiere]</th>
		</tr>
		<tr>
			<td class="c" style="width: 225px; font-weight: bold;">Moyenne par eXp :</td>
			<td class="c" style="width: 125px;">$expeditionDataTotal[moyRess]</td>
			<td style="width: 50px;"></td>
			<td class="c" style="width: 225px; font-weight: bold;">Moyenne par eXp :</td>
			<td class="c" style="width: 125px;">$expeditionDataMonth[moyRess]</td>
		</tr>
		<tr>
			<td class="c" style="width: 225px; font-weight: bold;">Total en points :</td>
			<td class="c" style="width: 125px;">$expeditionDataTotal[totPtRess]</td>
			<td style="width: 50px;"></td>
			<td class="c" style="width: 225px; font-weight: bold;">Total en points :</td>
			<td class="c" style="width: 125px;">$expeditionDataMonth[totPtRess]</td>
		</tr>
	</tbody>
</table>
<br />
<br />
<br />
<br />
<br />
<span style="font-weight: bold;">
	<big>Total des vaisseaux ramenés :</big>
</span>
<br />
<br />
<br />
<table style="text-align: left;" border="0" cellpadding="2" cellspacing="2">
	<tbody>
		<tr>
			<td class="c" style="width: 225px; font-weight: bold;">En tout depuis le $firstExp :</td>
			<td class="c" style="width: 125px;"></td>
			<td style="width: 50px;"></td>
			<td class="c" style="width: 225px; font-weight: bold;">Pour le mois $currentMonth :</td>
			<td class="c" style="width: 125px;"></td>
		</tr>
		<tr>
			<th style="width: 225px;">Petit Transporteur</th>
			<th style="width: 125px;">$expeditionDataTotal[sumpt] ($expeditionDataTotal[sumUpt] pts)</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">Petit Transporteur</th>
			<th style="width: 125px;">$expeditionDataMonth[sumpt] ($expeditionDataMonth[sumUpt] pts)</th>
		</tr>
		<tr>
			<th style="width: 225px;">Grand Transporteur</th>
			<th style="width: 125px;">$expeditionDataTotal[sumgt] ($expeditionDataTotal[sumUgt] pts)</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">Grand Transporteur</th>
			<th style="width: 125px;">$expeditionDataMonth[sumgt] ($expeditionDataMonth[sumUgt] pts)</th>
		</tr>
		<tr>
			<th style="width: 225px;">Chasseur Léger</th>
			<th style="width: 125px;">$expeditionDataTotal[sumcle] ($expeditionDataTotal[sumUcle] pts)</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">Chasseur Léger</th>
			<th style="width: 125px;">$expeditionDataMonth[sumcle] ($expeditionDataMonth[sumUcle] pts)</th>
		</tr>
		<tr>
			<th style="width: 225px;">Chasseur Lourd</th>
			<th style="width: 125px;">$expeditionDataTotal[sumclo] ($expeditionDataTotal[sumUclo] pts)</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">Chasseur Lourd</th>
			<th style="width: 125px;">$expeditionDataMonth[sumclo] ($expeditionDataMonth[sumUclo] pts)</th>
		</tr>
		<tr>
			<th style="width: 225px;">Croiseur</th>
			<th style="width: 125px;">$expeditionDataTotal[sumcr] ($expeditionDataTotal[sumUcr] pts)</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">Croiseur</th>
			<th style="width: 125px;">$expeditionDataMonth[sumcr] ($expeditionDataMonth[sumUcr] pts)</th>
		</tr>
		<tr>
			<th style="width: 225px;">Vaisseau de Bataille</th>
			<th style="width: 125px;">$expeditionDataTotal[sumvb] ($expeditionDataTotal[sumUvb] pts)</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">Vaisseau de Bataille</th>
			<th style="width: 125px;">$expeditionDataMonth[sumvb] ($expeditionDataMonth[sumUvb] pts)</th>
		</tr>
		<tr>
			<th style="width: 225px;">Vaisseau de Colonisation</th>
			<th style="width: 125px;">$expeditionDataTotal[sumvc] ($expeditionDataTotal[sumUvc] pts)</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">Vaisseau de Colonisation</th>
			<th style="width: 125px;">$expeditionDataMonth[sumvc] ($expeditionDataMonth[sumUvc] pts)</th>
		</tr>
		<tr>
			<th style="width: 225px;">Recycleur</th>
			<th style="width: 125px;">$expeditionDataTotal[sumrec] ($expeditionDataTotal[sumUrec] pts)</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">Recycleur</th>
			<th style="width: 125px;">$expeditionDataMonth[sumrec] ($expeditionDataMonth[sumUrec] pts)</th>
		</tr>
		<tr>
			<th style="width: 225px;">Sonde d'Espionnage</th>
			<th style="width: 125px;">$expeditionDataTotal[sumse] ($expeditionDataTotal[sumUse] pts)</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">Sonde d'Espionnage</th>
			<th style="width: 125px;">$expeditionDataMonth[sumse] ($expeditionDataMonth[sumUse] pts)</th>
		</tr>
		<tr>
			<th style="width: 225px;">Bombardier</th>
			<th style="width: 125px;">$expeditionDataTotal[sumbmb] ($expeditionDataTotal[sumUbmb] pts)</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">Bombardier</th>
			<th style="width: 125px;">$expeditionDataMonth[sumbmb] ($expeditionDataMonth[sumUbmb] pts)</th>
		</tr>
		<tr>
			<th style="width: 225px;">Destructeur</th>
			<th style="width: 125px;">$expeditionDataTotal[sumdst] ($expeditionDataTotal[sumUdst] pts)</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">Destructeur</th>
			<th style="width: 125px;">$expeditionDataMonth[sumdst] ($expeditionDataMonth[sumUdst] pts)</th>
		</tr>
		<tr>
			<th style="width: 225px;">Traqueur</th>
			<th style="width: 125px;">$expeditionDataTotal[sumtra] ($expeditionDataTotal[sumUtra] pts)</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">Traqueur</th>
			<th style="width: 125px;">$expeditionDataMonth[sumtra] ($expeditionDataMonth[sumUtra] pts)</th>
		</tr>
		<tr>
			<td class="c" style="width: 225px; font-weight: bold;">Moyenne de vaisseaux par eXp:</td>
			<td class="c" style="width: 125px;">$expeditionDataTotal[moyFleet]</td>
			<td style="width: 50px;"></td>
			<td class="c" style="width: 225px; font-weight: bold;">Moyenne de vaisseaux par eXp:</td>
			<td class="c" style="width: 125px;">$expeditionDataMonth[moyFleet]</td>
		</tr>
		<tr>
			<td class="c" style="width: 225px; font-weight: bold;">Moyenne de points par eXp:</td>
			<td class="c" style="width: 125px;">$expeditionDataTotal[moyUFleet]</td>
			<td style="width: 50px;"></td>
			<td class="c" style="width: 225px; font-weight: bold;">Moyenne de points par eXp :</td>
			<td class="c" style="width: 125px;">$expeditionDataMonth[moyUFleet]</td>
		</tr>		
		<tr>
			<td class="c" style="width: 225px; font-weight: bold;">Total de vaisseaux ramenés :</td>
			<td class="c" style="width: 125px;">$expeditionDataTotal[totFleet]</td>
			<td style="width: 50px;"></td>
			<td class="c" style="width: 225px; font-weight: bold;">Total de vaisseaux ramenés  :</td>
			<td class="c" style="width: 125px;">$expeditionDataMonth[totFleet]</td>
		</tr>		<tr>
			<td class="c" style="width: 225px; font-weight: bold;">Total en points :</td>
			<td class="c" style="width: 125px;">$expeditionDataTotal[totUFleet]</td>
			<td style="width: 50px;"></td>
			<td class="c" style="width: 225px; font-weight: bold;">Total en points :</td>
			<td class="c" style="width: 125px;">$expeditionDataMonth[totUFleet]</td>
		</tr>
	</tbody>
</table>
<br />
<br />
<br />
<br />
<br />
<br />
<span style="font-weight: bold;">
	<big>Total des marchands réquisitionnés :</big>
</span>
<br />
<br />
<br />
<table style="text-align: left;" border="0" cellpadding="2" cellspacing="2">
	<tbody>
		<tr>
			<td class="c" style="width: 225px; font-weight: bold;">En tout depuis le $firstExp :</td>
			<td class="c" style="width: 125px;"></td>
			<td style="width: 50px;"></td>
			<td class="c" style="width: 225px; font-weight: bold;">Pour le mois $currentMonth :</td>
			<td class="c" style="width: 125px;"></td>
		</tr>
		<tr>
			<th style="width: 225px;">Total</th>
			<th style="width: 125px;">$expeditionDataTotal[sumM]</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">Total</th>
			<th style="width: 125px;">$expeditionDataMonth[sumM]</th>
		</tr>
	</tbody>
</table>
<br>
<!-- FIN Insertion mod eXpedition : Stat -->
HERESTAT;

echo $template;
/*echo $pie_type_total;
echo $pie_type_month;*/
?>