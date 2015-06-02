<?php

if (!defined('IN_SPYOGAME')) die("Hacking attempt");

// recupérer le nom de quelqu'un
function getuserNameById($uid)
{
  global $db, $eXpDebug;
  $query = 
        "SELECT user_name
        FROM ".TABLE_USER."
        WHERE user_id = $uid ";
  if($eXpDebug) echo("<br /> Db : $query <br />");
          $result = $db->sql_query($query);
  list($nom) = $db->sql_fetch_row($result);
  return $nom;
}

/**
 *
 *   Retourne le contenu de la base pour la période donnée.
 *
 **/
function readDB($uid=0, $dateB=0, $dateE=0)
{
    global $db;
    if ($uid == 0)
      $uid = "[0-9]*";
        
    // Nombre d'expéditions par type
    $query = "SELECT count(*) as nb, type
        FROM ".TABLE_EXPEDITION."
        WHERE user_id REGEXP '$uid'
        AND date BETWEEN $dateB AND $dateE
        GROUP BY TYPE
        ORDER BY TYPE";
    
    $db->sql_query($query);
    
    // Init to avoid PHPNotice
    $expeditionData['nbNul'] = $expeditionData['nbFleet'] = $expeditionData['nbRess'] = $expeditionData['nbMerch'] = 0;
    
    while ( $nb = $db->sql_fetch_assoc(0) ){
        switch ($nb['type']){
            case 0:
                $expeditionData['nbNul'] = $nb['nb'];
                break;
            case 1:
                $expeditionData['nbRess'] = $nb['nb'];
                break;
            case 2:
                $expeditionData['nbFleet'] = $nb['nb'];
                break;
            case 3:
                $expeditionData['nbMerch'] = $nb['nb'];
                break;
        }
    }
    $expeditionData['nbTotal'] = $expeditionData['nbNul'] + $expeditionData['nbRess'] + $expeditionData['nbFleet'] + $expeditionData['nbMerch'];

    
    // Nombre de ressources récoltées
    $query = "SELECT sum(metal), sum(cristal), sum(deuterium), sum(antimatiere)
             FROM ".TABLE_EXPEDITION." AS exp,
             ".TABLE_EXPEDITION_RESS." AS expRess
             WHERE exp.id = expRess.id_eXpedition
             AND user_id REGEXP '$uid'
             AND date BETWEEN $dateB AND $dateE";
    $result = $db->sql_query($query);
    
    list($expeditionData['sumMetal'], $expeditionData['sumCristal'], $expeditionData['sumDeuterium'], $expeditionData['sumAntimatiere']) = $db->sql_fetch_row($result);


    // Nombre de vaisseaux ramenés
    $query =  "SELECT sum(pt), sum(gt), sum(cle), sum(clo), sum(cr), sum(vb), sum(vc), sum(rec), sum(se), sum(bmb), sum(dst), sum(tra)
	          FROM ".TABLE_EXPEDITION." AS exp, ".TABLE_EXPEDITION_FLEET." AS expFleet
              WHERE exp.id = expFleet.id_eXpedition
              AND user_id REGEXP '$uid'
              AND date BETWEEN $dateB AND $dateE";
    $result = $db->sql_query($query);

  list($expeditionData['sumpt'], $expeditionData['sumgt'], $expeditionData['sumcle'], $expeditionData['sumclo'], $expeditionData['sumcr'], $expeditionData['sumvb'], $expeditionData['sumvc'], $expeditionData['sumrec'], $expeditionData['sumse'], $expeditionData['sumbmb'], $expeditionData['sumdst'], $expeditionData['sumtra']) = $db->sql_fetch_row($result);
	$expeditionData['sumUpt'] 	= $expeditionData['sumpt']*4;
	$expeditionData['sumUgt'] 	= $expeditionData['sumgt']*12;
	$expeditionData['sumUcle'] 	= $expeditionData['sumcle']*4;
	$expeditionData['sumUclo'] 	= $expeditionData['sumclo']*10;
	$expeditionData['sumUcr'] 	= $expeditionData['sumcr']*29;
	$expeditionData['sumUvb']	  = $expeditionData['sumvb']*60;
	$expeditionData['sumUvc'] 	= $expeditionData['sumvc']*40;
	$expeditionData['sumUrec']	= $expeditionData['sumrec']*18;
	$expeditionData['sumUse'] 	= $expeditionData['sumse']*1;
	$expeditionData['sumUbmb']	= $expeditionData['sumbmb']*90;
	$expeditionData['sumUdst']	= $expeditionData['sumdst']*125;
	$expeditionData['sumUtra'] 	= $expeditionData['sumtra']*85;

  // Nombre de marchands Kidnappés
  $query = "SELECT count(*)
           FROM ".TABLE_EXPEDITION." AS exp, ".TABLE_EXPEDITION_MERCH." AS expMerch
           WHERE exp.id = expMerch.id_eXpedition
           AND user_id REGEXP '$uid'
           AND date BETWEEN $dateB AND $dateE ";

	$result = $db->sql_query($query);
    list($expeditionData['sumM']) = $db->sql_fetch_row($result);
       
	// Some Stats
  if($expeditionData['nbTotal'] != 0 ){
  	$expeditionData['prcRess'] = $expeditionData['nbRess'] * 100 / $expeditionData['nbTotal'];
  	$expeditionData['prcFleet'] = $expeditionData['nbFleet'] * 100 / $expeditionData['nbTotal'];
  	$expeditionData['prcMerch'] = $expeditionData['nbMerch'] * 100 / $expeditionData['nbTotal'];
  	$expeditionData['prcNul'] = $expeditionData['nbNul'] * 100 / $expeditionData['nbTotal'];
  }
  else{
    $expeditionData['prcRess'] = $expeditionData['prcFleet'] = $expeditionData['prcMerch'] = $expeditionData['prcNul'] = 0;
  }

	$expeditionData['totRess'] = $expeditionData['sumMetal'] + $expeditionData['sumCristal'] + $expeditionData['sumDeuterium'];
	$expeditionData['totPtRess'] = $expeditionData['totRess'] / 1000;
	$expeditionData['totFleet'] = $expeditionData['sumpt'] + $expeditionData['sumgt'] + $expeditionData['sumcle'] 
								+ $expeditionData['sumclo'] + $expeditionData['sumcr'] + $expeditionData['sumvb'] +
								$expeditionData['sumvc'] + $expeditionData['sumrec'] + $expeditionData['sumse'] +
								$expeditionData['sumbmb'] + $expeditionData['sumdst'] + $expeditionData['sumtra'];
	$expeditionData['totUFleet'] = $expeditionData['sumpt']*4 + $expeditionData['sumgt']*12 + $expeditionData['sumcle']*4
								+ $expeditionData['sumclo']*10 + $expeditionData['sumcr']*29 + $expeditionData['sumvb']*60 +
								$expeditionData['sumvc']*40 + $expeditionData['sumrec']*18 + $expeditionData['sumse'] +
								$expeditionData['sumbmb']*90 + $expeditionData['sumdst']*125 + $expeditionData['sumtra']*85;
	
	if(!$expeditionData['nbTotal'])
	{
		$expeditionData['moyRess'] = 0;
		$expeditionData['moyFleet'] = 0;
		$expeditionData['moyUFleet'] = 0;
	}
	else
	{
		$expeditionData['moyRess']   = $expeditionData['totRess']  /  $expeditionData['nbTotal'];
		$expeditionData['moyFleet']  = $expeditionData['totFleet']   /  $expeditionData['nbTotal'];
		$expeditionData['moyUFleet'] = $expeditionData['totUFleet']  /  $expeditionData['nbTotal'];
	}
	if(!$expeditionData['nbFleet'])
	{
		$expeditionData['moyVFleet'] = 0;
		$expeditionData['moyUVFleet'] = 0;
	}
	else
	{
		$expeditionData['moyVFleet']  = $expeditionData['totFleet']   /  $expeditionData['nbFleet'];
		$expeditionData['moyUVFleet'] = $expeditionData['totUFleet']  /  $expeditionData['nbFleet'];
	}

    return $expeditionData;
}

