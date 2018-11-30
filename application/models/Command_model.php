<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Command_model extends CI_Model {

    public function __construct() {
        $this->load->database();
        $this->load->model('Common_model', 'Common_model', true);
    }

    function getList() {
        return $this->db->select("id, title, description, command, code, syntax, example")
                        ->from($this->db->dbprefix('socket_command'))
                        ->order_by('title', 'ASC')
                        ->get()->result_array();
    }

    function add($insert_arr) {
        if ($this->db->insert($this->db->dbprefix('socket_command'), $insert_arr)) {
            return true;
        } else {
            return false;
        }
    }

    function edit($id) {
        $record = array();
        $record = $this->db->select('*')
                ->from($this->db->dbprefix('socket_command'))
                ->where('id', $id)
                ->get()
                ->row_array();
        return $record;
    }

    function update($updateArray, $id) {
        if ($this->db->update($this->db->dbprefix('socket_command'), $updateArray, array('id' => $id))) {
            return true;
        } else {
            return false;
        }
    }

    public function chk_add_command($command) {
        return $this->db->get_where('es_socket_command', array("command" => $command))->row_array();
    }

    public function chk_add_code($code) {
        return $this->db->get_where('es_socket_command', array("code" => $code))->row_array();
    }

    public function chk_update_command($command, $id) {
        return $this->db->get_where('es_socket_command', array("command" => $command, "id !=" => $id))->row_array();
    }

    public function chk_update_code($code, $id) {
        return $this->db->get_where('es_socket_command', array("code" => $code, "id !=" => $id))->row_array();
    }

    public function delete_record($id) {
        if ($this->db->delete('es_socket_command', ['id' => $id])) {
            return true;
        } else {

            return false;
        }
    }

    public function insert_command($data) {
        $arr = array();
        if ($this->db->insert($this->db->dbprefix('push_command'), $data)) {
            $arr['last_insertId'] = $this->db->insert_id();
            $arr['affectedRow'] = $this->db->affected_rows();
            $arr['last_query'] = $this->db->last_query();
            return $arr;
        } else {
            return $arr;
        }
    }

    public function update_command($update_arr, $id) {
        $this->db->update($this->db->dbprefix('push_command'), $update_arr, array('id' => $id));
    }

    public function insert_ack($data = array()) {
        if (!empty($data)) {
            return $this->db->insert($this->db->dbprefix('push_command'), $data);
        } else {
            return false;
        }
    }

    /*
     * Function: getTrackerList
     * Param: none
     * return : object array
     * Desc: Gets active tracker list
     */

    public function getTrackerList() {
        $where = array(
            "tarckId !=" => 'NULL',
            "isUnderMaint" => NOTUNDERMAINTAINANCE,
            "status" => ACTIVE,
            "isDeleted" => NOTDELETED,
        );
        $results = $this->db->select("scooterNumber, tarckId as tracker")
                        ->from($this->db->dbprefix("scooter_parking"))
                        ->where($where)
                        ->order_by('id', 'ASC')
                        ->get()->result_array();

        $collection = array(
            '' => '--SELECT--'
        );
        if ($results) {

            foreach ($results as $result) {
                $collection[$result['tracker']] = $result['scooterNumber'];
            }
        }

        return $collection;
    }

    public function submitCMD($post = array()) {
        try {

            if (is_array($post) && !empty($post)) {

                $config = array(
                    array(
                        'field' => 'field_action',
                        'label' => 'Value',
                        'rules' => 'required',
                        'errors' => array(
                            'required' => 'You must provide a %s.',
                        ),
                    ),
                    array(
                        'field' => 'field_tracker',
                        'label' => 'Scooter',
                        'rules' => 'required',
                        'errors' => array(
                            'required' => 'You must select a %s.',
                        ),
                    ),
                    array(
                        'field' => 'field_cmd',
                        'label' => 'Action',
                        'rules' => 'required',
                        'errors' => array(
                            'required' => 'You must select a %s.',
                        ),
                    ),
                );

                $this->form_validation->set_rules($config);

                if ($this->form_validation->run() == FALSE) {
                    $errors = validation_errors();
                    return array("status" => 400, "error" => $errors);
                } else {

                    $tracker = html_escape($post['field_tracker']);

                    $whereUpdate = array("trackerId" => $tracker, "isSent" => NOTSENT);
                    $updateArray = array("isSent" => UNREAD, "updateDate" => date("Y-m-d H:i:s"));
                    $this->db->update($this->db->dbprefix('sent_cmd'), $updateArray, $whereUpdate);


                    $insertArray = array(
                        "trackerId" => $tracker,
                        "cmd" => html_escape($post['field_action']),
                        "createdDate" => date("Y-m-d H:i:s")
                    );

                    if ($this->db->insert($this->db->dbprefix('sent_cmd'), $insertArray)) {
                        sleep(TIMEOUTSEC);
                        $row = $this->db->select("isSent")
                                        ->from($this->db->dbprefix('sent_cmd'))
                                        ->where("id", $this->db->insert_id())
                                        ->where("isSent", SENT)
                                        ->get()->row();

                        if ($row) {
                            return array("status" => 200, "message" => "Setting updated successfully!");
                        } else {

                            return array("status" => 200, "message" => "Setting not updated!");
                        }
                    } else {

                        return array("status" => 400, "message" => "Setting not added into queue!");
                    }
                }
            } else {
                return array("status" => 400, "message" => "Invalid Setting post");
            }
        } catch (Exception $e) {
            return array("status" => 400, "message" => $e->getMessage());
        }
    }

}
