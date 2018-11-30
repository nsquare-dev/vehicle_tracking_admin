#!/usr/bin/php -q 
<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
 date_default_timezone_set('Asia/Singapore'); 
require_once('AbstractDB.php');


//maintain log
err_log("Started", 'Server Status');

error_reporting(E_ALL);

/* Allow the script to hang around waiting for connections. */
set_time_limit(0);

/* Turn on implicit output flushing so we see what we're getting
 * as it comes in. */
ob_implicit_flush();

$address = '0.0.0.0';
$port = 9002;
 
if (($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
    echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
}

if (socket_bind($sock, $address, $port) === false) {
    echo "socket_bind() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
}

if (socket_listen($sock, 5) === false) {
    echo "socket_listen() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
}


do {
    if (($msgsock = socket_accept($sock)) === false) {
        echo "socket_accept() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
        break;
    }

    do {//socket_read($msgsock, 2048, PHP_NORMAL_READ)
        if (false === ($buf = socket_read($msgsock, 2048, PHP_NORMAL_READ))) {

            echo "socket_read() failed: reason: " . socket_strerror(socket_last_error($msgsock)) . "\n";
            break 2;
        }

        if (!$buf = trim($buf)) {
            continue;
        }

        if ($buf == 'quit') {
            break;
        }
        if ($buf == 'shutdown') {
            socket_close($msgsock);
            err_log("Stopped", 'Server Status');
            break 2;
        }

        echo "New client: $buf \n";
		
			
        //Write action to txt log
        $data = "Date time: " . date('Y-m-d H:i:s') . PHP_EOL
                . "Response: $buf" . PHP_EOL;
        //-
        file_put_contents('../commands/output/output_' . date("j.n.Y") . '.txt', $data, FILE_APPEND);
        err_log($buf, 'Received');
        $sentEvent = false;
        if (file_exists('../commands/input/input_' . date("j.n.Y") . '.txt')) {                        
            /* Send instructions. */
            $file = file('../commands/input/input_' . date("j.n.Y") . '.txt');

            static $lastCommand = false;
            static $lastInsert = false;
            for ($i = max(0, count($file) - 1); $i < count($file); $i++) {

                $msg = $file[$i];
 
                if (!empty($msg)) {
                    socket_write($msgsock, $msg, strlen($msg));
                    $sentPacket = substr($msg, 0, 5);
                    $sentEvent = substr($msg, 5, 2);
                    $lastCommand = $msg;
                    $data = "Command: $msg";
                    file_put_contents('../commands/output/output_' . date("j.n.Y") . '.txt', $data, FILE_APPEND);
                    $lastInsert = insert_command($lastCommand);

                    err_log($msg, 'Sent');
                }
                

                if ($msg == 'shutdown') {
                    socket_close($msgsock);
                    err_log("Stopped", 'Server Status');
                    break 2;
                }
            }

            // byfoget command and response
            $strResponce = str_replace(" ", "", $buf);
            if (strlen($strResponce) >= 6) {
                $receivedPacket = substr($strResponce, 0, 5);
                $receivedEvent = substr($strResponce, 5, 2);

                $packetArray = array(
                    "+#ACT", "+#ASC"
                );

                if (in_array(strtoupper($receivedPacket), $packetArray)) {

                    if ($receivedEvent == $sentEvent) {
                        update_command($lastInsert, $buf);
                    } else {
                        insert_ack($buf);
                    }
                } else {
                    insert_ack($buf);
                }
            } else {
                insert_ack($buf);
            }
            file_put_contents('../commands/input/input_' . date("j.n.Y") . '.txt', '');
        }else{
            insert_ack($buf);
        }

    } while (true);
    socket_close($msgsock);
} while (true);

socket_close($sock);
        
/*

 * Databse operations */
function err_log($data = false, $type = false) {

    //Write action to txt log
    $log = "Connection: " . $_SERVER['REMOTE_ADDR'] . ' - ' . date("F j, Y, g:i:s a") . PHP_EOL .
            "Type: $type " . PHP_EOL .
            "Response: $data " . PHP_EOL .
            "------------------------------------" . PHP_EOL;
    //-
    file_put_contents('../commands/logs/log_' . date("j.n.Y") . '.txt', $log, FILE_APPEND);
    // return true;
}
  
function insert_command($insert_cmd = null) {
    $DBObj = new AbstractDB();
    
    if ($insert_cmd !== null) { 
        $result = $DBObj->query("INSERT INTO `es_push_command` (`command`, `sent_date_time`, `isSent`, `updateDate`) VALUES ('".$DBObj->escape_string($insert_cmd)."', '".date('Y-m-d H:i:s')."', '".intval(true)."', '".date('Y-m-d H:i:s')."')");
        if ($result) {
            return $DBObj->getInsertedId();
        }else{
            return false;
        }
    }
}
function update_command($update_cmd = false, $ack = false) {
    $DBObj = new AbstractDB();
    if ($update_cmd !== false && $id !== false) { 
        $result = $DBObj->query("UPDATE `es_push_command` SET `ack`='".$DBObj->escape_string($ack)."',`received_date_time`='".date('Y-m-d H:i:s')."',`isReceived`=".intval(true).",`updateDate`='".date("Y-m-d H:i:s")."' WHERE id=$update_cmd");
    }
}
    
    
function insert_ack($insert_ack = false) {
     
    $DBObj = new AbstractDB();    
    if ($insert_ack!== false) {
         $DBObj->query("INSERT INTO `es_push_command` (`ack`, `received_date_time`, `isReceived`, `updateDate`) VALUES ('".$DBObj->escape_string($insert_ack)."', '".date('Y-m-d H:i:s')."', '".intval(true)."', '".date('Y-m-d H:i:s')."')");
    } 
}
?>