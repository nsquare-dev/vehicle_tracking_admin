<?php

define("POWERCUTOFF", 16);

if (file_exists('server_model.php')) {
    require_once('server_model.php');
} else {
    die("server_model required.");
}
/* !	@class		SocketServer
  @author		Gaurav Lohar
  @abstract 	A Framework for creating a multi-client server using the PHP language.
 */

class SocketServer extends Server_model {

    protected $config;
    protected $master_socket;
    public $max_read = 2048;
    public $tracker_id_position = 1;
    protected $streamType = "tcp";
    protected $defaultCmd = "Live";
    protected $timeout = 10000;
    protected $read_timeout = 5;
    protected $insertIds = array();
    protected $collection = array();
    protected $trackers = array("20180109", "20180131");
    protected $lastInsert;
    protected $sentEvent;
    protected $packetArray = array(
        "+#ACT", "+#ASC", "+KPT",
    );
    protected $tracker;
    protected $clients;
    protected $minvoltage;
    protected $timeOutArray;

    public function __construct($bind_ip, $port) {

        parent::__construct();

        set_time_limit(0);
        $this->config["ip"] = $bind_ip;
        $this->config["port"] = $port;
        $this->master_socket = stream_socket_server("$this->streamType://$bind_ip:$port", $errno, $errorMessage);
        SocketServer::debug("Script Started: OK ");
        if ($this->master_socket === false) {
            die("Could not bind to socket: $errorMessage \n");
        }

        $this->getAdminConfig();
        if ($this->getRow()) {
            $this->minvoltage = round($this->getField("batteryVoltage"));
        } else {
            $this->minvoltage = 30.1;
        }
    }

    public function execute($client_socks = array()) {
        //prepare readable sockets
        $read_socks = $client_socks;
        $read_socks[] = $this->master_socket;

        //start reading and use a large timeout
        if (!stream_select($read_socks, $write, $except, $this->timeout)) {
            die('something went wrong while selecting.\n');
        }

        //new client
        if (in_array($this->master_socket, $read_socks)) {
            $new_client = stream_socket_accept($this->master_socket);

            if ($new_client) {
                //print remote client information, ip and port number
                SocketServer::debug('Connection accepted from ' . stream_socket_get_name($new_client, true));
                $this->clients[] = $new_client;
                SocketServer::debug("Now there are total " . count($this->clients) . " clients.");
                $this->err_log("New Connection", 'accepted from ' . stream_socket_get_name($new_client, true));
                $this->err_log("Total Connections", 'Now there are total  ' . count($this->clients) . ' clients.');
            }
            //delete the server socket from the read sockets
            unset($read_socks[array_search($this->master_socket, $read_socks)]);
        }

        //message from existing client
        foreach ($read_socks as $sock) {

            //read the message from client
            $buf = $this->readData($sock);

            if (!$buf) {
                continue;
            }

            if ($buf == 'shutdown') {
                unset($this->clients[array_search($sock, $this->clients)]);
                @fclose($sock);
                $this->err_log("Stopped", 'Server Status');
            }

            if (strpos($buf, 'RPT') !== false || strpos($buf, 'ACT') !== false) {
                $this->checkTrackerStatus($buf);
            }

            /*
             * Explode tracker report data and get tracker id             
             */
            $_reportData = str_replace(" ", "", $buf);
            if (strlen($_reportData) >= 6) {
                $_trackerReportData = explode(',', $_reportData);
                $this->tracker = $_trackerReportData[$this->tracker_id_position];
                /*
                 * Check for commands in queue and send it
                 */
                $this->get_action_by_tracker($this->tracker);

                if ($this->getRow()) {
                    $cmd = $this->getField("cmd");
                    $device = $this->getField("trackerId");
                    $updateID = $this->getField("id");
                    $this->collection[$this->tracker] = $cmd;
                    $strCmd = str_replace(" ", "", $cmd);
                    $sentPacket = substr($strCmd, 0, 5);
                    $this->sentEvent[$this->tracker] = substr($strCmd, 5, 2);
                    $data = "Command: $cmd";
                    $this->lastInsert = $this->insert_command($this->tracker, $cmd);
                    $this->insertIds[$this->tracker] = $this->lastInsert;
                    $this->err_log($this->lastInsert, 'Last Insert ID');
                    $this->err_log($cmd, 'Sent');
                }

                /*
                 * Check reservation status as running and update current scooter locations.
                 * */
                if (strpos($_reportData, 'RPT') !== false || strpos($_reportData, 'ACT') !== false) {
                    $this->checkReservationAndUpdateTrack($this->tracker, htmlspecialchars($_reportData));
                }
            }

            if (count($this->collection) && array_key_exists($this->tracker, $this->collection)) {

                //send the message back to client
                $this->writeData($sock, $this->collection[$this->tracker]);
                /*
                 * Update record as sent
                 */
                $this->update_action_by_tracker($this->tracker, $updateID);
                unset($this->collection[$this->tracker]);

                $_acknowledgeStr = $this->readData($sock);

                if ($_acknowledgeStr) {
                    if (strlen($_acknowledgeStr) >= 6) {

                        if (strpos($_acknowledgeStr, 'RPT') !== false || strpos($_acknowledgeStr, 'ACT') !== false) {
                            $this->checkTrackerStatus($_acknowledgeStr);
                        }
                        $this->checkEventAndInsert($_acknowledgeStr);
                        if (strpos($_acknowledgeStr, 'RPT') !== false || strpos($_acknowledgeStr, 'ACT') !== false) {
                            $this->checkReservationAndUpdateTrack($this->tracker, htmlspecialchars($_acknowledgeStr));
                        }
                    }
                }
            }
            echo "Tracker : $this->tracker \n";

            if ($buf) {
                $this->checkEventAndInsert(htmlspecialchars($buf));
            }
            if (!in_array($this->tracker, $this->trackers)) {
                //send the message back to client
                $this->writeData($sock, $this->defaultCmd);
            }
        }
        return $this->clients;
    }

