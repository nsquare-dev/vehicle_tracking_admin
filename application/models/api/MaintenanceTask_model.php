<?php

class MaintenanceTask_model extends CI_Model {

    public function __construct() {
        $this->load->database();
        $this->load->model('api/Common_model', 'Common_model', true);
    }

    function getPendingIssueList() {
        if (empty($this->input->post('userId'))) {
            return array("status" => 400, "message" => "Please post user id.");
        }
        $totalPendingTask = $this->totalPendingTask($this->input->post('userId'));
        if (isset($totalPendingTask)) {
            $totalCount = $totalPendingTask['totalCount'];
        } else {
            $totalCount = 0;
        }
        $taskCategory = $this->db->get_where('maintenance_task_category', array("status" => ACTIVE, "isDeleted" => NOTDELETED))->result_array();

        if ($taskCategory) {

            foreach ($taskCategory as $key => $taskCat) {
                $totalPendingCategoryTask = $this->totalPendingCategoryTask($this->input->post('userId'), $taskCat['id']);
                if (isset($totalPendingCategoryTask)) {
                    $totalCategoryCount = $totalPendingCategoryTask['totalCategoryCount'];
                } else {
                    $totalCategoryCount = 0;
                }

                $maintenanceUnder = $this->db->get_where('maintenance_under_scooter', array("userId" => $this->input->post('userId'), "catId" => $taskCat['id'], "maintStatus" => MAINTPENDING, "status" => ACTIVE, "isDeleted" => NOTDELETED))->result_array();

                $task = array();
                if (isset($maintenanceUnder)) {

                    foreach ($maintenanceUnder as $key => $maintUnder) {
                        
                        $task[] = array("maintId" => $maintUnder['id'], "userId" => $maintUnder['userId'], "scooterNumber" => $maintUnder['scooterNumber'], "scooterLocation" => $maintUnder['scooterLocation'], "scooterLat" => $maintUnder['scooterLat'], "scooterLng" => $maintUnder['scooterLng'], "issueTitle" => $maintUnder['issueTitle'], "issueComment" => $maintUnder['issueComment']);
                    }
                }
                $data[] = array("catId" => $taskCat['id'], "categoryCount" => $totalCategoryCount, "categoryName" =>$taskCat['categoryName'], "categoryImage" =>  base_url("resource/app_photos/{$taskCat['categoryImage']}"), "pendingTaskList" => $task);
                //return array("status" => 200, "message" => "Maintenance under scooter list!", "info" => $data);
            }
            return array("status" => 200, "message" => "Category list!", "totalCount" => $totalCount, "info" => $data);
        } else {
            return array("status" => 400, "message" => "Not found any category!");
        }
    }

    function totalPendingTask($userId) {
        $where = array(
            "userId" => $userId,
            "maintStatus" => MAINTPENDING,
            "status" => ACTIVE,
            "isDeleted" => NOTDELETED
        );
        return $totalPendingTask = $this->db->select("count(id) as totalCount")
                        ->from($this->db->dbprefix('maintenance_under_scooter'))
                        ->where($where)
                        ->get()->row_array();
    }

    function totalPendingCategoryTask($userId, $catId) {
        $where = array(
            "userId" => $userId,
            "catId" => $catId,
            "maintStatus" => MAINTPENDING,
            "status" => ACTIVE,
            "isDeleted" => NOTDELETED
        );
        return $totalPendingTask = $this->db->select("count(id) as totalCategoryCount")
                        ->from($this->db->dbprefix('maintenance_under_scooter'))
                        ->where($where)
                        ->get()->row_array();
    }

//   

