<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ManageManauals extends CI_Controller {

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

        $this->load->model('Manuals_model');
        $this->load->helper('common_helper');
        $this->load->model('Common_model');
    }

    public function index() {
        if ($this->session->id) {      // $data = $this->data;
            $data['manuals'] = $this->Manuals_model->getManualsList();
            $data['header'] = TRUE;
            $data['sidebar'] = TRUE;
            $data['_view'] = "manuals/view_manuals";
            $data['footer'] = TRUE;
            $data['top_menu'] = "Manuals";
            $data['pagetitle'] = "Manuals";
            $this->load->view('basetemplate', $data);
        } else {
            $this->load->view('login');
        }
    }

    public function addManuals() {
        if ($this->session->id) {

            $config = array(
                array(
                    'field' => 'manualsName',
                    'label' => 'Name',
                    'rules' => 'trim|required',
                ),
                array(
                    'field' => 'manualsCat',
                    'label' => 'Category',
                    'rules' => 'trim|required',
                ),
                
            );

            if (empty($_FILES["image_file"]["name"])) {
                $config[] = array(
                    'field' => 'image_file',
                    'label' => 'File',
                    'rules' => 'trim|required'
                );
            }
            $this->form_validation->set_rules($config);

            if ($this->form_validation->run() == FALSE) {
                $errors = validation_errors();
                $result = array("status" => 400, "message" => nl2br(strip_tags($errors)));
            } else {
                 
                $config['upload_path'] = FCPATH.'resource/files/';
                $config['allowed_types'] = 'doc|pdf|docx';
                //$this->load->library('upload', $config);
                $this->upload->initialize($config, true);
                if (!$this->upload->do_upload('image_file')) {
                    $result = array('status' => 400, 'message' => $this->upload->display_errors(), 'type' => 'file');
                } else {
                    $data = $this->upload->data();
                    
                    switch(strtolower($this->input->post('manualsCat'))){
                        case 'engine': 
                            $icon ="engine_icn.png";
                            break;
                        
                        case 'indicators': 
                            $icon ="indicator_icn.png";
                            break;
                        
                        case 'brakes': 
                            $icon ="brakes_icn.png";
                            break;
                        
                        case 'battery': 
                            $icon ="battery_icn.png";
                            break;
                        
                        default:
                            $icon ="engine_icn.png";
                            break;
                    }
                    
                    $data1 = array(
                        'manualCategory' => $this->input->post('manualsCat'),
                        'name' => $this->input->post('manualsName'),
                        'filePath' => $data["file_name"],
                        'image' => $icon,
                        'createdDate' => date('Y-m-d H:i:s'),
                        'isDeleted' => NOTDELETED
                    );
                    $result = $this->Manuals_model->addManuals($data1);
                }
            }
            echo json_encode($result);
            die;
        } else {
            redirect('login');
        }
    }

    public function deleteManuals($manualsId, $value) {
        if ($this->session->id) {
            if ($this->Manuals_model->deleteManuals($manualsId, $value)) {
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                redirect($_SERVER['HTTP_REFERER']);
            }
        } else {
            $this->load->view('login');
        }
    }

}
