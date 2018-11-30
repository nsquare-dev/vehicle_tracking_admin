<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Map_model extends CI_Model {

    public function __construct() {
        $this->load->database();
        $this->load->model('Common_model', 'Common_model', true);
    }

    function getScooter() {
        // $this->db->select("*");
        $this->db->select("*");
        $this->db->from($this->db->dbprefix('scooter_parking'));
        $this->db->where("isDeleted", NOTDELETED);
        $this->db->order_by('id', 'DESC');
        return $this->db->get()->result_array();
    }

    function getRunningScooter() {
        // $this->db->select("*");
        $this->db->select("r.*,m.id as uId,m.userName");
        $this->db->from($this->db->dbprefix('scooter_reserve') . " AS r");
        $this->db->join($this->db->dbprefix('user_master') . " AS m", "r.userId=m.id", "inner");
        $this->db->where("r.rideStatus", RIDERUNNING);
        $this->db->where("r.isLockUnlock", ISUNLOCK);
        $this->db->order_by('r.id', 'DESC');
        return $this->db->get()->result_array();
    }

    function getRunningScooterDetails($reserveId, $uid) {
        $user_profile = $this->db->get_where('user_master', array("id" => decode($uid), "isDeleted" => NOTDELETED))->row_array();
        if (is_array($user_profile)) {
            $this->db->select("trackLat as lat,trackLng as lng"); //id as title,trackLocation as description,
            $this->db->from($this->db->dbprefix('scooter_track_location'));
            $this->db->where("userId", decode($uid));
            $this->db->where("reserveId", decode($reserveId));
            $this->db->order_by('id', 'ASC');
            $track_location = $this->db->get()->result_array();
            return $data = array("userId" => $user_profile['id'], "userName" => $user_profile['userName'], "email" => $user_profile['email'], "mobile" => $user_profile['mobile'], "profileImage" => $user_profile['profileImage'], "location" => $user_profile['location'], "status" => $user_profile['status'],"reserveId"=>decode($reserveId), "track_location" => $track_location);
        }
    }

    function getTrckLocation($reserveId,$uid) {
        $this->db->select("trackLat as lat,trackLng as long");
        $this->db->from($this->db->dbprefix('scooter_track_location'));
        $this->db->where("userId", $uid);
        $this->db->where("reserveId", $reserveId);
        $this->db->order_by('id', 'ASC');
        return $track_location = $this->db->get()->result_array();
    }

}
