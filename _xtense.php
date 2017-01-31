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
	/**
	 * @param $rapport
	 * @return int
	 */
	public function eXpedition_attack ($rapport)
	{
		global $io, $user_data;
		logging('ATTACK_RC');
		if (attack_analysis($user_data['user_id'], $rapport)) return Io::SUCCESS; else
			return Io::ERROR;
	}

	public function getCallbacks() {
		return array(
				array('function' => 'eXpedition_xtense2', 'type' => 'expedition'),
				array('function' => 'eXpedition_attack', 'type' => 'rc')
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
define("TABLE_EXPEDITION_ATTACKS", $table_prefix."eXpedition_attacks"); // 4 - Attaques
define("TABLE_EXPEDITION_ITEMS", $table_prefix."eXpedition_items");     // 5 - Attaques

function logging($msg)
{
	$dir = date('ymd');
	$file = 'log_expedition.log';
	if (!file_exists('journal/'.$dir)) @mkdir('journal/'.$dir);
	if (file_exists('journal/'.$dir)) {
		@chmod('journal/'.$dir, 0777);
		$fp = @fopen('journal/'.$dir.'/'.$file, 'a+');
		if ($fp) {
			fwrite($fp, date('d/m/Y H:i:s').' - '.$msg."\r\n");
			fclose($fp);
			@chmod('journal/'.$dir.'/'.$file, 0777);
		}
	}
}
function eXpedition_analysis($uid, $galaxy, $system, $timestamp, $content)
{
	global $db;
	// REGEX
 	$regexFleet = "/Votre\sflotte\ss\`est\sagrandie\,\svoici\sles\snouveaux\svaisseaux\squi\ss\`y\ssont\sjoints\s\:(.+\d)/";
 	$regexRess  = "/L`attaquant\sobtient\s(\S+)\s[\(\AM\)]*\s?([\d+\.+]*)/";
 	$regexMerch = ["/liste\sde\sclients\sprivilégiés/", "/dans\svotre\sempire\sun\sreprésentant\schargé\sde\sressources\sà\séchanger/"];
	$regexAttack = ["/Moa Tikarr/",
                    "/expédition a fait une rencontre fort peu agréable avec des pirates/",
                    "/Quelques pirates/",
                    "/pirates fortement alcoolisés/",
                    "/Le combat n'a pû être évité/",
                    "/Nous avons dû nous défendre contre des pirates/",
                    "/le combat avec les pirates était inévitable/",
                    "/Des vaisseaux inconnus ont attaqué la flotte/",
                    "/la flotte est en train d\`être attaquée/",
                    "/enfreint les espaces/",
                    "/par un petit groupe de vaisseaux inconnus/",
	                "/rencontre peu amicale avec une espèce inconnue/",
                    "/Une flotte de vaisseaux cristallins/",
                    "/Une espèce inconnue attaque notre expédition/",
                    "/Des barbares primitifs nous attaquent/"];

    $regexItems = "/L\`objet ([A-z ]+) en ([A-z]+) a été ajouté à l\`inventaire/";

	// Check if new
	$query = "SELECT * 
		FROM ".TABLE_EXPEDITION." 
		WHERE user_id = $uid 
		AND date = $timestamp 
		AND pos_galaxie = $galaxy 
		AND pos_sys = $system";
	logging("Content : ".$content);
	if($db->sql_numrows($db->sql_query($query)) == 0)
	{
		//FLEET ?
		if(preg_match($regexFleet, $content, $expFleet) != 0)
		{
			logging("Flotte0".$expFleet[0]);
			logging("Flotte1".$expFleet[1]);
			// Init
			$pt = $gt = $cle = $clo = $cr = $vb = $vc = $rec = $se = $bmb = $dst = $tra = 0;
			$units = 0;
			
			// Looking for specifics
			if(preg_match("/Petit\stransporteur:\s(\d+)/", $expFleet[1], $reg)){
				$pt = $reg[1]; 
				$units += ( 2  + 2  + 0  ) * $pt;
			}
			if(preg_match("/Grand\stransporteur:\s(\d+)/", $expFleet[1], $reg)){
				$gt = $reg[1]; 
				$units += ( 6  + 6  + 0  ) * $gt;
			}
			if(preg_match("/Chasseur\sléger:\s(\d+)/", $expFleet[1], $reg)){
				$cle = $reg[1]; 
				$units += ( 3  + 1  + 0  ) * $cle;
			}
			if(preg_match("/Chasseur\slourd:\s(\d+)/", $expFleet[1], $reg)){
				$clo = $reg[1]; 
				$units += ( 6  + 4  + 0  ) * $clo;
			}
			if(preg_match("/Croiseur:\s(\d+)/", $expFleet[1], $reg)){
				$cr = $reg[1]; 
				$units += ( 20 + 7  + 2  ) * $cr;
			}
			if(preg_match("/Vaisseau\sde\sbataille:\s(\d+)/", $expFleet[1], $reg)){
				$vb = $reg[1]; 
				$units += ( 45 + 15 + 0  ) * $vb;
			}
			if(preg_match("/Vaisseau\sde\scolonisation:\s(\d+)/", $expFleet[1], $reg)){
				$vc = $reg[1]; 
				$units += ( 10 + 20 + 10 ) * $vc;
			}
			if(preg_match("/Recycleur:\s(\d+)/", $expFleet[1], $reg)){
				$rec = $reg[1]; 
				$units += ( 10 + 6  + 2  ) * $rec;
			}
			if(preg_match("/Sonde\sd`espionnage:\s(\d+)/", $expFleet[1], $reg)){
				$se = $reg[1]; 
				$units += ( 0  + 1  + 0  ) * $se;
			}
			if(preg_match("/Bombardier:\s(\d+)/", $expFleet[1], $reg)){
				$bmb = $reg[1]; 
				$units += ( 50 + 25 + 15 ) * $bmb;
			}
			if(preg_match("/Destructeur:\s(\d+)/", $expFleet[1], $reg)){
				$dst = $reg[1]; 
				$units += ( 60 + 50 + 15 ) * $dst;
			}
			if(preg_match("/Traqueur:\s(\d+)/", $expFleet[1], $reg)){
				$tra = $reg[1]; 
				$units += ( 30 + 40 + 15 ) * $tra;
			}
			
			$query = 
					"INSERT INTO ".TABLE_EXPEDITION." 
					(user_id, date, pos_galaxie, pos_sys, type) 
					values ($uid, $timestamp, $galaxy, $system, 2)";
			$db->sql_query($query);
			$idInsert = $db->sql_insertid();
			
			$query = 
				"Insert into ".TABLE_EXPEDITION_FLEET." 
				(id_eXpedition, pt, gt, cle, clo, cr, vb, vc, rec, se, bmb, dst, tra, units)
				values ($idInsert, $pt, $gt, $cle, $clo, $cr, $vb, $vc, $rec, $se, $bmb, $dst, $tra, $units)";
			$db->sql_query($query);
			
			return true;			
		}
		// RESSOURCES ?
		else if(preg_match($regexRess, $content, $expRess) != 0){
			logging("RESSOURCES");
			logging("Ressources1 : ".$expRess[1]);
			logging("Ressources2 : ".$expRess[2]);
			//	L`attaquant obtient Antimatière (AM) 310.Extrait du journal
			log_('debug', $expRess[2]);
			if(preg_match("/Métal/", $expRess[1]))
			{
				$typeRess = 0;
				$met = str_replace('.','', $expRess[2]);
				$cri = 0;
				$deut = 0;
				$antimat = 0;
			}
			if(preg_match("/Cristal/", $expRess[1]))
			{
				$typeRess = 1;
				$met = 0;
				$cri = str_replace('.','', $expRess[2]);
				$deut = 0;
				$antimat = 0;
			}
			if(preg_match("/Deutérium/", $expRess[1]))
			{
				$typeRess = 2;
				$met = 0;
				$cri = 0;
				$deut = str_replace('.','', $expRess[2]);
				$antimat = 0;
			}
			if(preg_match("/Antimatière/", $expRess[1]))
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
			
			logging("Ressources : Insertion table : ".TABLE_EXPEDITION);
			$query = 
					"INSERT INTO ".TABLE_EXPEDITION." 
					(user_id, date, pos_galaxie, pos_sys, type) 
					VALUES ($uid, $timestamp, $galaxy, $system, 1)";
			$db->sql_query($query);
			$idInsert = $db->sql_insertid();
			logging("Ressources : Insertion table : ".TABLE_EXPEDITION." = OK");
			logging("Ressources : Insertion table : ".TABLE_EXPEDITION_RESS);
			$query = 
				"INSERT INTO ".TABLE_EXPEDITION_RESS." 
				(id_eXpedition, typeRessource, metal, cristal, deuterium, antimatiere) 
				VALUES ($idInsert, $typeRess, $met, $cri, $deut, $antimat)";
			$db->sql_query($query);
			logging("Ressources : Insertion table : ".TABLE_EXPEDITION_RESS." = OK");
			return true;
		}
		// MERCHANT ?
		else if( (preg_match($regexMerch[0], $content) != 0) || (preg_match($regexMerch[1], $content) != 0) ){
			logging("Marchand");
			$query = 
				"INSERT INTO ".TABLE_EXPEDITION." 
				(user_id, date, pos_galaxie, pos_sys, type) 
				VALUES ($uid, $timestamp, $galaxy, $system, 3)";
			$db->sql_query($query);
			$idInsert = $db->sql_insertid();
			$query = 
				"INSERT INTO ".TABLE_EXPEDITION_MERCH." 
				(id_eXpedition, typeRessource)
				 VALUES ($idInsert, 0)"; //TODO: Choisir le type
			$db->sql_query($query);
			
			return true;
		}
		// Items
        else if(preg_match($regexItems, $content, $expItems) != 0)
        {
            logging("Items");
            $itemType = $expItems[1];
            $itemQuality = $expItems[2];

            switch ($itemType)
            {
                case "Booster de métal":
                    $type = "Metal";
                    break;
                case "Booster de cristal":
                    $type = "Cristal";
                    break;
                case "Booster de deutérium":
                    $type = "Deut";
                    break;
                case "KRAKEN":
                    $type = "Kraken";
                    break;
                case "DETROID":
                    $type = "Detroid";
                    break;
                case "NEWTRON":
                    $type = "Newtron";
                    break;

                default:
                    return false;
            }

            $query = "INSERT INTO ".TABLE_EXPEDITION." (user_id, date, pos_galaxie, pos_sys, type) 
				        VALUES ($uid, $timestamp, $galaxy, $system, 5)";
            $db->sql_query($query);
            $idInsert = $db->sql_insertid();

            $query = "INSERT INTO " . TABLE_EXPEDITION_ITEMS ."(id_eXpedition, type, niveau) VALUES ($idInsert, '" . $type . "', '" . $itemQuality . "')";
            $db->sql_query($query);

            return true;
        }
		// OTHER
		else {
            // ATTAQUE ?
            $attaque =  false;
            foreach($regexAttack as $reg)
            {
                if(preg_match($reg, $content) != 0)
                    $attaque = true;
            }
            if ($attaque)
            {
                logging("Attaque");
                // Dans le cas d'une attaque on ne fait rien
            } else {


                logging("Autre");
                $query = "INSERT INTO " . TABLE_EXPEDITION . " (user_id, date, pos_galaxie, pos_sys, type) 
				        VALUES ($uid, $timestamp, $galaxy, $system, 0)";
                $db->sql_query($query);
                $idInsert = $db->sql_insertid();
                $query =
                    "INSERT INTO " . TABLE_EXPEDITION_NUL . " 
				(id_eXpedition, typeVitesse) 
				VALUES ($idInsert, 0)"; //TODO : Choisir le type !
                $db->sql_query($query);
            }
			
			return true;
		}
		
		logging("FIN");
	}
	else {
		return false;	
	}
}


// Import des Rapports de combats
/**
 * @param $user_id
 * @param $rapport
 * @return bool
 */
function attack_analysis ($user_id, $rapport)
{
	global $db;
	logging("ATTACK");

	$json = json_decode($rapport['json']);

	if (!isset($json->event_timestamp)) {
		return FALSE;
	} else {

		$timestamp = $json->event_timestamp;

		// On vérifie qu'il s'agit d'un RC d'expédition
		if($json->coordinates->position != '16')
			return TRUE;

		//On vérifie que cette attaque n'a pas déja été enregistrée
		$query = "SELECT * 
		FROM ".TABLE_EXPEDITION." 
		WHERE user_id = $user_id 
		AND date = $timestamp 
		AND pos_galaxie = {$json->coordinates->galaxy}
		AND pos_sys = {$json->coordinates->system}";
		if($db->sql_numrows($db->sql_query($query)) != 0)
			return TRUE;

		//On regarde dans les coordonnées de l'espace personnel du joueur qui insère les données via le plugin si il fait partie des attaquants et/ou des défenseurs
		$query = "SELECT coordinates FROM " . TABLE_USER_BUILDING . " WHERE user_id='" . $user_id . "'";
		$result = $db->sql_query($query);

		$coordinates = array();
		while ($coordinate = $db->sql_fetch_row($result))
			$coordinates[] = $coordinate[0];

		// L'utilisateur n'est pas celui ayant réalisé l'expédition, on s'arrête là
		$defenders = $json->defender;
		$defenderId = key($defenders);
		if (count(array_intersect (array($json->defender->$defenderId->ownerCoordinates), $coordinates)) == 0)
			return false;

		$defender = $json->defenderJSON;
		$lastRound = $defender->combatRounds[count($defender->combatRounds)-1];

		$ships = array('PT', 'GT', 'CLE', 'CLO', 'CR', 'VB', 'VC', 'REC', 'SE', 'BMD', 'DST', 'EDLM', 'TRA');
		$ships = array_fill_keys($ships, 0);

		$correspondance = array('202' => 'PT', '203' => 'GT',
			'204' => 'CLE','205' => 'CLO',
			'206' => 'CR','207' => 'VB',
			'208' => 'VC','209' => 'REC',
			'210' => 'SE', '211' => 'BMD',
			'212' => 'SAT', '213' => 'DST',
			'214' => 'EDLM', '215' => 'TRA');

        if(isset($lastRound->losses->$defenderId)) {
        	$losses = $lastRound->losses->$defenderId;
            foreach ($losses as $ship => $loss) {
                $ships[$correspondance[$ship]] = $loss;
            }
        }

		$query = "INSERT INTO ".TABLE_EXPEDITION."(user_id, date, pos_galaxie, pos_sys, type) 
					values ($user_id, $timestamp, {$json->coordinates->galaxy}, {$json->coordinates->system}, 4)";
		$db->sql_query($query);
		$idInsert = $db->sql_insertid();

		$query = "INSERT INTO " . TABLE_EXPEDITION_ATTACKS . " (id_eXpedition, pt, gt, cle, clo, cr, vb, vc, rec, se, bmb, dst, edlm, tra)
				 VALUES ($idInsert ";
		$query .= "," . $ships['PT'];
		$query .= "," . $ships['GT'];
		$query .=  "," . $ships['CLE'];
		$query .=  "," . $ships['CLO'];
		$query .=  "," . $ships['CR'];
		$query .=  "," . $ships['VB'];
		$query .=  "," . $ships['VC'];
		$query .=  "," . $ships['REC'];
		$query .=  "," . $ships['SE'];
		$query .=  "," . $ships['BMD'];
		$query .=  "," . $ships['DST'];
		$query .=  "," . $ships['EDLM'];
		$query .=  "," . $ships['TRA'];
		$query .= ")";
		$db->sql_query($query);

	}
	return TRUE;
}
?>
