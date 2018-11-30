<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

class Test extends REST_Controller {

    function __construct() {
        // Construct the parent class
        parent::__construct();

        $this->methods['truncateTable_post']['limit'] = 500; //default
        
        $this->load->model('api/Test_model');
        $this->load->model('api/Common_model');
     
            ## check header basic AUTH ##
            //$headersData = $this->input->request_headers();
            //print_r($_SERVER['PHP_AUTH_USER']);
             //print_r($_SERVER['PHP_AUTH_PW']);
           // die;
//            if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
//                $chkHeadersInfo = $this->Common_model->isChkHeadersInfo($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
//                if ($chkHeadersInfo['status'] == 400) {
//                    echo json_encode($chkHeadersInfo);
//                    die;
//                } else {
//                    ## check user id in DB ##
//                    $userId = $this->input->post('userId');
//                    if (isset($userId) && !empty($userId)) {
//                        $userCheck = $this->Common_model->isUnblockAndNotDeletedUser($userId);
//                        if ($userCheck['status'] == 422) {
//                            echo json_encode($userCheck);
//                            die;
//                        }
//                    }
//                }
//            } else {
//                $result = array("status" => 400, "message" => "No authentication header supplied. Please try later!");
//                echo json_encode($result);
//                die;
//            }
        
    }

   

    public function getUserData_post() {
        $result = $this->Test_model->getData();
        $this->set_response($result, REST_Controller::HTTP_OK);
    }
     public function getScooterList_post() {
        $result = $this->Test_model->getScooterList();
        $this->set_response($result, REST_Controller::HTTP_OK);
    }

    

   
}

?>