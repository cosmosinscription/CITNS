<?php
/*********************************************************************
 * protocol_changes.php - Tracks protocol changes and activation blocks
 ********************************************************************/

// Temp placeholder for associative array of protocol changes
$changes = array(
    // FORMAT
    // 'NAME' => array($version_major, $version_minor, $version_revision, $mainnet_block_index, $testnet_block_index),

    // Define `ACTION` commands and `ACTIVATION_BLOCK` for each (ALL UPPER case)
    // CITNS-420 SPEC defines when things are ACTUALLY activated
    // active here just means active for testing / debugging
    'AIRDROP'   => array(0,  10,   0,     0,      0),
    'BATCH'     => array(0,  10,   0,     0,      0),
    'BET'       => array(0,  10,   0,     0,      0),
    'CALLBACK'  => array(0,  10,   0,     0,      0),
    'DESTROY'   => array(0,  10,   0,     0,      0),
    'DISPENSER' => array(0,  10,   0,     0,      0),
    'DIVIDEND'  => array(0,  10,   0,     0,      0),
    'ISSUE'     => array(0,  10,   0,     0,      0),
    'LIST'      => array(0,  10,   0,     0,      0),
    'MINT'      => array(0,  10,   0,     0,      0),
    'RUG'       => array(0,  10,   0,     0,      0),
    'SEND'      => array(0,  10,   0,     0,      0),
    'SLEEP'     => array(0,  10,   0,     0,      0),
    'SWEEP'     => array(0,  10,   0,     0,      0),

    // Define protocol changes (ALL LOWER Case)
    // 'name' => array(0,  10,   0,     0,      0),
);

// Loop through changes, validate formats, die on any errors
$protocol_changes = array();
foreach($changes as $name => $info){
    $status = addProtocolChange($name, $info[0], $info[1], $info[2], $info[3], $info[4]);
    if(gettype($status)=='string')
        byeLog("PROTOCOL_CHANGE ERROR: {$name} - {$status}");
}

// Define PROTOCOL_CHANGES constant 
define("PROTOCOL_CHANGES", $protocol_changes);

// Examples
// 
// Validate that feature is active
// isEnabled($name=null, $network=null, $block_index=null);
// $enabled = isEnabled('AIRDROP','mainnet', 123456);

?>
