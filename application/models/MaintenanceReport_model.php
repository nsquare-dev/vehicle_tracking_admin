<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MaintenanceReport_model extends CI_Model {

    public function __construct() {
        $this->load->database();
        $this->load->model('Common_model', 'Common_model', true);
    }

    function getCompletedUserTaskList() {
        $user = $this->db->query("SELECT * FROM `es_user_master` WHERE `id` IN (SELECT DISTINCT  userId FROM `es_maintenance_under_scooter` WHERE maintStatus='" . MAINTCOMPLETE . "' and istaskcomplete='" . TASKCOMPLETEYES . "' and scooterStatus='".NOTACTIVE."')")->result_array();
        if ($user) {
            foreach ($user as $key => $user_profile) {
                //$count = $this->Common_model->UnderMaintUserDetails($user_profile['id']);
                $data[] = array("userId" => $user_profile['id'], "userName" => $user_profile['userName'], "email" => $user_profile['email'], "mobile" => $user_profile['mobile'], "profileImage" => $user_profile['profileImage'], "location" => $user_profile['location'], "status" => $user_profile['status']);
            }
            return $data;
        } else {
            return $data = array();
        }
    }

    function getUncompletedUserTaskList() {
        $user = $this->db->query("SELECT * FROM `es_user_master` WHERE `id` IN (SELECT DISTINCT  userId FROM `es_maintenance_under_scooter` WHERE maintStatus='" . MAINTCOMPLETE . "'and istaskcomplete='" . TASKCOMPLETENO . "' and scooterStatus='".NOTACTIVE."')")->result_array();
        if ($user) {
            foreach ($user as $key => $user_profile) {
                //$count = $this->Common_model->UnderMaintUserDetails($user_profile['id']);
                $data[] = array("userId" => $user_profile['id'], "userName" => $user_profile['userName'], "email" => $user_profile['email'], "mobile" => $user_profile['mobile'], "profileImage" => $user_profile['profileImage'], "location" => $user_profile['location'], "status" => $user_profile['status']);
            }
            return $data;
        } else {
            return $data = array();
        }
    }

    function getCompleteMaintUserDeatils($uid) {
        $userId = decode($uid);
        $user_profile = $this->db->get_where('user_master', array("id" => $userId, "isDeleted" => NOTDELETED))->row_array();
        $taskDetails = $this->db->query("SELECT es_maintenance_under_scooter.*,es_scooter_parking.id as scooterParkId ,es_scooter_parking.status as scooterStatus"
                . " FROM `es_maintenance_under_scooter` "
                . "RIGHT JOIN  es_scooter_parking on es_maintenance_under_scooter.scooterNumber=es_scooter_parking.scooterNumber "
                . "WHERE userId='{$userId}' and (maintStatus='" . MAINTCOMPLETE . "' and istaskcomplete='" . TASKCOMPLETEYES . "')")
                        ->result_array();
        $usertaskDetails = $this->Common_model->UnderMaintUserDetails2($userId,TASKCOMPLETEYES);
        $time = $this->Common_model->convertMinutesToHrs($usertaskDetails['totalMaintTime'], $usertaskDetails['totalMaintSecond']);
       if ($user_profile) {
            return $data = array(
                "userId" => $user_profile['id'],
                "userName" => $user_profile['userName'],
                "email" => $user_profile['email'], 
                "mobile" => $user_profile['mobile'], 
                "profileImage" => $user_profile['profileImage'], 
                "location" => $user_profile['location'], 
                "status" => $user_profile['status'], 
                "taskDetails" => $taskDetails,
                "usertaskDetails" => $usertaskDetails['totalCompletedTask'], 
                "totalTime" => $time);
        }
    }

    function getUncompleteMaintUserDeatils($uid) {
        $userId = decode($uid);
        $user_profile = $this->db->get_where('user_master', array("id" => $userId, "isDeleted" => NOTDELETED))->row_array();
        $where = array(
            "userId"=> $userId,
            "maintStatus"=> MAINTCOMPLETE,
            "istaskcomplete"=> TASKCOMPLETENO,
        );
        $result =  $this->db->select("id, scooterNumber, assignDate, scooterNumber, issueTitle")
                    ->from($this->db->dbprefix('maintenance_under_scooter'))
                    ->where($where)
                    ->get()->result_array();
        if ($result) {
            foreach ($result as $key => $task) {
                $this->db->select("*");
                $this->db->from($this->db->dbprefix('maintenance_under_scooter'));
                $this->db->where("scooterNumber", $task['scooterNumber']);
                $this->db->where("maintStatus", MAINTPENDING);
                $this->db->or_where('maintStatus', MAINTPROGRESS);
                $checkMaintUnder = $this->db->get()->row_array();
                if (!$checkMaintUnder) {
                    $taskDetails[] = array("id" => $task['id'], "assignDate" => $task['assignDate'], "scooterNumber" => $task['scooterNumber'], "issueTitle" => $task['issueTitle']);
                } else {
                    $taskDetails = array();
                }
            }
        } else {
            $taskDetails = array();
        }
        $usertaskDetails = $this->Common_model->UnderMaintUserDetails2($userId,TASKCOMPLETENO);
        $time = $this->Common_model->convertMinutesToHrs($usertaskDetails['totalMaintTime'], $usertaskDetails['totalMaintSecond']);
        //  print_r($usertaskDetails);die;
        if ($user_profile) {
            return $data = array("userId" => $user_profile['id'], "userName" => $user_profile['userName'], "email" => $user_profile['email'], "mobile" => $user_profile['mobile'], "profileImage" => $user_profile['profileImage'], "location" => $user_profile['location'], "status" => $user_profile['status'], "taskDetails" => $taskDetails, "usertaskDetails" => $usertaskDetails['totalCompletedTask'], "totalTime" => $time);
        }
    }

    function getDeatils($maintId) {
        $maintUnderDetails = $this->db->query("SELECT * FROM `es_maintenance_under_scooter` "
                . " LEFT JOIN es_maintenance_comment ON es_maintenance_under_scooter.id=es_maintenance_comment.maintId "
                . " WHERE es_maintenance_under_scooter.id='{$maintId}' ")->row_array();

        $time = $this->Common_model->convertMinutesToHrs($maintUnderDetails['totalMaintTime'], $maintUnderDetails['totalMaintSecond']);

        return $data = array(
            "userId" => $maintUnderDetails['id'], 
            "scooterNumber" => $maintUnderDetails['scooterNumber'], 
            "scooterLocation" => $maintUnderDetails['scooterLocation'], 
            "startDate" => $maintUnderDetails['progressStartDate'], 
            "startTime" => $maintUnderDetails['progressStartTime'], 
            "endTime" => $maintUnderDetails['progressEndTime'], 
            "issueTitle" => $maintUnderDetails['issueTitle'], 
            "timeSpent" => $time, 
            "comment" => $maintUnderDetails['comment'], 
            "image1" => $maintUnderDetails['image1'], 
            "image2" => $maintUnderDetails['image2'],
            "image3" => $maintUnderDetails['image3'], 
            "image4" => $maintUnderDetails['image4']);
    }
    
    function changeScooterStatus($scooterId, $value) {
        $scooter=$this->db->get_where('scooter_parking', array("id" => $scooterId))->row_array();
        $where = array(
            "id" => $scooterId,
        );
        $updateArray = array(
            'status' => $value,
        );
        $updateArray2 = array(
                "status" => ACTIVE,
                "isUnderMaint"=>NOTACTIVE
            );
        $this->db->update($this->db->dbprefix('scooter'), $updateArray, $where);
        $update = $this->db->update($this->db->dbprefix('scooter_parking'), $updateArray2, $where);
        /*
         * maintanace under status
         */
        $where2 = array(
            "scooterNumber" => $scooter['scooterNumber'],
            "maintStatus"=>MAINTCOMPLETE,
            "istaskcomplete"=>TASKCOMPLETEYES
        );
        $updateArray2 = array(
            "scooterStatus"=>ACTIVE
        );

        $this->db->update($this->db->dbprefix('maintenance_under_scooter'), $updateArray2, $where2);
        if ($update) {
            if ($value == ACTIVE) {
                $this->session->set_flashdata('success', "Scooter activated successfully");
            } else {
                $this->session->set_flashdata('success', "Scooter deactivated successfully");
            }
            return true;
        } else {
            $this->session->set_flashdata('error', "Action not performed");
            return false;
        }
    }

}