    public function readData($sock = false) {
        if ($sock === false) {
            return false;
        } else {

            $buf = fread($sock, $this->max_read);

            if (!$buf) {
                unset($this->clients[array_search($sock, $this->clients)]);
                @fclose($sock);
                SocketServer::debug("A client disconnected. Now there are total " . count($this->clients) . " clients.");
                $this->err_log("Connection closed", 'Now there are total  ' . count($this->clients) . ' clients.');
                return false;
            }

            SocketServer::debug("Client " . stream_socket_get_name($sock, true) . ": $buf");
            SocketServer::debug("Date And Time: " . date("r"));
            SocketServer::debug("---------------------------------------");

            $this->err_log($buf, 'Received');

            return $buf;
        }
    }

    public function writeData($sock = false, $cmd = false) {
        if ($sock == false) {
            return false;
        } elseif ($cmd == false) {
            return false;
        } else {
            if (fwrite($sock, "$cmd\r\n") === false) {
                SocketServer::debug("Failed to sent to client :" . stream_socket_get_name($sock, true) . ": $cmd");
                SocketServer::debug("Date And Time: " . date("r"));
                SocketServer::debug("---------------------------------------");
                $this->err_log($cmd, 'Sent Failed');
                return false;
            } else {

                SocketServer::debug("Server to " . stream_socket_get_name($sock, true) . ": $cmd");
                SocketServer::debug("Date And Time: " . date("r"));
                SocketServer::debug("---------------------------------------");
                $this->err_log($cmd, 'Sent');
                return true;
            }
        }
    }

    public function shutdown() {
        stream_socket_shutdown($this->master_socket, STREAM_SHUT_WR);
        SocketServer::debug("Socket closed: OK");
    }

