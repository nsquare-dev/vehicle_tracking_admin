<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ManageUser extends CI_Controller {

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

        $this->load->model('UserMaster_model');
        $this->load->helper('common_helper');
    }

    public function index() {
        if ($this->session->id) {      // $data = $this->data;
            $data['user'] = $this->UserMaster_model->getUserList();
            $data['header'] = TRUE;
            $data['sidebar'] = TRUE;
            $data['_view'] = "user_master/view_user";
            $data['footer'] = TRUE;
            $data['top_menu'] = "view_user";
            $data['pagetitle'] = "User";
            $this->load->view('basetemplate', $data);
        } else {
            $this->load->view('login');
        }
    }

    public function view_users_detail($userId) {
        if ($this->session->id) {
            $data['userDetails'] = $this->UserMaster_model->getUserDeatils($userId);
            $data['header'] = TRUE;
            $data['sidebar'] = TRUE;
            $data['_view'] = "user_master/view_user_details";
            $data['footer'] = TRUE;
            $data['top_menu'] = "view_user";
            $data['pagetitle'] = "User Details";
            $this->load->view('basetemplate', $data);
        } else {
            $this->load->view('login');
        }
    }

    public function view_users_feedbacks($userId) {
        if ($this->session->id) {      // $data = $this->data;
            $data['feedbacks'] = $this->UserMaster_model->getFeedbacksListByUser(decode($userId));
            $data['header'] = TRUE;
            $data['sidebar'] = TRUE;
            $data['_view'] = "user_master/view_feedbacks";
            $data['footer'] = TRUE;
            $data['top_menu'] = "view_user";
            $data['pagetitle'] = "User";
            $this->load->view('basetemplate', $data);
        } else {
            $this->load->view('login');
        }
    }

    public function view_detail($reserveId) {
        if ($this->session->id) {
            $data = $this->UserMaster_model->getDeatils($reserveId);
            echo json_encode($data);
        } else {
            $this->load->view('login');
        }
    }

    public function chkUserStatus($userId) {
        if ($this->session->id) {
            $data = $this->UserMaster_model->chkUserStatus($userId);
            echo json_encode($data);
        } else {
            $this->load->view('login');
        }
    }

    public function userStatus($userId, $value) {
        if ($this->session->id) {
            if ($this->UserMaster_model->changeUserStatus($userId, $value)) {
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
            if ($this->UserMaster_model->removeRecord($userId, $value)) {
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                redirect($_SERVER['HTTP_REFERER']);
            }
        } else {
            $this->load->view('login');
        }
    }

}
