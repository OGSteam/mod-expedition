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
$expeditionDataDay 	 = readDB($user_id, mktime(0,0,0,date('m'),date('d'),date('Y')), mktime(0,0,0,date('m'),date('d')+1,date('Y')));
$currentMonth        = date('m/Y');
$currentDay			 = date('d F');
$firstExp            = date('d/m/Y', $minDate);

// Pas de valeur décimale pour les points
$expeditionDataTotal['totPtRess']  = (int)round($expeditionDataTotal['totPtRess']);
$expeditionDataMonth['totPtRess']  = (int)round($expeditionDataMonth['totPtRess']);
$expeditionDataDay['totPtRess']    = (int)round($expeditionDataDay['totPtRess']);
$expeditionDataTotal['totUFleet']  = (int)round($expeditionDataTotal['totUFleet']);
$expeditionDataMonth['totUFleet']  = (int)round($expeditionDataMonth['totUFleet']);
$expeditionDataDay['totUFleet']    = (int)round($expeditionDataDay['totUFleet']);
$expeditionDataTotal['moyUVFleet'] = (int)round($expeditionDataTotal['moyUVFleet']);
$expeditionDataMonth['moyUVFleet'] = (int)round($expeditionDataMonth['moyUVFleet']);
$expeditionDataDay['moyUVFleet']   = (int)round($expeditionDataDay['moyUVFleet']);
$expeditionDataTotal['moyUFleet']  = (int)round($expeditionDataTotal['moyUFleet']);
$expeditionDataMonth['moyUFleet']  = (int)round($expeditionDataMonth['moyUFleet']);
$expeditionDataDay['moyUFleet']    = (int)round($expeditionDataDay['moyUFleet']);

