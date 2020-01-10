<?php
 
// Hack Protection
if (!defined('IN_SPYOGAME')) {
    exit('Hacking Attempt!');
}

// We'll need that
global $db, $table_prefix;
define("TABLE_EXPEDITION", $table_prefix."eXpedition");					// Toutes
define("TABLE_EXPEDITION_NUL", $table_prefix."eXpedition_nul"); 		// 0 - Nul
define("TABLE_EXPEDITION_RESS", $table_prefix."eXpedition_ress"); 		// 1 - Ressources
define("TABLE_EXPEDITION_FLEET", $table_prefix."eXpedition_fleet"); 	// 2 - Vaisseaux
define("TABLE_EXPEDITION_MERCH", $table_prefix."eXpedition_merch"); 	// 3 - Marchand
define("TABLE_EXPEDITION_ATTACKS", $table_prefix."eXpedition_attacks"); // 4 - Attaques
define("TABLE_EXPEDITION_ITEMS", $table_prefix."eXpedition_items");	    // 5 - Items
define("TABLE_XTENSE_CALLBACKS", $table_prefix."xtense_callbacks");		// xtense Callbacks
  
$mod_folder = 'expedition';
 
if (install_mod($mod_folder)) {
    // DELETE IF EXISTS
	$db->sql_query("DROP TABLE IF EXISTS ".TABLE_EXPEDITION);
	$db->sql_query("DROP TABLE IF EXISTS ".TABLE_EXPEDITION_NUL);
	$db->sql_query("DROP TABLE IF EXISTS ".TABLE_EXPEDITION_RESS);
	$db->sql_query("DROP TABLE IF EXISTS ".TABLE_EXPEDITION_FLEET);
	$db->sql_query("DROP TABLE IF EXISTS ".TABLE_EXPEDITION_MERCH);
    $db->sql_query("DROP TABLE IF EXISTS ".TABLE_EXPEDITION_ATTACKS);
    $db->sql_query("DROP TABLE IF EXISTS ".TABLE_EXPEDITION_ITEMS);
	
	// CREATE TABLES
	createTables($db);
	
	// ADD XTENSE CALLBACK
	addXtenseCallback($db);
}
else {
    echo '<script>alert(\'Un problème a eu lieu pendant l\'installation du mod, corrigez les problèmes survenus et réessayez.\');</script>';
}
 
 
function createTables($db){
	$query = "CREATE TABLE ".TABLE_EXPEDITION." ("
			. " id INT NOT NULL AUTO_INCREMENT, "
			. " user_id INT NOT NULL, "
			. " date INT(11) NOT NULL, "
			. " pos_galaxie INT NOT NULL, "
			. " pos_sys INT NOT NULL, "
			. " type INT NOT NULL, "
			. " primary key ( id )"
			. " )";
	$db->sql_query($query);
	
	$query = "CREATE TABLE ".TABLE_EXPEDITION_NUL." ("
		. " id INT NOT NULL AUTO_INCREMENT, "
		. " id_eXpedition INT NOT NULL, "
		. " typeVitesse INT NOT NULL, "
		. " primary key ( id )"
		. " )";
	$db->sql_query($query);
	
	$query = "CREATE TABLE ".TABLE_EXPEDITION_RESS." ("
		. " id INT NOT NULL AUTO_INCREMENT, "
		. " id_eXpedition INT NOT NULL, "
		. " typeRessource INT NOT NULL, "
		. " metal INT NOT NULL, "
		. " cristal INT NOT NULL, "
		. " deuterium INT NOT NULL, "
		. " antimatiere INT NOT NULL, "
		. " primary key ( id )"
		. " )";
	$db->sql_query($query);
	
	$query = "CREATE TABLE ".TABLE_EXPEDITION_FLEET." ("
		. " id INT NOT NULL AUTO_INCREMENT, "
		. " id_eXpedition INT NOT NULL, "
		. " pt INT NOT NULL, "
		. " gt INT NOT NULL, "
		. " cle INT NOT NULL, "
		. " clo INT NOT NULL, "
		. " cr INT NOT NULL, "
		. " vb INT NOT NULL, "
		. " vc INT NOT NULL, "
		. " rec INT NOT NULL, "
		. " se INT NOT NULL, "
		. " bmb INT NOT NULL, "
		. " dst INT NOT NULL, "
		. " tra INT NOT NULL, "
		. " fau INT NOT NULL, "
		. " ecl INT NOT NULL, "
		. " units INT NOT NULL, "
		. " primary key ( id )"
		. " )";
	$db->sql_query($query);

	$query = "CREATE TABLE ".TABLE_EXPEDITION_MERCH." ("
		. " id INT NOT NULL AUTO_INCREMENT, "
		. " id_eXpedition INT NOT NULL, "
		. " typeRessource INT NOT NULL, "
		. " primary key ( id )"
		. " )";
	$db->sql_query($query);

    $query = "CREATE TABLE ".TABLE_EXPEDITION_ATTACKS." ("
        . " id INT NOT NULL AUTO_INCREMENT, "
        . " id_eXpedition INT NOT NULL, "
        . " pt INT NOT NULL, "
        . " gt INT NOT NULL, "
        . " cle INT NOT NULL, "
        . " clo INT NOT NULL, "
        . " cr INT NOT NULL, "
        . " vb INT NOT NULL, "
        . " vc INT NOT NULL, "
        . " rec INT NOT NULL, "
        . " se INT NOT NULL, "
        . " bmb INT NOT NULL, "
        . " dst INT NOT NULL, "
        . " edlm INT NOT NULL,"
        . " tra INT NOT NULL, "
		. " fau INT NOT NULL, "
		. " ecl INT NOT NULL, "
        . " primary key ( id )"
        . " )";
    $db->sql_query($query);
	
	$query = "CREATE TABLE `" . TABLE_EXPEDITION_ITEMS . "` (
            `id` INT NOT NULL AUTO_INCREMENT,
            `id_eXpedition` INT NOT NULL DEFAULT '0',
            `type` ENUM('Metal', 'Cristal', 'Deut', 'Kraken', 'Detroid', 'Newtron') NOT NULL,
            `niveau` ENUM('bronze', 'argent', 'or') NOT NULL,
            PRIMARY KEY (`id`)
          ) DEFAULT CHARSET=utf8";
    $db->sql_query($query);
}

