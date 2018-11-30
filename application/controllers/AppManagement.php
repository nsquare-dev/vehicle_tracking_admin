<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class AppManagement extends CI_Controller {

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

        $this->load->model('App_model');
    }

    public function index() {
        if ($this->session->id) {
            $data['config'] = $this->App_model->getAllData();
            $data['topup'] = $this->App_model->getTopUpData();
            $data['header'] = TRUE;
            $data['sidebar'] = TRUE;
            $data['_view'] = "app_master/view_app_management";
            $data['footer'] = TRUE;
            $data['top_menu'] = "manage_app";
            $data['pagetitle'] = "Open Game";
            $this->load->view('basetemplate', $data);
        } else
            $this->load->view('login');
    }

    public function getData() {
        if ($this->session->id) {
            $data = $this->App_model->getScooterData();
            echo json_encode($data);
        } else {
            redirect('login');
        }
    }

    public function gettopup() {
        if ($this->session->id) {
            $data = $this->App_model->getTopUpData();
            echo json_encode($data);
        } else {
            redirect('login');
        }
    }

    public function updateDepositAmount() {
        if ($this->session->id) {
            $data = $this->App_model->updateDeposit($this->input->post());
            echo json_encode($data);
        } else {
            redirect('login');
        }
    }

    public function updateRideAmount() {
        if ($this->session->id) {
            $data = $this->App_model->updateRideAmount($this->input->post());
            echo json_encode($data);
        } else {
            redirect('login');
        }
    }

    public function updateTopUp() {
        if ($this->session->id) {
            $config = array(
                array(
                    'field' => 'price[]',
                    'label' => 'Price',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'bonus[]',
                    'label' => 'Bonus',
                    'rules' => 'trim'
                ),
                array(
                    'field' => 'id[]',
                    'label' => 'Row',
                    'rules' => 'trim'
                ),
            );

            $this->form_validation->set_rules($config);
            if ($this->form_validation->run() == FALSE) {
                $errors = validation_errors();
                $return = array("status" => 400, "message" => nl2br(strip_tags($errors)));
            } else {
                $return = $this->App_model->doupdateTopUp($this->input->post());
            }

            echo json_encode($return);
        } else {
            echo json_encode(array("status" => 400, "message" => "Please login again"));
        }
    }

    public function updatePaneltyCharges() {
        if ($this->session->id) {
            $data = $this->App_model->updatePaneltyCharges($this->input->post());
            echo json_encode($data);
        } else {
            redirect('login');
        }
    }

    public function updateConfig() {
        if ($this->session->id) {

            $config = array(
                array(
                    'field' => 'field_deposite_amount',
                    'label' => 'Deposite Amount',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'field_radious',
                    'label' => 'Radious',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'field_cancel_min',
                    'label' => 'Reservation Cancel In Min',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'field_charges_per_min',
                    'label' => 'Charges Per Min',
                    'rules' => 'required'
                ),
                /*array(
                    'field' => 'field_base_fare',
                    'label' => 'Base Fare',
                    'rules' => 'required'
                ),*/
                array(
                    'field' => 'field_own_refferal_amount',
                    'label' => 'Own Refferal Amount',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'field_other_refferal_amount',
                    'label' => 'Other Refferal Amount',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'field_confId',
                    'label' => 'Row ID',
                    'rules' => 'required'
                ),
            );

            $this->form_validation->set_rules($config);
            if ($this->form_validation->run() == FALSE) {
                $errors = validation_errors();
                $return = array("status" => 400, "message"  => nl2br(strip_tags($errors)));
            } else {
                $return = $this->App_model->updateConfiguration($this->input->post());
            }

            echo json_encode($return);
        } else {
            echo json_encode(array("status" => 400, "message" => "Please login again"));
        }
        
        exit;
    }

}
