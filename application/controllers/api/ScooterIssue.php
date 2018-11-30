<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

class ScooterIssue extends REST_Controller {

    function __construct() {
        // Construct the parent class
        parent::__construct();

        $this->methods['scooterIssueQuestion_post']['limit'] = 500; //
        $this->methods['scooterIssueQuestionOption_post']['limit'] = 500; //
        $this->methods['scooterIssueComment_post']['limit'] = 500; //
        $this->load->model('api/ScooterIssue_model');
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

    public function scooterIssueQuestion_post() {
        $result = $this->ScooterIssue_model->scooterStartIssueQuestion();
        $this->set_response($result, REST_Controller::HTTP_OK);
    }

    public function scooterIssueQuestionOption_post() {
        $result = $this->ScooterIssue_model->scooterStartIssueQuestionOption();
        $this->set_response($result, REST_Controller::HTTP_OK);
    }

    public function scooterIssueComment_post() {
        $result = $this->ScooterIssue_model->scooterStartIssueComment();
        $this->set_response($result, REST_Controller::HTTP_OK);
    }

}

?>