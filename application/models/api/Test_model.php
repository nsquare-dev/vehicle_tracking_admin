<?php

class Test_model extends CI_Model {

    public function __construct() {
        $this->load->database();
        $this->load->model('api/Common_model', 'Common_model', true);
    }

     function getScooterList() {
        if (!empty($this->input->post())) {
            if (empty($this->input->post('latitude')) || empty($this->input->post('longitude'))) {
                return array("status" => 400, "message" => "Please post latitude and longitude .");
            }
            $latitude = $this->input->post('latitude');
            $longitude = $this->input->post('longitude');
            $ScooterRadius = $this->Common_model->getAdminSetting();
            $predefinedRadius = $ScooterRadius['scooterRadius']; //20; // Radius in KM
            $radious = $predefinedRadius;
            // $str = "SELECT * , COALESCE((((acos(sin(('" . $latitude . "' *pi()/180)) * sin(( lat *pi()/180))+cos(('" . $latitude . "' *pi()/180)) * cos((lat *pi()/180)) * cos((('" . $longitude . "' - lng)*pi()/180))))*180/pi())*60*1.1515*1.609344),0) as distance FROM table name WHERE (expirydate >NOW() and status='created' and arate>'" . $rate . "' and COALESCE((((acos(sin(('" . $latitude . "' *pi()/180)) * sin(( lat *pi()/180))+cos(('" . $latitude . "' *pi()/180)) * cos((lat *pi()/180)) * cos((('" . $longitude . "' - lng)*pi()/180))))*180/pi())*60*1.1515*1.609344),0)<'" . $distance . "')  order by id  ASC";
            ###my radious scooter
            $myScooters = $this->db->query("SELECT * , COALESCE((((acos(sin(('" . $latitude . "' *pi()/180)) * sin(( lat *pi()/180))+cos(('" . $latitude . "' *pi()/180)) * cos((lat *pi()/180)) * cos((('" . $longitude . "' - lng)*pi()/180))))*180/pi())*60*1.1515*1.609344),0) as distance FROM es_scooter_parking where status='" . ACTIVE . "' and scooterStatus='" . NOTRESERVE . "' and isDeleted='" . NOTDELETED . "' and COALESCE((((acos(sin(('" . $latitude . "' *pi()/180)) * sin(( lat *pi()/180))+cos(('" . $latitude . "' *pi()/180)) * cos((lat *pi()/180)) * cos((('" . $longitude . "' - lng)*pi()/180))))*180/pi())*60*1.1515*1.609344),0)<'" . $radious . "'");
            $myScooters = $myScooters->result_array();
            //other radious scooter
            $otherScooters = $this->db->query("SELECT * , COALESCE((((acos(sin(('" . $latitude . "' *pi()/180)) * sin(( lat *pi()/180))+cos(('" . $latitude . "' *pi()/180)) * cos((lat *pi()/180)) * cos((('" . $longitude . "' - lng)*pi()/180))))*180/pi())*60*1.1515*1.609344),0) as distance FROM es_scooter_parking where status='" . ACTIVE . "' and scooterStatus='" . NOTRESERVE . "' and isDeleted='" . NOTDELETED . "' and COALESCE((((acos(sin(('" . $latitude . "' *pi()/180)) * sin(( lat *pi()/180))+cos(('" . $latitude . "' *pi()/180)) * cos((lat *pi()/180)) * cos((('" . $longitude . "' - lng)*pi()/180))))*180/pi())*60*1.1515*1.609344),0)>'" . $radious . "'");
            $otherScooters = $otherScooters->result_array();

            $radious = $predefinedRadius * 1000; // Radius in meters

            $myScootersdata = array();
            $otherScooterdata = array();
            if ($myScooters != '' && $otherScooters != '') {

                foreach ($myScooters as $key => $scooter) {
                    $myScootersdata[] = array("id" => $scooter['id'], "scooterId" => $scooter['scooterId'], "scooterNumber" => $scooter['scooterNumber'], "scooterLocation" => $scooter['location'], "scooterLat" => $scooter['lat'], "scooterLng" => $scooter['lng'], "distance" => $scooter['distance'], "rate" => 10);
                }
               
                return array("status" => 200, "message" => "Scooter list!", "radious" => $radious, "info" => $myScootersdata);
            } else {
                return array("status" => 400, "message" => "Not found any scooter!");
            }
        } else {
            return array("status" => 400, "message" => "Invalid post. Please try later!");
        }
    }

}

?>