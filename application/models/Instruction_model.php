<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Instruction_model extends CI_Model {

    function getInstructionList() {
        $results = $this->db->select("*")
                        ->from($this->db->dbprefix('instruction'))
                        ->where("isDeleted", NOTDELETED)
                        ->order_by('id', 'DESC')
                        ->get()->row_array();
        if ($results) {
            $results['filePath'] = base_url("resource/files/{$results['filePath']}");
        }
        return $results;
    }

    function addInstruction($data) {
        $chk = $this->db->select("*")
                        ->from($this->db->dbprefix('instruction'))
                        ->where("id", '1')
                        ->get()->row_array();
        if (isset($chk)) {
            $where = array(
                "id" => '1',
            );
            $updateArray = array(
                'isDeleted' => $data,
            );
            $update = $this->db->update($this->db->dbprefix('instruction'), $data, $where);
        } else {
            $update = $this->db->insert('instruction', $data);
        }
        if ($update) {
            $this->session->set_flashdata('success', "Updated instruction  successfully");
            return $data = array('status' => 200, 'message' => 'Updated instruction successfully');
        } else {
            $this->session->set_flashdata('error', "Action not performed");
            return $data = array('status' => 400, 'message' => 'Action not performed');
        }
    }

    function deleteInstruction($instructionId, $value) {
        $where = array(
            "id" => $instructionId,
        );
        $updateArray = array(
            'isDeleted' => $value,
        );
        $update = $this->db->update($this->db->dbprefix('instruction'), $updateArray, $where);
        if ($update) {
            $this->session->set_flashdata('Success', "Instruction deleted successfully");
            return true;
        } else {
            $this->session->set_flashdata('error', "Action not performed");
            return false;
        }
    }

}