    function getCompletedIssueList() {
        try {
            if (!empty($this->input->post())) {
                if (empty($this->input->post('userId'))) {
                    return array("status" => 400, "message" => "Please post user id.");
                }

                $where = array(
                    "userId" => $this->input->post('userId'),
                    "maintStatus" => MAINTCOMPLETE,
                    "status" => ACTIVE,
                    "isDeleted" => NOTDELETED
                );

                $maintenanceUnderDate = $this->db->select("DISTINCT(progressEndDate)")
                                ->from($this->db->dbprefix('maintenance_under_scooter'))
                                ->where($where)
                                ->order_by('progressEndDate', 'DESC')
                                ->get()->result_array();
                if ($maintenanceUnderDate) {

                    foreach ($maintenanceUnderDate as $key => $maintUnderdate) {
                        $maintenanceUnder = $this->db->get_where('maintenance_under_scooter', array("userId" => $this->input->post('userId'), "maintStatus" => MAINTCOMPLETE, "progressEndDate" => $maintUnderdate['progressEndDate'], "status" => ACTIVE, "isDeleted" => NOTDELETED))->result_array();
                        if (isset($maintenanceUnder)) {
                            $datewise = array();
                            foreach ($maintenanceUnder as $key => $maintUnder) {
                                $taskCategory = $this->db->get_where('maintenance_task_category', array("id" => $maintUnder['catId'], "status" => ACTIVE, "isDeleted" => NOTDELETED))->row_array();
                                $datewise[] = array("maintId" => $maintUnder['id'], "userId" => $maintUnder['userId'], "scooterNumber" => $maintUnder['scooterNumber'], "scooterLocation" => $maintUnder['scooterLocation'], "categoryName" => $taskCategory['categoryName'], "categoryImage" => base_url("resource/app_photos/{$taskCategory['categoryImage']}"), "progressEndDate" => $maintUnder['progressEndDate']);
                            }
                        }
                        $data[] = array("date" => date('d/m/Y', strtotime($maintUnderdate['progressEndDate'])), "datewise" => $datewise);
                    }
                    return array("status" => 200, "message" => "Maintenance completed scooter list!", "info" => $data);
                } else {
                    return array("status" => 400, "message" => "Not found any under maintenance scooter!");
                }
            } else {
                return array("status" => 400, "message" => "Invalid post. Please try later!");
            }
        } catch (Exception $e) {
            return array("status" => 400, "message" => $e->getMessage());
        }
    }

    function startScooterMaintenance() {
        try {
            if (!empty($this->input->post())) {
                if (empty($this->input->post('userId'))) {
                    return array("status" => 400, "message" => "Please post user id.");
                }
                if (empty($this->input->post('maintId'))) {
                    return array("status" => 400, "message" => "Please post maintenance id.");
                }
                $chkMaintenanceUnder = $this->db->get_where('maintenance_under_scooter', array("id" => $this->input->post('maintId'), "userId" => $this->input->post('userId'), "maintStatus" => MAINTPENDING, "status" => ACTIVE, "isDeleted" => NOTDELETED))->row_array();
                if (isset($chkMaintenanceUnder)) {
                    $data = array("maintId" => $chkMaintenanceUnder['id'], "userId" => $chkMaintenanceUnder['userId'], "scooterLocation" => $chkMaintenanceUnder['scooterLocation'], "scooterLat" => $chkMaintenanceUnder['scooterLat'], "scooterLng" => $chkMaintenanceUnder['scooterLng']);
                    return array("status" => 200, "message" => "Maintenance under scooter details!", "info" => $data);
                } else {
                    return array("status" => 400, "message" => "Not maintenance under scooter!");
                }
            } else {
                return array("status" => 400, "message" => "Invalid post. Please try later!");
            }
        } catch (Exception $e) {
            return array("status" => 400, "message" => $e->getMessage());
        }
    }

    function getProgressStatus() {
        try {
            if (!empty($this->input->post())) {
                if (empty($this->input->post('maintId'))) {
                    return array("status" => 400, "message" => "Please post maintenance id.");
                }
                if (empty($this->input->post('userId'))) {
                    return array("status" => 400, "message" => "Please post user id.");
                }
                if (empty($this->input->post('scooterNumber'))) {
                    return array("status" => 400, "message" => "Please post scooter number .");
                }
                $maintId = $this->input->post('maintId');
                $userId = $this->input->post('userId');
                $scooterNumber = $this->input->post('scooterNumber');

                $where = array(
                    "id" => $maintId,
                    "userId" => $userId,
                    "scooterStatus!=" => REASSIGN,
                    "status" => ACTIVE,
                    "isDeleted" => NOTDELETED,
                );

                $scooter = $this->db->select("maintStatus")
                                ->from($this->db->dbprefix('maintenance_under_scooter'))
                                ->where($where)
                                ->where("BINARY scooterNumber='{$scooterNumber}'", null, false)
                                ->get()->row();

                if ($scooter) {
                    return array("status" => 200, "message" => "Scooter status is {$scooter->maintStatus}!");
                } else {
                    return array("status" => 400, "message" => "Scooter Maintainance Task Is Re-assigned!");
                }
            } else {
                return array("status" => 400, "message" => "Invalid post. Please try later!");
            }
        } catch (Exception $ex) {
            return array("status" => 400, "message" => $e->getMessage());
        }
    }