function create_pie_expedition($_data, $_legend, $_title, $conteneur){
    global $server_config;
    $retour = "";

    // test erreurs donnés
    if (!check_var($_data, "Special", "#^[0-9(_x_)]+$#") || !check_var($_legend,
        "Text") || !check_var($_title, "Text") || !check_var($conteneur, "Text")) {
        $retour .= affiche_error($conteneur, 'erreur 1');
        return $retour;
    }

    //  recuperation des infos
    $data = explode('_x_', $_data);
    $legend = explode('_x_', $_legend);
    $title = $_title;

    // il doit y avoir autant de legende que de valeur
    if (count($data) != count($legend)) {
        $retour .= affiche_error($conteneur, 'erreur 2');
        return $retour;
    }

    // préparation des données
    $i = 0;
    $temp = array();
    while ($i < count($data)) {
        $temp[$i] = "['" . $legend[$i] . "'," . $data[$i] . " ]";
        $i++;
    }
    // format hightchart
    $format_data = implode(" , ", $temp);

    // création du script
    $retour .= "<script type=\"text/javascript\">
  var " . $conteneur . ";
  	$(document).ready(function() {
  	Highcharts.setOptions({
          chart: {
              backgroundColor: 'rgba(0, 0, 0, 0)'
          }
      });

      " . $conteneur . " = new Highcharts.Chart({
        chart: {
           renderTo: '" . $conteneur . "',
           plotBackgroundColor: null,
           plotBorderWidth: null,
           plotShadow: false
        },
        credits: {
          text: '',
      },
        title: {
           text: '" . $title . "'
        },
        tooltip: {
           formatter: function() {
              return '<b>'+ this.point.name +'</b>: '+ this.percentage.toFixed(2) +' %';
           }
        },
        plotOptions: {
           pie: {
              allowPointSelect: true,
              cursor: 'pointer',
              dataLabels: {
                  color: '#ffffff',
                  enabled: true,
                  style: {
  				    fontWeight: 'bold'
  				},
  				formatter: function() {
  					if (this.y != 0) {
  					  return this.point.name;
  					} else {
  					  return null;
  					}
  				}
              },
              showInLegend: false,
  			size: 175			
           }
        },
         series: [{
           type: 'pie',
           name: 'Browser share',
           
           data: [
              " . $format_data . "
           ]
        }]
     });
  }); ";

  $retour .= "</script> ";

  return $retour;
}


