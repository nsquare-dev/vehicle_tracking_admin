<?php

class Common_model extends CI_Model {

    public function __construct() {
        $this->load->database();
        $this->load->helper('url');
    }

    public function dofileUpload($fileName, $uploadPath) {
        try {
            $new_name = time() . mt_rand() . '.png';
            // $new_name = time().$_FILES[$fileName]['name'];
            $config['upload_path'] = $uploadPath;
            $config['allowed_types'] = 'gif|jpg|jpeg|png';
            // $config['max_size'] = '10000';
            //$config['max_width'] = '1024';
            //$config['max_height'] = '768';
            $config['file_name'] = $new_name;
            //$this->load->library('upload', $config);
            $this->upload->initialize($config, true);
            if (isset($_FILES[$fileName])) {

                if (!$this->upload->do_upload($fileName)) {
                    return array("status" => 400, "message" => $this->upload->display_errors());
                } else {

                    /* if(strtolower($this->router->fetch_method())=='addproduct'){
                      $config_img['image_library'] = 'gd2';
                      $config_img['source_image'] = $uploadPath.$new_name;
                      $config_img['create_thumb'] = FALSE;
                      $config_img['maintain_ratio'] = TRUE;
                      //$config_img['rotation_angle'] = 90;//counter-clockwise angle of rotation
                      $config_img['width']         = 750; //200px
                      $config_img['height']       = 200;//750px
                      $config_img['quality']     = 100;
                      $this->image_lib->initialize($config_img);

                      if ( ! $this->image_lib->resize())
                      {
                      return array("status"=> 400, "message"=> $this->image_lib->display_errors());
                      }
                      $this->image_lib->clear();
                      } */
                    return array("status" => 200, "data" => $this->upload->data('file_name'));
                }
            } else {
                return array("status" => 400, "message" => 'Not upload file. Please try again');
            }
        } catch (Exception $e) {
            return array("status" => 400, "message" => $e->getMessage());
        }
    }

    public function isChkHeadersInfo($basic_auth_username, $basic_auth_password) {
        try {
            if ($basic_auth_username !== false && $basic_auth_password !== false) {
                if ($basic_auth_username == BASICAUTHUSERNAME && $basic_auth_password == BASICAUTHPASSWORD) {
                    return array("status" => 200, "message" => "Authentication successfully!");
                } else {
                    return array("status" => 400, "message" => "Invalid credentials authentication header supplied!");
                }
            } else {
                return array("status" => 400, "message" => "No authentication header supplied!");
            }
        } catch (Exception $e) {
            return array("status" => 400, "message" => $e->getMessage());
        }
    }

