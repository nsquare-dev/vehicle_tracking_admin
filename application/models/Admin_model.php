<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model {

    public function __construct() {

        parent::__construct();

        $this->load->model('Scooter_model');
        $this->load->helper('common_helper');
    }

    function add($data) {
        unset($data['rpassword']);
        unset($data['tnc']);
        //print_r($data);
        $this->db->insert('admin_details', $data);
        if ($this->db->insert_id()) {
            $data = $this->db->get_where('admin_details', array('id' => $this->db->insert_id()))->row_array();
            //$this->session->set_userdata('some_name', 'some_value');
            $this->session->set_userdata($data);
            return true;
        } else
            return false;
    }

    function login($data) {

        if ($data['username'] == '') {
            $this->session->set_flashdata('wrong', "Please enter email id.");
            return FALSE;
        } else if ($data['password'] == '') {
            $this->session->set_flashdata('wrong', "Please enter password.");
            return FALSE;
        } else {


            //unset($data['remember']);

            $result = $this->db->get_where('admin_details', array('email LIKE BINARY' => $data['username'], 'password' => encode($data['password'])))->row_array();

            if (!empty($result)) {
                $this->session->set_userdata($result);
                $this->session->set_flashdata('success', "You are successfully logged in");
                return true;
            } else {
                $this->session->set_flashdata('wrong', "Invalid credential details. Please try later!");
                return FALSE;
            }
        }
    }

    function editprofile($data) {

        $config = array(
            array(
                'field' => 'name',
                'label' => 'Name',
                'rules' => 'required|alpha_numeric_spaces'
            ),
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'required|valid_email'
            ),
            array(
                'field' => 'phone',
                'label' => 'Phone',
                'rules' => 'required|numeric|min_length[8]|max_length[8]'
            )
        );
        
        $this->form_validation->set_rules($config);
        
        if ($this->form_validation->run() == FALSE) {
            $errors = validation_errors(); 
            $this->session->set_flashdata('error', nl2br(strip_tags($errors)));
            return false;
        }
        
        $this->db->update('admin_details', $data, array('id' => $this->session->id));
        $data = $this->db->get_where('admin_details', array('id' => $this->session->id))->row_array();
        $this->session->set_userdata($data);
        $this->session->set_flashdata('success', "User profile update successfully!");
        return true; 
    }

    function chnagepassword($data) {
        if ($data['password_old'] == '') {
            return $data = array('status' => 400, 'message' => 'Please enter old password', 'type' => 'oldpassword');
        } else if ($data['password'] == '') {
            return $data = array('status' => 400, 'message' => 'Please enter new password', 'type' => 'newpassword');
        } else if ($data['rpassword'] == '') {
            return $data = array('status' => 400, 'message' => 'Please enter re-type new password', 'type' => 'rpassword');
        } else {
            $chk = $this->db->get_where('admin_details', array('id' => $this->session->id))->row_array();
            if ($chk['password'] == encode($data['password_old'])) {
                if ($data['password'] == $data['rpassword']) {
                    $updateArray = array(
                        'password' => encode($data['password']),
                    );
                    // $update = $this->db->update($this->db->dbprefix('parking'), $updateArray, $where);
                    $update = $this->db->update('admin_details', $updateArray, array('id' => $this->session->id));
                    if ($update) {
                        $this->session->set_flashdata('success', "Password Change succefully");
                        return $data2 = array('status' => 200, 'message' => 'Password change successfully');
                    } else {
                        $this->session->set_flashdata('error', "Action not performed");
                        return $data2 = array('status' => 400, 'message' => 'Action not performed');
                    }
                } else {
                    return $data = array('status' => 400, 'message' => 'Confirm password not matching', 'type' => 'rpassword');
                }
            } else {
                return $data = array('status' => 400, 'message' => 'Please enter old password correct', 'type' => 'oldpassword');
            }
        }
    }

    function chnageprofile($file) {
        $this->db->update('admin_details', array('image' => $file), array('id' => $this->session->id));
        $this->session->set_flashdata('success', "Changed user profile Successfully!");
        return TRUE;
    }

    function getCounter() {
        $user = $this->db->query("SELECT count(id) as totalUser FROM `es_user_master` where role='" . USER . "' and isDeleted='" . NOTDELETED . "'")->row_array();
        $totalMaintUser = $this->db->query("SELECT count(id) as totalMaintUser FROM `es_user_master` where role='" . MAINTENANCE . "' and isDeleted='" . NOTDELETED . "'")->row_array();
        $totalScooter = $this->db->query("SELECT count(id) as totalScooter FROM `es_scooter_parking` where isDeleted='" . NOTDELETED . "'")->row_array();
        $totalOnRideScooter = $this->db->query("SELECT count(id) as totalOnRideScooter FROM `es_scooter_parking` where isDeleted='" . NOTDELETED . "' and isLockUnlock='" . ISUNLOCK . "'")->row_array();
        $totalParking = $this->db->query("SELECT count(id) as totalParking FROM `es_parking` where isDeleted='" . NOTDELETED . "'")->row_array();
        //print_r($user);die;
        return $data = array("totalUser" => $user['totalUser'], "totalMaintUser" => $totalMaintUser['totalMaintUser'], "totalScooter" => $totalScooter['totalScooter'], "totalParking" => $totalParking['totalParking'], "totalOnRideScooter" => $totalOnRideScooter['totalOnRideScooter']);
    }

    public function getLatestNotifications() {

        $where = array(
            "isRead" => NOTACTIVE,
            "isDeleted" => NOTDELETED,
        );

        $this->db->where($where);
        $this->db->from($this->db->dbprefix('scooter_notifications'));
        $total_rows = $this->db->count_all_results();

        $rows = $this->db->select("message, DATE_FORMAT( createdOn, '%h:%i %p') as createdOn", false)
                        ->from($this->db->dbprefix('scooter_notifications'))
                        ->where($where)
                        ->order_by("createdOn", "DESC")
                        ->limit(10)
                        ->get()->result();

        return array("status" => 200, "tota_count" => $total_rows, "data" => $rows);
    }

    public function getAllNotifications() {

        $where = array(
            "isRead" => NOTACTIVE,
            "isDeleted" => NOTDELETED,
        );
        $updateArray = array(
            "isRead" => ACTIVE,
            "updatedOn" => date("Y-m-d H:i:s"),
        );
        $this->db->update($this->db->dbprefix('scooter_notifications'), $updateArray, $where);

        $where = array(
            "isDeleted" => NOTDELETED,
        );

        return $this->db->select("reservedScooter, reservedTracker, message, DATE_FORMAT( createdOn, '%Y/%m/%d %h:%i:%s %p') as createdOn", false)
                        ->from($this->db->dbprefix('scooter_notifications'))
                        ->where($where)
                        ->order_by("createdOn", "DESC")
                        ->get()->result();
    }

}
