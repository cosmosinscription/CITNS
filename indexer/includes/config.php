<?php
/*********************************************************************
 * config.php - Config info / Credentials
 ********************************************************************/

/* 
 * Database Config
 */

// Mainnet config
if($runtype=='mainnet'){
    define("DB_HOST", "localhost");
    define("DB_USER", "mysql_username");
    define("DB_PASS", "mysql_password");
    define("DB_DATA", "BTNS_Counterparty"); // Database where BTNS data is stored
    define("CP_DATA", "Counterparty");      // Database where Counterparty data is stored 
}

// Testnet config
if($runtype=='testnet'){
    define("DB_HOST", "localhost");
    define("DB_USER", "mysql_username");
    define("DB_PASS", "mysql_password");
    define("DB_DATA", "BTNS_Counterparty_Testnet"); // Database where BTNS data is stored
    define("CP_DATA", "Counterparty_Testnet");      // Database where Counterparty data is stored 
}

// BTNS Indexer Version
define("VERSION_MAJOR", 0);
define("VERSION_MINOR", 10);
define("VERSION_REVISION",0);
define("VERSION_STRING", VERSION_MAJOR . '.' . VERSION_MINOR . '.' . VERSION_REVISION);

// First block with BTNS transaction
define("FIRST_BLOCK",789742);

// General functions
require_once('functions.php');

// Tracks Execution Time
require_once('profiler.php');

// Protocol Changes / Activation blocks
require_once('protocol_changes.php');

// BTNS Actions
require_once('actions/airdrop.php');
require_once('actions/batch.php');
require_once('actions/bet.php');
require_once('actions/callback.php');
require_once('actions/destroy.php');
require_once('actions/dispenser.php');
require_once('actions/dividend.php');
require_once('actions/issue.php');
require_once('actions/list.php');
require_once('actions/mint.php');
require_once('actions/rug.php');
require_once('actions/sleep.php');
require_once('actions/send.php');
require_once('actions/sweep.php');

// Start runtime clock
$runtime = new Profiler();

?>
