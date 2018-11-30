<?php

class CronJob_model extends CI_Model {

    public function __construct() {
        $this->load->database();
        $this->load->model('api/Common_model', 'Common_model', true);
    }

    /*
     * function : update product status if biding is started OR ended 
     * @param none
     * @return array
     */

    public function scooterReserveAutoCancel() {
        try {
            $ScooterCancelTime = $this->Common_model->getAdminSetting();
            $reserveScooter = $this->db->get_where('scooter_reserve', array("rideStatus" => RIDEPENDING, "status" => ACTIVE))->result_array();
            if ($reserveScooter != '') {
                foreach ($reserveScooter as $key => $scooter) {
                    $autocancelTime=$ScooterCancelTime['scooterCancelTime']; //minutes
                    $reservTime = $scooter['reserveDate'] . ' ' . $scooter['reserveTime'];
                    $datetime1 = strtotime($reservTime);
                    $datetime2 = strtotime(date('Y-m-d H:i:s'));
                    $interval = abs($datetime2 - $datetime1);
                    $minutes = round($interval / 60);
                    if ($autocancelTime < $minutes) {
                        //parking table status update
                        $where = array(
                            "id" =>$scooter['scooterParkId']
                        );

                        $updateArray = array(
                            "reserveUserId" => '0',
                            "scooterStatus" => NOTRESERVE
                        );
                        $update = $this->db->update($this->db->dbprefix('scooter_parking'), $updateArray, $where);
                        //reserve table status update
                        $where2 = array(
                            "id" => $scooter['id'],
                            "isLockUnlock" => ISLOCK
                        );

                        $updateArray2 = array(
                            "rideStatus" => RIDECANCEL
                        );
                        $update2 = $this->db->update($this->db->dbprefix('scooter_reserve'), $updateArray2, $where2);
//                        if ($update2) {
//                            return array("status" => 200, "message" => "Scooter reservation cancelled  successfully!");
//                        } else {
//                            return array("status" => 400, "message" => "Scooter not reservation cancelled. Please try again!");
//                        }
                    }
//                    else {
//                        return array("status" => 200, "message" => $minutes."not found any scooter!");
//                    }
                }
                
            }
        } catch (Exception $e) {
            return array("status" => 400, "message" => $e->getMessage());
        }
    }

}

?>