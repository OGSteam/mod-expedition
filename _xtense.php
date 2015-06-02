<?php


/* xtense.php - Line 1074 */

// Hack Protection
if (!defined('IN_SPYOGAME')) {
    exit('Hacking Attempt!');
}

if(class_exists("Callback")){
class eXpedition_Callback extends Callback {
	public $version = '2.6.0';
	public function eXpedition_xtense2($expedition){
		global $io, $user_data;
		if(eXpedition_analysis($user_data['user_id'], $expedition['coords'][0], $expedition['coords'][1], $expedition['time'], $expedition['content']))
			return Io::SUCCESS;
		else
			return Io::ERROR;
	}
	public function getCallbacks() {
		return array(
				array(
						'function' => 'eXpedition_xtense2',
						'type' => 'expedition'
				)
		);
   }
}
}

global $xtense_version, $table_prefix;

$xtense_version = "2.6.0";
define("TABLE_EXPEDITION", $table_prefix."eXpedition");					// Toutes
define("TABLE_EXPEDITION_NUL", $table_prefix."eXpedition_nul"); 		// 0 - Nul
define("TABLE_EXPEDITION_RESS", $table_prefix."eXpedition_ress"); 		// 1 - Ressources
define("TABLE_EXPEDITION_FLEET", $table_prefix."eXpedition_fleet"); 	// 2 - Vaisseaux
define("TABLE_EXPEDITION_MERCH", $table_prefix."eXpedition_merch"); 	// 3 - Marchand


