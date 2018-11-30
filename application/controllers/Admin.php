<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Admin_model');
    }

    public function add() {
        if ($this->Admin_model->add($this->input->post())) {
            // redirect("user/user");
            redirect("admin");
        } else
            redirect($_SERVER['HTTP_REFERER']);
    }

    public function index() {

        if ($this->session->id) {
            $data['result'] = $this->Admin_model->getCounter();
            $data['header'] = TRUE;
            $data['sidebar'] = TRUE;
            $data['_view'] = "admin/dashboard";
            $data['footer'] = TRUE;
            $data['top_menu'] = "dashboard";
            $data['sub_menu'] = "dash";
            $data['pagetitle'] = "E-scooter";
            $this->load->view('basetemplate', $data);
        } else
            redirect('login');
    }

    public function login() {
        
        if ($this->session->id) {
            $data['header'] = TRUE;
            $data['sidebar'] = TRUE;
            $data['_view'] = "admin/dashboard";
            $data['footer'] = TRUE;
            $data['top_menu'] = "dashboard";
            $data['sub_menu'] = "dash";
            $data['pagetitle'] = "Login";
            $this->load->view('basetemplate', $data);
        } else {
            if ($this->input->post()) {
                if ($this->Admin_model->login($this->input->post()))
                    redirect("admin");
                else
                   $this->load->view('login');
            } else
                $this->load->view('login');
        }
    }

    public function logout() {
        session_destroy();
        $this->session->sess_destroy();
        redirect('/');
    }

    public function profile() {
        if ($this->session->id) {     // $data = $this->data;
            $data['header'] = TRUE;
            $data['sidebar'] = TRUE;
            $data['_view'] = "admin/profile";
            $data['footer'] = TRUE;
            $data['top_menu'] = "";
            $data['sub_menu'] = "";
            $data['pagetitle'] = "Profile";
            $this->load->view('basetemplate', $data);
        } else
            redirect('login');
    }

    public function editprofile() {
        if ($this->session->id) {
            if ($this->Admin_model->editprofile($this->input->post())) {
                redirect($_SERVER['HTTP_REFERER']);
            } else{
                redirect($_SERVER['HTTP_REFERER']);
            }
        } else{
            redirect('login');
        }
    }

    public function chnageprofile() {
        if ($this->session->id) {
            $config['upload_path'] = './profile-img/';
            $config['allowed_types'] = 'gif|jpg|jpeg|png|bmp';
            $config['max_size'] = 4000;
            $config['max_width'] = 10024;
            $config['max_height'] = 7068;
            //$this->load->library('upload', $config);
            $this->upload->initialize($config, true);
            if (!$this->upload->do_upload('userfile')) {
                $error = strip_tags($this->upload->display_errors());
                $this->session->set_flashdata('error', "{$error}!");
            } else {
                $data = array('upload_data' => $this->upload->data());
                if ($this->upload->data('file_name')) {
                    $this->session->set_userdata('image', $this->upload->data('file_name'));
                    $this->Admin_model->chnageprofile($this->upload->data('file_name'));
                    $config['image_library'] = 'gd2';
                    $config['source_image'] = './profile-img/' . $this->upload->data('file_name');
                    $config['create_thumb'] = FALSE;
                    $config['maintain_ratio'] = FALSE;
                    $config['width'] = 300;
                    $config['height'] = 300;

                    $this->load->library('image_lib', $config);
                    $this->image_lib->resize();
                }
                 
            }

            redirect(base_url('admin/profile/#tab_1_2') );
        } else
            redirect('login');
    }

    public function changepassword() {
        if ($this->session->id) {
            $data = $this->Admin_model->chnagepassword($this->input->post());
            echo json_encode($data);
        } else
            redirect('login');
    }

    public function getLatestNotifications() {
        if ($this->session->id) {
            $data = $this->Admin_model->getLatestNotifications();
            echo json_encode($data);
        } else
            redirect('login');
    }

    public function notifications() {
        if ($this->session->id) {     // $data = $this->data;
            $data['header'] = TRUE;
            $data['sidebar'] = TRUE;
            $data['_view'] = "admin/notifications";
            $data['footer'] = TRUE;
            $data['top_menu'] = "Notifications";
            $data['sub_menu'] = "Notifications";
            $data['pagetitle'] = "Notifications";
            $data['notifications'] = $this->Admin_model->getAllNotifications();
            $this->load->view('basetemplate', $data);
        } else
            redirect('login');
    }

}
