<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ManageInstant extends CI_Controller {

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

        $this->load->model('Instant_model');
        $this->load->helper('common_helper');
    }

    public function index() {
        if ($this->session->id) {      // $data = $this->data;
            $data['instantList'] = $this->Instant_model->getInstantList();
            $data['header'] = TRUE;
            $data['sidebar'] = TRUE;
            $data['_view'] = "master/view_instant";
            $data['footer'] = TRUE;
            $data['top_menu'] = "Instance";
            $data['pagetitle'] = "Instance";
            $this->load->view('basetemplate', $data);
        } else{
            $this->load->view('login');
        }
    }
    public function instant_details($instatntId) {
        if ($this->session->id) {      // $data = $this->data;
            $data['instantDetails'] = $this->Instant_model->getInstantDetails($instatntId);
            $data['option'] = $this->Instant_model->getSelectedOptionList($data['instantDetails']['selectOption']);
            $data['rideStatus'] = $this->Instant_model->getRideStatus($data['instantDetails']['scooterNumber']);
            $data['header'] = TRUE;
            $data['sidebar'] = TRUE;
            $data['_view'] = "master/instant_details";
            $data['footer'] = TRUE;
            $data['top_menu'] = "Instance";
            $data['pagetitle'] = "Instance";
            $this->load->view('basetemplate', $data);
        } else{
            $this->load->view('login');
        }
    }

    public function stopride($scooter, $id){
        
        if($this->Instant_model->doStopRide(decode($scooter))){
            $this->session->set_flashdata('message', '<strong>Success!</strong> Scooter ride is stopped.');
            $this->session->set_flashdata('class', 'alert-success');
        }else{
            $this->session->set_flashdata('message', '<strong>Error!</strong> Unable to stop Scooter ride. Please try again!');
            $this->session->set_flashdata('class', 'alert-danger');
        }
        
        redirect('ManageInstant/instant_details/'. $id);
    }
   
}