    private function checkEventAndInsert($_acknowledgeStr = false) {

        $_ackResponce = str_replace(" ", "", $_acknowledgeStr);

        if (strlen($_ackResponce) >= 6) {
            $receivedPacket = substr($_ackResponce, 0, 5);
            $receivedEvent = substr($_ackResponce, 5, 2);

            if (in_array($receivedPacket, $this->packetArray)) {

                if ($receivedEvent == $this->sentEvent[$this->tracker]) {
                    $this->update_command($this->tracker, $this->insertIds[$this->tracker], htmlspecialchars($_acknowledgeStr));
                    unset($this->insertIds[$this->tracker]);
                    unset($this->sentEvent[$this->tracker]);
                /*} elseif (substr($_ackResponce, 2, 3) == "KPT" && isset($this->insertIds[$this->tracker])) {
                    $this->update_command($this->tracker, $this->insertIds[$this->tracker], htmlspecialchars($_acknowledgeStr));
                    unset($this->insertIds[$this->tracker]);
                    unset($this->sentEvent[$this->tracker]);*/
                } else {
                    $this->insert_ack($this->tracker, htmlspecialchars($_acknowledgeStr));
                }
            } else {

                $this->insert_ack($this->tracker, htmlspecialchars($_acknowledgeStr));
            }
        } else {
            $this->insert_ack($this->tracker, htmlspecialchars($_acknowledgeStr));
        }
    }

    /* !	@function	debug
      @static
      @abstract	Outputs Text directly.
      @discussion	Yeah, should probably make a way to turn this off.
      @param		string	- Text to Output
      @result		void
     */

    public static function debug($text) {
        echo("{$text}\r\n");
    }

    public function checkTrackerStatus($_acknowledgeStr = false) {
        $_ackResponce = str_replace(" ", "", $_acknowledgeStr);

        if (strlen($_ackResponce) >= 6) {

            $_packetArray = $this->getByforgettedResponse($_ackResponce);

            //Check tracker battery status
            if ($_packetArray['betteryStatus'] <= $this->minvoltage) {
                return $this->sendAdminNotification($_packetArray['deviceNumber'], 'LOWBATTERY');

                //Check Battery low
            } else if ((bool) ($_packetArray['optional_message'] & 1)) {
                return $this->sendAdminNotification($_packetArray['deviceNumber'], 'LOWBATTERY');

                /* /Switch on/off
                  }else if((bool)($_packetArray['optional_message'] & 3)){
                  return $this->sendAdminNotification($_packetArray['deviceNumber'], 'SWITCH_STATUS'); */

                //Alarm enable    
            } else if ((bool) ($_packetArray['optional_message'] & 5)) {
                return $this->sendAdminNotification($_packetArray['deviceNumber'], 'ALARM_ENABLED');

                //Arming   
            } else if ((bool) ($_packetArray['optional_message'] & 6)) {
                return $this->sendAdminNotification($_packetArray['deviceNumber'], 'ALARMING');
                //Check tracker status power cut off    
            } else if ((bool) ($_packetArray['optional_message'] & 16)) {

                return $this->sendAdminNotification($_packetArray['deviceNumber'], 'DISCONNECTED');

                //Thief alarm
            } else if ((bool) ($_packetArray['optional_message'] & 17)) {
                return $this->sendAdminNotification($_packetArray['deviceNumber'], 'THIEF_ALARAM');
                //Tow alarm 
            } else if ((bool) ($_packetArray['optional_message'] & 18)) {
                return $this->sendAdminNotification($_packetArray['deviceNumber'], 'TOW_ALARM');
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getByforgettedResponse($_ackResponce = false) {

        $string_responce = explode(',', $_ackResponce);

        $return = array(
            "packetAndEvent" => $string_responce[0],
            "deviceNumber" => $string_responce[1],
            "gpsValid" => $string_responce[2],
            "dateAndTime" => $string_responce[3],
            "loc" => $string_responce[4],
            "speed" => $string_responce[5],
            "dir" => $string_responce[6],
            "mileage" => $string_responce[7],
            "betteryStatus" => $string_responce[8],
        );

        if (isset($string_responce[9])) {

            $return["optional_message"] = base_convert($string_responce[9], 16, 10);
        } else {
            $return["optional_message"] = false;
        }


        return $return;
    }

}

?>