<?php
ini_set('error_reporting', E_ALL ^ E_NOTICE); 
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Singapore');
//date_default_timezone_set("Asia/Kolkata");
/*
 * Defined required parameters*
 */
 $port = 9002;
 $host = "0.0.0.0";  

 if(file_exists('SocketServer.php')){
    require_once('SocketServer.php');
    $Obj = new SocketServer($host, $port);
 }else{
     die("server_model required.");
 }
 
$client_socks = array();

while(true)
{
    $client_socks = $Obj->execute($client_socks);   
    sleep(0.25);
}

$Obj->shutdown();
?>