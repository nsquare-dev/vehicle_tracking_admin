<?php

class Common_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function dofileUpload($fileName, $uploadPath) {
        try {
            $type = substr($_FILES[$fileName]['name'], strrpos($_FILES[$fileName]['name'], '.') + 1);
            $new_name = time() . mt_rand(4, 6) . ".{$type}";
            $config['upload_path'] = $uploadPath;
            $config['allowed_types'] = 'jpg|jpeg|gif|png';
            $config['max_size'] = '10000';
            //$config['max_width'] = '1024';
            //$config['max_height'] = '768';
            $config['file_name'] = $new_name;
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
                    return array("status" => 200, "file_name" => $new_name);
                }
            } else {
                return array("status" => 400, "message" => 'Not upload file. Please try again');
            }
        } catch (Exception $e) {
            return array("status" => 400, "message" => $e->getMessage());
        }
    }

    function getCounter() {

        $totalUser = $this->db->query("SELECT count(id) as totalUser FROM `es_user_master` where isDeleted='" . NOTDELETED . "'")->row_array();
        $activeUser = $this->db->query("SELECT count(id) as activeUser FROM `es_user_master` where status='" . ACTIVE . "' and isDeleted='" . NOTDELETED . "'")->row_array();
        $notActiveUser = $this->db->query("SELECT count(id) as notActiveUser FROM `es_user_master` where status='" . NOTACTIVE . "' and isDeleted='" . NOTDELETED . "'")->row_array();
        $activeScooter = $this->db->query("SELECT count(id) as activeScooter FROM `es_scooter_parking` where status='" . ACTIVE . "' and isDeleted='" . NOTDELETED . "'")->row_array();
        $idelScooter = $this->db->query("SELECT count(id) as idelScooter FROM `es_scooter_parking` where status='" . NOTACTIVE . "' and isDeleted='" . NOTDELETED . "'")->row_array();
        $maintScooter = $this->db->query("SELECT count(id) as maintScooter FROM `es_scooter_parking` where isUnderMaint='" . ACTIVE . "' and isDeleted='" . NOTDELETED . "'")->row_array();
        return $data = array("totalUser" => $totalUser['totalUser'], "activeUser" => $activeUser['activeUser'], "notActiveUser" => $notActiveUser['notActiveUser'], "activeScooter" => $activeScooter['activeScooter'], "idelScooter" => $idelScooter['idelScooter'], "maintScooter" => $maintScooter['maintScooter']);
    }

    function getLatLng($address) {
        if (!empty($address)) {
            //Formatted address
            $formattedAddr = str_replace(' ', '+', $address);
            //Send request and receive json data by address
            $geocodeFromAddr = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=' . $formattedAddr . '&sensor=false&key=' . GOOGLE_LOC_API_KEY);
            $output = json_decode($geocodeFromAddr);
            //Get latitude and longitute from json data
            $data['lat'] = $output->results[0]->geometry->location->lat;
            $data['lng'] = $output->results[0]->geometry->location->lng;
            //Return latitude and longitude of the given address
            if (!empty($data)) {
                return $data;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

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
            if ($hours == 0) {
                return $runningTime = $minutes . ' min ' . $second2 . ' sec';
            } else {
                return $runningTime = $hours . ' h ' . $minutes . ' min ' . $second2 . ' sec';
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

    function getScooterLocation($scooterNumber) {
        return $this->db->query("SELECT * FROM `es_scooter_parking` where scooterNumber='" . $scooterNumber . "'")->row_array();
    }

    function getCategory($categoryId) {
        return $this->db->get_where('maintenance_task_category', array("id" => $categoryId))->row_array();
    }

    public function chkScooterNumber($scooterNumber) {
        return $this->db->get_where('scooter_parking', array("scooterNumber" => $scooterNumber, "isDeleted" => NOTDELETED))->row_array();
    }

    public function chkTrackerId($trackerId) {
        return $this->db->get_where('scooter_parking', array("scooterNumber" => $trackerId, "isDeleted" => NOTDELETED))->row_array();
    }

    public function chkEmailid($email) {
        return $this->db->get_where('user_master', array("email" => $email, "isDeleted" => NOTDELETED))->row_array();
    }

    public function chkMobileNumber($mobile) {
        return $this->db->get_where('user_master', array("mobile" => $mobile, "isDeleted" => NOTDELETED))->row_array();
    }

    function chkUnderMaintOrNot($scooterNumber) {
        return $this->db->query("SELECT * FROM `es_maintenance_under_scooter` where scooterNumber='{$scooterNumber}' and (maintStatus='" . MAINTPENDING . "' or maintStatus='" . MAINTPROGRESS . "' OR maintStatus='" . MAINTCOMPLETE . "') and istaskcomplete='" . TASKCOMPLETENO . "'")->row_array();
    }

    function UnderMaintDetails($scooterNumber) {
        return $this->db->query("SELECT sum(totalMaintTime) as totalMaintTime,sum(totalMaintSecond) as totalMaintSecond FROM `es_maintenance_under_scooter` WHERE scooterNumber='" . $scooterNumber . "' and maintStatus='" . MAINTCOMPLETE . "'")->row_array();
    }

    function UnderMaintUserDetails($userId) {
        return $this->db->query("SELECT sum(totalMaintTime) as totalMaintTime,sum(totalMaintSecond) as totalMaintSecond,count(id) as totalCompletedTask FROM `es_maintenance_under_scooter` WHERE userId='" . $userId . "' and maintStatus='" . MAINTCOMPLETE . "'")->row_array();
    }

    function UnderMaintUserDetails2($userId, $isCompleted) {
        return $this->db->query("SELECT sum(totalMaintTime) as totalMaintTime,sum(totalMaintSecond) as totalMaintSecond,count(id) as totalCompletedTask FROM `es_maintenance_under_scooter` WHERE userId='" . $userId . "' and maintStatus='" . MAINTCOMPLETE . "' and istaskcomplete='" . $isCompleted . "'")->row_array();
    }

    function scooterDetails($scooterNumber) {
        $summary = $this->db->query("SELECT sum(runningTime) as totalRunningTime,sum(runningSecond) as totalRunningSecond,sum(runningDistance) as totalRunningDistance FROM `es_bill_summary` WHERE scooterNumber='" . $scooterNumber . "'")->row_array();
        if ($summary['totalRunningDistance'] == '' || empty($summary['totalRunningDistance'])) {
            $distance = 0 . ' Km';
        } else {
            $distance = $summary['totalRunningDistance'] . ' Km';
        }
        return array("distance" => $distance);
    }

    public function pendingTaskCount($userId) {
        return $this->db->query("SELECT count(id) as pendingtask FROM `es_maintenance_under_scooter` where userId='" . $userId . "' and (maintStatus='" . MAINTPENDING . "' or maintStatus='" . MAINTPROGRESS . "')")->row_array();
    }

    public function pendingTaskCountType($userId, $type) {
        return $this->db->query("SELECT count(id) as pendingtask FROM `es_maintenance_under_scooter` where userId='" . $userId . "' and taskType='" . $type . "' and (maintStatus='" . MAINTPENDING . "' or maintStatus='" . MAINTPROGRESS . "')")->row_array();
    }

    public function checkIsEnteredInRestrictedArea($trackLat = false, $trackLng = false) {

        $rows = $this->db->select("lat, lng")
                        ->from($this->db->dbprefix('scooter_restricted_area'))
                        ->where("status", ACTIVE)
                        ->where("isDeleted", NOTDELETED)
                        ->get()->result();

        foreach ($rows as $k => $v) {
            $distance = $this->getDistanceBetweenCoOrdinates($trackLat, $trackLng, $v->lat, $v->lng);

            if ($distance <= RESTRICTED_AREA_RADIUS) {
                return true;
            }
        }

        return false;
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

    public function sendSMS($mobile, $msgContent) {
        $msgUrlEncoded = urlencode($msgContent);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            //CURLOPT_URL => "http://api.msg91.com/api/sendhttp.php?sender=" . OTP_SENDER . "&mobiles=" . OTP_COUNRTY . "{$mobile}&authkey=" . OTP_AUTH . "&message={$msgUrlEncoded}",
            CURLOPT_URL => "http://api.msg91.com/api/sendhttp.php?sender=" . OTP_SENDER . "&mobiles=91{$mobile}&authkey=" . OTP_AUTH . "&message={$msgUrlEncoded}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
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
            if ($res_array) {
                if (strtolower($res_array->type) == OTP_SUCCESS) {
                    return array("status" => 200, "message" => "SMS sent successfully.");
                } else {
                    return array("status" => 400, "message" => "Failed to send SMS");
                }
            }
        }
    }

    function validateDate($date, $format = 'Y-m-d H:i:s') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

}

?>