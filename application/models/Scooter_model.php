<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Scooter_model extends CI_Model {

    public function __construct() {
        $this->load->database();
        $this->load->model('Common_model', 'Common_model', true);
    }

    function getScooterList() {
        //return $this->db->get_where('scooter_parking')->result_array();
        return $this->db->order_by('id', 'DESC')->get_where('scooter_parking', array("isDeleted" => NOTDELETED))->result_array();
    }

    function getScooterDeatils($scooterID) {
        $scooterNumber = decode($scooterID);
        $scooterDetails = $this->db->get_where('scooter_parking', array("scooterNumber" => $scooterNumber, "isDeleted" => NOTDELETED))->row_array();

        if ($scooterDetails['status'] == ACTIVE) {
            if ($scooterDetails['scooterStatus'] == RESERVE) {
                if ($scooterDetails['isLockUnlock'] == ISUNLOCK) {
                    $status = 'On Ride';
                } else {
                    $status = 'Reserve';
                }
            } else {
                $status = 'Free';
            }
        } else {
            $status = 'Under Maintanace';
        }
        $runningDistance = $this->Common_model->scooterDetails($scooterNumber);
        $maintUnderDetails = $this->Common_model->UnderMaintDetails($scooterNumber);
        $time = $this->Common_model->convertMinutesToHrs($maintUnderDetails['totalMaintTime'], $maintUnderDetails['totalMaintSecond']);
        $scooterUsagesDetails = $this->db->query("SELECT es_scooter_reserve.*,es_user_master.userName FROM `es_scooter_reserve` left join es_user_master ON es_scooter_reserve.userId=es_user_master.id WHERE scooterNumber='" . $scooterNumber . "' and rideStatus='" . RIDECOMPLETE . "' order by es_scooter_reserve.id DESC")->result_array();
        if ($scooterDetails) {

            $scooterDetails['scooterUsagesDetails'] = $scooterUsagesDetails;
            $scooterDetails['status'] = $status;
            $scooterDetails['time'] = $time;
            $scooterDetails['runningDistance'] = $runningDistance['distance'];

            return $scooterDetails;
        }
    }

    function getDeatils($reserveId) {
        $rideDetails = $this->db->query("SELECT * FROM `es_scooter_reserve` WHERE id='" . $reserveId . "' ")->row_array();
        return $data = array("userId" => $rideDetails['id'], "scooterNumber" => $rideDetails['scooterNumber'], "startLocation" => $rideDetails['startLocation'], "endLocation" => $rideDetails['endLocation'], "startDate" => $rideDetails['startDate'], "startTime" => $rideDetails['startTime'], "endDate" => $rideDetails['endDate'], "endTime" => $rideDetails['endTime']);
    }

    function addScooter($data = array()) {

        $result = $this->Common_model->getLatLng($data['location']);
        if ($result) {
            if ($this->Common_model->checkIsEnteredInRestrictedArea($result['lat'], $result['lng'])) {
                return array('status' => 400, 'message' => 'Cannot Add Scooter As Scooter Location Comes Under Restricted Area.');
            }
            $data2 = array(
                'scooterNumber' => strtoupper($data['scooteNumber']),
                'tarckId' => $data['tarckId'],
                'scooterLocation' => $data['location'],
                'scooterLat' => $result['lat'],
                'scooterLng' => $result['lng'],
                'status' => ACTIVE,
                'isDeleted' => NOTDELETED,
                'createdDate' => date('Y-m-d H:i:s'),
            );
            $res = $this->db->insert('scooter', $data2);
            if ($res) {
                $scooterId = $this->db->insert_id();
                $data2 = array(
                    'scooterId' => $scooterId,
                    'reserveUserId' => 0,
                    'scooterNumber' => strtoupper($data['scooteNumber']),
                    'tarckId' => $data['tarckId'],
                    'location' => $data['location'],
                    'lat' => $result['lat'],
                    'lng' => $result['lng'],
                    'scooterStatus' => NOTRESERVE,
                    'isLockUnlock' => ISLOCK,
                    'status' => ACTIVE,
                    'isDeleted' => NOTDELETED,
                    'createdDate' => date('Y-m-d H:i:s'),
                );
                $res2 = $this->db->insert('scooter_parking', $data2);
                if ($res2) {
                    $this->session->set_flashdata('success', "New scooter added successfully");
                    return $data2 = array('status' => 200, 'message' => 'New scooter added successfully');
                    //return true;
                } else {
                    $this->session->set_flashdata('error', "Action not performed");
                    return $data2 = array('status' => 400, 'message' => 'Action not performed');
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function updateScooter($data = array()) {

        $result = $this->Common_model->getLatLng($data['edit_location']);
        if ($result) {
            if ($this->Common_model->checkIsEnteredInRestrictedArea($result['lat'], $result['lng'])) {
                return array('status' => 400, 'message' => 'Cannot Add Scooter As Scooter Location Comes Under Restricted Area.');
            }

            $scooterId = html_escape($data['edit_id']);
            $where = array(
                "id" => $scooterId,
            );

            $updateArray = array(
                'scooterNumber' => strtoupper($data['edit_scooteNumber']),
                'tarckId' => $data['edit_tarckId'],
                'scooterLocation' => $data['edit_location'],
                'scooterLat' => $result['lat'],
                'scooterLng' => $result['lng'],
                'status' => ACTIVE,
                'isDeleted' => NOTDELETED,
                'createdDate' => date('Y-m-d H:i:s'),
            );

            if ($this->db->update('scooter', $updateArray, $where)) {

                $where = array(
                    'scooterId' => $scooterId,
                );

                $updateArray = array(
                    'scooterNumber' => strtoupper($data['edit_scooteNumber']),
                    'tarckId' => strtoupper($data['edit_tarckId']),
                    'location' => $data['edit_location'],
                    'lat' => $result['lat'],
                    'lng' => $result['lng'],
                );

                if ($this->db->update('scooter_parking', $updateArray, $where)) {
                    $this->session->set_flashdata('success', "Scooter updated successfully");
                    return array('status' => 200, 'message' => 'Scooter updated successfully');
                } else {
                    $this->session->set_flashdata('error', "Action not performed");
                    return array('status' => 400, 'message' => 'Action not performed');
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    //parking function
    function insertParking($data) {

        $result = $this->Common_model->getLatLng($data['parkingLocation']);

        if ($result) {

            if ($this->Common_model->checkIsEnteredInRestrictedArea($result['lat'], $result['lng'])) {
                return array('status' => 400, 'message' => 'Cannot Add Parking As This Location Comes Under Restricted Area.');
            }

            $insertArray = array(
                'parkingName' => ucfirst($data['parkingName']),
                'parkingLocation' => ucwords($data['parkingLocation']),
                'parkingLat' => $result['lat'],
                'parkingLng' => $result['lng'],
                'status' => ACTIVE,
                'isDeleted' => NOTDELETED,
                'createdDate' => date('Y-m-d H:i:s'),
            );

            if ($this->db->insert('parking', $insertArray)) {

                $this->session->set_tempdata('success', 'Parking created successfully', 300);

                return array('status' => 200, 'message' => 'Parking created successfully');
            } else {
                $this->session->set_flashdata('error', "Action not performed");
                return array('status' => 400, 'message' => 'Action not performed');
            }
        } else {
            return array('status' => 400, 'message' => 'Please enter correct parking location', 'type' => 'location');
        }
    }

    function doUpdateParking($data) {

        $result = $this->Common_model->getLatLng($data['edit_parkingLocation']);

        if ($result) {

            if ($this->Common_model->checkIsEnteredInRestrictedArea($result['lat'], $result['lng'])) {
                return array('status' => 400, 'message' => 'Cannot Add Parking As This Location Comes Under Restricted Area.');
            }
            $edit_id = html_escape($data['edit_id']);
            $where = array(
                "id" => $edit_id
            );
            $updateArray = array(
                'parkingName' => ucfirst($data['edit_parkingName']),
                'parkingLocation' => ucwords($data['edit_parkingLocation']),
                'parkingLat' => $result['lat'],
                'parkingLng' => $result['lng'],
            );

            if ($this->db->update('parking', $updateArray, $where)) {
                $this->session->set_tempdata('success', 'Parking updated successfully', 300);
                return array('status' => 200, 'message' => 'Parking updated successfully');
            } else {
                $this->session->set_flashdata('error', "Action not performed");
                return array('status' => 400, 'message' => 'Action not performed');
            }
        } else {
            return array('status' => 400, 'message' => 'Please enter correct parking location', 'type' => 'location');
        }
    }

    function getParkingList() {
        return $this->db->order_by('id', 'DESC')->get_where('parking', array("isDeleted" => NOTDELETED))->result_array();
    }

    function removeParking($parkingId, $value) {
        $where = array(
            "id" => $parkingId,
        );

        $updateArray = array(
            'isDeleted' => $value,
        );

        if ($this->db->update($this->db->dbprefix('parking'), $updateArray, $where)) {
            $this->session->set_flashdata('success', "Parking removed successfully");
            return true;
        } else {
            $this->session->set_flashdata('error', "Action not performed");
            return false;
        }
    }

    function changeParkingStatus($parkingId, $value) {
        $where = array(
            "id" => $parkingId,
        );
        $updateArray = array(
            'status' => $value,
        );
        $update = $this->db->update($this->db->dbprefix('parking'), $updateArray, $where);
        if ($update) {
            if ($value == ACTIVE) {
                $this->session->set_flashdata('success', "Parking activated successfully");
            } else {
                $this->session->set_flashdata('success', "Parking De-activated successfullys");
            }

            return true;
        } else {
            $this->session->set_flashdata('error', "Action not performed");
            return false;
        }
    }

    function chkScooterStatus($scooterId) {
        $chkstatus = $this->db->query("SELECT * FROM `es_scooter_parking` where (id='" . $scooterId . "' and scooterStatus='" . ACTIVE . "') or (id='" . $scooterId . "' and isUnderMaint='" . ACTIVE . "')")->row_array();
        if ($chkstatus) {
            if ($chkstatus['isLockUnlock'] == ISUNLOCK) {
                return $data = array('status' => 400, 'message' => 'This scooter on ride. Please stop ride .', 'type' => 'ride');
            } else if ($chkstatus['isUnderMaint'] == ACTIVE) {
                return $data = array('status' => 400, 'message' => 'This scooter under mainatance scooter. Please reassigne another.', 'type' => 'underMaint');
            } else {
                return $data = array('status' => 400, 'message' => 'This scooter reserve sooter. Please reserveatin cancelled .', 'type' => 'reserve');
            }
        } else {
            return $data = array('status' => 200, 'message' => '');
        }
    }

    function removeScooter($scooterId, $value) {
        $where = array(
            "id" => $scooterId,
        );
        $updateArray = array(
            'isDeleted' => DELETED,
        );

        $this->db->update($this->db->dbprefix('scooter'), $updateArray, $where);
        $update = $this->db->update($this->db->dbprefix('scooter_parking'), $updateArray, $where);
        if ($update) {
            $this->session->set_flashdata('success', "Scooter Removed Successfully");
            return true;
        } else {
            $this->session->set_flashdata('error', "Action not performed");
            return false;
        }
    }

    function changeScooterStatus($scooterId, $value) {
        $where = array(
            "id" => $scooterId,
        );
        $updateArray = array(
            'status' => $value,
        );

        $this->db->update($this->db->dbprefix('scooter'), $updateArray, $where);
        $update = $this->db->update($this->db->dbprefix('scooter_parking'), $updateArray, $where);
        if ($update) {
            if ($value == ACTIVE) {
                $this->session->set_flashdata('success', "Scooter activated successfully");
            } else {
                $this->session->set_flashdata('success', "Scooter De-activated successfully");
            }
            return true;
        } else {
            $this->session->set_flashdata('error', "Action not performed");
            return false;
        }
    }

    function getRestrictedParkingList() {
        return $this->db->order_by('location', 'ASC')->get_where('scooter_restricted_area', array("isDeleted" => NOTDELETED))->result();
    }

    function changeAreaStatus($parkingId, $value) {
        $where = array(
            "id" => $parkingId,
        );
        $updateArray = array(
            'status' => $value,
            "updatedOn" => date("Y-m-d H:i:s")
        );
        $update = $this->db->update($this->db->dbprefix('scooter_restricted_area'), $updateArray, $where);
        if ($update) {
            if ($value == ACTIVE) {
                $this->session->set_flashdata('success', "Area activated successfully");
            } else {
                $this->session->set_flashdata('success', "Area De-activated successfullys");
            }

            return true;
        } else {
            $this->session->set_flashdata('error', "Action not performed");
            return false;
        }
    }

    /**
     * Restriacted Area Table
     *      */
    function addArea($post = array()) {

        $result = $this->Common_model->getLatLng($post['parkingLocation']);

        if ($result) {

            $insertArray = array(
                'name' => ucfirst($post['parkingName']),
                'location' => ucwords($post['parkingLocation']),
                'lat' => $result['lat'],
                'lng' => $result['lng'],
                'status' => ACTIVE,
                'isDeleted' => NOTDELETED,
                'createdOn' => date('Y-m-d H:i:s'),
            );

            if ($this->db->insert('scooter_restricted_area', $insertArray)) {

                $this->session->set_tempdata('success', 'Area added successfully', 300);
                return array('status' => 200, 'message' => 'Area added successfully');
            } else {
                $this->session->set_flashdata('error', "Action not performed");
                return array('status' => 400, 'message' => 'Action not performed');
            }
        } else {
            return array('status' => 400, 'message' => 'Please enter correct Area location', 'type' => 'location');
        }
    }

    function updateArea($post = array()) {

        $result = $this->Common_model->getLatLng($post['edit_parkingLocation']);

        if ($result) {
            $edit_id = html_escape($post['edit_id']);
            $where = array(
                "id" => $edit_id,
            );

            $updatedArray = array(
                'name' => ucfirst($post['edit_parkingName']),
                'location' => ucwords($post['edit_parkingLocation']),
                'lat' => $result['lat'],
                'lng' => $result['lng'],
            );

            if ($this->db->update('scooter_restricted_area', $updatedArray, $where)) {

                $this->session->set_tempdata('success', 'Area updated successfully', 300);
                return array('status' => 200, 'message' => 'Area updated successfully');
            } else {
                $this->session->set_flashdata('error', "Action not performed");
                return array('status' => 400, 'message' => 'Action not performed');
            }
        } else {
            return array('status' => 400, 'message' => 'Please enter correct Area location', 'type' => 'location');
        }
    }

    function removeArea($parkingId, $value) {

        $where = array(
            "id" => (int) $parkingId,
        );

        $updateArray = array(
            "isDeleted" => DELETED,
            "updatedOn" => date("Y-m-d H:i:s")
        );

        if ($this->db->update($this->db->dbprefix('scooter_restricted_area'), $updateArray, $where)) {

            $this->session->set_flashdata('success', "Area removed successfully");
            return true;
        } else {
            $this->session->set_flashdata('error', "Action not performed");
            return false;
        }
    }

}
