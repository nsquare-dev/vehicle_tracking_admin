<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

class UserMaster extends REST_Controller {

    function __construct() {
        // Construct the parent class
        parent::__construct();

        $this->methods['index_post']['limit'] = 500; //default
        $this->methods['signUp_post']['limit'] = 500; //sign up user
        $this->methods['signIn_post']['limit'] = 500; //sign in user
        $this->methods['confirmOTP_post']['limit'] = 500; //confirm OTP
        $this->methods['forgotPass_post']['limit'] = 500; //Forgotpass
        $this->methods['resetPassword_post']['limit'] = 500; //Resetpass
        $this->methods['changePassword_post']['limit'] = 500; //Change Password
        $this->methods['chkRefferalCode_post']['limit'] = 500; //chk RefferalCode
        $this->methods['userFeedback_post']['limit'] = 500; //user feddback
        $this->methods['addFavouriteLocation_post']['limit'] = 500; //user addFavourite
        $this->methods['getFavouriteLocation_post']['limit'] = 500; //user getFavourite
        $this->methods['editUserProfile_post']['limit'] = 500; //user edit profile
        $this->methods['viewProfile_post']['limit'] = 500; //user edit profile
        $this->methods['logout_post']['limit'] = 500; //user edit profile
        $this->load->model('api/UserMaster_model');
        $this->load->model('api/Common_model');

        ## check header basic AUTH ##
        //$headersData = $this->input->request_headers();
        //print_r($_SERVER['PHP_AUTH_USER']);
        //print_r($_SERVER['PHP_AUTH_PW']);
        // die;
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

    public function index_post() {
        $userId = $this->input->post('userId');
        if (isset($userId) && !empty($userId)) {
            $result = array("status" => 400, "message" => "Logged in. Please use API methods to retrive data");
        } else {
            $result = array("status" => 400, "message" => "Please log in to access methods");
        }
        $this->set_response($result, REST_Controller::HTTP_OK);
    }

    public function signUp_post() {
        $result = $this->UserMaster_model->signeUp();
        $this->set_response($result, REST_Controller::HTTP_OK);
    }

    public function signIn_post() {
        $result = $this->UserMaster_model->signeIn();
        $this->set_response($result, REST_Controller::HTTP_OK);
    }

    public function confirmOTP_post() {

        $result = $this->UserMaster_model->checkOTP();
        $this->set_response($result, REST_Controller::HTTP_OK);
    }

    public function resendOTP_post() {
        $result = $this->UserMaster_model->resendOTP();
        $this->set_response($result, REST_Controller::HTTP_OK);
    }

    public function forgotPass_post() {
        $result = $this->UserMaster_model->doForgotPass();
        $this->set_response($result, REST_Controller::HTTP_OK);
    }

    public function resetPassword_post() {
        $result = $this->UserMaster_model->resetPassword();
        $this->set_response($result, REST_Controller::HTTP_OK);
    }

    public function changePassword_post() {
        $result = $this->UserMaster_model->changePassword();
        $this->set_response($result, REST_Controller::HTTP_OK);
    }

    public function chkRefferalCode_post() {
        $result = $this->UserMaster_model->chkRefferalCode();
        $this->set_response($result, REST_Controller::HTTP_OK);
    }

    public function userFeedback_post() {
        $result = $this->UserMaster_model->addFeedback();
        $this->set_response($result, REST_Controller::HTTP_OK);
    }

    public function addFavouriteLocation_post() {
        $result = $this->UserMaster_model->addFavouriteLocation();
        $this->set_response($result, REST_Controller::HTTP_OK);
    }

    public function getFavouriteLocation_post() {
        $result = $this->UserMaster_model->getFavouriteLocation();
        $this->set_response($result, REST_Controller::HTTP_OK);
    }

    public function editUserProfile_post() {
        $result = $this->UserMaster_model->editUserProfile();
        $this->set_response($result, REST_Controller::HTTP_OK);
    }

    public function viewProfile_post() {
        $result = $this->UserMaster_model->getUserProfile();
        $this->set_response($result, REST_Controller::HTTP_OK);
    }

    public function logout_post() {
        $result = $this->UserMaster_model->doLogout();
        $this->set_response($result, REST_Controller::HTTP_OK);
    }

}

?>