function readDBHOF(){

  global $db;

  // HOF CUMUL POINTS GAGNES
  $query = "SELECT (totalRess.totalRess + totalFleet.totalUnits) as total, exp.user_id 
      FROM ".TABLE_EXPEDITION." as exp
      JOIN (SELECT user_id, sum( (metal + cristal + deuterium) / 1000 ) as totalRess
      FROM ".TABLE_EXPEDITION." as exp1
      inner join ".TABLE_EXPEDITION_RESS." as ress on exp1.id = ress.id_eXpedition
      group by user_id) as totalRess
      JOIN (SELECT user_id, sum(units) as totalUnits
      FROM ".TABLE_EXPEDITION." as exp2
      inner join ".TABLE_EXPEDITION_FLEET." as fleet on exp2.id = fleet.id_eXpedition
      group by user_id ) as totalFleet
      WHERE exp.user_id = totalRess.user_id
      GROUP BY exp.user_id
      ORDER BY total DESC
      LIMIT 10";
  
  $result = $db->sql_query($query);
  for($i = 0 ; $i < 10 ; $i ++)
  {
    if($row = $db->sql_fetch_row($result))
    { 
        $cumulPoints[$i]['cumul'] = $row[0];
        $cumulPoints[$i]['pseudo'] = getuserNameById($row[1]);
    }
    else
    {
        $cumulPoints[$i]['cumul'] = "/";
        $cumulPoints[$i]['pseudo']   = "/";
    }
  }

  // HOF METAL RAPPORTE
  $query =  "SELECT sum(metal) as total, user_id
     FROM ".TABLE_EXPEDITION." AS exp
     INNER JOIN ".TABLE_EXPEDITION_RESS." AS ress
     WHERE exp.id = ress.id_eXpedition 
     GROUP BY user_id
     ORDER BY total DESC";   

  $result = $db->sql_query($query);
    for($i = 0 ; $i < 10 ; $i ++)
    {
      if($row = $db->sql_fetch_row($result))
      {
          $cumulRessources[$i]['M']['cumul'] = $row[0];
          $cumulRessources[$i]['M']['pseudo'] = getuserNameById($row[1]);
      }
      else
      {
          $cumulRessources[$i]['M']['cumul'] = "/";
          $cumulRessources[$i]['M']['pseudo']   = "/";
      }
    }

  // HOF CRISTAL RAPPORTE
  $query =  "SELECT sum(cristal) as total, user_id
     FROM ".TABLE_EXPEDITION." AS exp
     INNER JOIN ".TABLE_EXPEDITION_RESS." AS ress
     WHERE exp.id = ress.id_eXpedition 
     GROUP BY user_id
     ORDER BY total DESC";   
  $result = $db->sql_query($query);
    for($i = 0 ; $i < 10 ; $i ++)
    {
      if($row = $db->sql_fetch_row($result))
      {
          $cumulRessources[$i]['C']['cumul'] = $row[0];
          $cumulRessources[$i]['C']['pseudo'] = getuserNameById($row[1]);
      }
      else
      {
          $cumulRessources[$i]['C']['cumul'] = "/";
          $cumulRessources[$i]['C']['pseudo']   = "/";
      }
    }

  // HOF DEUTERIUM RAPPORTE
  $query =  "SELECT sum(deuterium) as total, user_id
     FROM ".TABLE_EXPEDITION." AS exp
     INNER JOIN ".TABLE_EXPEDITION_RESS." AS ress
     WHERE exp.id = ress.id_eXpedition 
     GROUP BY user_id
     ORDER BY total DESC";   
     $result = $db->sql_query($query);
    for($i = 0 ; $i < 10 ; $i ++)
    {
      if($row = $db->sql_fetch_row($result))
      {
          $cumulRessources[$i]['D']['cumul'] = $row[0];
          $cumulRessources[$i]['D']['pseudo'] = getuserNameById($row[1]);
      }
      else
      {
          $cumulRessources[$i]['D']['cumul'] = "/";
          $cumulRessources[$i]['D']['pseudo']   = "/";
      }
    }

  // HOF ANTIMATIERE RAPPORTE
  $query =  "SELECT sum(antimatiere) as total, user_id
     FROM ".TABLE_EXPEDITION." AS exp
     INNER JOIN ".TABLE_EXPEDITION_RESS." AS ress
     WHERE exp.id = ress.id_eXpedition 
     GROUP BY user_id
     ORDER BY total DESC";   
     $result = $db->sql_query($query);
    for($i = 0 ; $i < 10 ; $i ++)
    {
      if($row = $db->sql_fetch_row($result))
      {
          $cumulRessources[$i]['A']['cumul'] = $row[0];
          $cumulRessources[$i]['A']['pseudo'] = getuserNameById($row[1]);
      }
      else
      {
          $cumulRessources[$i]['A']['cumul'] = "/";
          $cumulRessources[$i]['A']['pseudo']   = "/";
      }
    }


  // HOF EXPEDITION METAL
  $query =  "SELECT metal as total, user_id
      FROM ".TABLE_EXPEDITION." AS exp
      INNER JOIN ".TABLE_EXPEDITION_RESS." AS ress
      WHERE exp.id = ress.id_eXpedition 
      ORDER BY total DESC";   

  $result = $db->sql_query($query);
  for($i = 0 ; $i < 10 ; $i ++)
  {
    if($row = $db->sql_fetch_row($result))
    {
        $hofExpedition[$i]['M']['quantite'] = $row[0];
        $hofExpedition[$i]['M']['pseudo'] = getuserNameById($row[1]);
    }
    else
    {
        $hofExpedition[$i]['M']['quantite'] = "/";
        $hofExpedition[$i]['M']['pseudo']   = "/";
    }
  }

  // HOF EXPEDITION CRISTAL
  $query =  "SELECT cristal as total, user_id
      FROM ".TABLE_EXPEDITION." AS exp
      INNER JOIN ".TABLE_EXPEDITION_RESS." AS ress
      WHERE exp.id = ress.id_eXpedition 
      ORDER BY total DESC";   

  $result = $db->sql_query($query);
  for($i = 0 ; $i < 10 ; $i ++)
  {
    if($row = $db->sql_fetch_row($result))
    {
        $hofExpedition[$i]['C']['quantite'] = $row[0];
        $hofExpedition[$i]['C']['pseudo'] = getuserNameById($row[1]);
    }
    else
    {
        $hofExpedition[$i]['C']['quantite'] = "/";
        $hofExpedition[$i]['C']['pseudo']   = "/";
    }
  }

  // HOF EXPEDITION DEUTERIUM
  $query =  "SELECT deuterium as total, user_id
      FROM ".TABLE_EXPEDITION." AS exp
      INNER JOIN ".TABLE_EXPEDITION_RESS." AS ress
      WHERE exp.id = ress.id_eXpedition 
      ORDER BY total DESC";   

  $result = $db->sql_query($query);
  for($i = 0 ; $i < 10 ; $i ++)
  {
    if($row = $db->sql_fetch_row($result))
    {
        $hofExpedition[$i]['D']['quantite'] = $row[0];
        $hofExpedition[$i]['D']['pseudo'] = getuserNameById($row[1]);
    }
    else
    {
        $hofExpedition[$i]['D']['quantite'] = "/";
        $hofExpedition[$i]['D']['pseudo']   = "/";
    }
  }

  // HOF EXPEDITION ANTIMATIERE
  $query =  "SELECT antimatiere as total, user_id
      FROM ".TABLE_EXPEDITION." AS exp
      INNER JOIN ".TABLE_EXPEDITION_RESS." AS ress
      WHERE exp.id = ress.id_eXpedition 
      ORDER BY total DESC";   

  $result = $db->sql_query($query);
  for($i = 0 ; $i < 10 ; $i ++)
  {
    if($row = $db->sql_fetch_row($result))
    {
        $hofExpedition[$i]['A']['quantite'] = $row[0];
        $hofExpedition[$i]['A']['pseudo'] = getuserNameById($row[1]);
    }
    else
    {
        $hofExpedition[$i]['A']['quantite'] = "/";
        $hofExpedition[$i]['A']['pseudo']   = "/";
    }
  }

  // HOF VAISSEAUX RAPPORTES
  $query =  "SELECT sum(units) as total, user_id
      FROM ".TABLE_EXPEDITION." AS exp
      INNER JOIN ".TABLE_EXPEDITION_FLEET." AS fleet
      WHERE exp.id = fleet.id_eXpedition
      GROUP BY user_id
      ORDER BY total DESC";  

  $result = $db->sql_query($query);

  for($i = 0 ; $i < 10 ; $i ++)
  {
    if($row = $db->sql_fetch_row($result))
    {
        $cumulFleet[$i]['cumul'] = $row[0];
        $cumulFleet[$i]['pseudo'] = getuserNameById($row[1]);
    }
    else
    {
        $cumulFleet[$i]['cumul'] = "/";
        $cumulFleet[$i]['pseudo']   = "/";
    }
  }

  // HOF EXPEDITION VAISSEAUX
  $query =  "SELECT units as total, user_id
      FROM ".TABLE_EXPEDITION." AS exp
      INNER JOIN ".TABLE_EXPEDITION_FLEET." AS fleet
      WHERE exp.id = fleet.id_eXpedition
      ORDER BY total DESC";  

  $result = $db->sql_query($query);

  for($i = 0 ; $i < 10 ; $i ++)
  {
    if($row = $db->sql_fetch_row($result))
    {
        $hofFleet[$i]['cumul'] = $row[0];
        $hofFleet[$i]['pseudo'] = getuserNameById($row[1]);
    }
    else
    {
        $hofFleet[$i]['cumul'] = "/";
        $hofFleet[$i]['pseudo']   = "/";
    }
  }

  $hofArray['cumul'] = $cumulPoints;
  $hofArray['cumulRess'] = $cumulRessources;
  $hofArray['ress'] = $hofExpedition;
  $hofArray['cumulFleet'] = $cumulFleet;
  $hofArray['fleet'] = $hofFleet;
  
  return $hofArray;  
}

