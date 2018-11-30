<?php

require_once('AbstractDB.php');

/*
 * Custom constants defined here
 */
define('VERIFIED', 1);
define('NOTVERIFIED', 0);
define('ACTIVE', 1);
define('NOTACTIVE', 0);
define('DELETED', 1);
define('NOTDELETED', 0);

define('RESERVE', 1);
define('NOTRESERVE', 0);
define('ISLOCK', 0);
define('ISUNLOCK', 1);

define('RIDEPENDING', 'pending');
define('RIDERUNNING', 'running');
define('RIDECANCEL', 'cancel');
define('RIDECOMPLETE', 'complete');

define('RIDETRANSCTIONS', 0);
define('TOPUPTRANSCTIONS', 1);
define('REFFERALTRANSCTIONS', 2);

define('TIMEOUTSEC', 10);
define('GOOGLE_LOC_API_KEY', 'AIzaSyA4cydsYV4YgUWEPVM0tU2I6H74HQXvcj4');
define('NOTIFICATION_AUTH', 'AIzaSyCQl8x_w5R0uwo-vf0xTBV9HvLjeei68po');

class Server_model extends AbstractDB {

    function __construct() {
        parent::__construct();
    }

    function insert_command($device = false, $insert_cmd = null) {

        if ($insert_cmd !== null) {
            $result = $this->query("INSERT INTO `es_push_command` (`trackerId`, `command`, `sent_date_time`, `isSent`, `updateDate`) VALUES ('{$device}','" . $this->escape_string($insert_cmd) . "', '" . date('Y-m-d H:i:s') . "', '" . intval(true) . "', '" . date('Y-m-d H:i:s') . "')");
            if ($result) {
                return $this->getInsertedId();
            } else {
                return false;
            }
        }
    }

    function update_command($device = false, $update_cmd = false, $ack = false) {

        if ($update_cmd !== false && $id !== false) {
            $this->query("UPDATE `es_push_command` SET  `ack`='" . $this->escape_string($ack) . "',`received_date_time`='" . date('Y-m-d H:i:s') . "',`isReceived`=" . intval(true) . ",`updateDate`='" . date("Y-m-d H:i:s") . "' WHERE id=$update_cmd AND `trackerId`= {$device}");
        }
    }

    function insert_ack($device = false, $insert_ack = false) {

        if ($insert_ack !== false) {
            $this->query("INSERT INTO `es_push_command` (`trackerId`,`ack`, `received_date_time`, `isReceived`, `updateDate`) VALUES ('{$device}','" . $this->escape_string($insert_ack) . "', '" . date('Y-m-d H:i:s') . "', '" . intval(true) . "', '" . date('Y-m-d H:i:s') . "')");
        }
    }

    function get_action_by_tracker($tracker = false) {
        if ($tracker !== false) {
            return $this->query("SELECT `id`, `trackerId`, `cmd`, `createdDate` FROM `es_sent_cmd` WHERE  `trackerId`={$tracker} AND `isSent`=0 AND id=(SELECT max(id) from `es_sent_cmd` WHERE  `trackerId`={$tracker}) ORDER BY id DESC");
        } else {
            return false;
        }
    }

    function update_action_by_tracker($tracker = false, $updateID = false) {
        if ($tracker !== false && $updateID !== false) {
            return $this->query("UPDATE `es_sent_cmd` SET `isSent`=1, `updateDate`='" . date('Y-m-d H:i:s') . "'  WHERE `trackerId`={$tracker} AND `id`={$updateID} AND `isSent`=0");
        } else {
            return false;
        }
    }