    function sendUserOTP($mobile, $userId) {

        try {
            $otp = mt_rand(1000, 9999);

            if ($otp === false) {
                return array("status" => 400, "message" => "OTP not generated.");
            } else if ($mobile === false) {
                return array("status" => 400, "message" => "Mobile number not provided.");
            } else if ($userId === false) {
                return array("status" => 400, "message" => "Invalid user.");
            } else {

                $data = array(
                    "userId" => $userId,
                    "otp" => $otp,
                    "verified" => NOTVERIFIED,
                    "createdDate" => date("Y-m-d H:i:s")
                );
                $this->db->insert('user_otp', $data);

                $msg = "Your eScooter verification code is $otp";

                $msgUrlEncoded = urlencode($msg);

                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "http://control.msg91.com/api/sendotp.php?authkey=" . OTP_AUTH . "&message={$msgUrlEncoded}&sender=" . OTP_SENDER . "&mobile=" . OTP_COUNRTY . "{$mobile}&otp={$otp}&otp_expiry=" . OTP_EXPIRY,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => "",
                    CURLOPT_SSL_VERIFYHOST => 0,
                    CURLOPT_SSL_VERIFYPEER => 0,
                ));

                $response = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                if ($err) {
                    //echo "cURL Error #:" . $err;
                    return array("status" => 200, "message" => "cURL Error #: {$err}");
                } else {
                    //echo $response;
                    $res_array = json_decode($response);

                    if (strtolower($res_array->type) == OTP_SUCCESS) {
                        return array("status" => 200, "message" => "OTP sent successfully.");
                    } else {
                        return array("status" => 400, "message" => "Invalid OTP");
                    }
                }
            }
        } catch (Exception $e) {
            return array("status" => 400, "message" => $e->getMessage());
        }
    }

    public function resendUserOTP($mobile, $userId) {
        try {

            if ($mobile === false) {
                return array("status" => 400, "message" => "Mobile number not provided.");
            } else if ($userId === false) {
                return array("status" => 400, "message" => "Invalid user.");
            } else {
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => "http://control.msg91.com/api/retryotp.php?authkey=" . OTP_AUTH . "&mobile=" . OTP_COUNRTY . "{$mobile}&retrytype=" . OTP_RETRYTYPE,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => "",
                    CURLOPT_SSL_VERIFYHOST => 0,
                    CURLOPT_SSL_VERIFYPEER => 0,
                    CURLOPT_HTTPHEADER => array(
                        "content-type: application/x-www-form-urlencoded"
                    ),
                ));

                $response = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                if ($err) {
                    //echo "cURL Error #:" . $err;
                    return array("status" => 200, "message" => "cURL Error #: {$err}");
                } else {
                    $res_array = json_decode($response);

                    if (strtolower($res_array->type) == OTP_SUCCESS) {
                        return array("status" => 200, "message" => "OTP resent successfully.");
                    } else {
                        return array("status" => 400, "message" => "Invalid OTP");
                    }
                }
            }
        } catch (Exception $e) {
            return array("status" => 400, "message" => $e->getMessage());
        }
    }

    public function verifyOTP($mobile, $otp) {
        try {
            if ($mobile === false) {
                return array("status" => 400, "message" => "Mobile number not provided.");
            } else if ($otp === false) {
                return array("status" => 400, "message" => "Invalid otp.");
            } else {
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://control.msg91.com/api/verifyRequestOTP.php?authkey=" . OTP_AUTH . "&mobile=" . OTP_COUNRTY . "{$mobile}&otp={$otp}",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => "",
                    CURLOPT_SSL_VERIFYHOST => 0,
                    CURLOPT_SSL_VERIFYPEER => 0,
                    CURLOPT_HTTPHEADER => array(
                        "content-type: application/x-www-form-urlencoded"
                    ),
                ));

                $response = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                if ($err) {
                    //echo "cURL Error #:" . $err;
                    return array("status" => 400, "message" => "cURL Error #: {$err}");
                } else {
                    //echo $response;
                    $res_array = json_decode($response);

                    if (strtolower($res_array->type) == OTP_SUCCESS) {
                        return array("status" => 200, "message" => "OTP verified successfully.");
                    } else {
                        return array("status" => 400, "message" => "Invalid OTP");
                    }
                }
            }
        } catch (Exception $exc) {
            return array("status" => 400, "message" => $exc->getTraceAsString());
        }
    }

    public function randomString() {
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $string = '';
        $length = 8; //default random string length

        $characters_length = strlen($characters) - 1;

        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, $characters_length)];
        }

        $this->db->select('id');
        $this->db->from($this->db->dbprefix('user_master'));
        $this->db->where("refferralCode", $string);
        $row = $this->db->get()->row();

        if (isset($row)) {
            $this->randomString();
        } else {

            return $string;
        }
    }

    public function isUnblockAndNotDeletedUser($userid = false) {
        try {
            if ($userid !== false) {
                $where = array("id" => $userid);
                $this->db->select("id, isDeleted, status");
                $this->db->from($this->db->dbprefix('user_master'));
                $this->db->where($where);
                $res = $this->db->get()->row_array();

                if (!empty($res) && is_array($res)) {
                    if (intval($res['isDeleted']) == DELETED) {
                        return array("status" => 422, "message" => "User is removed from system. Please try later!");
                    } else if (intval($res['status']) == NOTACTIVE) {
                        return array("status" => 422, "message" => "User is blocked. Please try later!");
                    } else {
                        return array("status" => 200);
                    }
                } else {
                    return array("status" => 422, "message" => "Invalid user. Please try later!");
                }
            } else {
                return array("status" => 422, "message" => "Invalid post. Please try later!");
            }
        } catch (Exception $e) {
            return array("status" => 400, "message" => $e->getMessage());
        }
    }

    public function chkUserDeviceId($deviceId, $userId) {
        try {
            if ($deviceId !== false && $userId !== false) {
                $where = array("id" => $userId);
                $this->db->select("id, deviceId, verified");
                $this->db->from($this->db->dbprefix('user_master'));
                $this->db->where($where);
                $res = $this->db->get()->row_array();

                if (!empty($res) && is_array($res)) {

                    if (intval($res['verified']) == NOTVERIFIED) {

                        $this->db->update($this->db->dbprefix('user_master'), array("tokenId" => ''), array('id' => $userId));
                        return array("status" => 200);
                    } else if ($res['deviceId'] == $deviceId) {
                        return array("status" => 200);
                    } else {
                        return array("status" => 422, "message" => "You've logged in to another device.");
                    }
                } else {
                    return array("status" => 422, "message" => "Invalid user. Please try later!");
                }
            } else {
                return array("status" => 422, "message" => "Invalid post. Please try later!");
            }
        } catch (Exception $e) {
            return array("status" => 400, "message" => $e->getMessage());
        }
    }

    public function getDistance($reserveId, $userId) {

        try {
            if ($reserveId === false) {
                return array("status" => 400, "message" => "Please provided reserve id.");
            } else if ($userId === false) {
                return array("status" => 400, "message" => "Invalid user.");
            } else {
                $total_dis = 0.00;
                $where = array(
                    "reserveId" => $reserveId,
                    "userId" => $userId,
                    "isDeleted " => NOTDELETED,
                );
                $data = $this->db->get_where('scooter_track_location', $where)->result_array();
                if (is_array($data)) {

                    $data2 = $this->db->select("trackLat, trackLng")
                                    ->from($this->db->dbprefix('scooter_track_location'))
                                    ->where("reserveId", $reserveId)
                                    ->where("userId", $userId)
                                    ->order_by("id", "ASC")
                                    ->get()->row_array();

                    foreach ($data as $key => $data) {
                        $lat1 = $data['trackLat'];
                        $lon1 = $data['trackLng'];
                        $lat2 = $data2['trackLat'];
                        $lon2 = $data2['trackLng'];
                        $haver_dis = $this->distance_haversine($lat1, $lon1, $lat2, $lon2);
                        $total_dis = $total_dis + $haver_dis;
                    }
                    return $total_dis;
                } else {
                    return array("status" => 400, "message" => "not found any tracking location.");
                }
            }
        } catch (Exception $e) {
            return array("status" => 400, "message" => $e->getMessage());
        }
    }

