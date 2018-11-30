<?php

class ScooterIssue_model extends CI_Model {

    public function __construct() {
        $this->load->database();
        $this->load->model('api/Common_model', 'Common_model', true);
        $this->load->helper('url');
    }

    function scooterStartIssueQuestion() {
        $question = $this->db->get_where('scooter_start_issue_question', array("status" => ACTIVE, "isDeleted" => NOTDELETED))->result_array();
        if ($question) {
            foreach ($question as $key => $question) {
                $data[] = array("id" => $question['id'], "question" => $question['name']);
            }
            return array("status" => 200, "message" => "Scooter start issue question!", "info" => $data);
        } else {
            return array("status" => 400, "message" => "Not availble any question!");
        }
    }

    function scooterStartIssueQuestionOption() {
        if (!empty($this->input->post())) {
            if (empty($this->input->post('questionId'))) {
                return array("status" => 400, "message" => "Please provide question id.");
            }
            $option = $this->db->get_where('scooter_issue_option', array("questionId" => $this->input->post('questionId'), "status" => ACTIVE, "isDeleted" => NOTDELETED))->result_array();
            if ($option) {
                foreach ($option as $key => $option) {
                    $data[] = array("id" => $option['id'], "questionId" => $option['questionId'], "option" => $option['name']);
                }
                return array("status" => 200, "message" => "Scooter start issue question!", "info" => $data);
            } else {
                return array("status" => 400, "message" => "Not availble any option!");
            }
        } else {
            return array("status" => 400, "message" => "Invalid post. Please try later!");
        }
    }

    function scooterStartIssueComment() {
        
        if (!empty($this->input->post())) {
            
            if (empty($this->input->post('userId')) && empty($this->input->post('scooterNumber'))  && empty($this->input->post('scooterNumber'))) {
                return array("status" => 400, "message" => "Please provide scooter number!");
            }
             
            $scooterDetails  = $this->Common_model->chkScooterNumber($this->input->post('scooterNumber'));
            
            if ($scooterDetails) {
                
                
                $reserveId = html_escape($this->input->post('reserveId'));
                $userId = html_escape($this->input->post('userId'));

                $where =  array("id" => $reserveId, "userId" => $userId);
                $scooter = $this->db->get_where('scooter_reserve', $where)->row_array();
                if ($scooter['rideStatus'] == RIDECOMPLETE) {
                    $data = $this->getBillingDetails($userId, $reserveId);    
                    return  array("status" => 101, "message" => "Ride Stopped.", "info" => $data, "scooterStatus"=> RIDECOMPLETE);
                }else if( $scooter['rideStatus'] == RIDECANCEL){
                    return  array("status" => 101, "message" => "Scooter reservation is cancelled.", "scooterStatus"=> RIDECANCEL);
                }    

                $data = array(
                    'userId' => $this->input->post('userId'),
                    'questionId' => $this->input->post('questionId'),
                    'scooterNumber' => $this->input->post('scooterNumber'),
                    'email' => $this->input->post('email'),
                    'selectOption' => $this->input->post('selectOption'),
                    'comment' => $this->input->post('comment'),
                    'status' => ACTIVE,
                    'isDeleted' => NOTDELETED,
                    'createdDate' => date('Y-m-d H:i:s'),
                );
                $imageArray = array();
                if (isset($_FILES) && !empty($_FILES)) {
                    $imageCount = 4;
                    for ($i = 1; $i <= $imageCount; $i++) {
                        if (isset($_FILES['image' . $i]) && !empty($_FILES['image' . $i])) {
                            $fileUploadPath = './resource/user_photos/';
                            $fileRes = $this->Common_model->dofileUpload('image' . $i, $fileUploadPath);
                            if (is_array($fileRes) && !empty($fileRes)) {
                                if ($fileRes['status'] == 200) {
                                    $imageArray['image_' . $i] = base_url() . 'resource/user_photos/' . $fileRes['data'];
                                } else {
                                    return $fileRes;
                                }
                            }
                        }
                    }
                }
                $alldata = array_merge($data, $imageArray);

                if ($this->db->insert('scooter_start_issue_comment', $alldata)) {
                    return array("status" => 200, "message" => "Thanks for reporting scooter issue");
                } else {
                    return array("status" => 400, "message" => "Report issue failed. Please try later!");
                }
            }else{
                return array("status" => 400, "message" => "Entered scooter number does not exist. Please provide valie scooter number!"); 
            }
        } else {
            return array("status" => 400, "message" => "Invalid post. Please try later!");
        }
        
    }

}

?>