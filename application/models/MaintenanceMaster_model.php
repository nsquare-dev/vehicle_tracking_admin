<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MaintenanceMaster_model extends CI_Model {

    public function __construct() {
        $this->load->database();
        $this->load->model('Common_model', 'Common_model', true);
    }

    function getMaintUserList() {
        $userlist = $this->db->order_by('id', 'DESC')->get_where('user_master', array("role" => MAINTENANCE, "isDeleted" => NOTDELETED))->result_array();
        if ($userlist) {
            foreach ($userlist as $key => $user_profile) {
                $count = $this->Common_model->pendingTaskCount($user_profile['id']);
                $data[] = array("id" => $user_profile['id'], "userName" => $user_profile['userName'], "email" => $user_profile['email'], "mobile" => $user_profile['mobile'], "profileImage" => $user_profile['profileImage'], "location" => $user_profile['location'], "status" => $user_profile['status'], "count" => $count['pendingtask']);
            }
            return $data;
        }
        return $data = array();
    }

    function chkScooterStatus($scooterNumber) {
        $chkstatus = $this->db->query("SELECT * FROM `es_scooter_parking` where (scooterNumber='" . $scooterNumber . "' and scooterStatus='" . ACTIVE . "') or (scooterNumber='" . $scooterNumber . "' and isUnderMaint='" . ACTIVE . "')")->row_array();
        if ($chkstatus) {
            if ($chkstatus['isLockUnlock'] == ISUNLOCK) {
                return $data = array('status' => 400, 'message' => 'This scooter on ride. Please stop ride .', 'type' => 'ride');
            } else if ($chkstatus['scooterStatus'] == RESERVE) {
                return $data = array('status' => 400, 'message' => 'This scooter reserve. Please cancelled reservation ride .', 'type' => 'reserve');
            } else if ($chkstatus['isUnderMaint'] == ACTIVE) {
                $result = $this->db->query("SELECT * FROM `es_maintenance_under_scooter` where (scooterNumber='" . $scooterNumber . "' and maintStatus='" . MAINTPROGRESS . "')")->row_array();
                if ($result) {
                    return $data = array('status' => 400, 'message' => 'This scooter progress start. Please stop progress.', 'type' => 'underMaint');
                } else {
                    return $data = array('status' => 200, 'message' => '');
                }
            } else {
                return $data = array('status' => 400, 'message' => 'This scooter reserve. Please reserveation cancelled .', 'type' => 'reserve');
            }
        } else {
            return $data = array('status' => 200, 'message' => '');
        }
    }

    function getMaintUser() {
        $userlist = $this->db->order_by('id', 'DESC')->get_where('user_master', array("status" => ACTIVE, "role" => MAINTENANCE, "isDeleted" => NOTDELETED))->result_array();
        if ($userlist) {
            foreach ($userlist as $key => $user_profile) {
                $count = $this->Common_model->pendingTaskCount($user_profile['id']);
                $userlistdata[] = array("id" => $user_profile['id'], "userName" => $user_profile['userName'], "email" => $user_profile['email'], "mobile" => $user_profile['mobile'], "profileImage" => $user_profile['profileImage'], "location" => $user_profile['location'], "status" => $user_profile['status'], "count" => $count['pendingtask']);
            }
            $categorylist = $this->db->get_where('maintenance_task_category', array("status" => ACTIVE, "isDeleted" => NOTDELETED))->result_array();
            return array("status" => 200, "userlist" => $userlistdata, "categorylist" => $categorylist);
        } else {
            return array("status" => 400, "message" => "No Maintainace User Available!");
        }
    }

    function getMaintUserDeatils($uid) {
        $userId = decode($uid);
        $user_profile = $this->db->get_where('user_master', array("id" => $userId, "isDeleted" => NOTDELETED))->row_array();
        $taskDetails = $this->db->query("SELECT * FROM `es_maintenance_under_scooter` WHERE userId='" . $userId . "' and (maintStatus='" . MAINTCOMPLETE . "' or maintStatus='" . MAINTCANCEL . "')")->result_array();
        $userCompleteTaskDetails = $this->Common_model->UnderMaintUserDetails2($userId, TASKCOMPLETEYES);
        $userUncompleteTaskDetails = $this->Common_model->UnderMaintUserDetails2($userId, TASKCOMPLETENO);
        $timeComplet = $this->Common_model->convertMinutesToHrs($userCompleteTaskDetails['totalMaintTime'], $userCompleteTaskDetails['totalMaintSecond']);
        $timeUncomplet = $this->Common_model->convertMinutesToHrs($userUncompleteTaskDetails['totalMaintTime'], $userUncompleteTaskDetails['totalMaintSecond']);
        //  print_r($usertaskDetails);die;
        if ($user_profile) {
            return $data = array("userId" => $user_profile['id'], "userName" => $user_profile['userName'], "email" => $user_profile['email'], "mobile" => $user_profile['mobile'], "profileImage" => $user_profile['profileImage'], "location" => $user_profile['location'], "status" => $user_profile['status'], "taskDetails" => $taskDetails, "userCompletedTaskDetails" => $userCompleteTaskDetails['totalCompletedTask'], "userUncompleteTaskDetails" => $userUncompleteTaskDetails['totalCompletedTask'], "timeComplet" => $timeComplet, "timeUncomplet" => $timeUncomplet);
        }
    }

    function getDeatils($id) {
        $maintUnderDetails = $this->db->query("SELECT * FROM `es_maintenance_under_scooter` left join es_maintenance_comment ON es_maintenance_under_scooter.id=es_maintenance_comment.maintId WHERE es_maintenance_under_scooter.id='" . $id . "' ")->row_array();
        return $data = array("userId" => $maintUnderDetails['id'], "scooterNumber" => $maintUnderDetails['scooterNumber'], "scooterLocation" => $maintUnderDetails['scooterLocation'], "startDate" => $maintUnderDetails['progressStartDate'], "startTime" => $maintUnderDetails['progressStartTime'], "endTime" => $maintUnderDetails['progressEndTime'], "issueTitle" => $maintUnderDetails['issueTitle'], "timeSpent" => $maintUnderDetails['totalMaintTime'], "comment" => $maintUnderDetails['comment'], "image1" => $maintUnderDetails['image1'], "image2" => $maintUnderDetails['image2'], "image3" => $maintUnderDetails['image3'], "image4" => $maintUnderDetails['image4']);
    }

    function addUser($data) {

        $config = array(
            array(
                'field' => 'userName',
                'label' => 'Full Name',
                'rules' => 'trim|required|alpha_numeric_spaces',
            ),
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'trim|required|valid_email',
            ),
            array(
                'field' => 'mobile',
                'label' => 'Mobile',
                'rules' => 'trim|required|numeric|min_length[8]|max_length[8]',
            ),
            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'trim|required',
            ),
            array(
                'field' => 'cpassword',
                'label' => 'Confirm Password',
                'rules' => 'trim|required|matches[password]',
            ),
        );

        $this->form_validation->set_rules($config);

        if ($this->form_validation->run() == FALSE) {
            $errors = validation_errors();
            return array("status" => 400, "message" => nl2br(strip_tags($errors)));
        } else {

            $mobile = "+65{$data['mobile']}";

            if ($this->Common_model->chkMobileNumber($mobile)) {
                return $data = array('status' => 400, 'message' => 'Mobile number entered is exist in system. Please use different Mobile number .', 'type' => 'mobile');
            } else if ($this->Common_model->chkEmailid($data['email'])) {
                return $data = array('status' => 400, 'message' => 'email id is exist in system. Please use different Email id .', 'type' => 'email_id');
            } else {
                $password = html_escape($data['password']);
                $data = array(
                    'userName' => $data['userName'],
                    'email' => $data['email'],
                    'mobile' => $mobile,
                    'password' => encode($password),
                    'aboveYear' => '1',
                    'verified' => VERIFIED,
                    'status' => ACTIVE,
                    'role' => MAINTENANCE,
                    'signUpType' => SIGNUPGEN,
                    'isDeleted' => NOTDELETED,
                    'createdDate' => date('Y-m-d H:i:s'),
                    'isLogged' => NOTLOGGED,
                );

                if ($this->db->insert('user_master', $data)) {

                    $msg = "Welcome to " . TITLE . ", \r\n Your login password is: {$password}. \r\n Use registered mobile number for login.";
                    //$this->Common_model->sendSMS($mobile, $msg);
                    $this->Common_model->sendSMS(8796973394, $msg);
                    $this->session->set_flashdata('success', "Maintainance User Added Successfully");
                    return array('status' => 200, 'message' => 'Maintainance User Added Successfully');
                } else {
                    $this->session->set_flashdata('error', "Action Not Performed");
                    return array('status' => 400, 'message' => 'Action Not Performedl');
                }
            }
        }
    }

    function getUserTaskList() {

        $user = $this->db->query("SELECT * FROM `es_user_master` WHERE `id` IN (SELECT DISTINCT  userId FROM `es_maintenance_under_scooter` WHERE  taskType='" . USERTASK . "' and (maintStatus='" . MAINTPROGRESS . "' or maintStatus='" . MAINTPENDING . "')) ")->result_array();
        if ($user) {
            foreach ($user as $key => $user_profile) {
                $count = $this->Common_model->pendingTaskCountType($user_profile['id'], USERTASK);
                $data[] = array("userId" => $user_profile['id'], "userName" => $user_profile['userName'], "email" => $user_profile['email'], "mobile" => $user_profile['mobile'], "profileImage" => $user_profile['profileImage'], "location" => $user_profile['location'], "status" => $user_profile['status'], "count" => $count['pendingtask']);
            }
            return $data;
        } else {
            return $data = array();
        }
    }

    function getUserAdminTaskList() {

        $user = $this->db->query("SELECT * FROM `es_user_master` WHERE `id` IN (SELECT DISTINCT  userId FROM `es_maintenance_under_scooter` WHERE  taskType='" . ADMINTASK . "' and (maintStatus='" . MAINTPROGRESS . "' or maintStatus='" . MAINTPENDING . "')) ")->result_array();
        if ($user) {
            foreach ($user as $key => $user_profile) {
                $count = $this->Common_model->pendingTaskCountType($user_profile['id'], ADMINTASK);
                $data[] = array("userId" => $user_profile['id'], "userName" => $user_profile['userName'], "email" => $user_profile['email'], "mobile" => $user_profile['mobile'], "profileImage" => $user_profile['profileImage'], "location" => $user_profile['location'], "status" => $user_profile['status'], "count" => $count['pendingtask']);
            }
            return $data;
        } else {
            return $data = array();
        }
    }

    function addAssignTask($data) {
        $redirect = '';
        $result = $this->Common_model->getScooterLocation($data['scooterNumber']);
        $category = $this->Common_model->getCategory($data['categoryId']);
        if ($scooter = $this->Common_model->chkUnderMaintOrNot($data['scooterNumber'])) {
            /*
             * reassign scooter code
             */
            if ($data['uncomplete'] == 'uncomplete') {

                /*
                 * maintanace under status
                 */
                $where2 = array(
                    "id" => $scooter['id']
                );
                $updateArray2 = array(
                    "scooterStatus" => REASSIGN
                );

                $this->db->update($this->db->dbprefix('maintenance_under_scooter'), $updateArray2, $where2);
            } else {

                $where = array(
                    "id" => $scooter['id']
                );

                $updateArray = array(
                    "maintStatus" => MAINTCANCEL,
                    "scooterStatus" => REASSIGN,
                );
                $update = $this->db->update($this->db->dbprefix('maintenance_under_scooter'), $updateArray, $where);
            }
            /*
             * update user id
             */
            $wherearray = array(
                "scooterNumber" => $data['scooterNumber']
            );
            $updatedataarray = array(
                "reserveUserId" => $data['userId'],
            );
            $update = $this->db->update($this->db->dbprefix('scooter_parking'), $updatedataarray, $wherearray);
            $data2 = array(
                'userId' => $data['userId'],
                'catId' => $data['categoryId'],
                'issueId' => $scooter['issueId'],
                'scooterNumber' => $data['scooterNumber'],
                'scooterLocation' => $scooter['scooterLocation'],
                'scooterLat' => $scooter['scooterLat'],
                'scooterLng' => $scooter['scooterLng'],
                'issueTitle' => $category['categoryName'],
                'issueComment' => $scooter['issueComment'],
                'assignDate' => date('Y-m-d H:i:s'),
                'istaskcomplete' => 'No',
                'maintStatus' => MAINTPENDING,
                'taskType' => $scooter['taskType'],
                'createdDate' => date('Y-m-d H:i:s'),
                'status' => ACTIVE,
                'isDeleted' => NOTDELETED,
            );
        } else {
            /*
             * new assigne scooter 
             */
            $redirect = 'assign';
            $data2 = array(
                'userId' => $data['userId'],
                'catId' => $data['categoryId'],
                'issueId' => $data['issueId'],
                'scooterNumber' => $data['scooterNumber'],
                'scooterLocation' => $data['location'],
                'scooterLat' => $data['lat'],
                'scooterLng' => $data['lng'],
                'issueTitle' => $category['categoryName'],
                'issueComment' => $data['comment'],
                'assignDate' => date('Y-m-d H:i:s'),
                'istaskcomplete' => 'No',
                'maintStatus' => MAINTPENDING,
                'taskType' => USERTASK,
                'createdDate' => date('Y-m-d H:i:s'),
                'status' => ACTIVE,
                'isDeleted' => NOTDELETED,
            );
        }
        $res = $this->db->insert('maintenance_under_scooter', $data2);
        if ($res) {
            /*
             * Scooter De-active 
             */
            $where = array(
                "scooterNumber" => $data['scooterNumber']
            );

            $updateArray = array(
                "status" => NOTACTIVE,
            );
            $updateArray2 = array(
                "reserveUserId" => $data['userId'],
                "status" => NOTACTIVE,
                "isUnderMaint" => ACTIVE
            );
            $update = $this->db->update($this->db->dbprefix('scooter_parking'), $updateArray2, $where);
            $update = $this->db->update($this->db->dbprefix('scooter'), $updateArray, $where);
            /*
             * Assige scooter status change in scooter_start_issue_comment table 
             */
            $where2 = array(
                "scooterNumber" => $data['scooterNumber']
            );

            $updateArray2 = array(
                "isAssign" => ASSIGN,
            );
            $update = $this->db->update($this->db->dbprefix('scooter_start_issue_comment'), $updateArray2, $where2);
            $this->session->set_flashdata('success', "Maintenance user assign task successfully!");
            if ($redirect == 'assign') {
                redirect('ManageInstant');
            } else {
                return true;
            }
        } else {
            $this->session->set_flashdata('error', "Action not performed");
            return false;
        }
    }

    function addAdminAssignTask($data) {
        $result = $this->Common_model->getScooterLocation($data['scooterNumber']);
        $category = $this->Common_model->getCategory($data['categoryId']);

        /*
         * assigne scooter admin new task
         */
        $data2 = array(
            'userId' => $data['userId'],
            'catId' => $data['categoryId'],
            'issueId' => $data['issueId'],
            'scooterNumber' => $data['scooterNumber'],
            'scooterLocation' => $data['location'],
            'scooterLat' => $data['lat'],
            'scooterLng' => $data['lng'],
            'issueTitle' => $category['categoryName'],
            'issueComment' => $data['comment'],
            'assignDate' => date('Y-m-d H:i:s'),
            'istaskcomplete' => 'No',
            'maintStatus' => MAINTPENDING,
            'taskType' => ADMINTASK,
            'createdDate' => date('Y-m-d H:i:s'),
            'status' => ACTIVE,
            'isDeleted' => NOTDELETED,
        );

        $res = $this->db->insert('maintenance_under_scooter', $data2);
        if ($res) {
            /*
             * Scooter De-active 
             */
            $where = array(
                "scooterNumber" => $data['scooterNumber']
            );

            $updateArray = array(
                "status" => NOTACTIVE,
            );
            $updateArray2 = array(
                "reserveUserId" => $data['userId'],
                "status" => NOTACTIVE,
                "isUnderMaint" => ACTIVE
            );
            $update = $this->db->update($this->db->dbprefix('scooter_parking'), $updateArray2, $where);
            $update = $this->db->update($this->db->dbprefix('scooter'), $updateArray, $where);
            /*
             * Assige scooter status change in scooter_start_issue_comment table 
             */
            $where2 = array(
                "scooterNumber" => $data['scooterNumber']
            );

            $updateArray2 = array(
                "isAssign" => ASSIGN,
            );
            $update = $this->db->update($this->db->dbprefix('scooter_start_issue_comment'), $updateArray2, $where2);
            $this->session->set_flashdata('success', "Maintenance user assign task successfully!");
            return true;
        } else {
            $this->session->set_flashdata('error', "Action not performed");
            return false;
        }
    }

