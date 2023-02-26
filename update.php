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
define("TABLE_EXPEDITION_ITEMS", $table_prefix."eXpedition_items"); 	// 5 - Items
define("TABLE_XTENSE_CALLBACKS", $table_prefix."xtense_callbacks");		// xtense Callbacks

$mod_folder = 'expedition';
$mod_name   = 'eXpedition';

//On récupère la version actuelle du mod
$query = "SELECT id, version FROM ".TABLE_MOD." WHERE action='eXpedition'";
$result = $db->sql_query($query);
list($mod_id, $version) = $db->sql_fetch_row($result);

// Mise à jour depuis les versions 1.1.0 et 1.0.2
if($version == "1.1.0" || $version == "1.0.2"){
  $db->sql_query("DROP TABLE IF EXISTS ".TABLE_EXPEDITION_NUL);
  $db->sql_query("DROP TABLE IF EXISTS ".TABLE_EXPEDITION_RESS);
  $db->sql_query("DROP TABLE IF EXISTS ".TABLE_EXPEDITION_FLEET);
  $db->sql_query("DROP TABLE IF EXISTS ".TABLE_EXPEDITION_MERCH);

  $query = "RENAME TABLE
   ".$table_prefix."expedition_type_0 TO ".$table_prefix."expedition_nul,
   ".$table_prefix."expedition_type_1 TO ".$table_prefix."eXpedition_ress,
   ".$table_prefix."expedition_type_2 TO ".$table_prefix."eXpedition_fleet,
   ".$table_prefix."expedition_type_3 TO ".$table_prefix."eXpedition_merch";
  $db->sql_query($query);

  $query = "DROP TABLE ".$table_prefix."expedition_opts";
  $db->sql_query($query);
}

// Mise à jour pour intégration des items et attcks (versions inférieures à 1.1.8)
$query = "CREATE TABLE IF NOT EXISTS ".TABLE_EXPEDITION_ATTACKS." ("
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
        . " primary key ( id )"
        . " )";
    $db->sql_query($query);

    $query = "CREATE TABLE IF NOT EXISTS `" . TABLE_EXPEDITION_ITEMS . "` (
            `id` INT NOT NULL AUTO_INCREMENT,
            `id_eXpedition` INT NOT NULL DEFAULT '0',
            `type` ENUM('Metal', 'Cristal', 'Deut', 'Kraken', 'Detroid', 'Newtron') NOT NULL,
            `niveau` ENUM('bronze', 'argent', 'or') NOT NULL,
            PRIMARY KEY (`id`)
          ) DEFAULT CHARSET=utf8";
    $db->sql_query($query);

// Mise à jour depuis les versions 1.1.8 et 1.1.9
if($version == "1.1.8" || $version == "1.1.9"){
	$query = "ALTER TABLE ".TABLE_EXPEDITION_ATTACKS." ADD fau INT NOT NULL , ADD ecl INT NOT NULL ";
	$db->sql_query($query);

	$query = "ALTER TABLE ".TABLE_EXPEDITION_FLEET." ADD fau INT NOT NULL AFTER tra , ADD ecl INT NOT NULL AFTER fau ";
	$db->sql_query($query);
}

// Mise à jour de la version du mod et des Callbacks
$query = "REPLACE INTO " . TABLE_XTENSE_CALLBACKS . " ( `mod_id` , `function` , `type` ) VALUES ( '" . $mod_id . "', 'eXpedition_attack', 'rc')";
$db->sql_query($query);


update_mod($mod_folder, $mod_name);

?>
