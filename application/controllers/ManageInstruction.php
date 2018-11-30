<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ManageInstruction extends CI_Controller {

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

        $this->load->model('Instruction_model');
        $this->load->helper('common_helper');
        $this->load->model('Common_model');
    }

    public function index() {
        if ($this->session->id) {      // $data = $this->data;
            $data['instructions'] = $this->Instruction_model->getInstructionList();
            $data['header'] = TRUE;
            $data['sidebar'] = TRUE;
            $data['_view'] = "manuals/view_instruction";
            $data['footer'] = TRUE;
            $data['top_menu'] = "Instruction";
            $data['pagetitle'] = "Instruction";
            $this->load->view('basetemplate', $data);
        } else {
            $this->load->view('login');
        }
    }

    function add_instruction() {
        if ($this->session->id) {
            $config = array(
                array(
                    'field' => 'instructionName',
                    'label' => 'Name',
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

                $config['upload_path'] = FCPATH . 'resource/files/';
                $config['allowed_types'] = 'doc|pdf|docx';
                $this->upload->initialize($config, true);
                //$this->load->library('upload', $config);
                if (!$this->upload->do_upload('image_file')) {
                    $result = array('status' => 400, 'message' => $this->upload->display_errors(), 'type' => 'file');
                } else {
                    $data = $this->upload->data();
                    $filePath = $data["file_name"];
                    $data1 = array(
                        'instruction' => $this->input->post('instructionName'),
                        'filePath' => $filePath,
                        'createdDate' => date('Y-m-d H:i:s'),
                        'isDeleted' => NOTDELETED
                    );
                    $result = $this->Instruction_model->addInstruction($data1);
                }
            }
            echo json_encode($result);
        } else {
            $this->load->view('login');
        }
    }

    public function deleteInstruction($instructionId, $value) {
        if ($this->session->id) {
            if ($this->Instruction_model->deleteInstruction($instructionId, $value)) {
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                redirect($_SERVER['HTTP_REFERER']);
            }
        } else {
            $this->load->view('login');
        }
    }

}
