<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ManageReport extends CI_Controller {

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

        $this->load->model('MaintenanceReport_model');
        $this->load->helper('common_helper');
    }

    public function view_completed_task() {
        if ($this->session->id) {
            $data['userDetails'] = $this->MaintenanceReport_model->getCompletedUserTaskList();
            $data['header'] = TRUE;
            $data['sidebar'] = TRUE;
            $data['_view'] = "task_report/completed_task";
            $data['footer'] = TRUE;
            $data['top_menu'] = "taskreport";
            $data['pagetitle'] = "Task Report";
            $this->load->view('basetemplate', $data);
        } else {
            $this->load->view('login');
        }
    }

    public function view_uncompleted_task() {
        if ($this->session->id) {
            $data['userDetails'] = $this->MaintenanceReport_model->getUncompletedUserTaskList();
            $data['header'] = TRUE;
            $data['sidebar'] = TRUE;
            $data['_view'] = "task_report/uncompleted_task";
            $data['footer'] = TRUE;
            $data['top_menu'] = "taskreport";
            $data['pagetitle'] = "Task Report";
            $this->load->view('basetemplate', $data);
        } else {
            $this->load->view('login');
        }
    }

    public function completed_task_details($userId) {
        if ($this->session->id) {
            $data['userDetails'] = $this->MaintenanceReport_model->getCompleteMaintUserDeatils($userId);
            $data['header'] = TRUE;
            $data['sidebar'] = TRUE;
            $data['_view'] = "task_report/completed_task_details";
            $data['footer'] = TRUE;
            $data['top_menu'] = "taskreport";
            $data['pagetitle'] = "Task Report";
            $this->load->view('basetemplate', $data);
        } else {
            $this->load->view('login');
        }
    }

    public function uncompleted_task_details($userId) {
        if ($this->session->id) {
            $data['userDetails'] = $this->MaintenanceReport_model->getUncompleteMaintUserDeatils($userId);
            $data['header'] = TRUE;
            $data['sidebar'] = TRUE;
            $data['_view'] = "task_report/uncompleted_task_details";
            $data['footer'] = TRUE;
            $data['top_menu'] = "taskreport";
            $data['pagetitle'] = "Task Report";
            $this->load->view('basetemplate', $data);
        } else {
            $this->load->view('login');
        }
    }

    public function getDetails($maintId) {
        if ($this->session->id) {
            $data = $this->MaintenanceReport_model->getDeatils($maintId);
            echo json_encode($data);
        } else {
            $this->load->view('login');
        }
    }

    public function scooterStatus($scooterId, $value) {
        if ($this->session->id) {
            if ($this->MaintenanceReport_model->changeScooterStatus($scooterId, $value)) {
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                redirect($_SERVER['HTTP_REFERER']);
            }
        } else {
            $this->load->view('login');
        }
    }

}
