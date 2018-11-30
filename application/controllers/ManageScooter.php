<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ManageScooter extends CI_Controller {

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

        $this->load->model('Scooter_model');
        $this->load->model('Map_model');
        $this->load->helper('common_helper');
    }

    public function index() {
        if ($this->session->id) {
            $data['scooter'] = $this->Scooter_model->getScooterList();
            $data['header'] = TRUE;
            $data['sidebar'] = TRUE;
            $data['_view'] = "scooter_master/view_scooter";
            $data['footer'] = TRUE;
            $data['top_menu'] = "view_scooter";
            $data['pagetitle'] = "Scooter";
            $this->load->view('basetemplate', $data);
        } else
            $this->load->view('login');
    }

    public function addScooter() {
        if ($this->session->id) {
            $config = array(
                array(
                    'field' => 'scooteNumber',
                    'label' => 'Scooter Number',
                    'rules' => 'trim|required|alpha_numeric|callback_check_isUniqueScooter',
                ),
                array(
                    'field' => 'tarckId',
                    'label' => 'Tracker ID',
                    'rules' => 'trim|required|callback_check_isUniqueTracker',
                ),
                array(
                    'field' => 'location',
                    'label' => 'Location',
                    'rules' => 'trim|required',
                ),
            );

            $this->form_validation->set_rules($config);

            if ($this->form_validation->run() == FALSE) {
                $errors = validation_errors();
                $data = array("status" => 400, "message" => nl2br(strip_tags($errors)));
            } else {
                $data = $this->Scooter_model->addScooter($this->input->post());
            }
            echo json_encode($data);
        } else {
            echo json_encode(array("status" => 400, "message" => "Please login again"));
        }
    }

    public function editScooter() {
        if ($this->session->id) {
            $config = array(
                array(
                    'field' => 'edit_scooteNumber',
                    'label' => 'Scooter Number',
                    'rules' => 'trim|required|alpha_numeric|callback_check_isUniqueScooter[' . $this->input->post('edit_id') . ']',
                ),
                array(
                    'field' => 'edit_tarckId',
                    'label' => 'Tracker ID',
                    'rules' => 'trim|required|callback_check_isUniqueTracker[' . $this->input->post('edit_id') . ']',
                ),
                array(
                    'field' => 'edit_location',
                    'label' => 'Location',
                    'rules' => 'trim|required',
                ),
                array(
                    'field' => 'edit_id',
                    'label' => 'Row ID',
                    'rules' => 'trim|required|numeric',
                ),
            );

            $this->form_validation->set_rules($config);

            if ($this->form_validation->run() == FALSE) {
                $errors = validation_errors();
                $data = array("status" => 400, "message" => nl2br(strip_tags($errors)));
            } else {
                $data = $this->Scooter_model->updateScooter($this->input->post());
            }
            echo json_encode($data);
        } else {
            echo json_encode(array("status" => 400, "message" => "Please login again"));
        }
    }

    public function removeScooter($scooterId, $value) {
        if ($this->session->id) {
            if ($this->Scooter_model->removeScooter($scooterId, $value)) {
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                redirect($_SERVER['HTTP_REFERER']);
            }
        } else {
            $this->load->view('login');
        }
    }

    public function addParking() {

        if ($this->session->id) {
            $config = array(
                array(
                    'field' => 'parkingName',
                    'label' => 'Name',
                    'rules' => 'trim|required',
                ),
                array(
                    'field' => 'parkingLocation',
                    'label' => 'Location',
                    'rules' => 'trim|required|callback_check_isUniqueParking',
                    'errors' => array(
                        'is_unique' => 'Enterd %s is already allocated.',
                    ),
                ),
            );

            $this->form_validation->set_rules($config);

            if ($this->form_validation->run() == FALSE) {
                $errors = validation_errors();
                $data = array("status" => 400, "message" => nl2br(strip_tags($errors)));
            } else {
                $data = $this->Scooter_model->insertParking($this->input->post());
            }

            echo json_encode($data);
        } else {
            echo json_encode(array("status" => 400, "message" => "Please login again"));
        }
    }

    public function updateParking() {
        if ($this->session->id) {

            $config = array(
                array(
                    'field' => 'edit_id',
                    'label' => 'Row ID',
                    'rules' => 'trim|required',
                ),
                array(
                    'field' => 'edit_parkingName',
                    'label' => 'Name',
                    'rules' => 'trim|required',
                ),
                array(
                    'field' => 'edit_parkingLocation',
                    'label' => 'Location',
                    'rules' => 'trim|required|callback_check_isUniqueParking[' . $this->input->post('edit_id') . ']',
                    'errors' => array(
                        'is_unique' => 'Enterd %s is already allocated.',
                    ),
                ),
            );

            $this->form_validation->set_rules($config);

            if ($this->form_validation->run() == FALSE) {
                $errors = validation_errors();
                $data = array("status" => 400, "message" => nl2br(strip_tags($errors)));
            } else {
                $data = $this->Scooter_model->doUpdateParking($this->input->post());
            }
            echo json_encode($data);
        } else {
            echo json_encode(array("status" => 400, "message" => "Please login again"));
        }
    }

    public function view_scooter_details($scooterNumber) {
        if ($this->session->id) {
            $data['scooterDetails'] = $this->Scooter_model->getScooterDeatils($scooterNumber);
            $data['header'] = TRUE;
            $data['sidebar'] = TRUE;
            $data['_view'] = "scooter_master/view_scooter_details";
            $data['footer'] = TRUE;
            $data['top_menu'] = "view_scooter";
            $data['pagetitle'] = "Scooter Details";
            $this->load->view('basetemplate', $data);
        } else {
            $this->load->view('login');
        }
    }
    

    public function view_detail($reserveId) {
        if ($this->session->id) {
            $data = $this->Scooter_model->getDeatils($reserveId);
            echo json_encode($data);
        } else {
            $this->load->view('login');
        }
    }
    /*
     * Map History
     */
    public function view_tracking_map($reserveId,$userId) {
        if ($this->session->id) {      // $data = $this->data;
            $data['userDetails'] = $this->Map_model->getRunningScooterDetails($reserveId,$userId);
            $data['header'] = TRUE;
            $data['sidebar'] = TRUE;
            $data['_view'] = "scooter_master/view_tracking_map";
            $data['footer'] = TRUE;
            $data['top_menu'] = "view_scooter";
            $data['pagetitle'] = "Tracking History";
            $this->load->view('basetemplate', $data);
        } else{
            $this->load->view('login');
        }
    }
    //parking function
    public function view_parking_list() {
        if ($this->session->id) {
            $data['parking'] = $this->Scooter_model->getParkingList();
            $data['header'] = TRUE;
            $data['sidebar'] = TRUE;
            $data['_view'] = "scooter_master/view_parking";
            $data['footer'] = TRUE;
            $data['top_menu'] = "view_parking";
            $data['pagetitle'] = "Parking";
            $this->load->view('basetemplate', $data);
        } else
            $this->load->view('login');
    }

    public function parkingStatus($parkingId, $value) {
        if ($this->session->id) {
            if ($this->Scooter_model->changeParkingStatus($parkingId, $value)) {
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                redirect($_SERVER['HTTP_REFERER']);
            }
        } else {
            $this->load->view('login');
        }
    }

    public function removeParking($parkingId, $value) {
        if ($this->session->id) {
            if ($this->Scooter_model->removeParking($parkingId, $value)) {
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                redirect($_SERVER['HTTP_REFERER']);
            }
        } else {
            $this->load->view('login');
        }
    }

    public function chkScooterStatus($scooterId) {
        if ($this->session->id) {
            $data = $this->Scooter_model->chkScooterStatus($scooterId);
            echo json_encode($data);
        } else {
            $this->load->view('login');
        }
    }

    public function scooterStatus($scooterId, $value) {
        if ($this->session->id) {
            if ($this->Scooter_model->changeScooterStatus($scooterId, $value)) {
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                redirect($_SERVER['HTTP_REFERER']);
            }
        } else {
            $this->load->view('login');
        }
    }

    public function view_restricted_parking_list() {
        if ($this->session->id) {
            $data['parking'] = $this->Scooter_model->getRestrictedParkingList();
            $data['header'] = TRUE;
            $data['sidebar'] = TRUE;
            $data['_view'] = "scooter_master/view_restricted_parking";
            $data['footer'] = TRUE;
            $data['top_menu'] = "view_restricted_parking";
            $data['pagetitle'] = "Restricted Parking";
            $this->load->view('basetemplate', $data);
        } else
            $this->load->view('login');
    }

    public function areaStatus($parkingId, $value) {
        if ($this->session->id) {
            if ($this->Scooter_model->changeAreaStatus($parkingId, $value)) {
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                redirect($_SERVER['HTTP_REFERER']);
            }
        } else {
            $this->load->view('login');
        }
    }

    public function addArea() {

        if ($this->session->id) {
            $config = array(
                array(
                    'field' => 'parkingName',
                    'label' => 'Name',
                    'rules' => 'trim|required',
                ),
                array(
                    'field' => 'parkingLocation',
                    'label' => 'Location',
                    'rules' => 'trim|required|callback_check_isUniqueRestrictedParking',
                    'errors' => array(
                        'is_unique' => 'Enterd %s is already allocated.',
                    ),
                ),
            );

            $this->form_validation->set_rules($config);

            if ($this->form_validation->run() == FALSE) {
                $errors = validation_errors();
                $data = array("status" => 400, "message" => nl2br(strip_tags($errors)));
            } else {
                $data = $this->Scooter_model->addArea($this->input->post());
            }
            echo json_encode($data);
        } else {
            echo json_encode(array("status" => 400, "message" => "Please login again"));
        }
    }
    
    
    public function updateArea() {

        if ($this->session->id) {
            $config = array(
                array(
                    'field' => 'edit_id',
                    'label' => 'Row ID',
                    'rules' => 'trim|required',
                ),
                array(
                    'field' => 'edit_parkingName',
                    'label' => 'Name',
                    'rules' => 'trim|required',
                ),
                array(
                    'field' => 'edit_parkingLocation',
                    'label' => 'Location',
                    'rules' => 'trim|required|callback_check_isUniqueRestrictedParking['.$this->input->post('edit_id').']',
                    'errors' => array(
                        'is_unique' => 'Enterd %s is already allocated.',
                    ),
                ),
            );

            $this->form_validation->set_rules($config);

            if ($this->form_validation->run() == FALSE) {
                $errors = validation_errors();
                $data = array("status" => 400, "message" => nl2br(strip_tags($errors)));
            } else {
                $data = $this->Scooter_model->updateArea($this->input->post());
                
            }
            echo json_encode($data);
        } else {
            echo json_encode(array("status" => 400, "message" => "Please login again"));
        }
    }

    public function areaRemove($parkingId, $value) {
        if ($this->session->id) {
            if ($this->Scooter_model->removeArea($parkingId, $value)) {
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                redirect($_SERVER['HTTP_REFERER']);
            }
        } else {
            $this->load->view('login');
        }
    }

    public function check_isUniqueScooter($str, $row = false) {

        $where = array(
            "scooterNumber" => strtoupper($str),
            "isDeleted" => NOTDELETED,
        );
        if ($row !== false) {
            $where = array_merge($where, array("id!=" => $row));
        }
        $rows = $this->db->from($this->db->dbprefix('scooter'))
                        ->where($where)
                        ->get()->num_rows();

        if ($rows) {
            $this->form_validation->set_message('check_isUniqueScooter', 'This {field} already exists');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function check_isUniqueTracker($str, $row = false) {
        $where = array(
            "tarckId" => strtoupper($str),
            "isDeleted" => NOTDELETED,
        );

        if ($row !== false) {
            $where = array_merge($where, array("id!=" => $row));
        }
        $rows = $this->db->from($this->db->dbprefix('scooter'))
                        ->where($where)
                        ->get()->num_rows();

        if ($rows) {
            $this->form_validation->set_message('check_isUniqueTracker', 'This {field} already exists');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function check_isUniqueParking($str, $row = false) {
        $where = array(
            "parkingLocation" => strtoupper($str),
            "isDeleted" => NOTDELETED,
        );

        if ($row !== false) {
            $where = array_merge($where, array("id!=" => $row));
        }
        $rows = $this->db->from($this->db->dbprefix('parking'))
                        ->where($where)
                        ->get()->num_rows();

        if ($rows) {
            $this->form_validation->set_message('check_isUniqueParking', 'This {field} already exists');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function check_isUniqueRestrictedParking($str, $row = false) {
        $where = array(
            "location" => strtoupper($str),
            "isDeleted" => NOTDELETED,
        );

        if ($row !== false) {
            $where = array_merge($where, array("id!=" => $row));
        }
        $rows = $this->db->from($this->db->dbprefix('scooter_restricted_area'))
                        ->where($where)
                        ->get()->num_rows();

        if ($rows) {
            $this->form_validation->set_message('check_isUniqueRestrictedParking', 'This {field} already exists');
            return FALSE;
        } else {
            return TRUE;
        }
    }
}
