<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Knowledge_model extends CI_Model {

    function getKnowledgeList() {
        $results = $this->db->select("*")
                ->from($this->db->dbprefix('knowledge_base'))
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
     function addKnowledge($data) {
        $res=$this->db->insert('knowledge_base', $data);
        if ($res) {
                $this->session->set_flashdata('success', "New knowledge base added successfully");
                return $data = array('status' => 200, 'message' => 'New knowledge base added successfully');
            } else {
                $this->session->set_flashdata('error', "Action not performed");
                return $data = array('status' => 400, 'message' => 'Action not performed');
            }
    }
    function deleteKnowledge($knowledgeId, $value) {
        $where = array(
            "id" => $knowledgeId,
        );
        $updateArray = array(
            'isDeleted' => $value,
        );
        $update = $this->db->update($this->db->dbprefix('knowledge_base'), $updateArray, $where);
        if ($update) {
            $this->session->set_flashdata('Success', "Knowledge base deleted successfully");
            return true;
        } else {
            $this->session->set_flashdata('error', "Action not performed");
            return false;
        }
    }

}