function eXpedition_analysis($uid, $galaxy, $system, $timestamp, $content)
{
	global $db;

	// REGEX
	$regexFleet = "#Votre\sflotte\ss`est\sagrandie,\svoici\sles\snouveaux\svaisseaux\squi\ss`y\ssont\sjoints\s:(.+)#";
	$regexRess  = "#L`attaquant\sobtient\s(\S+)\s(\d+(.?\d*)?)#";
	$regexMerch = ["#liste\sde\sclients\sprivilégiés#", "#dans\svotre\sempire\sun\sreprésentant\schargé\sde\sressources\sà\séchanger#"];
	
	// Check if new
	$query = "SELECT * 
		FROM ".TABLE_EXPEDITION." 
		WHERE user_id = $uid 
		AND date = $timestamp 
		AND pos_galaxie = $galaxy 
		AND pos_sys = $system";
	if($db->sql_numrows($db->sql_query($query)) == 0)
	{
		//FLEET ?
		if(preg_match($regexFleet, $content, $expFleet) != 0)
		{
			// Init
			$pt = $gt = $cle = $clo = $cr = $vb = $vc = $rec = $se = $bmb = $dst = $tra = 0;
			$units = 0;
			
			// Looking for specifics
			if(preg_match("#Petit\stransporteur\s(\d+)#", $expFleet[1], $reg)){
				$pt = $reg[1]; 
				$units += ( 2  + 2  + 0  ) * $pt;
			}
			if(preg_match("#Grand\stransporteur\s(\d+)#", $expFleet[1], $reg)){
				$gt = $reg[1]; 
				$units += ( 6  + 6  + 0  ) * $gt;
			}
			if(preg_match("#Chasseur\sléger\s(\d+)#", $expFleet[1], $reg)){
				$cle = $reg[1]; 
				$units += ( 3  + 1  + 0  ) * $cle;
			}
			if(preg_match("#Chasseur\slourd\s(\d+)#", $expFleet[1], $reg)){
				$clo = $reg[1]; 
				$units += ( 6  + 4  + 0  ) * $clo;
			}
			if(preg_match("#Croiseur\s(\d+)#", $expFleet[1], $reg)){
				$cr = $reg[1]; 
				$units += ( 20 + 7  + 2  ) * $cr;
			}
			if(preg_match("#Vaisseau\sde\sbataille\s(\d+)#", $expFleet[1], $reg)){
				$vb = $reg[1]; 
				$units += ( 45 + 15 + 0  ) * $vb;
			}
			if(preg_match("#Vaisseau\sde\scolonisation\s(\d+)#", $expFleet[1], $reg)){
				$vc = $reg[1]; 
				$units += ( 10 + 20 + 10 ) * $vc;
			}
			if(preg_match("#Recycleur\s(\d+)#", $expFleet[1], $reg)){
				$rec = $reg[1]; 
				$units += ( 10 + 6  + 2  ) * $rec;
			}
			if(preg_match("#Sonde\sd`espionnage\s(\d+)#", $expFleet[1], $reg)){
				$se = $reg[1]; 
				$units += ( 0  + 1  + 0  ) * $se;
			}
			if(preg_match("#Bombardier\s(\d+)#", $expFleet[1], $reg)){
				$bmb = $reg[1]; 
				$units += ( 50 + 25 + 15 ) * $bmb;
			}
			if(preg_match("#Destructeur\s(\d+)#", $expFleet[1], $reg)){
				$dst = $reg[1]; 
				$units += ( 60 + 50 + 15 ) * $dst;
			}
			if(preg_match("#Traqueur\s(\d+)#", $expFleet[1], $reg)){
				$tra = $reg[1]; 
				$units += ( 30 + 40 + 15 ) * $tra;
			}
			
			$query = 
					"INSERT INTO ".TABLE_EXPEDITION." 
					(id, user_id, date, pos_galaxie, pos_sys, type) 
					values ('', $uid, $timestamp, $galaxy, $system, 2)";
			$db->sql_query($query);
			$idInsert = $db->sql_insertid();
			
			$query = 
				"Insert into ".TABLE_EXPEDITION_FLEET." 
				(id, id_eXpedition, pt, gt, cle, clo, cr, vb, vc, rec, se, bmb, dst, tra, units)
				values ('', $idInsert, $pt, $gt, $cle, $clo, $cr, $vb, $vc, $rec, $se, $bmb, $dst, $tra, $units)";
			$db->sql_query($query);
			
			return true;			
		}
		// RESSOURCES ?
		else if(preg_match($regexRess, $content, $expRess) != 0){
			log_('debug', $expRess[2]);
			if(preg_match("#Métal#", $expRess[1]))
			{
				$typeRess = 0;
				$met = str_replace('.','', $expRess[2]);
				$cri = 0;
				$deut = 0;
				$antimat = 0;
			}
			if(preg_match("#Cristal#", $expRess[1]))
			{
				$typeRess = 1;
				$met = 0;
				$cri = str_replace('.','', $expRess[2]);
				$deut = 0;
				$antimat = 0;
			}
			if(preg_match("#Deutérium#", $expRess[1]))
			{
				$typeRess = 2;
				$met = 0;
				$cri = 0;
				$deut = str_replace('.','', $expRess[2]);
				$antimat = 0;
			}
			if(preg_match("#Antimatière#", $expRess[1]))
			{
				$typeRess = 3;
				$met = 0;
				$cri = 0;
				$deut = 0;
				$antimat = str_replace('.','', $expRess[2]);
			}
			if($typeRess == -1)
			{
				die("Parsing Error");
			}
			
			$query = 
					"INSERT INTO ".TABLE_EXPEDITION." 
					(id, user_id, date, pos_galaxie, pos_sys, type) 
					VALUES ('', $uid, $timestamp, $galaxy, $system, 1)";
			$db->sql_query($query);
			$idInsert = $db->sql_insertid();
			
			$query = 
				"INSERT INTO ".TABLE_EXPEDITION_RESS." 
				(id, id_eXpedition, typeRessource, metal, cristal, deuterium, antimatiere) 
				VALUES ('', $idInsert, $typeRess, $met, $cri, $deut, $antimat)";
			$db->sql_query($query);
			
			return true;
		}
		// MERCHANT ?
		else if( (preg_match($regexMerch[0], $content) != 0) || (preg_match($regexMerch[1], $content) != 0) ){
			$query = 
				"INSERT INTO ".TABLE_EXPEDITION." 
				(id, user_id, date, pos_galaxie, pos_sys, type) 
				VALUES ('', $uid, $timestamp, $galaxy, $system, 3)";
			$db->sql_query($query);
			$idInsert = $db->sql_insertid();
			$query = 
				"INSERT INTO ".TABLE_EXPEDITION_MERCH." 
				(id, id_eXpedition, typeRessource)
				 VALUES ('', $idInsert, 0)"; //TODO: Choisir le type
			$db->sql_query($query);
			
			return true;
		}
		// OTHER
		else{
			$query = 
				"INSERT INTO ".TABLE_EXPEDITION." 
				(id, user_id, date, pos_galaxie, pos_sys, type) 
				VALUES ('', $uid, $timestamp, $galaxy, $system, 0)";
			$db->sql_query($query);
			$idInsert = $db->sql_insertid();
			$query = 
				"INSERT INTO ".TABLE_EXPEDITION_NUL." 
				(id, id_eXpedition, typeVitesse) 
				VALUES ('', $idInsert, 0)"; //TODO : Choisir le type !
			$db->sql_query($query);
			
			return true;
		}
	}
	else {
		return false;	
	}
}
?>
