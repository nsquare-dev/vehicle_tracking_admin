<?php

class Temp_model extends CI_Model {

    public function __construct() {
        $this->load->database();
        $this->load->model('api/Common_model', 'Common_model', true);
    }

    public function truncateTable() {
        $this->db->truncate('scooter_reserve');
        $this->db->truncate('user_topup_transactions');
        $this->db->truncate('bill_summary');
        $this->db->truncate('scooter_track_location');
        $this->db->truncate('user_scooter_rating');
        $this->db->truncate('push_command');
        $this->db->truncate('sent_cmd');
        $this->db->truncate('scooter_notifications');
        $this->db->query("UPDATE `es_scooter_parking` SET `reserveUserId`='0',`scooterStatus`='0',`isLockUnlock`='0'");
        return TRUE;
    }

}

?>