// lecture des infos de la base 
function readDBuserDet($uid = 0, $datedeb = 0, $datefin = 0)
{
  global $db, $eXpDebug;
  $TR0 = '';
  $TR1 = '';
  $TR2 = '';
  $TR3 = '';
  // On recupere le tableau des expeditions ratées :
  $query = 
        "SELECT date, pos_galaxie, pos_sys, user_id, e.id
        FROM ".TABLE_EXPEDITION." AS e, ".TABLE_EXPEDITION_NUL." AS t0
        WHERE e.id = t0.id_eXpedition ";
if($uid != 0) $query .=   "and user_id =$uid ";
if($datefin != 0) $query .=   "and date between $datedeb and $datefin ";
   $query .=    "order by date DESC, user_id ASC";
  if($eXpDebug) echo("<br /> Db : $query <br />");
          $result = $db->sql_query($query);
  while ($row = $db->sql_fetch_row($result)) 
  {
      $TR0[] = $row;
  }



  // On recupere le tableau des expeditions ressources :

  $query = 
        "SELECT date, pos_galaxie, pos_sys, metal, cristal, deuterium, antimatiere, user_id, e.id
        FROM ".TABLE_EXPEDITION." AS e, ".TABLE_EXPEDITION_RESS." AS t1
        WHERE e.id = t1.id_eXpedition ";
if($uid != 0) $query .=   "and user_id =$uid ";
if($datefin != 0) $query .=   "and date between $datedeb and $datefin ";
   $query .=    "order by date DESC, user_id ASC";

  if($eXpDebug) echo("<br /> Db : $query <br />");
          $result = $db->sql_query($query);
  while ($row = $db->sql_fetch_row($result)) 
  {
      $TR1[] = $row;
  }



  // On recupere le tableau des expeditions vaisseau :

  $query = 
        "SELECT date, pos_galaxie, pos_sys, pt, gt, cle, clo, cr, vb, vc, rec, se, bmb, dst, tra, units, user_id, e.id
        FROM ".TABLE_EXPEDITION." AS e, ".TABLE_EXPEDITION_FLEET." AS t2
        WHERE e.id = t2.id_eXpedition ";
if($uid != 0) $query .=   "and user_id =$uid ";
if($datefin != 0) $query .=   "and date between $datedeb and $datefin ";
   $query .=    "order by date DESC, user_id ASC";

  if($eXpDebug) echo("<br /> Db : $query <br />");
          $result = $db->sql_query($query);
  while ($row = $db->sql_fetch_row($result)) 
  {
      $TR2[] = $row;
  }


  // On recupere le tableau des expeditions marchand :


  $query = 
        "SELECT date, pos_galaxie, pos_sys, user_id, e.id
        FROM ".TABLE_EXPEDITION." AS e, ".TABLE_EXPEDITION_MERCH." AS t3
        WHERE e.id = t3.id_eXpedition ";
if($uid != 0) $query .=   "and user_id =$uid ";
if($datefin != 0) $query .=   "and date between $datedeb and $datefin ";
   $query .=    "order by date DESC, user_id ASC";

  if($eXpDebug) echo("<br /> Db : $query <br />");
          $result = $db->sql_query($query);
  while ($row = $db->sql_fetch_row($result)) 
  {
      $TR3[] = $row;
  }


  // et on renvoie le tout dans un super tableau qui est un tableau de tableau de tableau de ....

  $superTableau['Rate']  = $TR0;
  $superTableau['Ress']  = $TR1;
  $superTableau['Vaiss'] = $TR2;
  $superTableau['March'] = $TR3;

  return $superTableau;
}



?>