    function checkReservationAndUpdateTrack($tracker = false, $responce = false) {
        if ($tracker !== false && $responce !== false) {

            $this->query("SELECT sr.id, sr.userId, sp.tarckId"
                    . " FROM es_scooter_reserve AS sr"
                    . " INNER JOIN es_scooter_parking AS sp ON sp.id=sr.scooterParkId"
                    . " WHERE sp.tarckId={$tracker} AND sp.scooterStatus=1 AND sp.isLockUnlock=1"
                    . " AND sp.status=1 AND sr.rideStatus='running'  order by sr.createdDate desc LIMIT 1");

            if ($this->getRow()) {

                $reserveId = $this->getField("id");
                $userId = $this->getField("userId");
                $trackerId = $this->getField("tarckId");

                $responceArray = explode(",", $responce);


                if (is_array($responceArray) && !empty($responceArray)) {

                    $result = $this->convertDMStoDec($responceArray[4]);

                    if ($result) {

                        if (intval($result['lat']) != 0 && intval($result['long']) != 0) {
                            $distance = $this->calculateDistance($result['lat'], $result['long'], $reserveId, $userId);

                            $this->query("INSERT INTO `es_scooter_track_location`"
                                    . "(`reserveId`, `userId`,  `trackLat`, `trackLng`, `distance`, `isDeleted`, `createdDate`)"
                                    . " VALUES ('{$reserveId}','{$userId}', '{$result['lat']}','{$result['long']}', "
                                    . "'{$distance}', '0', '" . date('Y-m-d H:i:s') . "')");
                            return true;
                        }
                        return false;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /*
     * Databse operations 
     */

    function err_log($data = false, $type = false) {

        //Write action to txt log
        $log = "Connection: " . $_SERVER['REMOTE_ADDR'] . ' - ' . date("r") . PHP_EOL .
                "Type: $type " . PHP_EOL .
                "Response: $data " . PHP_EOL .
                "------------------------------------" . PHP_EOL;
        //-
        file_put_contents('../commands/logs/log_' . date("j.n.Y") . '.txt', $log, FILE_APPEND);
        // return true;
    }

    /*
     * Converting From DMS To Decimal
     * 
     * North latitude or East longitude is converted as:
     * $decimal = $degrees + ($minutes / 60) + ($seconds / 3600);
     * South latitude or West longitude is calculated as:
     * $decimal = ($degrees + ($minutes / 60) + ($seconds / 3600)) * -1;
     *          */

    function convertDMStoDec($str = false) {

        if ($str !== false) {

            $strLen = strlen($str);
            $iLoc = strpos(strtolower($str), "e");
            $iLoc2 = strpos(strtolower($str), "w");
            //Get lat
            $sTemp = ($iLoc == -1 ? substr($str, 0, $iLoc2) : substr($str, 0, $iLoc));
            $laDegree = floatval(substr($sTemp, 1, 2));
            $laMin = floatval(substr($sTemp, 3));
            $laSec = 0;
            $laDir = substr($sTemp, 0, 1);
            //Get long
            $sTemp_lo = ($iLoc == -1 ? substr($str, $iLoc2) : substr($str, $iLoc));
            $loDegree = floatval(substr($sTemp_lo, 1, 3));
            $loMin = floatval(substr($sTemp_lo, 4));
            $loSec = 0;
            $loDir = substr($sTemp_lo, 0, 1);

            $lat = $this->DMS2Decimal($laDegree, $laMin, $laSec, $laDir);
            $long = $this->DMS2Decimal($loDegree, $loMin, $loSec, $loDir);
            $return = array(
                "lat" => ($lat) ? $lat : 0,
                "long" => ($long) ? $long : 0,
            );

            return $return;
        } else {
            return false;
        }
    }

    function DMS2Decimal($degrees = 0, $minutes = 0, $seconds = 0, $direction = 'n') {
        //converts DMS coordinates to decimal
        //returns false on bad inputs, decimal on success
        //direction must be n, s, e or w, case-insensitive
        $d = strtolower($direction);
        $ok = array('n', 's', 'e', 'w');

        //degrees must be integer between 0 and 180
        if (!is_numeric($degrees) || $degrees < 0 || $degrees > 180) {
            $decimal = false;
        }
        //minutes must be integer or float between 0 and 59
        elseif (!is_numeric($minutes) || $minutes < 0 || $minutes > 59) {
            $decimal = false;
        }
        //seconds must be integer or float between 0 and 59
        elseif (!is_numeric($seconds) || $seconds < 0 || $seconds > 59) {
            $decimal = false;
        } elseif (!in_array($d, $ok)) {
            $decimal = false;
        } else {
            //inputs clean, calculate
            $decimal = $degrees + ($minutes / 60) + ($seconds / 3600);

            //reverse for south or west coordinates; north is assumed
            if ($d == 's' || $d == 'w') {
                $decimal *= -1;
            }
        }

        return $decimal;
    }

    function calculateDistance($lat = false, $long = false, $reserveId = false, $userId = false) {

        $this->query("SELECT trackLat, trackLng"
                . " FROM `es_scooter_track_location`"
                . " WHERE reserveId='{$reserveId}'"
                . " AND userId='{$userId}'"
                . " ORDER BY id DESC LIMIT 1");

        if ($this->getRow()) {

            return $this->distance_haversine($this->getField('trackLat'), $this->getField('trackLng'), $lat, $long);
        } else {
            return 0;
        }
    }

    public function distance_haversine($lat1, $lon1, $lat2, $lon2) {
        $earth_radius = 3960.00;
        //global $earth_radius;
        $delta_lat = $lat2 - $lat1;
        $delta_lon = $lon2 - $lon1;
        $alpha = $delta_lat / 2;
        $beta = $delta_lon / 2;

        $a = sin(deg2rad($alpha)) * sin(deg2rad($alpha)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin(deg2rad($beta)) * sin(deg2rad($beta));
        $c = asin(min(1, sqrt($a)));
        $distance = 2 * $earth_radius * $c;
        $distance = round($distance, 4);

        return $distance;
    }

    public function getAdminConfig() {
        return $this->query("SELECT `batteryVoltage`  FROM `es_admin_setting` WHERE `status`=1 AND `isDeleted`=0");
    }

    public function sendAdminNotification($tracker = false, $type = 'DISCONNECTED') {

        if ($tracker === false) {
            return false;
        }

        if ($type === false) {
            return false;
        }

        //Check related scooter is reserved or not
        $this->query("SELECT `id`, `scooterId`, `reserveUserId`, `scooterNumber`, `tarckId`, "
                . " `scooterStatus`,  `isLockUnlock` FROM `es_scooter_parking` "
                . " WHERE tarckId={$tracker} AND `isUnderMaint`=0 AND `status`=1 AND `isDeleted`=0");

        if ($this->getRow()) {
            $scooterStatus = $this->getField("scooterStatus");
            $isLockUnlock = $this->getField("isLockUnlock");
            $scooterNumber = $this->getField("scooterNumber");
            $reservedBy = $this->getField("reserveUserId");
            $reservedScooter = $this->getField("scooterId");
            $reservedTracker = $this->getField("tarckId");

            $this->query("SELECT `mobile` FROM `es_user_master` WHERE `id`='{$reservedBy}' "
                    . " AND `isLogged`=1 AND `status`=1 AND `isDeleted`=0");
            if ($this->getRow()) {
                $mobile = $this->getField("mobile");
            } else {
                $mobile = false;
            }

            $message = "";

            switch (strtoupper($type)) {
                case 'LOWBATTERY':
                    $message = "Low Battery voltage of scooter {$scooterNumber}. \r\n";
                    $this->sendUserNotification($reservedTracker, $reservedBy, $scooterNumber);
                    break;

                case 'DISCONNECTED':
                    $message = "Disconnected Scooter {$scooterNumber}. \r\n";
                    $this->sendUserNotification($reservedTracker, $reservedBy, $scooterNumber);
                    break;

                case 'ALARM_ENABLED':
                    $message = "Alarm enabled for Scooter {$scooterNumber}. \r\n";
                    break;

                case 'ALARMING':
                    $message = "Alarming for Scooter {$scooterNumber}. \r\n";
                    break;


                case 'THIEF_ALARAM':
                    $message = "Thief alarm enabled for Scooter {$scooterNumber}. \r\n";
                    break;


                case 'TOW_ALARM':
                    $message = "Tow alarm enabled for Scooter {$scooterNumber}. \r\n";
                    break;

                default: break;
            }

            if ($scooterStatus) {
                $message .= " Scooter is reserved by $mobile";
            } else {
                $message .= " Scooter is no reserved";
            }
            if ($isLockUnlock) {
                $message .= " And User is riding.\r\n";
            } else {
                $message .= " And User has not started ride yet.\r\n";
            }


            $createdOn = date("Y-m-d H:i:s");

            return $this->query("INSERT INTO `es_scooter_notifications`(`reservedScooter`, `reservedBy`, `reservedTracker`, `message`, `createdOn`) "
                            . " SELECT  '{$reservedScooter}', '{$reservedBy}', '{$reservedTracker}', '{$message}', {$createdOn} "
                            . " WHERE (TIMESTAMPDIFF(MINUTE,(SELECT `createdOn` FROM `es_scooter_notifications` WHERE `reservedTracker`='{$reservedTracker}' ORDER BY id DESC LIMIT 1),NOW())>5"
                            . " OR TIMESTAMPDIFF(MINUTE,(SELECT `createdOn` FROM `es_scooter_notifications` WHERE `reservedTracker`='{$reservedTracker}' ORDER BY id DESC LIMIT 1),NOW()) IS NULL )");
        } else {
            return false;
        }
    }

    public function sendUserNotification($tracker = false, $userId = false, $scooterNumber = false) {

        if ($tracker === false || $userId === false) {
            return false;
        }
        $this->sendOSNotification($userId, 'stopride');
        
        /*
        $this->query("SELECT `id` FROM `es_scooter_reserve` WHERE `userId`='{$userId}' "
                . "  AND `scooterNumber`='{$scooterNumber}' AND `rideStatus`='" . RIDERUNNING . "'");

        if ($this->getRow()) {
            $reserveId = $this->getField("id");
            //Stop tracker
            $responce = $this->sendStopRide($tracker);

            if ($responce) {
                $string_responce = $responce['data'];

                $this->query("SELECT `scooterParkId`, `startDate`, `startTime`, `endDate`, `endTime`, `scooterNumber`"
                        . " FROM `es_scooter_reserve` WHERE `id`='{$reserveId}' AND `userId`='{$userId}' "
                        . " AND `isLockUnlock`='" . ISUNLOCK . "' ");

                if ($this->getRow()) {

                    $scooterParkId = $this->getField('scooterParkId');
                    $startDate = $this->getField('startDate');
                    $startTime = $this->getField('startTime');
                    $endDate = $this->getField('endDate');
                    $endTime = $this->getField('endTime');
                    $scooterNumber = $this->getField('scooterNumber');

                    $this->query("SELECT `trackLat`, `trackLng` FROM `es_scooter_track_location`"
                            . " WHERE `reserveId`='{$reserveId}' AND `isDeleted`='" . NOTDELETED . "' ORDER BY  createdDate DESC LIMIT 0,1");
                    if ($this->getRow()) {

                        $trackLat = $this->getField("trackLat");
                        $trackLng = $this->getField("trackLng");
                        $lastAddress = $this->getaddress($trackLat, $trackLng);
                        $updateDate = date('Y-m-d H:i:s');
                        //Update scooter parking
                        $this->query("UPDATE `es_scooter_parking` SET `reserveUserId`='0' , `location`='{$lastAddress}',"
                                . " `lat`='{$trackLat}', `lng`='{$trackLng}', `scooterStatus`='" . NOTRESERVE . "', "
                                . "`isLockUnlock`='" . ISLOCK . "',`updateDate`='{$updateDate}' "
                                . " WHERE `id`='$scooterParkId'");

                        
                        //Update scooter resevration
                        $this->query("UPDATE `es_scooter_reserve` SET `endLocation`= '{$lastAddress}', `endLat`= '{$trackLat}'"
                                . " , `endLng`='{$trackLng}', `endDate`='{$endDate}', `endTime`='{$endTime}'"
                                . " , `rideStatus`='" . RIDECOMPLETE . "', `isLockUnlock`='" . ISLOCK . "' , `updatedDate`='{$updateDate}'"
                                . " WHERE `id`='{$reserveId}' AND `userId`='{$userId}' "
                                . " AND `rideStatus`='" . RIDERUNNING . "' AND `isLockUnlock`='" . ISUNLOCK . "'");

                        ##### START: Add bill summeary data  #####
                        $date = new DateTime($startDate . ' ' . $startTime);
                        $date2 = new DateTime($endDate . ' ' . $endTime);
                        $num_seconds = $date2->getTimestamp() - $date->getTimestamp();
                        $second = $num_seconds % 60;
                        $runningMinutes = floor($num_seconds / 60);
                        if ($second != 0) {
                            $runningMinutes = $runningMinutes + 1;
                        }
                        //get distance
                        $distance = $this->getDistance($reserveId, $userId);
                        //Get admin setting data
                        $this->query("SELECT `scooterBaseFair`, `scooterPerMinChrages` "
                                . " FROM `es_admin_setting` WHERE `id`='1' AND `status`='" . ACTIVE . "' AND `isDeleted`='" . NOTDELETED . "' ");

                        if ($this->getRow()) {
                            //$basefair = $this->getField('scooterBaseFair');
                            $timeBill = $runningMinutes * $this->getField('scooterPerMinChrages');
                            $totalAmount = $timeBill;
                            //actual running minutes
                            if ($second != 0) {
                                $netRunningMinutes = $runningMinutes - 1;
                            }

                            //Check offers and calculate fair
                            $this->query("SELECT `offerId` FROM `es_user_promo_code_offers` "
                                    . " WHERE  `userId`='{$userId}' AND `reserveId`='{$reserveId}'"
                                    . " AND `status`='" . ACTIVE . "' AND `isDeleted`='" . NOTDELETED . "'");
                            if ($this->getRow()) {
                                $offerId = $this->getField('offerId');
                                $this->query("SELECT `offerTitle`, `offerPrice` FROM `es_offers`  WHERE `id`='{$offerId}'");
                                if ($this->getRow()) {
                                    $offerTitle = $this->getField('offerTitle');
                                    $offerPrice = $this->getField('offerPrice');
                                    $discountAmount = $offerPrice / 100 * $totalAmount;
                                    $totalBill = $totalAmount - $discountAmount;
                                } else {
                                    $offerTitle = '';
                                    $discountAmount = 0;
                                    $totalBill = $totalAmount;
                                }
                            } else {
                                $offerTitle = '';
                                $discountAmount = 0;
                                $totalBill = $totalAmount;
                            }


                            $createdDate = date("Y-m-d H:i:s");
                            $this->query("INSERT INTO `es_bill_summary`(`reserveId`, `userId`, `scooterNumber`, `runningTime`, `runningSecond`"
                                    . " , `runningDistance`,  `distanceBill`, `timeBill`, `offerRedeem`, `discountAmount`"
                                    . " , `totalBill`, `status`, `createdDate`) "
                                    . " VALUES ('{$reserveId}', '{$userId}', '{$scooterNumber}', '{$netRunningMinutes}', '{$second}'"
                                    . " ,'{$distance}',  0 , '{$timeBill}', '{$offerTitle}', '{$discountAmount}'"
                                    . " ,'{$totalBill}', '" . ACTIVE . "', '{$createdDate}')");

                            $billsummaryId = $this->getInsertedId();
                            $createdDate = date("Y-m-d H:i:s");
                            $val = date("Ymdhis");
                            $transctionId = "RIDT-{$val}";
                            $result = $this->query("INSERT INTO `es_user_topup_transactions`(`userId`, `billSummaryId`, `transctionId`, `price`"
                                    . " , `transactionsType`, `status`, `isDeleted`, `createdDate`) "
                                    . " VALUES ( '{$userId}', '{$billsummaryId}', '{$transctionId}', '{$totalBill}'"
                                    . " ,'" . RIDETRANSCTIONS . "', '" . ACTIVE . "', '" . NOTDELETED . "', '{$createdDate}')");

                            if ($result) {
                                $this->sendOSNotification($userId, 'stopride');
                            } else {
                                return false;
                            }
                            
                        } else {
                            return false;
                        }

                        ##### END :Add bill summeary data  #####
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }*/
    }

    public function sendStopRide($tracker = false, $cmd = 'POWER_OFF') {

        if ($tracker !== false) {

            $this->query("SELECT `command` FROM `es_socket_command` WHERE `code`='{$cmd}'");

            if ($this->getRow()) {
                $command = $this->getField("command");
                $createdOn = date("Y-m-d H:i:s");
                $this->query("INSERT INTO `es_sent_cmd`(`trackerId`, `cmd`, `cmd_auto`, `createdDate`) "
                        . " VALUES ('{$tracker}', '{$command}', '1' , '{$createdOn}')");

                sleep(TIMEOUTSEC);

                return $this->readReceivedAck($tracker, $command);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function readReceivedAck($device = false, $command = null) {

        $this->query("SELECT `ack` FROM  `es_push_command` WHERE `trackerId`='{$device}' AND "
                . " `command`='{$command}' ORDER BY `updateDate` DESC  LIMIT 0, 1");

        if ($this->getRow()) {
            $last_received_packet = $this->getField("ack");
            if ($last_received_packet != '') {
                $sentCommand = substr($command, 0, 5);
                $sentEvent = substr($command, 5, 2);

                $strResponce = str_replace(" ", "", $last_received_packet);

                if (strlen($strResponce) >= 6) {
                    $receivedPacket = substr($strResponce, 0, 5);
                    $receivedEvent = substr($strResponce, 5, 2);

                    $string_responce = explode(',', $strResponce);

                    if (empty($string_responce)) {
                        $string_responce = explode(',', "+#RPT00,20180109,A,180110055043,N2237.4279E11403.2702,000,000,65535.0,39.8,0241A044");
                    }

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


                    if (isset($string_responce[9]))
                        $return["optional_message"] = $string_responce[9];

                    switch (strtoupper($receivedPacket)) {
                        case "+#ACT":
                        case "+#ASC":
                            if ($receivedEvent == $sentEvent) {
                                return array("status" => 200, "data" => $return);
                            } else {
                                return false;
                            }
                        default:
                            return false;
                            break;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getaddress($lat = false, $lng = false) {
        $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . trim($lat) . ',' . trim($lng) . '&key=' . GOOGLE_LOC_API_KEY;
        $json = @file_get_contents($url);
        $data = json_decode($json);
        $status = $data->status;
        if ($status == "OK") {
            return $data->results[0]->formatted_address;
        } else {
            return false;
        }
    }

    public function getDistance($reserveId = false, $userId = false) {
        if ($reserveId === false) {
            return false;
        }

        if ($userId === false) {
            return false;
        }

        $total_dis = 0.00;

        $this->query("SELECT `id`, `reserveId`, `userId`, `trackLocation`, `trackLat`, `trackLng`, `distance`, `isDeleted`, `createdDate` FROM `es_scooter_track_location` "
                . "WHERE `reserveId`='{$reserveId}' AND `userId`='$userId' AND `isDeleted`='" . NOTDELETED . "'");

        $results = $this->multy_result();
        if ($results) {
            $data = $this->query("SELECT `trackLat`, `trackLng` "
                    . " FROM `es_scooter_track_location` "
                    . " WHERE reserveId='{$reserveId}' and userId='{$userId}' ORDER BY id ASC LIMIT 1");

            if ($this->getRow()) {
                $lat2 = $this->getField("trackLat");
                $lon2 = $this->getField("trackLng");

                foreach ($results as $key => $result) {
                    $lat1 = $result['trackLat'];
                    $lon1 = $result['trackLng'];

                    $haver_dis = $this->distance_haversine($lat1, $lon1, $lat2, $lon2);
                    $total_dis = $total_dis + $haver_dis;
                }
                return $total_dis;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function sendOSNotification($user = false, $action = false) {
        try {
            if ($user !== false && $action !== false) {


                switch (strtolower($action)) {


                    case 'stopride':
                        $message = array(
                            "notification_type" => "stop_ride",
                            "notification_title" => "Battery Voltage Is Low. Please  Stop Ride!",
                            "notification_description" => "Battery Voltage Is Low. Please  Stop Ride!",
                            "notification_date" => date('d-m-Y'),
                        );

                        break;

                    default:
                        $message = array(
                            "notification_type" => "",
                            "notification_title" => "",
                            "notification_description" => "",
                            "notification_date" => date('d-m-Y'),
                        );
                        break;
                }

                $this->query("SELECT `id`, `deviceType`, `deviceId`, `tokenId` FROM `es_user_master` "
                        . " WHERE `id`='{$user}' AND `verified`='" . VERIFIED . "' AND "
                        . " `status`='" . ACTIVE . "' AND `isDeleted`='" . NOTDELETED . "'");
                if ($this->getRow()) {
                    $tokenId = $this->getField('tokenId');
                    $deviceType = $this->getField('deviceType');
                    $id = $this->getField('id');
                    $result = array(
                        "tokenId" => $tokenId,
                        "deviceType" => $deviceType,
                        "id" => $id,
                    );
                    if (!empty($tokenId)) {
                        return $this->sendNotificationToDevice($result, $message);
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    public function sendNotificationToDevice($result = array(), $message = array()) {

        if (is_array($result) && !empty($result)) {

            $message = array_merge($message, array("notification_user" => $result['id']));

            if (strtoupper($result['deviceType']) == "I") {
                return $this->send_push_notification_fcm_ios($result['tokenId'], $message);
            } else if (strtoupper($result['deviceType']) == "A") {
                return $this->send_push_notification_fcm($result['tokenId'], $message);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function send_push_notification_fcm_ios($registration_id, $message = array()) {
        $url = "https://fcm.googleapis.com/fcm/send";
        $serverKey = NOTIFICATION_AUTH;

        if (isset($message['notification_title'])) {
            $title = $message['notification_title'];
        } else {
            $title = false;
        }

        $body = $message; //"Body of the message";
        $notification = array('title' => $title, 'text' => '', 'sound' => 'default', 'badge' => '1', 'notidata' => $body);
        $arrayToSend = array('to' => $registration_id, 'notification' => $notification, 'priority' => 'high');
        $json = json_encode($arrayToSend);
        $headers = array(
            'Content-Type: application/json',
            'Authorization: key=' . $serverKey,
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        //Send the request
        $response = curl_exec($ch);
        //Close request
        if ($response === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
        } else {
            curl_close($ch);
            $data = json_encode($arrayToSend);
            $createdDate = date("Y-m-d H:i:s");
            return $this->query("INSERT INTO `es_notifications`(`data`, `status`, `createdDate`) "
                            . " VALUES ('{$data}', '{$response}', '{$createdDate}')");
        }
    }

    function send_push_notification_fcm($registration_id, $message = array()) {

        $url = "https://fcm.googleapis.com/fcm/send";
        $serverKey = NOTIFICATION_AUTH;
        $arrayToSend = array('to' => $registration_id, 'data' => $message);
        $json = json_encode($arrayToSend);
        $headers = array(
            'Content-Type: application/json',
            'Authorization: key=' . $serverKey,
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //Send the request
        $response = curl_exec($ch);

        //Close request
        if ($response === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
        } else {

            curl_close($ch);
            $data = json_encode($arrayToSend);
            $createdDate = date("Y-m-d H:i:s");
            return $this->query("INSERT INTO `es_notifications`(`data`, `status`, `createdDate`) "
                            . " VALUES ('{$data}', '{$response}', '{$createdDate}')");
        }
    }

}

?>