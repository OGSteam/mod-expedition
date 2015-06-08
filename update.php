<?php

// Hack Protection
if (!defined('IN_SPYOGAME')) {
    exit('Hacking Attempt!');
}

// We'll need that
global $db, $table_prefix;
define("TABLE_EXPEDITION_NUL", $table_prefix."eXpedition_nul"); 		// 0 - Nul
define("TABLE_EXPEDITION_RESS", $table_prefix."eXpedition_ress"); 		// 1 - Ressources
define("TABLE_EXPEDITION_FLEET", $table_prefix."eXpedition_fleet"); 	// 2 - Vaisseaux
define("TABLE_EXPEDITION_MERCH", $table_prefix."eXpedition_merch"); 	// 3 - Marchand

$mod_folder = 'expedition';
$mod_name   = 'eXpedition';

//On récupère la version actuelle du mod
$query = "SELECT id, version FROM ".TABLE_MOD." WHERE action='eXpedition'";
$result = $db->sql_query($query);
list($mod_id, $version) = $db->sql_fetch_row($result);
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

update_mod($mod_folder, $mod_name);

?>