    function getProgressStart() {
        try {
            if (!empty($this->input->post())) {
                if (empty($this->input->post('maintId'))) {
                    return array("status" => 400, "message" => "Please post maintenance id.");
                }
                if (empty($this->input->post('userId'))) {
                    return array("status" => 400, "message" => "Please post user id.");
                }
                if (empty($this->input->post('scooterNumber'))) {
                    return array("status" => 400, "message" => "Please post scooter number .");
                }
                $maintId = $this->input->post('maintId');
                $userId = $this->input->post('userId');
                $scooterNumber = $this->input->post('scooterNumber');

                $where = array(
                    "id" => $maintId,
                    "userId" => $userId,
                    "maintStatus" => MAINTPENDING,
                    "status" => ACTIVE,
                    "isDeleted" => NOTDELETED,
                );

                $scooter = $this->db->from($this->db->dbprefix('maintenance_under_scooter'))
                                ->where($where)
                                ->where("BINARY scooterNumber='{$scooterNumber}'", null, false)
                                ->get()->num_rows();

                if ($scooter) {
                    $updateWhere = array(
                        "id" => $maintId,
                    );
                    $updateArray = array(
                        'progressStartDate' => date('Y-m-d'),
                        'progressStartTime' => date('H:i:s'),
                        "maintStatus" => MAINTPROGRESS,
                    );

                    if ($this->db->update($this->db->dbprefix('maintenance_under_scooter'), $updateArray, $updateWhere)) {
                        return array("status" => 200, "message" => "Scooter unlocked successfully", "maintId" => $maintId);
                    } else {
                        return array("status" => 400, "message" => "Please provide valid scooter number!");
                    }
                } else {
                    return array("status" => 400, "message" => "Please provide valid scooter number!");
                }
            } else {
                return array("status" => 400, "message" => "Invalid post. Please try later!");
            }
        } catch (Exception $e) {
            return array("status" => 400, "message" => $e->getMessage());
        }
    }