//Ajout YopShot
$expeditionDataMonth['totPtGlob']  = (int)round(($expeditionDataMonth[totPtRess]+$expeditionDataMonth[totUFleet]-$expeditionDataMonth[totUFleetLost]));
$expeditionDataTotal['totPtGlob']  = (int)round(($expeditionDataTotal[totPtRess]+$expeditionDataTotal[totUFleet]-$expeditionDataTotal[totUFleetLost]));
$expeditionDataDay['totPtGlob']    = (int)round(($expeditionDataDay[totPtRess]+$expeditionDataDay[totUFleet]-$expeditionDataDay[totUFleetLost]));
$expeditionDataMonth['moyPtGlob']  = (int)round($expeditionDataMonth[totPtGlob]/$expeditionDataMonth[nbTotal]);
$expeditionDataTotal['moyPtGlob']  = (int)round($expeditionDataTotal[totPtGlob]/$expeditionDataTotal[nbTotal]);
$expeditionDataDay['moyPtGlob']    = (int)round($expeditionDataDay[totPtGlob]/$expeditionDataDay[nbTotal]);

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
foreach ($expeditionDataDay as &$value){
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
			<td class="c" style="width: 100px;"><big>Pour le $currentDay</big></td>
		</tr>
		<tr>
			<td class="c" style="width: 500px;"><big>Nombre total d'eXpedition :</big></td>
			<td class="c" style="width: 100px;"><big>$expeditionDataTotal[nbTotal]</big></td>
			<td class="c" style="width: 100px;"><big>$expeditionDataMonth[nbTotal]</big></td>
			<td class="c" style="width: 100px;"><big>$expeditionDataDay[nbTotal]</big></td>
		</tr>
		<tr>
			<td class="c" style="width: 500px;"><big>Nombre de points total acquis :</big></td>
			<td class="c" style="width: 100px;"><big>$expeditionDataTotal[totPtGlob]</big></td>
			<td class="c" style="width: 100px;"><big>$expeditionDataMonth[totPtGlob]</big></td>
			<td class="c" style="width: 100px;"><big>$expeditionDataDay[totPtGlob]</big></td>
		</tr>
		<tr>
			<td class="c" style="width: 500px;"><big>Moyenne de points par eXpedition :</big></td>
			<td class="c" style="width: 100px;"><big>$expeditionDataTotal[moyPtGlob]</big></td>
			<td class="c" style="width: 100px;"><big>$expeditionDataMonth[moyPtGlob]</big></td>
			<td class="c" style="width: 100px;"><big>$expeditionDataDay[moyPtGlob]</big></td>
		</tr>
		<tr>
			<td class="c" style="width: 500px;"><big>Nombre d'eXpeditions ayant ramené des ressources :</big></td>
			<td class="c" style="width: 100px;"><big>$expeditionDataTotal[nbRess] ($expeditionDataTotal[prcRess] %)</big></td>
			<td class="c" style="width: 100px;"><big>$expeditionDataMonth[nbRess] ($expeditionDataMonth[prcRess] %)</big></td>
			<td class="c" style="width: 100px;"><big>$expeditionDataDay[nbRess] ($expeditionDataDay[prcRess] %)</big></td>
		</tr>
		<tr>
			<td class="c" style="width: 500px;"><big>Nombre d'eXpeditions ayant ramené des vaisseaux :</big></td>
			<td class="c" style="width: 100px;"><big>$expeditionDataTotal[nbFleet] ($expeditionDataTotal[prcFleet] %)</big></td>
			<td class="c" style="width: 100px;"><big>$expeditionDataMonth[nbFleet] ($expeditionDataMonth[prcFleet] %)</big></td>
			<td class="c" style="width: 100px;"><big>$expeditionDataDay[nbFleet] ($expeditionDataDay[prcFleet] %)</big></td>
		</tr>
		<tr>
			<td class="c" style="width: 500px;"><big>Nombre d'eXpeditions ayant ramené un marchand :</big></td>
			<td class="c" style="width: 100px;"><big>$expeditionDataTotal[nbMerch] ($expeditionDataTotal[prcMerch] %)</big></td>
			<td class="c" style="width: 100px;"><big>$expeditionDataMonth[nbMerch] ($expeditionDataMonth[prcMerch] %)</big></td>
			<td class="c" style="width: 100px;"><big>$expeditionDataDay[nbMerch] ($expeditionDataDay[prcMerch] %)</big></td>
		</tr>
		<tr>
			<td class="c" style="width: 500px;"><big>Nombre d'eXpeditions ayant ramené un objet :</big></td>
			<td class="c" style="width: 100px;"><big>$expeditionDataTotal[nbItems] ($expeditionDataTotal[prcItems] %)</big></td>
			<td class="c" style="width: 100px;"><big>$expeditionDataMonth[nbItems] ($expeditionDataMonth[prcItems] %)</big></td>
			<td class="c" style="width: 100px;"><big>$expeditionDataDay[nbItems] ($expeditionDataDay[prcItems] %)</big></td>
		</tr>				
		<tr>
			<td class="c" style="width: 500px;"><big>Nombre d'eXpeditions n'ayant rien ramené :</big></td>
			<td class="c" style="width: 100px;"><big>$expeditionDataTotal[nbNul] ($expeditionDataTotal[prcNul] %)</big></td>
			<td class="c" style="width: 100px;"><big>$expeditionDataMonth[nbNul] ($expeditionDataMonth[prcNul] %)</big></td>
			<td class="c" style="width: 100px;"><big>$expeditionDataDay[nbNul] ($expeditionDataDay[prcNul] %)</big></td>
		</tr>
		<tr>
			<td class="c" style="width: 500px;"><big>Nombre d'eXpeditions ayant subit une attaque :</big></td>
			<td class="c" style="width: 100px;"><big>$expeditionDataTotal[nbAttacks] ($expeditionDataTotal[prcAttacks] %)</big></td>
			<td class="c" style="width: 100px;"><big>$expeditionDataMonth[nbAttacks] ($expeditionDataMonth[prcAttacks] %)</big></td>
			<td class="c" style="width: 100px;"><big>$expeditionDataDay[nbAttacks] ($expeditionDataDay[prcAttacks] %)</big></td>
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
			<th style="width: 225px;">Faucheur</th>
			<th style="width: 125px;">$expeditionDataTotal[sumfau] ($expeditionDataTotal[sumUfau] pts)</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">Faucheur</th>
			<th style="width: 125px;">$expeditionDataMonth[sumfau] ($expeditionDataMonth[sumUfau] pts)</th>
		</tr>
		<tr>
			<th style="width: 225px;">Eclaireur</th>
			<th style="width: 125px;">$expeditionDataTotal[sumecl] ($expeditionDataTotal[sumUecl] pts)</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">Eclaireur</th>
			<th style="width: 125px;">$expeditionDataMonth[sumecl] ($expeditionDataMonth[sumUecl] pts)</th>
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
<br />
<br />
<br />
<br />
<br />
<br />
<span style="font-weight: bold;">
	<big>Total des objets ramenés :</big>
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
			<th style="width: 225px;">Booster de métal en bronze</th>
			<th style="width: 125px;">$expeditionDataTotal[itemMetalbronze]</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">Booster de métal en bronze</th>
			<th style="width: 125px;">$expeditionDataMonth[itemMetalbronze]</th>
		</tr>
        <tr>
			<th style="width: 225px;">Booster de métal en argent</th>
			<th style="width: 125px;">$expeditionDataTotal[itemMetalargent]</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">Booster de métal en argent</th>
			<th style="width: 125px;">$expeditionDataMonth[itemMetalargent]</th>
		</tr>
		<tr>
			<th style="width: 225px;">Booster de métal en or</th>
			<th style="width: 125px;">$expeditionDataTotal[itemMetalor]</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">Booster de métal en or</th>
			<th style="width: 125px;">$expeditionDataMonth[itemMetalor]</th>
		</tr>
		<tr>
			<th style="width: 225px;">Booster de cristal en bronze</th>
			<th style="width: 125px;">$expeditionDataTotal[itemCristalbronze]</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">Booster de cristal en bronze</th>
			<th style="width: 125px;">$expeditionDataMonth[itemCristalbronze]</th>
		</tr>
        <tr>
			<th style="width: 225px;">Booster de cristal en argent</th>
			<th style="width: 125px;">$expeditionDataTotal[itemCristalargent]</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">Booster de cristal en argent</th>
			<th style="width: 125px;">$expeditionDataMonth[itemCristalargent]</th>
		</tr>
		<tr>
			<th style="width: 225px;">Booster de cristal en or</th>
			<th style="width: 125px;">$expeditionDataTotal[itemCristalor]</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">Booster de cristal en or</th>
			<th style="width: 125px;">$expeditionDataMonth[itemCristalor]</th>
		</tr>
		<tr>
			<th style="width: 225px;">Booster de deutérium en bronze</th>
			<th style="width: 125px;">$expeditionDataTotal[itemDeutbronze]</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">Booster de deutérium en bronze</th>
			<th style="width: 125px;">$expeditionDataMonth[itemDeutbronze]</th>
		</tr>
        <tr>
			<th style="width: 225px;">Booster de deutérium en argent</th>
			<th style="width: 125px;">$expeditionDataTotal[itemDeutargent]</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">Booster de deutérium en argent</th>
			<th style="width: 125px;">$expeditionDataMonth[itemDeutargent]</th>
		</tr>
		<tr>
			<th style="width: 225px;">Booster de deutérium en or</th>
			<th style="width: 125px;">$expeditionDataTotal[itemDeutor]</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">Booster de deutérium en or</th>
			<th style="width: 125px;">$expeditionDataMonth[itemDeutor]</th>
		</tr>
		<tr>
			<th style="width: 225px;">KRAKEN en bronze</th>
			<th style="width: 125px;">$expeditionDataTotal[itemKrakenbronze]</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">KRAKEN en bronze</th>
			<th style="width: 125px;">$expeditionDataMonth[itemKrakenbronze]</th>
		</tr>
        <tr>
			<th style="width: 225px;">KRAKEN en argent</th>
			<th style="width: 125px;">$expeditionDataTotal[itemKrakenargent]</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">KRAKEN en argent</th>
			<th style="width: 125px;">$expeditionDataMonth[itemKrakenargent]</th>
		</tr>
		<tr>
			<th style="width: 225px;">KRAKEN en or</th>
			<th style="width: 125px;">$expeditionDataTotal[itemKrakenor]</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">KRAKEN en or</th>
			<th style="width: 125px;">$expeditionDataMonth[itemKrakenor]</th>
		</tr>
		<tr>
			<th style="width: 225px;">DETROID en bronze</th>
			<th style="width: 125px;">$expeditionDataTotal[itemDetroidbronze]</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">DETROID en bronze</th>
			<th style="width: 125px;">$expeditionDataMonth[itemDetroidbronze]</th>
		</tr>
        <tr>
			<th style="width: 225px;">DETROID en argent</th>
			<th style="width: 125px;">$expeditionDataTotal[itemDetroidargent]</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">DETROID en argent</th>
			<th style="width: 125px;">$expeditionDataMonth[itemDetroidargent]</th>
		</tr>
		<tr>
			<th style="width: 225px;">DETROID en or</th>
			<th style="width: 125px;">$expeditionDataTotal[itemDetroidor]</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">DETROID en or</th>
			<th style="width: 125px;">$expeditionDataMonth[itemDetroidor]</th>
		</tr>
		<tr>
			<th style="width: 225px;">NEWTRON en bronze</th>
			<th style="width: 125px;">$expeditionDataTotal[itemNewtronbronze]</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">NEWTRON en bronze</th>
			<th style="width: 125px;">$expeditionDataMonth[itemNewtronbronze]</th>
		</tr>
        <tr>
			<th style="width: 225px;">NEWTRON en argent</th>
			<th style="width: 125px;">$expeditionDataTotal[itemNewtronargent]</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">NEWTRON en argent</th>
			<th style="width: 125px;">$expeditionDataMonth[itemNewtronargent]</th>
		</tr>
		<tr>
			<th style="width: 225px;">NEWTRON en or</th>
			<th style="width: 125px;">$expeditionDataTotal[itemNewtronor]</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">NEWTRON en or</th>
			<th style="width: 125px;">$expeditionDataMonth[itemNewtronor]</th>
		</tr>
		<tr>
			<td class="c" style="width: 225px; font-weight: bold;">Total :</td>
			<td class="c" style="width: 125px;">$expeditionDataTotal[totItems]</td>
			<td style="width: 50px;"></td>
			<td class="c" style="width: 225px; font-weight: bold;">Total :</td>
			<td class="c" style="width: 125px;">$expeditionDataMonth[totItems]</td>
		</tr>														
	</tbody>
</table>
<br />
<br />
<br />
<br />
<br />
<span style="font-weight: bold;">
	<big>Total des vaisseaux perdus :</big>
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
			<th style="width: 125px;">$expeditionDataTotal[sumptLost] ($expeditionDataTotal[sumUptLost] pts)</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">Petit Transporteur</th>
			<th style="width: 125px;">$expeditionDataMonth[sumptLost] ($expeditionDataMonth[sumUptLost] pts)</th>
		</tr>
		<tr>
			<th style="width: 225px;">Grand Transporteur</th>
			<th style="width: 125px;">$expeditionDataTotal[sumgtLost] ($expeditionDataTotal[sumUgtLost] pts)</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">Grand Transporteur</th>
			<th style="width: 125px;">$expeditionDataMonth[sumgtLost] ($expeditionDataMonth[sumUgtLost] pts)</th>
		</tr>
		<tr>
			<th style="width: 225px;">Chasseur Léger</th>
			<th style="width: 125px;">$expeditionDataTotal[sumcleLost] ($expeditionDataTotal[sumUcleLost] pts)</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">Chasseur Léger</th>
			<th style="width: 125px;">$expeditionDataMonth[sumcleLost] ($expeditionDataMonth[sumUcleLost] pts)</th>
		</tr>
		<tr>
			<th style="width: 225px;">Chasseur Lourd</th>
			<th style="width: 125px;">$expeditionDataTotal[sumcloLost] ($expeditionDataTotal[sumUcloLost] pts)</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">Chasseur Lourd</th>
			<th style="width: 125px;">$expeditionDataMonth[sumcloLost] ($expeditionDataMonth[sumUcloLost] pts)</th>
		</tr>
		<tr>
			<th style="width: 225px;">Croiseur</th>
			<th style="width: 125px;">$expeditionDataTotal[sumcrLost] ($expeditionDataTotal[sumUcrLost] pts)</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">Croiseur</th>
			<th style="width: 125px;">$expeditionDataMonth[sumcrLost] ($expeditionDataMonth[sumUcrLost] pts)</th>
		</tr>
		<tr>
			<th style="width: 225px;">Vaisseau de Bataille</th>
			<th style="width: 125px;">$expeditionDataTotal[sumvbLost] ($expeditionDataTotal[sumUvbLost] pts)</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">Vaisseau de Bataille</th>
			<th style="width: 125px;">$expeditionDataMonth[sumvbLost] ($expeditionDataMonth[sumUvbLost] pts)</th>
		</tr>
		<tr>
			<th style="width: 225px;">Vaisseau de Colonisation</th>
			<th style="width: 125px;">$expeditionDataTotal[sumvcLost] ($expeditionDataTotal[sumUvcLost] pts)</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">Vaisseau de Colonisation</th>
			<th style="width: 125px;">$expeditionDataMonth[sumvcLost] ($expeditionDataMonth[sumUvcLost] pts)</th>
		</tr>
		<tr>
			<th style="width: 225px;">Recycleur</th>
			<th style="width: 125px;">$expeditionDataTotal[sumrecLost] ($expeditionDataTotal[sumUrecLost] pts)</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">Recycleur</th>
			<th style="width: 125px;">$expeditionDataMonth[sumrecLost] ($expeditionDataMonth[sumUrecLost] pts)</th>
		</tr>
		<tr>
			<th style="width: 225px;">Sonde d'Espionnage</th>
			<th style="width: 125px;">$expeditionDataTotal[sumseLost] ($expeditionDataTotal[sumUseLost] pts)</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">Sonde d'Espionnage</th>
			<th style="width: 125px;">$expeditionDataMonth[sumseLost] ($expeditionDataMonth[sumUseLost] pts)</th>
		</tr>
		<tr>
			<th style="width: 225px;">Bombardier</th>
			<th style="width: 125px;">$expeditionDataTotal[sumbmbLost] ($expeditionDataTotal[sumUbmbLost] pts)</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">Bombardier</th>
			<th style="width: 125px;">$expeditionDataMonth[sumbmbLost] ($expeditionDataMonth[sumUbmbLost] pts)</th>
		</tr>
		<tr>
			<th style="width: 225px;">Destructeur</th>
			<th style="width: 125px;">$expeditionDataTotal[sumdstLost] ($expeditionDataTotal[sumUdstLost] pts)</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">Destructeur</th>
			<th style="width: 125px;">$expeditionDataMonth[sumdstLost] ($expeditionDataMonth[sumUdstLost] pts)</th>
		</tr>
		<tr>
			<th style="width: 225px;">EDLM</th>
			<th style="width: 125px;">$expeditionDataTotal[sumedlmLost] ($expeditionDataTotal[sumUedlmLost] pts)</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">EDLM</th>
			<th style="width: 125px;">$expeditionDataMonth[sumedlmLost] ($expeditionDataMonth[sumUedlmLost] pts)</th>
		</tr>		
		<tr>
			<th style="width: 225px;">Traqueur</th>
			<th style="width: 125px;">$expeditionDataTotal[sumtraLost] ($expeditionDataTotal[sumUtraLost] pts)</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">Traqueur</th>
			<th style="width: 125px;">$expeditionDataMonth[sumtraLost] ($expeditionDataMonth[sumUtraLost] pts)</th>
		</tr>
		<tr>
			<th style="width: 225px;">Faucheur</th>
			<th style="width: 125px;">$expeditionDataTotal[sumfauLost] ($expeditionDataTotal[sumUfauLost] pts)</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">Faucheur</th>
			<th style="width: 125px;">$expeditionDataMonth[sumfauLost] ($expeditionDataMonth[sumUfauLost] pts)</th>
		</tr>
		<tr>
			<th style="width: 225px;">Eclaireur</th>
			<th style="width: 125px;">$expeditionDataTotal[sumeclLost] ($expeditionDataTotal[sumUeclLost] pts)</th>
			<td style="width: 50px;"></td>
			<th style="width: 225px;">Eclaireur</th>
			<th style="width: 125px;">$expeditionDataMonth[sumeclLost] ($expeditionDataMonth[sumUeclLost] pts)</th>
		</tr>
		<tr>
			<td class="c" style="width: 225px; font-weight: bold;">Moyenne de vaisseaux par eXp:</td>
			<td class="c" style="width: 125px;">$expeditionDataTotal[moyFleetLost]</td>
			<td style="width: 50px;"></td>
			<td class="c" style="width: 225px; font-weight: bold;">Moyenne de vaisseaux par eXp:</td>
			<td class="c" style="width: 125px;">$expeditionDataMonth[moyFleetLost]</td>
		</tr>
		<tr>
			<td class="c" style="width: 225px; font-weight: bold;">Moyenne de points par eXp:</td>
			<td class="c" style="width: 125px;">$expeditionDataTotal[moyUFleetLost]</td>
			<td style="width: 50px;"></td>
			<td class="c" style="width: 225px; font-weight: bold;">Moyenne de points par eXp :</td>
			<td class="c" style="width: 125px;">$expeditionDataMonth[moyUFleetLost]</td>
		</tr>		
		<tr>
			<td class="c" style="width: 225px; font-weight: bold;">Total de vaisseaux perdus :</td>
			<td class="c" style="width: 125px;">$expeditionDataTotal[totFleetLost]</td>
			<td style="width: 50px;"></td>
			<td class="c" style="width: 225px; font-weight: bold;">Total de vaisseaux Lost  :</td>
			<td class="c" style="width: 125px;">$expeditionDataMonth[totFleetLost]</td>
		</tr>		<tr>
			<td class="c" style="width: 225px; font-weight: bold;">Total en points :</td>
			<td class="c" style="width: 125px;">$expeditionDataTotal[totUFleetLost]</td>
			<td style="width: 50px;"></td>
			<td class="c" style="width: 225px; font-weight: bold;">Total en points :</td>
			<td class="c" style="width: 125px;">$expeditionDataMonth[totUFleetLost]</td>
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