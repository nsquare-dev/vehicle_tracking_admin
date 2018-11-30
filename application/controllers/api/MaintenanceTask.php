<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

class MaintenanceTask extends REST_Controller {

    function __construct() {
        // Construct the parent class
        parent::__construct();


        $this->methods['getPendingIssueList_post']['limit'] = 500; //
        $this->methods['getCompletedIssueList_post']['limit'] = 500; //
        $this->methods['getstartScooterMaintenance_post']['limit'] = 500;
        $this->methods['getProgressStart_post']['limit'] = 500; //
        $this->methods['getProgressStop_post']['limit'] = 500; //
        $this->methods['getTotalPendingTaskCount_post']['limit'] = 500; //
        $this->methods['getTimer_post']['limit'] = 500; //
        $this->methods['chkLockUnlock_post']['limit'] = 500; //
        $this->load->model('api/MaintenanceTask_model');
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
                        $userCheck = $this->Common_model->chkUserDeviceId($deviceId,$userId);
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

    public function getPendingIssueList_post() {
        $result = $this->MaintenanceTask_model->getPendingIssueList();
        $this->set_response($result, REST_Controller::HTTP_OK);
    }

    public function getCompletedIssueList_post() {
        $result = $this->MaintenanceTask_model->getCompletedIssueList();
        $this->set_response($result, REST_Controller::HTTP_OK);
    }

    public function getstartScooterMaintenance_post() {
        $result = $this->MaintenanceTask_model->startScooterMaintenance();
        $this->set_response($result, REST_Controller::HTTP_OK);
    }

    public function getProgressStatus_post(){
        $result = $this->MaintenanceTask_model->getProgressStatus();
        $this->set_response($result, REST_Controller::HTTP_OK);
    }
    
    public function getProgressStart_post() {
        $result = $this->MaintenanceTask_model->getProgressStart();
        $this->set_response($result, REST_Controller::HTTP_OK);
    }

    public function getProgressStop_post() {
        $result = $this->MaintenanceTask_model->getProgressStop();
        $this->set_response($result, REST_Controller::HTTP_OK);
    }

    public function getTotalPendingTaskCount_post() {
        $result = $this->MaintenanceTask_model->totalPendingTaskCount();
        $this->set_response($result, REST_Controller::HTTP_OK);
    }

    public function getTimer_post() {
        $result = $this->MaintenanceTask_model->getTimerCount();
        $this->set_response($result, REST_Controller::HTTP_OK);
    }
    public function chkLockUnlock_post() {
        $result = $this->MaintenanceTask_model->chkLockUnlock();
        $this->set_response($result, REST_Controller::HTTP_OK);
    }

}

?>