//task details function
    function getMaintUserTaskDeatils($uid) {
        $userId = decode($uid);
        $user_profile = $this->db->get_where('user_master', array("id" => $userId, "isDeleted" => NOTDELETED))->row_array();
        $tsakDetails = $this->db->query("SELECT * FROM `es_maintenance_under_scooter` WHERE userId='" . $userId . "' and taskType='" . USERTASK . "' and (maintStatus='" . MAINTPENDING . "' or maintStatus='" . MAINTPROGRESS . "')")->result_array();
        $userCompleteTaskDetails = $this->Common_model->UnderMaintUserDetails2($userId, TASKCOMPLETEYES);
        $userUncompleteTaskDetails = $this->Common_model->UnderMaintUserDetails2($userId, TASKCOMPLETENO);
        $timeComplet = $this->Common_model->convertMinutesToHrs($userCompleteTaskDetails['totalMaintTime'], $userCompleteTaskDetails['totalMaintSecond']);
        $timeUncomplet = $this->Common_model->convertMinutesToHrs($userUncompleteTaskDetails['totalMaintTime'], $userUncompleteTaskDetails['totalMaintSecond']);
        if ($user_profile) {
            return $data = array("userId" => $user_profile['id'], "userName" => $user_profile['userName'], "email" => $user_profile['email'], "mobile" => $user_profile['mobile'], "profileImage" => $user_profile['profileImage'], "location" => $user_profile['location'], "status" => $user_profile['status'], "tsakDetails" => $tsakDetails, "userCompletedTaskDetails" => $userCompleteTaskDetails['totalCompletedTask'], "userUncompleteTaskDetails" => $userUncompleteTaskDetails['totalCompletedTask'], "timeComplet" => $timeComplet, "timeUncomplet" => $timeUncomplet);
        }
    }

    function getMaintUserAdminTaskDeatils($uid) {
        $userId = decode($uid);
        $user_profile = $this->db->get_where('user_master', array("id" => $userId, "isDeleted" => NOTDELETED))->row_array();
        $tsakDetails = $this->db->query("SELECT * FROM `es_maintenance_under_scooter` WHERE userId='" . $userId . "' and taskType='" . ADMINTASK . "' and (maintStatus='" . MAINTPENDING . "' or maintStatus='" . MAINTPROGRESS . "')")->result_array();
        $userCompleteTaskDetails = $this->Common_model->UnderMaintUserDetails2($userId, TASKCOMPLETEYES);
        $userUncompleteTaskDetails = $this->Common_model->UnderMaintUserDetails2($userId, TASKCOMPLETENO);
        $timeComplet = $this->Common_model->convertMinutesToHrs($userCompleteTaskDetails['totalMaintTime'], $userCompleteTaskDetails['totalMaintSecond']);
        $timeUncomplet = $this->Common_model->convertMinutesToHrs($userUncompleteTaskDetails['totalMaintTime'], $userUncompleteTaskDetails['totalMaintSecond']);
        if ($user_profile) {
            return $data = array("userId" => $user_profile['id'], "userName" => $user_profile['userName'], "email" => $user_profile['email'], "mobile" => $user_profile['mobile'], "profileImage" => $user_profile['profileImage'], "location" => $user_profile['location'], "status" => $user_profile['status'], "tsakDetails" => $tsakDetails, "userCompletedTaskDetails" => $userCompleteTaskDetails['totalCompletedTask'], "userUncompleteTaskDetails" => $userUncompleteTaskDetails['totalCompletedTask'], "timeComplet" => $timeComplet, "timeUncomplet" => $timeUncomplet);
        }
    }

    function reservationCancelled($scooterNumber) {
        $where = array(
            "scooterNumber" => $scooterNumber
        );

        $updateArray = array(
            "reserveUserId" => '0',
            "scooterStatus" => NOTRESERVE
        );
        $update = $this->db->update($this->db->dbprefix('scooter_parking'), $updateArray, $where);
        //reserve table status update
        $where2 = array(
            "scooterNumber" => $scooterNumber,
            "isLockUnlock" => ISLOCK
        );

        $updateArray2 = array(
            "rideStatus" => RIDECANCEL
        );
        $update2 = $this->db->update($this->db->dbprefix('scooter_reserve'), $updateArray2, $where2);
        if ($update2) {
            $this->session->set_flashdata('success', "Scooter reservation is cancelled  successfully!");
            return true;
        } else {
            $this->session->set_flashdata('error', "Scooter reservation not cancelled.Please try later!");
            return false;
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
                $this->session->set_flashdata('success', "Maintainance User deactivated successfully");
            } else {
                $this->session->set_flashdata('success', "Maintainance User activated successfully");
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
            $this->session->set_flashdata('success', "Maintainance User removed successfully");
            return true;
        } else {
            $this->session->set_flashdata('error', "Action not performed.Please try later!");
            return false;
        }
    }

}
