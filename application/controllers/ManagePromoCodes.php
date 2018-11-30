<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ManagePromoCodes extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->model('Promocode_model');
        $this->load->helper('common_helper');
    }

    public function index() {

        if ($this->session->id) {
            $data['results'] = $this->Promocode_model->getList();
            $data['header'] = TRUE;
            $data['sidebar'] = TRUE;
            $data['_view'] = "promo_code/view";
            $data['footer'] = TRUE;
            $data['top_menu'] = "mgt_promocode";
            $data['sub_menu'] = "dash";
            $data['pagetitle'] = "E-scooter";
            $this->load->view('basetemplate', $data);
        } else {
            redirect('login');
        }
    }

    public function changeStatus($id, $status) {
        if ($this->session->id) {
            if ($this->Promocode_model->updateStatus($id, $status)) {
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                redirect($_SERVER['HTTP_REFERER']);
            }
        } else {
            $this->load->view('login');
        }
    }

    public function remove($id, $value) {
        if ($this->session->id) {
            if ($this->Promocode_model->removeRecord($id, $value)) {
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                redirect($_SERVER['HTTP_REFERER']);
            }
        } else {
            $this->load->view('login');
        }
    }

    public function add() {
        if ($this->session->id) {
            $config = array(
                array(
                    'field' => 'field_title',
                    'label' => 'Title',
                    'rules' => 'trim|required',
                ),
                array(
                    'field' => 'field_desc',
                    'label' => 'Description',
                    'rules' => 'trim|required',
                ),
                array(
                    'field' => 'field_price',
                    'label' => 'Percentage',
                    'rules' => 'trim|required|alpha_numeric',
                ),
                array(
                    'field' => 'field_code',
                    'label' => 'Promo Code',
                    'rules' => 'trim|required|alpha_numeric|min_length[6]|max_length[10]|callback_check_isUnique_code',
                ),
                array(
                    'field' => 'field_startDate',
                    'label' => 'Start Date',
                    'rules' => 'trim|required',
                ),
                array(
                    'field' => 'field_endDate',
                    'label' => 'End Date',
                    'rules' => 'trim|required',
                ),
            );

            if (empty($_FILES['field_image'])) {
                $config[] = array(
                    'field' => 'field_image',
                    'label' => 'Image',
                    'rules' => 'trim|required',
                );
            }
            if (empty($_FILES['field_banner'])) {
                $config[] = array(
                    'field' => 'field_banner',
                    'label' => 'Banner',
                    'rules' => 'trim|required',
                );
            }

            $this->form_validation->set_rules($config);

            if ($this->form_validation->run() == FALSE) {
                $errors = validation_errors();
                $data = array("status" => 400, "message" => nl2br(strip_tags($errors)));
            } else {
                $data = $this->Promocode_model->addRecord($this->input->post());
            }
            echo json_encode($data);
        } else {
            echo json_encode(array("status" => 400, "message" => "Please login again"));
        }
    }

    public function edit() {
        if ($this->session->id) {
            $config = array(
                array(
                    'field' => 'field_edit_title',
                    'label' => 'Title',
                    'rules' => 'trim|required',
                ),
                array(
                    'field' => 'field_edit_desc',
                    'label' => 'Description',
                    'rules' => 'trim|required',
                ),
                array(
                    'field' => 'field_edit_price',
                    'label' => 'Percentage',
                    'rules' => 'trim|required|alpha_numeric',
                ),
                array(
                    'field' => 'field_edit_code',
                    'label' => 'Promo Code',
                    'rules' => 'trim|required|alpha_numeric|min_length[6]|max_length[10]|callback_check_isUnique_code[' . $this->input->post('edit_id') . ']',
                ),
                array(
                    'field' => 'field_edit_startDate',
                    'label' => 'Start Date',
                    'rules' => 'trim|required',
                ),
                array(
                    'field' => 'field_edit_endDate',
                    'label' => 'End Date',
                    'rules' => 'trim|required',
                ),
                array(
                    'field' => 'edit_id',
                    'label' => 'Row ID',
                    'rules' => 'trim|required',
                ),
            );

            $this->form_validation->set_rules($config);

            if ($this->form_validation->run() == FALSE) {
                $errors = validation_errors();
                $data = array("status" => 400, "message" => nl2br(strip_tags($errors)));
            } else {
                $data = $this->Promocode_model->updateRecord($this->input->post());
            }
            echo json_encode($data);
        } else {
            echo json_encode(array("status" => 400, "message" => "Please login again"));
        }
    }

    public function check_isUnique_code($str, $row = false) {
        $where = array(
            "promoCode" => strtoupper($str),
            "isDeleted" => NOTDELETED,
        );

        if ($row !== false) {
            $where = array_merge($where, array("id!=" => $row));
        }
        $rows = $this->db->from($this->db->dbprefix('offers'))
                        ->where($where)
                        ->get()->num_rows();

        if ($rows) {
            $this->form_validation->set_message('check_isUnique_code', 'This {field} already exists');
            return FALSE;
        } else {
            return TRUE;
        }
    }

}
