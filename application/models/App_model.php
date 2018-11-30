<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class App_model extends CI_Model {

    public function __construct() {
        $this->load->model('Common_model', 'Common_model', true);
    }

    function getAllData() {
        $this->db->select("*");
        $this->db->from($this->db->dbprefix('admin_setting'));
        $this->db->where("isDeleted", NOTDELETED);
        $this->db->order_by('id', 'DESC');
        return $this->db->get()->row_array();
    }

    function getTopUpData() {
        return $this->db->select("*")
                ->from($this->db->dbprefix('topup'))
                ->where("status ", ACTIVE)
                ->where("isDeleted", NOTDELETED)
                ->order_by('price', 'ASC')
                ->get()->result_array();
    }

    function getScooterData() {
        return $this->db->select("*")
                ->from($this->db->dbprefix('admin_setting'))
                ->where("isDeleted", NOTDELETED)
                ->order_by('id', 'DESC')
                ->get()->row_array();
    }

    function updateDeposit($data1) {
        $where2 = array(
            "id" => 1,
        );
        $updateArray = array(
            'depositAmount' => $data1['depositAmount'],
        );
        $update = $this->db->update($this->db->dbprefix('admin_setting'), $updateArray, $where2);

        if ($update) {
            $this->session->set_flashdata('success', "Deposit amount updated successfully");
            return $data = array('status' => 200, 'message' => 'Deposit amount updated successfully');
        } else {
            $this->session->set_flashdata('error', "Action not performed");
            return $data = array('status' => 400, 'message' => 'Action not performed');
        }
    }

    function updateRideAmount($data1) {
        $where2 = array(
            "id" => 1,
        );
        $updateArray = array(
            'scooterBaseFair' => $data1['rideAmount'],
        );
        $update = $this->db->update($this->db->dbprefix('admin_setting'), $updateArray, $where2);

        if ($update) {
            $this->session->set_flashdata('success', "Ride Charges updated successfully");
            return $data = array('status' => 200, 'message' => 'Ride Charges updated successfully');
        } else {
            $this->session->set_flashdata('error', "Action not performed");
            return $data = array('status' => 400, 'message' => 'Action not performed');
        }
    }

    function doupdateTopUp($post = array()) { 
        $price = $post['price'];
        $bonus = $post['bonus'];
        $id = $post['id'];
        $count = count($id);
        $idcount = count($price);
        $update = false;
        for ($i = 0; $i < $count; $i++) {
            if ($id[$i] != '') {
                if (isset($price[$i]) && $price[$i] !== null) {
                    $where2 = array(
                        "id" => $id[$i],
                    );
                    $updateArray = array(
                        'price' => $price[$i],
                        'bonus' => $bonus[$i]
                    );
                    $update = $this->db->update($this->db->dbprefix('topup'), $updateArray, $where2);
                } else {
                    $where = array(
                        "id" => $id[$i],
                    );
                    $updateArray2 = array(
                        'isDeleted' => DELETED,
                    );
                    $update = $this->db->update($this->db->dbprefix('topup'), $updateArray2, $where);
                }
            } else {
                if (isset($price[$i]) && $price[$i] !== null) {
                    $adddata = array(
                        'price' => $price[$i],
                        'bonus' => $bonus[$i],
                        'status' => ACTIVE,
                        'isDeleted' => NOTDELETED,
                        'createdDate' => date('Y-m-d H:i:s'),
                        'updatedDate' => date('Y-m-d H:i:s'),
                    );
                    $update = $this->db->insert('topup', $adddata);
                }
            }
        }
        if ($update) {
            $this->session->set_flashdata('success', "Top-up amount updated successfully");
            return $data = array('status' => 200, 'message' => 'Top-up amount updated successfully');
        } else {
            $this->session->set_flashdata('error', "Action not performed");
            return $data = array('status' => 400, 'message' => 'Action not performed');
        }
    }

    function updatePaneltyCharges($data) {
        //return $data;die;
        $where2 = array(
            "id" => 1,
        );
        $updateArray = array(
            'illgelParking' => $data['illegalParking'],
            'limitViolation' => $data['limitViolation'],
            'rashDriving' => $data['rashDriving'],
        );
        $update = $this->db->update($this->db->dbprefix('admin_setting'), $updateArray, $where2);

        if ($update) {
            $this->session->set_flashdata('success', "Penalty charges  updated successfully");
            return $data = array('status' => 200, 'message' => 'Penalty charges  updated successfully');
        } else {
            $this->session->set_flashdata('error', "Action not performed");
            return $data = array('status' => 400, 'message' => 'Action not performed');
        }
    }

    public function doUpdateConfig($post = array()) {

        try {
            if (is_array($post) && !empty($post)) {
                $cnfId = decode(html_escape($post['field_confId']));
                $maxSpeed = html_escape($post['field_speed']);
                $minVol = html_escape($post['field_voltage']);


                if (empty($maxSpeed)) {
                    return array("status" => 400, "message" => "Please provide max speed value!");
                }

                if (empty($minVol)) {
                    return array("status" => 400, "message" => "Please provide min voltage value");
                }

                $updateWhere = array(
                    "id" => $cnfId
                );

                $updateArray = array(
                    "speedLimit" => $maxSpeed,
                    "batteryVoltage" => $minVol,
                );

                if ($this->db->update($this->db->dbprefix('admin_setting'), $updateArray, $updateWhere)) {

                    $trackers = $this->Command_model->getTrackerList();

                    if ($trackers) {

                        $insertArray = array();
                        foreach ($trackers as $tracker => $title) {

                            if ($tracker != '') {

                                $whereUpdate = array("trackerId" => $tracker, "isSent" => NOTSENT);
                                $updateArray = array("isSent" => UNREAD, "updateDate" => date("Y-m-d H:i:s"));
                                $this->db->update($this->db->dbprefix('sent_cmd'), $updateArray, $whereUpdate);

                                //Set MAX Speed
                                $insertArray[] = array(
                                    "trackerId" => $tracker,
                                    "cmd" => SETMAXSPEEDCMD . ",{$maxSpeed},10,1",
                                    "createdDate" => date("Y-m-d H:i:s")
                                );
                            }
                        }
                    }

                    if (count($insertArray)) {
                        $this->db->insert_batch($this->db->dbprefix('sent_cmd'), $insertArray);
                    }

                    //Add sleep of 10 sec to execute line
                    sleep(10);

                    if ($trackers) {

                        $insertArray = array();
                        foreach ($trackers as $tracker => $title) {

                            if ($tracker != '') {

                                $whereUpdate = array("trackerId" => $tracker, "isSent" => NOTSENT);
                                $updateArray = array("isSent" => UNREAD, "updateDate" => date("Y-m-d H:i:s"));
                                $this->db->update($this->db->dbprefix('sent_cmd'), $updateArray, $whereUpdate);

                                //Set Min voltage
                                $insertArray[] = array(
                                    "trackerId" => $tracker,
                                    "cmd" => SETMINVOLTAGE . ",{$minVol},1",
                                    "createdDate" => date("Y-m-d H:i:s")
                                );
                            }
                        }
                    }

                    if (count($insertArray)) {
                        $this->db->insert_batch($this->db->dbprefix('sent_cmd'), $insertArray);
                    }

                    return array("status" => 200, "message" => "Configuration updated successfully");
                } else {
                    return array("status" => 400, "message" => "Configuration not updated. Please try later!");
                }
            } else {
                return array("status" => 400, "message" => "Invalid post data. Please try later!");
            }
        } catch (Exception $e) {
            return array("status" => 400, "message" => $e->getMessage());
        }
    }

    public function updateConfiguration($post = array()) {
        try {
            
            $cnfId = html_escape($post['field_confId']);
            
            $updateWhere = array(
                "id" => $cnfId
            );

            $updateArray = array(
                "depositAmount" => html_escape($post['field_deposite_amount']),
                "scooterRadius" => html_escape($post['field_radious']),
                "scooterCancelTime" => html_escape($post['field_cancel_min']),
                "scooterPerMinChrages" => html_escape($post['field_charges_per_min']),
                //"scooterBaseFair" => html_escape($post['field_base_fare']),
                "ownRefferralAmount" => html_escape($post['field_own_refferal_amount']),
                "anotherRefferralAmount" => html_escape($post['field_other_refferal_amount']),
            );

            if ($this->db->update($this->db->dbprefix('admin_setting'), $updateArray, $updateWhere)) {
                return array("status" => 200, "message" => "Configuration updated successfully");
            } else {
                return array("status" => 400, "message" => "Configuration not updated. Please try later!");
            }
        } catch (Exception $e) {
            return array("status" => 400, "message" => $e->getMessage());
        }
    }

}
