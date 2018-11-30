<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Manuals_model extends CI_Model {

    function getManualsList() {

        $results = $this->db->select("*")
                        ->from($this->db->dbprefix('manuals'))
                        ->where("isDeleted", NOTDELETED)
                        ->order_by('id', 'DESC')
                        ->get()->result_array();

        if ($results) {
            foreach ($results as $key => $result) {
                $results[$key]['filePath'] = base_url("resource/files/{$result['filePath']}");
            }
        }
        return $results;
    }

    function addManuals($data1) {
        $res = $this->db->insert('manuals', $data1);
        if ($res) {
            $this->session->set_flashdata('success', "New manuals added successfully");
            return $data = array('status' => 200, 'message' => 'New manuals added successfully');
        } else {
            $this->session->set_flashdata('error', "Action not performed");
            return $data = array('status' => 400, 'message' => 'Action not performed');
        }
    }

    function deleteManuals($manualsId, $value) {
        $where = array(
            "id" => $manualsId,
        );
        $updateArray = array(
            'isDeleted' => $value,
        );
        $update = $this->db->update($this->db->dbprefix('manuals'), $updateArray, $where);
        if ($update) {
            $this->session->set_flashdata('success', "Manuals deleted successfully");
            return true;
        } else {
            $this->session->set_flashdata('error', "Action not performed");
            return false;
        }
    }

}
