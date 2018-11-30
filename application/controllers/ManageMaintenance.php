<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ManageMaintenance extends CI_Controller {

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

        $this->load->model('MaintenanceMaster_model');
        $this->load->helper('common_helper');
    }

    public function index() {
        if ($this->session->id) {      // $data = $this->data;
            $data['user'] = $this->MaintenanceMaster_model->getMaintUserList();
            $data['header'] = TRUE;
            $data['sidebar'] = TRUE;
            $data['_view'] = "maint_master/view_maint_user";
            $data['footer'] = TRUE;
            $data['top_menu'] = "view_maint_user";
            $data['pagetitle'] = "User";
            $this->load->view('basetemplate', $data);
        } else {
            $this->load->view('login');
        }
    }

    public function userList($scooterNumber) {
        if ($this->session->id) {
            $chkscooter = $this->MaintenanceMaster_model->chkScooterStatus($scooterNumber);
            if ($chkscooter['status'] == '400') {
                echo json_encode($chkscooter);
            } else {
                $data = $this->MaintenanceMaster_model->getMaintUser();
                echo json_encode($data);
            }
        } else {
            $this->load->view('login');
        }
    }

    public function getMaintdetails($userId) {
        if ($this->session->id) {
            $data['userDetails'] = $this->MaintenanceMaster_model->getMaintUserDeatils($userId);
            $data['header'] = TRUE;
            $data['sidebar'] = TRUE;
            $data['_view'] = "maint_master/view_user_details";
            $data['footer'] = TRUE;
            $data['top_menu'] = "view_maint_user";
            $data['pagetitle'] = "User Details";
            $this->load->view('basetemplate', $data);
        } else {
            $this->load->view('login');
        }
    }

    public function addUser() {
        if ($this->session->id) {
            $data = $this->MaintenanceMaster_model->addUser($this->input->post());
            echo json_encode($data);
        } else {
            redirect('login');
        }
    }

    public function view_detail($reserveId) {
        if ($this->session->id) {
            $data = $this->MaintenanceMaster_model->getDeatils($reserveId);
            echo json_encode($data);
        } else {
            $this->load->view('login');
        }
    }

    //task managent function

    public function view_user_task() {
        if ($this->session->id) {
            $data['userDetails'] = $this->MaintenanceMaster_model->getUserTaskList();
            $data['header'] = TRUE;
            $data['sidebar'] = TRUE;
            $data['_view'] = "maint_master/task_management";
            $data['footer'] = TRUE;
            $data['top_menu'] = "task";
            $data['pagetitle'] = "Task Management";
            $this->load->view('basetemplate', $data);
        } else {
            $this->load->view('login');
        }
    }

    public function getMaintTaskdetails($userId) {
        if ($this->session->id) {
            $data['userDetails'] = $this->MaintenanceMaster_model->getMaintUserTaskDeatils($userId);
            $data['header'] = TRUE;
            $data['sidebar'] = TRUE;
            $data['_view'] = "maint_master/view_task_details";
            $data['footer'] = TRUE;
            $data['top_menu'] = "task";
            $data['pagetitle'] = "Task Management";
            $this->load->view('basetemplate', $data);
        } else {
            $this->load->view('login');
        }
    }

    public function view_admin_task() {
        if ($this->session->id) {
            $data['userDetails'] = $this->MaintenanceMaster_model->getUserAdminTaskList();
            $data['header'] = TRUE;
            $data['sidebar'] = TRUE;
            $data['_view'] = "maint_master/admin_task";
            $data['footer'] = TRUE;
            $data['top_menu'] = "task";
            $data['pagetitle'] = "Task Management";
            $this->load->view('basetemplate', $data);
        } else {
            $this->load->view('login');
        }
    }

    public function getMaintAdminTaskdetails($userId) {
        if ($this->session->id) {
            $data['userDetails'] = $this->MaintenanceMaster_model->getMaintUserAdminTaskDeatils($userId);
            $data['header'] = TRUE;
            $data['sidebar'] = TRUE;
            $data['_view'] = "maint_master/view_task_details";
            $data['footer'] = TRUE;
            $data['top_menu'] = "task";
            $data['pagetitle'] = "Task Management";
            $this->load->view('basetemplate', $data);
        } else {
            $this->load->view('login');
        }
    }

    public function addAssignTask() {
        if ($this->session->id) {
            if ($this->MaintenanceMaster_model->addAssignTask($this->input->post())) {
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                redirect($_SERVER['HTTP_REFERER']);
            }
        } else {
            redirect('login');
        }
    }

    public function addAdminAssignTask() {

        if ($this->session->id) {
            if ($this->MaintenanceMaster_model->addAdminAssignTask($this->input->post())) {
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                redirect($_SERVER['HTTP_REFERER']);
            }
        } else {
            redirect('login');
        }
    }

    public function reservation_cancelled($scooterNumber, $value) {

        if ($this->session->id) {
            if ($this->MaintenanceMaster_model->reservationCancelled($scooterNumber)) {
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                redirect($_SERVER['HTTP_REFERER']);
            }
        } else {
            redirect('login');
        }
    }

    public function userStatus($userId, $value) {
        if ($this->session->id) {
            if ($this->MaintenanceMaster_model->changeUserStatus($userId, $value)) {
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                redirect($_SERVER['HTTP_REFERER']);
            }
        } else {
            $this->load->view('login');
        }
    }

    public function removeRecord($userId, $value) {
        if ($this->session->id) {
            if ($this->MaintenanceMaster_model->removeRecord($userId, $value)) {
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                redirect($_SERVER['HTTP_REFERER']);
            }
        } else {
            $this->load->view('login');
        }
    }

}
