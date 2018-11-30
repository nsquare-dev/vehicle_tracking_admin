<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Instant_model extends CI_Model {

    public function __construct() { 
        $this->load->model('api/Scooter_model', 'Scooter_model', true);       
    }
    
    function getInstantList() {
        
        $this->db->select("c.*, q.name");
        
        $this->db->from($this->db->dbprefix('scooter_start_issue_comment') . " AS c");
        $this->db->join($this->db->dbprefix('scooter_start_issue_question') . " AS q", "c.questionId=q.id", "left");
        $this->db->where("c.isAssign", NOTASSIGN);
        $this->db->where("c.status", ACTIVE);
        $this->db->where("c.isDeleted", NOTDELETED);
        $this->db->order_by('c.id', 'DESC');
        return $this->db->get()->result_array();
    }

    function getInstantDetails($instatntId) {
        $data = $this->db->select("c.*, s.location, s.lat, s.lng, s.scooterStatus, s.isLockUnlock, s.tarckId")
                ->from($this->db->dbprefix('scooter_start_issue_comment') . " AS c")
                ->join($this->db->dbprefix('scooter_parking') . " AS s", "c.scooterNumber=s.scooterNumber", "inner")
                ->where("c.id", decode($instatntId))
                ->get()->row_array();
        
        $scooterstatus = $this->db->select("id As maintId, maintStatus")
                    ->from($this->db->dbprefix('maintenance_under_scooter'))
                    ->where("scooterNumber", $data['scooterNumber'])
                    ->where("status", ACTIVE)
                    ->where("(maintStatus='" . MAINTPROGRESS . "' or maintStatus = '" . MAINTPENDING."')" )
                    ->get()->row_array();
                
        if ($scooterstatus) {
            $result = array_merge($data, $scooterstatus);
        } else {
            $scooterstatus = array("maintId" => '', "maintStatus" => '');
            $result = array_merge($data, $scooterstatus);
        }
        return $result;
    }

    function getSelectedOptionList($optionId) {
        if ($optionId) {
            $result = $this->db->query("SELECT * from es_scooter_issue_option where id IN (" . $optionId . ")")->result_array();
        } else {
            $result = array();
        }
        return $result;
    }

    
    function doStopRide($scooter = false){
        
        if($scooter === false ){
            return false;
        }else{
             
            $scooterDetails = $this->db->select("reserveUserId, tarckId, scooterNumber")
                    ->from($this->db->dbprefix("scooter_parking"))
                    ->where("scooterNumber", $scooter)
                    ->get()->row();

            if($scooterDetails){
                 
                $reserveDetails = $this->db->select("id")
                    ->from($this->db->dbprefix("scooter_reserve"))
                    ->where("userId", $scooterDetails->reserveUserId)
                    ->where("scooterNumber",$scooterDetails->scooterNumber)
                    ->where("rideStatus", RIDERUNNING)
                    ->get()->row(); 
                 
                if($reserveDetails){
                    $userId = $scooterDetails->reserveUserId;
                    $reserveId = $reserveDetails->id;
                    $where  =  array("id" => $reserveId, "status" => ACTIVE);
                    
                    $scooterstatus = $this->db->get_where('scooter_reserve',$where)->row_array();
                    if ($scooterstatus) {

                        $responce = $this->Scooter_model->doStopScooter($scooterstatus,  $userId, $reserveId );

                        if($responce['status']==200){                                   
                            return true;
                        }else{
                            return false;
                        }
                     } else {
                        return false;
                    }
                }else{
                    return false;
                }
            }else{
                return false;
            }
         }
    }
    
    public function getRideStatus($scooter  = false){
        
        if($scooter === false){
            return (object)array("rideStatus"=>0);
        }else{
            
            $where = array(
                "sr.status" => ACTIVE, 
                "sp.scooterNumber" => $scooter,
                "sp.isUnderMaint" => NOTUNDERMAINTAINANCE,
                "sp.status" => ACTIVE,
                "sp.isDeleted" => NOTDELETED,                
                               
            );
            
            return $this->db->select("sr.rideStatus")
                    ->from($this->db->dbprefix('scooter_reserve')." AS sr")
                    ->join($this->db->dbprefix('scooter_parking')." AS sp", "sr.scooterNumber = sp.scooterNumber AND sr.userId = sp.reserveUserId", "INNER")
                    ->where($where)
                    ->get()->row();
        }
    }
}
