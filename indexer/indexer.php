#!/usr/bin/env php
<?php
/*********************************************************************
 * Broadcast Token Name System (CITNS-420) Indexer
 *
 * DISCLAIMER: 
 *
 * CITNS-420 is a bleeding-edge experimental protocol to play around 
 * with token functionality on Bitcoin and Counterparty. This is a 
 * hobby project, and I am NOT responsible for any losses, financial 
 * or otherwise, incurred from using this experimental protocol and 
 * its functionality.
 * 
 * CITNS Transactions that are considered valid one day may be 
 * considered invalid the next day after further review.
 *
 * Science is messy sometimes... DO NOT put in funds your not willing to lose!
 * 
 * If you use this, please donate: 1CITNS42oqp1vzLTfBzHQGPhfvQdi2UjoEc
 *
 * Author: Lei Xiaobo <lei@commswap.com>
 * 
 * Command line arguments :
 * --testnet    Load data from testnet
 * --block=#    Load data for given block
 * --rollback=# Rollback data to a given block
 * --single     Load single block
 ********************************************************************/

// Hide all but errors and parse issues
error_reporting(E_ERROR|E_PARSE);

// Parse in any command line args and set basic runtime flags
$args     = getopt("", array("testnet::", "block::", "single::", "rollback::",));
$testnet  = (isset($args['testnet'])) ? true : false;
$single   = (isset($args['single'])) ? true : false;  
$block    = (is_numeric($args['block'])) ? intval($args['block']) : false;
$network  = ($testnet) ? 'testnet' : 'mainnet';
$rollback = (is_numeric($args['rollback'])) ? intval($args['rollback']) : false;

// Define some constants used for locking processes and logging errors
define("LOCKFILE", '/var/tmp/CITNS-indexer-' . $network . '.lock');
define("LASTFILE", '/var/tmp/CITNS-indexer-' . $network . '.last-block');
define("ERRORLOG", '/var/tmp/CITNS-indexer-' . $network . '.errors');

// Load config (only after $network is defined)
require_once('includes/config.php');

// Print indexer version number so it shows up in debug logs
print "CITNS Indexer v" . VERSION_STRING . "\n";

// Set database name from global var CP_DATA 
$dbase = CP_DATA; 

// Initialize the database connection
initDB();

// Create a lock file, and bail if we detect an instance is already running
createLockFile();

// Handle rollbacks
if($rollback)
    CITNSRollback($rollback);

// If no block given, load last block from state file, or use first block with BTNX tx
if(!$block){
    $last  = file_get_contents(LASTFILE);
    $first = FIRST_BLOCK; // First block a CITNS transaction is seen
    $block = (isset($last) && $last>=$first) ? (intval($last) + 1) : $first;
}

// Get the current block index from status info
$results = $mysqli->query("SELECT block_index FROM {$dbase}.blocks ORDER BY block_index DESC limit 1");
if($results){
    $row     = (object) $results->fetch_assoc();
    $current = $row->block_index;
} else {
    byeLog('Error while trying to lookup current block');
}

// Check to make sure cp2mysql is not running and parsing in block data
// Prevents issue where tokens might be missed because we are still in middle of parsing in a block
$service  = 'counterparty'; // counterparty / dogeparty
$lockfile = '/var/tmp/' . $service . '2mysql-' . $network . '.lock';
if(file_exists($lockfile))
    byeLog("found {$service} parsing a block... exiting");

// Loop through the blocks until we are current
while($block <= $current){
    $timer = new Profiler();
    print "processing block {$block}...";

    // Lookup any CITNS action broadcasts in this block (anything with bt: or CITNS: prefix)
    $sql = "SELECT
                b.text,
                b.value as version,
                t.hash as tx_hash,
                a.address as source
            FROM
                {$dbase}.broadcasts b,
                {$dbase}.index_transactions t,
                {$dbase}.index_addresses a
            WHERE 
                t.id=b.tx_hash_id AND
                a.id=b.source_id AND
                b.block_index='{$block}' AND
                b.status='valid' AND
                (b.text LIKE 'bt:%' OR b.text LIKE 'CITNS:%')
            ORDER BY b.tx_index ASC";
    $results = $mysqli->query($sql);
    if($results){
        if($results->num_rows){
            while($row = $results->fetch_assoc()){
                // Assoc arrays to track address/ticker changes
                $addresses = array();
                $tickers   = array();
                $error     = false;                  
                $row       = (object) $row;
                $prefixes  = array('/^bt:/','/^CITNS:/');
                $params    = explode('|',preg_replace($prefixes,'',$row->text));
                $version   = $row->version;  // Project Version
                $source    = $row->source;   // Source address

                // Create database records and get ids for tx_hash and source address
                $source_id  = createAddress($row->source);
                $tx_hash_id = createTransaction($row->tx_hash);

                // Trim whitespace from any PARAMS
                foreach($params as $idx => $value)
                    $params[$idx] = trim($value);

                // Extract ACTION from PARAMS
                $action = strtoupper(array_shift($params)); 

                // Support legacy CITNS format with no VERSION on DEPLOY/MINT/TRANSFER actions (default to VERSION 0)
                if(in_array($action,array('DEPLOY','MINT','TRANSFER')) && isLegacyCITNSFormat($params))
                    array_splice($params, 0, 0, 0);

                // Support old BRC20/SRC20 actions 
                if($action=='TRANSFER') $action = 'SEND';
                if($action=='DEPLOY')   $action = 'ISSUE';

                // Define basic CITNS transaction data object
                $data = (object) array(
                    'ACTION'      => $action,       // Action (ISSUE, MINT, SEND, etc)
                    'BLOCK_INDEX' => $block,        // Block index 
                    'SOURCE'      => $row->source,  // Source/Broadcasting address
                    'TX_HASH'     => $row->tx_hash  // Transaction Hash
                );

                // Create a record of this transaction in the transactions table
                createTxIndex($data);

                // Get tx_index of record using tx_hash
                $data->TX_INDEX = getTxIndex($data->TX_HASH);

                // Validate Action
                if(!array_key_exists($action,PROTOCOL_CHANGES))
                    $error = 'invalid: Unknown ACTION';

                // Verify action is activated (past ACTIVATION_BLOCK)
                if(!$error && !isEnabled($action, $network, $block))
                    $error = 'invalid: ACTIVATION_BLOCK';

                // Handle processing the specific CITNS ACTION commands
                CITNSAction($action, $params, $data, $error);
            }
        }
    } else {
        byeLog("Error while trying to lookup CITNS broadcasts");
    }

    // Create hash of the credits/debits/balances table and create record in `blocks` table
    createBlock($block);

    // Report time to process block
    $time = $timer->finish();
    print " Done [{$time}ms]\n";

    // Bail out if user only wants to process one block
    if($single){
        print "detected single block... bailing out\n";
        break;
    } else {
        // Save block# to state file (so we can resume from this block next run)
        if($block>$last)
            file_put_contents(LASTFILE, $block);
    }

    // Increase block before next loop
    $block++;
}    

// Remove the lockfile now that we are done running
removeLockFile();

print "Total Execution time: " . $runtime->finish() ." seconds\n";
