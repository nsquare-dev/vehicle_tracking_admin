<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

class Scooter extends REST_Controller {

    function __construct() {
        // Construct the parent class
        parent::__construct();

        $this->methods['index_get']['limit'] = 500;
        $this->methods['getScooterList_post']['limit'] = 500;
        $this->methods['scooterReserve_post']['limit'] = 500;
        $this->methods['scooterReserveCancel_post']['limit'] = 500;
        $this->methods['scooterReserveUnlock_post']['limit'] = 500;
        $this->methods['scooterReserveLock_post']['limit'] = 500;
        $this->methods['userScooterRating_post']['limit'] = 500;
        $this->methods['getParking_post']['limit'] = 500;
        $this->methods['myTrip_post']['limit'] = 500;
        $this->methods['myTripDetails_post']['limit'] = 500;
        $this->methods['addTrackingLocation_post']['limit'] = 500;
        $this->methods['getRideDetails_post']['limit'] = 500;
        $this->methods['chkReservationStatus_post']['limit'] = 500;
        $this->methods['getTimeandDistance_post']['limit'] = 500;
        $this->methods['getScooterListtemp_post']['limit'] = 500;
        $this->load->model('api/Scooter_model');
        $this->load->model('api/Common_model');
        ## check header basic AUTH ##
        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
            $chkHeadersInfo = $this->Common_model->isChkHeadersInfo($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
            if ($chkHeadersInfo['status'] == 400) {
                echo json_encode($chkHeadersInfo);
                die;
            } else {
                ## check user id in DB ##
                $userId = $this->input->post('userId');
                $deviceId = $this->input->post('deviceId');
                if (isset($userId) && !empty($userId)) {
                    $userCheck = $this->Common_model->isUnblockAndNotDeletedUser($userId);
                    if ($userCheck['status'] == 422) {
                        echo json_encode($userCheck);
                        die;
                    }
                    if (isset($deviceId) && !empty($deviceId)) {
                        $userCheck = $this->Common_model->chkUserDeviceId($deviceId, $userId);
                        if ($userCheck['status'] == 422) {
                            echo json_encode($userCheck);
                            die;
                        }
                    }
                }
            }
        } else {
            $result = array("status" => 400, "message" => "No authentication header supplied. Please try later!");
            echo json_encode($result);
            die;
        }
    }

    public function getScooterList_post() {
        $result = $this->Scooter_model->getScooterList();
        array_walk_recursive($result, 'recursive_replacer');
        $this->set_response($result, REST_Controller::HTTP_OK);
    }

    public function scooterReserve_post() {
        $result = $this->Scooter_model->addScooterReserve();
        array_walk_recursive($result, 'recursive_replacer');
        $this->set_response($result, REST_Controller::HTTP_OK);
    }

    public function scooterReserveCancel_post() {
        $result = $this->Scooter_model->scooterReserveCancel();
        array_walk_recursive($result, 'recursive_replacer');
        $this->set_response($result, REST_Controller::HTTP_OK);
    }

    public function scooterReserveUnlock_post() {
        $result = $this->Scooter_model->scooterReserveUnlock();
        array_walk_recursive($result, 'recursive_replacer');
        $this->set_response($result, REST_Controller::HTTP_OK);
    }

    public function scooterReserveLock_post() {
        $result = $this->Scooter_model->scooterReserveLock();
        array_walk_recursive($result, 'recursive_replacer');
        $this->set_response($result, REST_Controller::HTTP_OK);
    }

    public function userScooterRating_post() {
        $result = $this->Scooter_model->userRating();
        array_walk_recursive($result, 'recursive_replacer');
        $this->set_response($result, REST_Controller::HTTP_OK);
    }

    public function getParking_post() {
        $result = $this->Scooter_model->getParking();
        array_walk_recursive($result, 'recursive_replacer');
        $this->set_response($result, REST_Controller::HTTP_OK);
    }

    public function myTrip_post() {
        $result = $this->Scooter_model->getMyTrip();
        array_walk_recursive($result, 'recursive_replacer');
        $this->set_response($result, REST_Controller::HTTP_OK);
    }

    public function myTripDetails_post() {
        $result = $this->Scooter_model->getMyTripDetails();
        array_walk_recursive($result, 'recursive_replacer');
        $this->set_response($result, REST_Controller::HTTP_OK);
    }

    public function addTrackingLocation_post() {
        $result = array("status" => 200, "message" => "Tracking location add successfully!");
        $this->Scooter_model->addTracking();
        $this->set_response($result, REST_Controller::HTTP_OK);
    }

    public function getRideDetails_post() {
        $result = $this->Scooter_model->getRideDetails();
        array_walk_recursive($result, 'recursive_replacer');
        $this->set_response($result, REST_Controller::HTTP_OK);
    }

    public function chkReservationStatus_post() {
        $result = $this->Scooter_model->chkReservationStatus();
        array_walk_recursive($result, 'recursive_replacer');
        $this->set_response($result, REST_Controller::HTTP_OK);
    }

    public function chkUserBalance_post() {
        $result = $this->Scooter_model->chkReservationStatus();
        array_walk_recursive($result, 'recursive_replacer');
        $this->set_response($result, REST_Controller::HTTP_OK);
    }

    public function getTimeandDistance_post() {
        $result = $this->Scooter_model->getTimeandDistance();
        array_walk_recursive($result, 'recursive_replacer');
        $this->set_response($result, REST_Controller::HTTP_OK);
    }

    public function getScooterListtemp_post() {
        $result = $this->Scooter_model->getScooterListtemp();
        array_walk_recursive($result, 'recursive_replacer');
        $this->set_response($result, REST_Controller::HTTP_OK);
    }

}

?>