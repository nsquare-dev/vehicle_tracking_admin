<?php

class MaintenanceKnowledgeBase_model extends CI_Model {

    public function __construct() {
        $this->load->database();
        $this->load->model('api/Common_model', 'Common_model', true);
    }

    function getknowledgeBaseList() {
        $results = $this->db->get_where('knowledge_base', array("isDeleted" => NOTDELETED))->result_array();
        if ($results) {
            $data = array();
            foreach ($results as $key => $result) {
                $data[] = array("id" => $result['id'], "name" => $result['name'], "filePath" => base_url("resource/files/{$result['filePath']}") );
            }
            return array("status" => 200, "message" => "Knowledge base list!", "info" => $data);
        } else {
            return array("status" => 400, "message" => "Not found any knowledge base", "info"=>array());
        }
    }

    function getInstruction() {
        $result = $this->db->get_where('instruction', array("id" => 1, "isDeleted" => NOTDELETED))->row_array();
        if ($result) {
            $data[] = array("id" => $result['id'], "instruction" => $result['instruction'], "filePath" => base_url("resource/files/{$result['filePath']}"));
            return array("status" => 200, "message" => "Instructions found", "info" => $data);
        } else {
            return array("status" => 400, "message" => "Not found any Instructions" ,"info"=>array());
        }
    }
     function getManualList() {
        $results = $this->db->get_where('manuals', array("isDeleted" => NOTDELETED))->result_array();
        $data = array();
        if ($results) {
            
            foreach ($results as $key => $result) {
                
                $data[] = array(
                        "id" => $result['id'], 
                        "manualCategory" => $result['manualCategory'], 
                        "name" => $result['name'], 
                        "filePath" => base_url("resource/files/{$result['filePath']}"),
                        "image" => base_url("resource/app_photos/{$result['image']}") ,
                    );
            }
            return array("status" => 200, "message" => "Manuals list!", "info" => $data);
        } else {
            return array("status" => 400, "message" => "Not found any manuals","info"=> $data);
        }
    }
}

?>