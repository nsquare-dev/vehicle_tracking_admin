<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

class CronJob extends REST_Controller {

    function __construct() {
        // Construct the parent class
        parent::__construct();
        $this->methods['scooterReserveAutoCancel_get']['limit'] = 500; //Auto cancel scooter
        $this->load->model('api/CronJob_model');
    }

   public function scooterReserveAutoCancel_get() {
        $result = $this->CronJob_model->scooterReserveAutoCancel();
        $this->set_response($result, REST_Controller::HTTP_OK);
    }

   

}

?>