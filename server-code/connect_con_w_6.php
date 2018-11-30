<?php
 
    error_reporting(E_ALL);
   
    echo "<h2>TCP/IP Connection Write continuosly  Client 6</h2>\n";
    // Workaround for the missing define
    if(!defined('MSG_DONTWAIT')) define('MSG_DONTWAIT', 0x40);
    /* Get the port for the WWW service. */
    $service_port = 9002;

    /* Get the IP address for the target host. */
    $address = '127.0.0.1';

    /* Create a TCP/IP socket. */
    $socket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    if ($socket === false) {
        echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
        die;
    } else {
        echo "OK.\n";
    }

    echo "Attempting to connect to '$address' on port '$service_port'...";
    $result = socket_connect($socket, $address, $service_port);
    if ($result === false) {
        echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
    } else {
        echo "OK.\n";
    }
 
    $defaultResponce = "+#RPT00,20180106,L,180122144659,N2237.4410E11403.2599,000,000,65535.0,39.3,0241A046\n";
        
   
    // Loop continuously 
    while (true) 
    { 
       
        /*
         * Write responce to socket
         */ 
          
        if(false === @socket_write($socket, $defaultResponce, strlen($defaultResponce))){
            socket_close($socket);  
            break;
        }
        
        
        //$out = socket_read($socket, 2048); 
        //sleep(5);  
        if (false !== ($bytes = socket_recv($socket, $out, 1024, 0))) {
            echo "Read $bytes bytes from socket_recv(). \n";
 
        } else {
            echo "socket_recv() failed; reason: " . socket_strerror(socket_last_error($socket)) . "\n";
            break;
        }

        echo "Client: $defaultResponce \n";
        
        if ($out) 
        {
            echo "Sever: $out\n"; 
        } 
                
                
        // byfoget command and response
        $strResponce = str_replace(" ", "", $out);
        if (strlen($strResponce) >= 6) {
            $receivedPacket = substr($strResponce, 0, 5);
            $receivedEvent = substr($strResponce, 5, 2);                             
            if($receivedPacket === "++CTL"){                    
                $defaultResponce = "+#ACT$receivedEvent,20180106,L,180122144659,N2237.4410E11403.2599,000,000,65535.0,39.3,0241A046\n";                    
            }else if($receivedPacket === "++SET"){
                $defaultResponce = "+#ACS$receivedEvent,20180106,L,180122144659,N2237.4410E11403.2599,000,000,65535.0,39.3,0241A046\n";
            } else{
                $defaultResponce = "+#RPT00,20180106,L,180122144659,N2237.4410E11403.2599,000,000,65535.0,39.3,0241A046\n";
            }
         }else{
             $defaultResponce = "+#RPT00,20180106,L,180122144659,N2237.4410E11403.2599,000,000,65535.0,39.3,0241A046\n";
         } 
  
        sleep(0.25); 
     
    } 

    echo "Closing socket...";
    socket_close($socket); 
    echo "OK.\n\n";
?>