    function getProgressStop() {
        try {
            if (!empty($this->input->post())) {
                if (empty($this->input->post('maintId'))) {
                    return array("status" => 400, "message" => "Please post maintenance id.");
                }
                if (empty($this->input->post('userId'))) {
                    return array("status" => 400, "message" => "Please post user id.");
                }
                $where = array(
                    "id" => $this->input->post('maintId'),
                    "userId" => $this->input->post('userId'),
                    "maintStatus" => MAINTPROGRESS
                );
                $scooter = $this->db->get_where('maintenance_under_scooter', $where)->row_array();
                if (isset($scooter)) {
                    $startdatetime = new DateTime($scooter['progressStartDate'] . ' ' . $scooter['progressStartTime']);
                    $enddatetime = new DateTime(date('Y-m-d') . ' ' . date('H:i:s'));
//                    $interval = abs($enddatetime - $startdatetime);
//                    $totalMaintananceTime = round($interval / 60);
//                    if ($totalMaintananceTime == 0) {
//                        $totalMaintananceTime = 1;
//                    }
                    $num_seconds = $enddatetime->getTimestamp() - $startdatetime->getTimestamp();
                    $second = $num_seconds % 60;
                    $totalMaintananceTime = floor($num_seconds / 60);
//                    if($second!=0){
//                    $runningMinutes=$runningMinutes + 1;
//                    }
//                   
//                    //actual running minutes
//                    if($second!=0){
//                    
//                    $netRunningMinutes = $runningMinutes - 1;
//                    }


                    $where = array(
                        "id" => $this->input->post('maintId'),
                    );
                    $updateArray = array(
                        'progressEndDate' => date('Y-m-d'),
                        'progressEndTime' => date('H:i:s'),
                        'totalMaintTime' => $totalMaintananceTime,
                        'totalMaintSecond' => $second,
                        'istaskcomplete' => $this->input->post('istaskcomplete'),
                        'maintStatus' => MAINTCOMPLETE,
                    );
                    $update = $this->db->update($this->db->dbprefix('maintenance_under_scooter'), $updateArray, $where);
                    if ($update) {
                        ##add comment ##
                        $data = array(
                            'maintId' => $this->input->post('maintId'),
                            'comment' => $this->input->post('comment'),
                            'createdDate' => date('Y-m-d H:i:s'),
                            'isDeleted' => NOTDELETED
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
                                            $imageArray['image' . $i] = base_url() . '/resource/user_photos/' . $fileRes['data'];
                                        } else {
                                            return $fileRes;
                                        }
                                    }
                                }
                            }
                        }
                        $alldata = array_merge($data, $imageArray);
                        $alldata = $this->db->insert('maintenance_comment', $alldata);


                        return array("status" => 200, "message" => "Stop progress successfully");
                    } else {
                        return array("status" => 400, "message" => "Not stop progress!");
                    }
                } else {
                    return array("status" => 400, "message" => "Not stop progress!");
                }
            } else {
                return array("status" => 400, "message" => "Invalid post. Please try later!");
            }
        } catch (Exception $e) {
            return array("status" => 400, "message" => $e->getMessage());
        }
    }

    function totalPendingTaskCount() {
        try {
            if (!empty($this->input->post())) {
                if (empty($this->input->post('userId'))) {
                    return array("status" => 400, "message" => "Please post user id.");
                }
                $totalPendingTask = $this->totalPendingTask($this->input->post('userId'));
                if (isset($totalPendingTask)) {
                    $totalCount = $totalPendingTask['totalCount'];
                } else {
                    $totalCount = 0;
                }
                return array("status" => 200, "message" => "Total pending task list count!", "totalCount" => $totalCount);
            } else {
                return array("status" => 400, "message" => "Invalid post. Please try later!");
            }
        } catch (Exception $e) {
            return array("status" => 400, "message" => $e->getMessage());
        }
    }

    function getTimerCount() {
        try {
            if (!empty($this->input->post())) {
                if (empty($this->input->post('userId'))) {
                    return array("status" => 400, "message" => "Please post user id.");
                }
                if (empty($this->input->post('maintId'))) {
                    return array("status" => 400, "message" => "Please post maintenance id.");
                }
                $faultyScooter = $this->db->get_where('maintenance_under_scooter', array("id" => $this->input->post('maintId'), "userId" => $this->input->post('userId'), "maintStatus" => MAINTPROGRESS))->row_array();
                if ($faultyScooter) {
                    $progressTime = $this->Common_model->getTimer($faultyScooter['progressStartDate'], $faultyScooter['progressStartTime'], date('Y-m-d'), date('H:i:s'));
                    return array("status" => 200, "message" => "your progress running time.", "progressTime" => $progressTime);
                } else {
                    return array("status" => 400, "message" => "Not found progress details. Please try again!");
                }
            } else {
                return array("status" => 400, "message" => "Invalid post. Please try later!");
            }
        } catch (Exception $e) {
            return array("status" => 400, "message" => $e->getMessage());
        }
    }

    function chkLockUnlock() {
        try {
            if (!empty($this->input->post())) {
                if (empty($this->input->post('userId'))) {
                    return array("status" => 400, "message" => "Please post user id.");
                }

                $chk = $this->db->get_where('maintenance_under_scooter', array("userId" => $this->input->post('userId'), "maintStatus" => MAINTPROGRESS))->row_array();
                if (!$chk) {
                    return array("status" => 200, "message" => "Not any progress start.Please start progress");
                } else {
                    return array("status" => 400, "message" => "Your scooter already progress. Please end progress!");
                }
            } else {
                return array("status" => 400, "message" => "Invalid post. Please try later!");
            }
        } catch (Exception $e) {
            return array("status" => 400, "message" => $e->getMessage());
        }
    }

}

?>