//    public function convertMinutesToHrs($totalMinutes,$second) {
//        try {
//            //convert minutes to hrs
//            $time = $totalMinutes;
//            $hours = floor($time / 60);
//            $minutes = $time % 60;
//            if ($hours == 0) {
//                return $runningTime = $minutes . ' min '.$second .' sec';
//            } else {
//                return $runningTime = $hours . ' h ' . $minutes . ' min '.$second .' sec';
//            }
//        } catch (Exception $e) {
//            return array("status" => 400, "message" => $e->getMessage());
//        }
//    }
    public function convertMinutesToHrs($totalMinutes, $second) {
        try {
            $time2 = $this->secondsToTime($second);
            //convert minutes to hrs
            $time = $totalMinutes;
            $hours = floor($time / 60);
            $hours = $hours + $time2['h'];
            $minutes = $time % 60;
            $minutes = $minutes + $time2['m'];
            $second2 = $time2['s'];
            if ($hours < 9) {
                $hours = '0' . $hours;
            }
            if ($minutes < 9) {
                $minutes = '0' . $minutes;
            }
            if ($second2 < 9) {
                $second2 = '0' . $second2;
            }
            if ($hours == 0) {
                return $runningTime = '00 : ' . $minutes . ' : ' . $second2 . ' Hrs';
            } else {
                return $runningTime = $hours . ' : ' . $minutes . ' : ' . $second2 . ' Hrs';
            }
        } catch (Exception $e) {
            return array("status" => 400, "message" => $e->getMessage());
        }
    }

    function secondsToTime($seconds) {

        // extract hours
        $hours = floor($seconds / (60 * 60));

        // extract minutes
        $divisor_for_minutes = $seconds % (60 * 60);
        $minutes = floor($divisor_for_minutes / 60);

        // extract the remaining seconds
        $divisor_for_seconds = $divisor_for_minutes % 60;
        $seconds = ceil($divisor_for_seconds);

        // return the final array
        $obj = array(
            "h" => $hours,
            "m" => $minutes,
            "s" => $seconds,
        );

        return $obj;
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
        $distance = round($distance, 2);

        return $distance;
    }

    public function getMinutes($startDate, $startTime, $endDate, $endTime) {
        $startdatetime = strtotime($startDate . ' ' . $startTime);
        $enddatetime = strtotime($endDate . ' ' . $endTime);
        $interval = abs($enddatetime - $startdatetime);
        $minutes = round($interval / 60);
        $d = floor($minutes / 1440);
        $h = floor(($minutes - $d * 1440) / 60);
        $min = $minutes - ($d * 1440) - ($h * 60);
        if ($d != 0) {
            return $runningMinutes = "{$d} Days, {$h} Hrs, {$min} Min";
        } else {
            if ($h != 0) {
                return $runningMinutes = "{$h} Hrs, {$min} Min";
            } else {
                return $runningMinutes = "{$min} Min";
            }
        }
    }

    public function getTimer($startDate, $startTime, $endDate, $endTime) {
        $startdatetime = strtotime($startDate . ' ' . $startTime);
        $enddatetime = strtotime($endDate . ' ' . $endTime);
        $seconds = $enddatetime - $startdatetime;
        $milesecond = $seconds * 1000;
        return $runningMinutes = $milesecond;
    }

    /*
     * checking function
     */

    public function chkUser($userId) {
        return $this->db->get_where('user_master', array("id" => $userId, "isDeleted" => NOTDELETED))->row_array();
    }

    public function chkScooterNumber($scooterNumber) {
        return $this->db->get_where('scooter_parking', array("scooterNumber" => $scooterNumber, "isDeleted" => NOTDELETED))->row_array();
    }

    public function chkmobile2($mobile) {
        return $this->db->get_where('user_master', array("mobile" => $mobile, "isDeleted" => NOTDELETED))->row_array();
    }

    public function chkemail($email) {
        return $this->db->get_where('user_master', array("email" => $email, "isDeleted" => NOTDELETED))->row_array();
    }

    public function chkemaileditprofile($email, $userId) {
        return $this->db->query("SELECT * FROM `es_user_master` WHERE id!='" . $userId . "' and email='" . $email . "' and isDeleted='" . NOTDELETED . "'")->row_array();
        // return $this->db->get_where('user_master', array("email" => $email, "isDeleted" => NOTDELETED))->row_array();
    }

    public function chkmobileeditprofile($mobile, $userId) {
        return $this->db->query("SELECT * FROM `es_user_master` WHERE id!='" . $userId . "' and mobile='" . $mobile . "' and isDeleted='" . NOTDELETED . "'")->row_array();
    }

    public function getScooterCancelTime() {
        return $this->db->get_where('scooter_cancel_time', array("id" => 1, "status" => ACTIVE, "isDeleted" => NOTDELETED))->row_array();
    }

    public function getAdminSetting() {
        return $this->db->get_where('admin_setting', array("id" => 1, "status" => ACTIVE, "isDeleted" => NOTDELETED))->row_array();
    }

    public function chkDeviceId($userId) {
        return $this->db->query("SELECT * FROM `es_user_device` WHERE userId='" . $userId . "' and isDeleted='" . NOTDELETED . "'")->row_array();
    }

    public function chkRideStatus($userId) {
        return $this->db->get_where('scooter_reserve', array("userId" => $userId, "rideStatus" => RIDERUNNING))->row_array();
        //return false;
    }

    public function chkBalance($userId) {
        $amountArray = $this->getUserCurrentBalance($userId);
        if ($amountArray['status'] == 200) {
            $depositAmount = $amountArray['depositAmount'];
            if ($depositAmount < $amountArray['currentBalance'] && $depositAmount > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    function getUserCurrentBalance($userId) {
        if (empty($userId)) {
            return array("status" => 400, "message" => "Please post user id.");
        }
        ##Topup amount##
        $topupAmount = $this->db->query("SELECT SUM(price) as totalPrices,SUM(bonus) as totalBonus FROM `es_user_topup_transactions` WHERE userId='" . $userId . "' and transactionsType='" . TOPUPTRANSCTIONS . "' and status='" . ACTIVE . "' and isDeleted='" . NOTDELETED . "'");
        $topupAmount = $topupAmount->row_array();
        if ($topupAmount) {
            $totalTopupAmount = $topupAmount['totalPrices'] + $topupAmount['totalBonus'];
        } else {
            $totalTopupAmount = 0;
        }
        ##Refferal amount##
        $RefferalAmount = $this->db->query("SELECT SUM(price) as totalRPrices FROM `es_user_topup_transactions` WHERE userId='" . $userId . "' and transactionsType='" . REFFERALTRANSCTIONS . "' and status='" . ACTIVE . "' and isDeleted='" . NOTDELETED . "'");
        $RefferalAmount = $RefferalAmount->row_array();
        if ($RefferalAmount) {
            $totalRefferalAmount = $RefferalAmount['totalRPrices'];
        } else {
            $totalRefferalAmount = 0;
        }
        ##ride amount##
        $rideAmount = $this->db->query("SELECT SUM(price) as totalPrice FROM `es_user_topup_transactions` WHERE userId='" . $userId . "' and transactionsType='" . RIDETRANSCTIONS . "' and status='" . ACTIVE . "' and isDeleted='" . NOTDELETED . "'");
        $rideAmount = $rideAmount->row_array();
        if ($rideAmount) {
            $TotalRideAmount = $rideAmount['totalPrice'];
        } else {
            $TotalRideAmount = 0;
        }

        $adminSettings = $this->getAdminSetting();

        if (isset($adminSettings)) {


            $depositAmount = $adminSettings['depositAmount'];
            if (($totalTopupAmount + $totalRefferalAmount) <= $depositAmount) {
                $currentBalance = "0";
                $depositAmount = $totalTopupAmount + $totalRefferalAmount;
            } else {
                $currentBalance = round(($totalTopupAmount + $totalRefferalAmount) - ($TotalRideAmount + $depositAmount), 2);
            }


            return array("status" => 200, "currentBalance" => "$currentBalance", "depositAmount" => "$depositAmount");
        } else {
            //return array("status" => 400, "currentBalance" => $currentBalance2, "depositAmount" => 0);
            return array("status" => 400, "message" => "Not found deposit amount !");
        }
    }

    /*
     *  notification Function
     */

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
            $insertArray = array(
                "data" => json_encode($arrayToSend),
                "status" => $response,
                "createdDate" => date("Y-m-d H:i:s"),
            );
            return $this->db->insert($this->db->dbprefix('notifications'), $insertArray);
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
            $insertArray = array(
                "data" => json_encode($arrayToSend),
                "status" => $response,
                "createdDate" => date("Y-m-d H:i:s"),
            );
            return $this->db->insert($this->db->dbprefix('notifications'), $insertArray);
        }
    }

    function temp($registration_ids, $message = array()) {
        $url = "https://fcm.googleapis.com/fcm/send";
        //$token = "fEm8wHyj0ok:APA91bFke5gzGepaJOydVNnRDcnZ7ssryqwsV4rGZ-hcnGXDggpaiaZBtTdADwljm54rrfRBR7ONEBjWlz0cVFdLLr2MKVZnsl0V4G-7BXOkJmmZ8-O4Cx3ZQgT_LbhrwAbQaUqbVngl";
        $token = $registration_ids;
        $serverKey = 'AIzaSyAz6oXgIhk0E3NKNdYFuZb7g75DjXKJHYk';
        //  $notification = array('text' => $body, 'sound' => 'default', 'badge' => '1');
//        $arrayToSend = array('to' => $token, 'notification' => $message);
//        $json = json_encode($arrayToSend);
//        $headers = array();
//        $headers[] = 'Content-Type: application/json';
//        $headers[] = 'Authorization: key=' . $serverKey;
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
//        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
//   //Send the request
//        $result = shell_exec($ch);
//        print_r($result);die;
        shell_exec('curl -X POST --header "Authorization: key=AIzaSyAz6oXgIhk0E3NKNdYFuZb7g75DjXKJHYk" --header "Content-Type: application/json" https://fcm.googleapis.com/fcm/send -d "{\"to\":\"' . $token . '\",\"priority\":\"high\",\"notification\":{\"body\": \"' . $message . '\"}}"');
        $output_including_status = shell_exec("command 2>&1; echo $?");
        print_r($output_including_status);
        die;
        //Close request 
        if ($result === FALSE) {
            // die('FCM Send Error: ' . curl_error($ch));
        } else {

            curl_close($ch);
            $insertArray = array(
                "data" => json_encode($arrayToSend),
                "status" => $result,
                "createdDate" => date("Y-m-d H:i:s"),
            );
            $this->db->insert($this->db->dbprefix('notifications'), $insertArray);
            return true;
        }
    }

    /*
     * function: send notifications to user
     * @param $user, $action
     * @return boolean
     */

    public function sendNotification($user = false, $action = false) {
        try {
            if ($user !== false && $action !== false) {


                switch (strtolower($action)) {

                    case 'reservescooter':
                        $message = array(
                            "notification_type" => "reserve_scooter",
                            "notification_title" => "New Scooter reservation",
                            "notification_description" => "Scooter reserve successfully!",
                            "notification_date" => date('d-m-Y'),
                        );

                        break;

                    case 'stopride':
                        $message = array(
                            "notification_type" => "stop_ride",
                            "notification_title" => "Your ride stoped successfully",
                            "notification_description" => "Your ride stopped successfully!",
                            "notification_date" => date('d-m-Y'),
                        );

                        break;

                    case 'add_topup':
                        $message = array(
                            "notification_type" => "add_topup",
                            "notification_title" => "Your topup successfully!",
                            "notification_description" => "Your topup successfully!",
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


                $where = array(
                    "verified" => VERIFIED,
                    "status" => ACTIVE,
                    "isDeleted" => NOTDELETED,
                    "id=" => $user
                );
                $result = $this->db->get_where('user_master', $where)->row_array();

                if (!empty($result['tokenId'])) {
                    return $this->sendNotificationToDevice($result, $message);
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

    public function write_command($device = false, $cmd = false) {

        if ($cmd === false) {
            return array("status" => 400, "message" => "Please check command!");
        } else {

            $row = $this->db->select("command")
                            ->from($this->db->dbprefix('socket_command'))
                            ->where("code", $cmd)
                            ->get()->row();

            if ($row) {
                /* if($cmd == "KEEP_ALIVE"){
                  $row->command = "{$row->command}$device";
                  } */

                $insertArray = array(
                    "trackerId" => $device,
                    "cmd" => $row->command,
                    "createdDate" => date("Y-m-d H:i:s"),
                );

                $this->db->insert($this->db->dbprefix('sent_cmd'), $insertArray);
                sleep(TIMEOUTSEC);
                $responce = $this->command_ack($device, $row->command);

                return $responce;
            } else {
                return array("status" => 400, "message" => "Please check command!");
            }
        }
    }

    public function command_ack($device = false, $command = null) {
        if ($command !== null) {
            $now = date("Y-m-d H:i:s");
            $row = $this->db->select("ack")
                            ->from($this->db->dbprefix('push_command'))
                            ->where("trackerId", $device)
                            ->where("command", $command)
                            ->where("TIME_TO_SEC(TIMEDIFF(received_date_time, '{$now}'))<=10", null, false)
                            ->where("isReceived", 1)
                            ->order_by('updateDate', 'DESC')
                            ->limit(1)->get()->row_array();

            if ($row) {

                $last_received_packet = $row['ack'];
                $sentCommand = substr($command, 0, 5);
                $sentEvent = substr($command, 5, 2);

                $strResponce = str_replace(" ", "", $last_received_packet);

                if (strlen($strResponce) >= 6) {
                    $receivedPacket = substr($strResponce, 0, 5);
                    $receivedEvent = substr($strResponce, 5, 2);

                    $string_responce = explode(',', $strResponce);

                    if (empty($string_responce)) {
                        return array("status" => 400);
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
                                return array("status" => 400);
                            }
                        default:
                            return array("status" => 400);
                            break;
                    }
                }
            } else {
                return array("status" => 400, "message" => "Failed to establish connection with Scooter!");
            }
        } else {
            return array("status" => 400);
        }
    }

    public function getScooterData($scooterNumber = false) {

        $row = $this->db->select("ack")
                        ->from($this->db->dbprefix('push_command'))
                        ->where("trackerId", $scooterNumber)
                        ->like('ack', 'RPT')
                        ->order_by('updateDate', 'DESC')
                        ->limit(1)->get()->row_array();

        if ($row) {

            $string_responce = explode(',', $row['ack']);

            if (count($string_responce) >= 8) {
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

                return $return;
            }else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function checkIsTrackerConnected($scooterNumber = false) {
        $now = date("Y-m-d H:i:s");
        $row = $this->db->select("ack")
                        ->from($this->db->dbprefix('push_command'))
                        ->where("trackerId", $scooterNumber)
                        ->like('ack', 'KPT')
                        ->where("TIME_TO_SEC(TIMEDIFF(received_date_time,'{$now}'))<=10", null, false)
                        ->order_by('updateDate', 'DESC')
                        ->limit(1)->get()->row();

        if ($row) {
            return true;
        } else {
            return false;
        }
    }

    public function getTrackingIdbyScooterNumber($scooterNumber = false) {

        if ($scooterNumber === false) {
            return false;
        }

        $where = array("scooterNumber" => $scooterNumber, "status" => ACTIVE, "isDeleted" => NOTDELETED);
        return $this->db->get_where('scooter_parking', $where)->row();
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

    public function getConfiguredVoltage() {
        $row = $this->db->select('batteryVoltage')->from($this->db->dbprefix('admin_setting'))->where('status', '1')->get()->row();
        return round($row->batteryVoltage);
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

    function getDistanceBetweenCoOrdinates($latitude1, $longitude1, $latitude2, $longitude2) {
        $earth_radius = 6371 * 1000;

        $dLat = deg2rad($latitude2 - $latitude1);
        $dLon = deg2rad($longitude2 - $longitude1);

        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * asin(sqrt($a));
        $d = $earth_radius * $c;

        return round($d);
    }

    function checkIsEnteredInRestrictedArea($reserveId = false) {

        $row = $this->db->select("trackLat, trackLng")
                        ->from($this->db->dbprefix('scooter_track_location'))
                        ->where("reserveId", $reserveId)
                        ->where("isDeleted", NOTDELETED)
                        ->order_by('createdDate', 'DESC')
                        ->get()->row();

        $rows = $this->db->select("lat, lng")
                        ->from($this->db->dbprefix('scooter_restricted_area'))
                        ->where("status", ACTIVE)
                        ->where("isDeleted", NOTDELETED)
                        ->get()->result();

        if ($rows) {
            foreach ($rows as $k => $v) {
                $distance = $this->getDistanceBetweenCoOrdinates($row->trackLat, $row->trackLng, $v->lat, $v->lng);

                if ($distance <= RESTRICTED_AREA_RADIUS) {
                    return true;
                }
            }
        }

        return false;
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

}

?>