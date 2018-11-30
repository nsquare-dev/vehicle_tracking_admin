<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

class MaintenanceKnowledgeBase extends REST_Controller {

    function __construct() {
        // Construct the parent class
        parent::__construct();


        $this->methods['getknowledgeBaseList_post']['limit'] = 500; //
        $this->methods['getInstruction_post']['limit'] = 500; //
        $this->load->model('api/MaintenanceKnowledgeBase_model');
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

    public function getknowledgeBaseList_post() {
        $result = $this->MaintenanceKnowledgeBase_model->getknowledgeBaseList();
        $this->set_response($result, REST_Controller::HTTP_OK);
    }

    public function getInstruction_post() {
        $result = $this->MaintenanceKnowledgeBase_model->getInstruction();
        $this->set_response($result, REST_Controller::HTTP_OK);
    }

    public function getManualList_post() {
        $result = $this->MaintenanceKnowledgeBase_model->getManualList();
        $this->set_response($result, REST_Controller::HTTP_OK);
    }

}

?>