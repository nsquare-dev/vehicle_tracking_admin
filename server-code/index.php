<?php 
date_default_timezone_set('Asia/Singapore'); 
//date_default_timezone_set("Asia/Kolkata");
require_once('server_model.php'); 
$DBObj = new Server_model();

ini_set('error_reporting', E_ALL ^ E_NOTICE); 
ini_set('display_errors', 0);  
// if (ob_get_level() == 0) ob_start(); 
// Set time limit to indefinite execution 
set_time_limit (0); 

// Set the ip and port we will listen on 
$address = '0.0.0.0'; 
$port = 9002; 
$dataByte = 1024;
$timeout = array('sec'=>5,'usec'=>0); 

// Create a TCP Stream socket 
$sock = socket_create(AF_INET, SOCK_STREAM, 0); 

// Bind the socket to an address/port 
socket_bind($sock, $address, $port) or die('Could not bind to address'); 

// Start listening for connections 
socket_listen($sock); 

// Non block socket type 
socket_set_nonblock($sock); 

$defaultCmd =  "Live";
// Loop continuously 
static $lastInsert;
static $sentEvent;
$packetArray = array(
    "+#ACT", "+#ASC"
); 
$insertIds = array();
$collection = array();            
$trackers = array("20180109", "20180131");

echo "Script Started: OK \n"; 
$DBObj->err_log("Started", 'Server Status'); 
while (true) 
{      
    unset($read); 
    $j = 0; 
    if (count($client)) 
    { 
        foreach ($client AS $k => $v) 
        { 
            //$read[$j] = $v; 
            $read[$k] = $v; 
            $j++; 
        } 
    } 
    $client = $read;     
    if ($newsock = @socket_accept($sock)) 
    {           
        if (is_resource($newsock)) 
        {   
            echo "New client connected: $j \n";             
            if (!socket_set_option($newsock, SOL_SOCKET, SO_RCVTIMEO, $timeout)) {
                echo 'Unable to set option on socket: '. socket_strerror(socket_last_error()) . PHP_EOL;
            }
            $defaultCmd =  "Live";             
            if(false === @socket_write($newsock, $defaultCmd, strlen($defaultCmd))){
                socket_close($newsock);                  
            }            
            echo "New client packet Live sent : OK \n";                   
            if (false === ($bytes = socket_recv($newsock, $buf, $dataByte, 0))) 
            { 
                $DBObj->err_log("No data received from : $k", 'Tracker Status');
                $errorcode = socket_last_error($v);
                if($errorcode !== 11){ 
                    echo "New client socket_recv() failed; reason: " . socket_strerror($errorcode) . "\n"; 
                    socket_close($newsock);  
                }                 
            } 
            else 
            { 
                echo "New client Read $bytes bytes from socket_recv(). \n"; 
            }               
            echo "New client: $buf \n";            
            $strData = str_replace(" ", "", $buf);
            if (strlen($strData) >= 6) {
                /*
                 * Replace device tracking and socket resource with array key and value.
                 */                
                $responceData = explode(',', $strData);
                if(is_array($responceData)){
                    $client[$responceData[1]] = $newsock;                    
                } else{
                    $client[$j] = $newsock; 
                }
            }else{
                $client[$j] = $newsock; 
            } 
            $j++; 
        }
        sleep(1);
    }  
    if (count($client)) 
    { 
        foreach ($client AS $k => $v) 
        {  
            /*
             *Send instrucations 
             **/ 
            $DBObj->get_action_by_tracker($k);  
            if($DBObj->getRow()){                 
                $defaultCmd = $DBObj->getField("cmd"); 
                $device = $DBObj->getField("trackerId");  
                $updateID = $DBObj->getField("id");                
                $collection[$device] = $defaultCmd;
                $strCmd = str_replace(" ", "", $defaultCmd); 
                $sentPacket = substr($strCmd, 0, 5);
                $sentEvent = substr($strCmd, 5, 2); 
                $data = "Command: $defaultCmd"; 
                $lastInsert = $DBObj->insert_command($device, $defaultCmd);
                $insertIds[$k]  = $lastInsert;
                $DBObj->err_log($lastInsert, 'Last Insert ID');
                $DBObj->err_log($defaultCmd, 'Sent');      
                
            }else{
                if(in_array( $k, $trackers)){
                    $defaultCmd = "";
                }else{
                    $defaultCmd = "Live";
                }                 
            }            
            if (count($collection) && array_key_exists($k, $collection)){   
                 
                if(false === @socket_write($v, $collection[$k], strlen($defaultCmd))){
                    socket_close($v); 
                    unset($client[$k]); 
                }
                /*
                * Update record as sent
                */
                $DBObj->update_action_by_tracker($k, $updateID);
                unset($collection[$k]);                
            }else{
                
                if(false === @socket_write($v, $defaultCmd, strlen($defaultCmd))){
                    socket_close($v); 
                    unset($client[$k]);  
                }
            }            
            if (false === ($bytes = socket_recv($v, $buf, $dataByte, 0))) 
            { 
                $errorcode = socket_last_error($v); 
                if($errorcode !== 11){
                    echo "Client $k: socket_recv() failed; reason: " . socket_strerror($errorcode) . "\n";
                    unset($client[$k]); 
                    socket_close($v); 
                } 
            } 
            else 
            { 
                echo "Client $k: Read $bytes bytes from socket_recv(). \n";
            }            
            if (!$buf = trim($buf)) {
                continue;
            }
            if ($buf == 'quit') {
                break;
            }
            if ($buf == 'shutdown') {
                unset($client[$k]); 
                socket_close($v); 
                $DBObj->err_log("Stopped", 'Server Status'); 
            }
            if ($buf) 
            {
                echo "Client $k: $buf\n"; 
            }   
            $DBObj->err_log($buf, 'Received');            
            // byfoget command and response
            $strResponce = str_replace(" ", "", $buf);
            if (strlen($strResponce) >= 6) {
                $receivedPacket = substr($strResponce, 0, 5);
                $receivedEvent = substr($strResponce, 5, 2);                
                if (in_array($receivedPacket, $packetArray)) {
                    if ($receivedEvent == $sentEvent) {
                        $DBObj->update_command($k, $insertIds[$k], $buf);
                        unset($insertIds[$k]);
                    } else {
                        $DBObj->insert_ack($k, $buf);
                    }
                } else {
                    $DBObj->insert_ack($k, $buf);
                }                  
                $DBObj->checkReservationAndUpdateTrack($k, $buf);                
            } else {
                $DBObj->insert_ack($k, $buf);
            }   
        }         
    }
    
    //ob_flush();
    //flush();
    sleep(1); 
} 
//ob_end_flush();
// Close the master sockets 
socket_close($sock); 
echo "Socket closed: OK \n";  
?>