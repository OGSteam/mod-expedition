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
define("TABLE_EXPEDITION_OPTIONS", $table_prefix."eXpedition_options");	// Options
define("TABLE_XTENSE_CALLBACKS", $table_prefix."xtense_callbacks");		// xtense Callbacks

// Get mod_id for xtense callback
$query = "SELECT id FROM ".TABLE_MOD." WHERE action='eXpedition' LIMIT 1";
$result = $db->sql_query($query);
$mod_id = $db->sql_fetch_row($result);
$mod_id = $mod_id[0];

$mod_uninstall_name  = 'eXpedition';
$mod_uninstall_table = TABLE_EXPEDITION.','.TABLE_EXPEDITION_NUL.','.TABLE_EXPEDITION_RESS.','.TABLE_EXPEDITION_FLEET.','.TABLE_EXPEDITION_MERCH.','.TABLE_EXPEDITION_ATTACKS.','.TABLE_EXPEDITION_ITEMS.','.TABLE_EXPEDITION_OPTIONS;
uninstall_mod ($mod_uninstall_name, $mod_uninstall_table);

// Check if xtense_callbacks table exists :
$query = 'SHOW TABLES LIKE "'.TABLE_XTENSE_CALLBACKS.'" ';
$result = $db->sql_query($query);
if($db->sql_numrows($result) != 0){
	// Table exists
	// Check if the mod is added
	$query = 'SELECT * FROM '.TABLE_XTENSE_CALLBACKS.' WHERE mod_id = '.$mod_id;
	$result = $db->sql_query($query);
	$nresult = $db->sql_numrows($result);
	if($nresult != 0) {
		// We remove it
		$query = 'DELETE FROM '.TABLE_XTENSE_CALLBACKS.' WHERE mod_id = '.$mod_id;
		$db->sql_query($query);
	}
}
