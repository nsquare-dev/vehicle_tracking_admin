<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ManageMap extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct() {

        parent::__construct();

        $this->load->model('Map_model');
        $this->load->helper('common_helper');
        $this->load->model('Common_model');
    }

    public function index() {
        if ($this->session->id) {      // $data = $this->data;
            $data['map'] = $this->Map_model->getScooter();
            $data['counter'] = $this->Common_model->getCounter();
            $data['header'] = TRUE;
            $data['sidebar'] = TRUE;
            $data['_view'] = "master/view_map";
            $data['footer'] = TRUE;
            $data['top_menu'] = "Map";
            $data['pagetitle'] = "Map";
            $this->load->view('basetemplate', $data);
        } else{
            $this->load->view('login');
        }
    }
     public function getScooters() {
         if ($this->session->id) {  
            $data = $this->Map_model->getScooter();
             echo json_encode($data);

        } else {
            $this->load->view('login');
        }

    }
    
    //Live tracking Function
    
    public function view_running_scooter() {
        if ($this->session->id) {      // $data = $this->data;
            $data['scooter'] = $this->Map_model->getRunningScooter();
            $data['header'] = TRUE;
            $data['sidebar'] = TRUE;
            $data['_view'] = "master/view_running_scooter";
            $data['footer'] = TRUE;
            $data['top_menu'] = "live";
            $data['pagetitle'] = "Live";
            $this->load->view('basetemplate', $data);
        } else{
            $this->load->view('login');
        }
    }
    public function view_live_tracking($reserveId,$userId) {
        if ($this->session->id) {      // $data = $this->data;
            $data['userDetails'] = $this->Map_model->getRunningScooterDetails($reserveId,$userId);
            $data['header'] = TRUE;
            $data['sidebar'] = TRUE;
            $data['_view'] = "master/view_live_tracking";
            $data['footer'] = TRUE;
            $data['top_menu'] = "live";
            $data['pagetitle'] = "Live";
            $this->load->view('basetemplate', $data);
        } else{
            $this->load->view('login');
        }
    }
    
    public function getTrckLocation($reserveId,$userId) {
        if ($this->session->id) {
            $data = $this->Map_model->getTrckLocation($reserveId,$userId);
            echo json_encode($data);
        } else {
            $this->load->view('login');
        }
    }
   
}