function addXtenseCallback($db){
	// Get mod ID
	$query = "SELECT `id` FROM `".TABLE_MOD."` WHERE `action`='eXpedition' AND `active`='1' LIMIT 1";
	$result = $db->sql_query($query);
	$mod_id = $db->sql_fetch_row($result);
	$mod_id = $mod_id[0];
	
	// Check if xtense_callbacks table exists :
	$query = 'SHOW TABLES LIKE "'.TABLE_XTENSE_CALLBACKS.'"';
	$result = $db->sql_query($query);
	if($db->sql_numrows($result) != 0){
		// GOOD
		// Check if the mod isn't already added
		$query = 'SELECT * FROM '.TABLE_XTENSE_CALLBACKS.' WHERE mod_id = '.$mod_id;
		$result = $db->sql_query($query);
		$nresult = $db->sql_numrows($result);
		if($nresult == 0) {
			// We add it
			$query = 'INSERT INTO '.TABLE_XTENSE_CALLBACKS.' (mod_id, function, type, active) VALUES ('.$mod_id.', "eXpedition_xtense2", "expedition", 1)';
			$db->sql_query($query);

            $query = "INSERT INTO " . TABLE_XTENSE_CALLBACKS . " ( `mod_id` , `function` , `type` ) VALUES ( '" . $mod_id . "', 'eXpedition_attack', 'rc')";
            $db->sql_query($query);
		}
	} else {
		// FAIL : NO XTENSE2
		echo("<script> alert('Le module Xtense 2 n\'est pas installé. \nSi vous voulez que les rapport d'expédition soient ajoutés automatiquement, merci d'installer le module xtense 2\n') </script>");
	}
}

?>