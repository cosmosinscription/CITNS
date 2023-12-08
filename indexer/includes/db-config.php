<?php
/*********************************************************************
 * db-config.php - Database Config / Credentials
 ********************************************************************/

// Mainnet config
if(NETWORK=='mainnet'){
    define("DB_HOST", "localhost");
    define("DB_USER", "mysql_username");
    define("DB_PASS", "mysql_password");
    define("DB_DATA", "CITNS_Cosmos"); // Database where CITNS data is stored
    define("CP_DATA", "Cosmos");      // Database where Cosmos data is stored 
}

// Testnet config
if(NETWORK=='testnet'){
    define("DB_HOST", "localhost");
    define("DB_USER", "mysql_username");
    define("DB_PASS", "mysql_password");
    define("DB_DATA", "CITNS_Cosmos_Testnet"); // Database where CITNS data is stored
    define("CP_DATA", "Cosmos_Testnet");      // Database where Cosmos data is stored 
}

?>
