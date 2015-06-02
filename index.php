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
define("TABLE_EXPEDITION_OPTIONS", $table_prefix."eXpedition_options");	// Options

//On vérifie que le mod est activé
$query = "SELECT `active`,`root` FROM `".TABLE_MOD."` WHERE `action`='eXpedition' AND `active`='1' LIMIT 1";
if (!$db->sql_numrows($db->sql_query($query))) die('Mod désactivé !');
$result = $db->sql_query($query);
list($active,$root) = $db->sql_fetch_row($result);

// MOD FOLDER
define('FOLDER_EXP','mod/'.$root);

require_once("views/page_header.php");

if(isset($pub_subaction)){
	switch($pub_subaction){
		case 'stats':
			include("statistiques.php");
			break;
		case 'global':
			include("statistiques.php");
			break;
		case 'hof':
			include("hof.php");
			break;
		case 'detail':
			include("detail.php");
			break;
		case 'detailAll':
			include("detail.php");
			break;
		default:
			include ("statistiques.php");
			break;
	}
	
}
else{
	include("statistiques.php");
}


require_once("views/page_tail.php");

?>