<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class UserMaster_model extends CI_Model {

    public function __construct() {
        $this->load->database();
        $this->load->model('Common_model', 'Common_model', true);
    }

    function getUserList() {
        $this->db->select("*");
        $this->db->from($this->db->dbprefix('user_master'));
        $this->db->where("role", USER);
        $this->db->where("isDeleted", NOTDELETED);
        $this->db->order_by('id', 'DESC');
        return $this->db->get()->result_array();
    }

    function getUserDeatils($id) {
        $userId = decode($id);
        $user_profile = $this->db->get_where('user_master', array("id" => $userId, "isDeleted" => NOTDELETED))->row_array();
        $rideDetails = $this->db->query("SELECT es_scooter_reserve.*,es_bill_summary.totalBill FROM `es_scooter_reserve` inner join es_bill_summary ON es_scooter_reserve.id=es_bill_summary.reserveId WHERE es_scooter_reserve.userId='" . $userId . "' and (es_scooter_reserve.rideStatus='" . RIDECANCEL . "' OR es_scooter_reserve.rideStatus='" . RIDECOMPLETE . "')")->result_array();
        $summary = $this->db->query("SELECT sum(runningTime) as totalRunningTime,sum(runningSecond) as totalRunningSecond,sum(runningDistance) as totalRunningDistance FROM `es_bill_summary` WHERE userId='" . $userId . "'");
        $summary = $summary->row_array();
        if ($summary['totalRunningDistance'] == '' || empty($summary['totalRunningDistance'])) {
            $distance = 0 . ' Km';
        } else {
            $distance = $summary['totalRunningDistance'] . ' Km';
        }
        $minutes = $summary['totalRunningTime'];
        $second = $summary['totalRunningSecond'];
        $time = $this->Common_model->convertMinutesToHrs($minutes, $second);

        if (is_array($user_profile)) {
            return $data = array("userId" => $user_profile['id'], "userName" => $user_profile['userName'], "email" => $user_profile['email'], "mobile" => $user_profile['mobile'], "profileImage" => $user_profile['profileImage'], "location" => $user_profile['location'], "status" => $user_profile['status'], "totalRunningTime" => $time, "totalRunningDistance" => $distance, "rideDetails" => $rideDetails);
        }
    }

    function getDeatils($reserveId) {

        $rideDetails = $this->db->select("sc.*, bs.totalBill, "
                                . "IFNULL(usr.rating, 0) as rating, usr.comment, "
                                . "usr.image1, usr.image2, "
                                . "usr.image3, usr.image4 ")
                        ->from($this->db->dbprefix('scooter_reserve') . " AS sc")
                        ->join($this->db->dbprefix('bill_summary') . " AS bs", "sc.id=bs.reserveId", "INNER")
                        ->join($this->db->dbprefix('user_scooter_rating') . " AS usr", "sc.id=usr.reverseId", "LEFT")
                        ->where("sc.id", $reserveId)
                        ->get()->row_array();
        return array(
            "userId" => $rideDetails['id'],
            "scooterNumber" => $rideDetails['scooterNumber'],
            "startLocation" => $rideDetails['startLocation'],
            "endLocation" => $rideDetails['endLocation'],
            "startDate" => $rideDetails['startDate'],
            "startTime" => $rideDetails['startTime'],
            "endDate" => $rideDetails['endDate'],
            "endTime" => $rideDetails['endTime'],
            "totalBill" => $rideDetails['totalBill'],
            "comment" => $rideDetails['comment'],
            "rating" => $rideDetails['rating'],
            "image1" => $rideDetails['image1'],
            "image2" => $rideDetails['image2'],
            "image3" => $rideDetails['image3'],
            "image4" => $rideDetails['image4']
        );
    }

    function chkUserStatus($userId) {
        $chkstatus = $this->db->query("SELECT * FROM `es_scooter_parking` where (reserveUserId='" . $userId . "' and scooterStatus='" . ACTIVE . "') or (reserveUserId='" . $userId . "' and isUnderMaint='" . ACTIVE . "')")->row_array();
        if ($chkstatus) {
            if ($chkstatus['isLockUnlock'] == ISUNLOCK) {
                return $data = array('status' => 400, 'message' => 'This user on ride. Please stop ride .', 'type' => 'ride');
            } else if ($chkstatus['isUnderMaint'] == ACTIVE) {
                return $data = array('status' => 400, 'message' => 'This user under mainatance scooter. Please reassigne another.', 'type' => 'underMaint');
            } else {
                return $data = array('status' => 400, 'message' => 'This user reserve sooter. Please reserveatin cancelled .', 'type' => 'reserve');
            }
        } else {
            return $data = array('status' => 200, 'message' => '');
        }
    }

    function changeUserStatus($userId, $value) {
        $where = array(
            "id" => $userId,
        );
        $updateArray = array(
            "status" => $value,
        );
        $update = $this->db->update('user_master', $updateArray, array('id' => $userId));
        if ($update) {
            if ($value == 1) {
                $this->session->set_flashdata('success', "User unblocked successfully");
            } else {
                $this->session->set_flashdata('success', "User blocked successfully");
            }
            return true;
        } else {
            $this->session->set_flashdata('error', "Action not performed.Please try later!");
            return false;
        }
    }
    function removeRecord($userId, $value) {
        $where = array(
            "id" => $userId,
        );
        $updateArray = array(
            "isDeleted" => $value,
        );
        $update = $this->db->update('user_master', $updateArray, array('id' => $userId));
        if ($update) {
            $this->session->set_flashdata('success', "User removed successfully");
            return true;
        } else {
            $this->session->set_flashdata('error', "Action not performed.Please try later!");
            return false;
        }
    }

    public function getFeedbacksListByUser($user = false) {
        if ($user !== false) {

            $where = array(
                "userId" => $user,
                "isDeleted" => NOTDELETED,
            );

            $results = $this->db->select("id, comment, createdDate")
                            ->from($this->db->dbprefix('user_feedback'))
                            ->where($where)
                            ->get()->result();
            return $results;
        } else {
            return array();
        }
    }

}
