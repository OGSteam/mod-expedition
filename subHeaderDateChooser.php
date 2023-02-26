<?php

if (!defined('IN_SPYOGAME')) die("Hacking attempt"); // Pas daccès direct

$datePickerFolder = FOLDER_EXP."/datePicker";


// Pour le module détail
if(isset($pub_subaction))
{
	if($pub_subaction == 'detailAll')
	{
		$typeUser = 0;
	}
	else if($pub_subaction == 'detail')
	{
 		$typeUser = $user_data['user_id'];
	}
	else
	{
		$typeUser = '';
	}
}
else
{
	$typeUser = '';
	$pub_subaction = '';
}

$aujourdhuiMinuit = mktime(0,0,0);
$nouvelanMinuit = mktime(0,0,0, 1, 1);
$debutDuMois = mktime(0, 0, 0, date('m'), 1, date('Y'));

if(!isset($pub_datedebut))
{
	if($pub_subaction == 'detail')
	{	if(date('d') < 15)
		{
			$quinzaine = 1;
			$titrePeriode = "eXpeditions depuis le début du mois :";
		}
		else
		{
			$quinzaine = 15;
			$titrePeriode = "eXpeditions depuis le début de la deuxième quinzaine du mois :";
		}
		$datedebut =  mktime(0, 0, 0, date('m'), $quinzaine, date('Y')); // début du mois
	}

	if($pub_subaction == 'detailAll')
	{
	    	$datedebut =  0; // depuis toujours
	  	$titrePeriode = "eXpeditions depuis toujours :";
	}
	$datefin =  99999999999;     //maintenant (la fin des temps...)

}
else
{
	if(preg_match("#(\d{2})-(\d{2})-(\d{4})#", $pub_datedebut, $dat))
	{
		$pub_datedebut = mktime(0, 0, 0, $dat[2], $dat[1], $dat[3]);
	}
	if(!isset($pub_datefin))
	{
		$datedebut = $pub_datedebut;
		$datefin =  99999999999;     //maintenant (la fin des temps...)
		$titrePeriode = "eXpeditions depuis le ".date('d/m/Y', $datedebut)." jusqu'à maintenant :";
	}
	else
	{
		if(preg_match("#(\d{2})-(\d{2})-(\d{4})#", $pub_datefin, $dat))
		{
			$pub_datefin = mktime(0, 0, 0, $dat[2], $dat[1], $dat[3]);
		}
		$datedebut = $pub_datedebut;
		$datefin   = $pub_datefin;
		if($datedebut > $datefin)
		{
			$datefin = $datedebut + 1;
		}
		$titrePeriode = "eXpeditions dans la période allant du ".date('d/m/Y', $datedebut)." au ".date('d/m/Y', $datefin)." :";
	}
}

$pageSubHeader = <<<HERESUBHEADER

<!-- DEBUT Insertion mod eXpedition : Detail -->
<br /><br /><br />
<script type="text/javascript" src="$datePickerFolder/js.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="$datePickerFolder/css.css" />

<span style="font-weight: bold;">
Voir eXpeditions du
<a href="index.php?action=eXpedition&module=$pub_subaction&subaction=$pub_subaction&datedebut=$aujourdhuiMinuit"> jour</a>,
<a href="index.php?action=eXpedition&module=$pub_subaction&subaction=$pub_subaction&datedebut=$debutDuMois"> mois</a>, de l'
<a href="index.php?action=eXpedition&module=$pub_subaction&subaction=$pub_subaction&datedebut=$nouvelanMinuit"> année</a>, ou encore
<a href="index.php?action=eXpedition&module=$pub_subaction&subaction=$pub_subaction&datedebut=0"> depuis toujours !</a>
<br />
<br />
<br />
<form name='form' method='get' action='index.php'>
	ou dans la période allant du
	<input type="hidden" name="action" value="eXpedition">
	<input type="hidden" name="module" value="$pub_subaction">
	<input type="hidden" name="subaction" value="$pub_subaction">
	<input type="text" name="datedebut">
	<input type=button value="Calendrier" onclick="displayDatePicker('datedebut', false, 'dmy', '-');">
 au
	<input type="text" name="datefin">
	<input type=button value="Calendrier" onclick="displayDatePicker('datefin', false, 'dmy', '-');">
	<input value='Valider' type='submit'>
</form>
</span>

<br />
<br />
<br />
HERESUBHEADER;


//affichage de la page
echo($pageSubHeader);
?>
