<?php

class Scooter_model extends CI_Model {

    protected $minvoltage;

    public function __construct() {
        $this->load->database();
        $this->load->model('api/Common_model', 'Common_model', true);
        $this->minvoltage = $this->Common_model->getConfiguredVoltage();
        if ($this->minvoltage == 0) {
            $this->minvoltage = round(MINBATTERYLVL);
        }
    }

    function getScooterList() {
        if (!empty($this->input->post())) {
            if (empty($this->input->post('latitude')) || empty($this->input->post('longitude'))) {
                return array("status" => 400, "message" => "Please post latitude and longitude .");
            }
            $latitude = $this->input->post('latitude');
            $longitude = $this->input->post('longitude');
            $adminSettings = $this->Common_model->getAdminSetting();
            $predefinedRadius = $adminSettings['scooterRadius']; //20; // Radius in KM
            $radious = $predefinedRadius;
            ###my radious scooter
            $myScooters = $this->db->query("SELECT * , COALESCE((((acos(sin(('" . $latitude . "' *pi()/180)) * sin(( lat *pi()/180))+cos(('" . $latitude . "' *pi()/180)) * cos((lat *pi()/180)) * cos((('" . $longitude . "' - lng)*pi()/180))))*180/pi())*60*1.1515*1.609344),0) as distance FROM {$this->db->dbprefix('scooter_parking')} where status='" . ACTIVE . "' and scooterStatus='" . NOTRESERVE . "' and isDeleted='" . NOTDELETED . "' and COALESCE((((acos(sin(('" . $latitude . "' *pi()/180)) * sin(( lat *pi()/180))+cos(('" . $latitude . "' *pi()/180)) * cos((lat *pi()/180)) * cos((('" . $longitude . "' - lng)*pi()/180))))*180/pi())*60*1.1515*1.609344),0)<'" . $radious . "'");
            $myScooters = $myScooters->result_array();
            //other radious scooter
            $otherScooters = $this->db->query("SELECT * , COALESCE((((acos(sin(('" . $latitude . "' *pi()/180)) * sin(( lat *pi()/180))+cos(('" . $latitude . "' *pi()/180)) * cos((lat *pi()/180)) * cos((('" . $longitude . "' - lng)*pi()/180))))*180/pi())*60*1.1515*1.609344),0) as distance FROM {$this->db->dbprefix('scooter_parking')} where status='" . ACTIVE . "' and scooterStatus='" . NOTRESERVE . "' and isDeleted='" . NOTDELETED . "' and COALESCE((((acos(sin(('" . $latitude . "' *pi()/180)) * sin(( lat *pi()/180))+cos(('" . $latitude . "' *pi()/180)) * cos((lat *pi()/180)) * cos((('" . $longitude . "' - lng)*pi()/180))))*180/pi())*60*1.1515*1.609344),0)>'" . $radious . "'");
            $otherScooters = $otherScooters->result_array();

            $radious = $predefinedRadius * 1000; // Radius in meters

            $myScootersdata = array();
            $otherScooterdata = array();
            if ($myScooters != '' && $otherScooters != '') {

                foreach ($myScooters as $key => $scooter) {
                    if ($scooter['scooterStatus'] == NOTRESERVE) {
                        $myScootersdata[] = array(
                            "id" => $scooter['id'],
                            "scooterId" => $scooter['scooterId'],
                            "scooterNumber" => $scooter['scooterNumber'],
                            "scooterLocation" => $scooter['location'],
                            "scooterLat" => $scooter['lat'],
                            "scooterLng" => $scooter['lng'],
                            "distance" => $scooter['distance'],
                            "rate" => $adminSettings['scooterPerMinChrages']);
                    }
                }
                foreach ($otherScooters as $key => $otherScooter) {
                    if ($otherScooter['scooterStatus'] == NOTRESERVE) {
                        $otherScooterdata[] = array(
                            "id" => $otherScooter['id'],
                            "scooterId" => $otherScooter['scooterId'],
                            "scooterNumber" => $otherScooter['scooterNumber'],
                            "scooterLocation" => $otherScooter['location'],
                            "scooterLat" => $otherScooter['lat'],
                            "scooterLng" => $otherScooter['lng'],
                            "distance" => $otherScooter['distance'],
                            "rate" => $adminSettings['scooterPerMinChrages']);
                    }
                }

                return array("status" => 200, "message" => "Scooter list!", "radious" => $radious, "info" => $myScootersdata, "otherInfo" => $otherScooterdata);
            } else {
                return array("status" => 400, "message" => "Not found any scooter!");
            }
        } else {
            return array("status" => 400, "message" => "Invalid post. Please try later!");
        }
    }

    function addScooterReserve() {
        if (!empty($this->input->post())) {

            if (empty($this->input->post('userId')) || empty($this->input->post('scooterNumber'))) {
                return array("status" => 400, "message" => "Please post userId and scooterNumber.");
            }
            if (empty($this->input->post('startLocation')) || empty($this->input->post('startLat')) || empty($this->input->post('startLng'))) {
                return array("status" => 400, "message" => "Please post scooter start location.");
            }
            if (empty($this->input->post('scooterParkId'))) {
                return array("status" => 400, "message" => "Please post scooterParkId.");
            }

            $scooterNumber = $this->input->post('scooterNumber');

            $scooterRow = $this->Common_model->getTrackingIdbyScooterNumber($scooterNumber);
            if (!$scooterRow) {
                return array("status" => 400, "message" => "Please Enter Valid Scooter Number.");
            }

            /*
             * Check tracker is connected with server
             *               */
            //if (!$this->Common_model->checkIsTrackerConnected($scooterRow->tarckId)) {
            //return array("status" => 400, "message" => "Failed to connect. Please try again!");
            //}
            //Check for scooter connectivity
            /* $responce = $this->Common_model->write_command($scooterRow->tarckId, 'KEEP_ALIVE');
              if ($responce['status'] == 400) {
              return $responce;
              } */
            /*
             * Check scooter battery level before reserver
             */
//            $scooterData = $this->Common_model->getScooterData($scooterRow->tarckId);
//            if (!$scooterData) {
//                return array("status" => 400, "message" => "Scooter Is Not Able To Connect Server.");
//            }
//            if (round($scooterData['betteryStatus']) <= $this->minvoltage) {
//                return array("status" => 400, "message" => "Scooter batterey is low. Cant reserve.");
//            }

            if ($this->Common_model->chkBalance($this->input->post('userId'))) {
                $scooterstatus = $this->db->get_where('scooter_parking', array("id" => $this->input->post('scooterParkId'), "status" => ACTIVE))->row_array();
                if ($scooterstatus) {
                    $user = $this->db->query("SELECT * FROM `es_scooter_parking` WHERE reserveUserId='" . $this->input->post('userId') . "' and (scooterStatus='" . RESERVE . "' or isLockUnlock='" . ISUNLOCK . "')")->row_array();
                    if (!$user) {
                        $scooterpark = $this->db->get_where('scooter_parking', array("id" => $this->input->post('scooterParkId'), "scooterStatus" => NOTRESERVE, "isLockUnlock" => ISLOCK))->row_array();
                        if (isset($scooterpark)) {

                            $where = array(
                                "id" => $this->input->post('scooterParkId')
                            );

                            $updateArray = array(
                                "reserveUserId" => $this->input->post('userId'),
                                "scooterStatus" => RESERVE
                            );
                            $update = $this->db->update($this->db->dbprefix('scooter_parking'), $updateArray, $where);
                            $data = array(
                                'userId' => $this->input->post('userId'),
                                'scooterId' => $this->input->post('scooterId'),
                                'scooterParkId' => $this->input->post('scooterParkId'),
                                'scooterNumber' => $this->input->post('scooterNumber'),
                                'distance' => $this->input->post('distance'),
                                'startLocation' => $this->input->post('startLocation'),
                                'startLat' => $this->input->post('startLat'),
                                'startLng' => $this->input->post('startLng'),
                                'reserveDate' => date('Y-m-d'),
                                'reserveTime' => date('H:i:s'),
                                'rideStatus' => RIDEPENDING,
                                'isLockUnlock' => ISLOCK,
                                'status' => ACTIVE,
                                'createdDate' => date('Y-m-d H:i:s'),
                                'updatedDate' => date('Y-m-d H:i:s'),
                            );
                            $add = $this->db->insert('scooter_reserve', $data);
                            $reserveid = $this->db->insert_id();
                            $reserve_scooter = $this->db->get_where('scooter_reserve', array("id" => $reserveid))->row_array();
                            //$ScooterCancelTime = $this->db->get_where('scooter_cancel_time', array("id" => 1, "status" => ACTIVE, "isDeleted" => NOTDELETED))->row_array();
                            $ScooterCancelTime = $this->Common_model->getAdminSetting();
                            $data = array("reserveId" => $reserve_scooter['id'], "scooterParkId" => $reserve_scooter['scooterParkId'], "scooterNumber" => $reserve_scooter['scooterNumber'], "startLocation" => $reserve_scooter['startLocation'], "startLat" => $reserve_scooter['startLat'], "startLng" => $reserve_scooter['startLng'], "distance" => $reserve_scooter['distance'], "countDown" => $ScooterCancelTime['scooterCancelTime']);
                            if ($add) {
                                $this->Common_model->sendNotification($this->input->post('userId'), 'reservescooter');
                                return array("status" => 200, "message" => "Scooter reserve successfully!", "info" => $data);
                            } else {
                                return array("status" => 400, "message" => "Scooter not reserve. Please try later!");
                            }
                        } else {
                            return array("status" => 400, "message" => "Scooter already reserved. Please reserve another scooter!");
                        }
                    } else {
                        return array("status" => 400, "message" => "You are already reserved scooter.!");
                    }
                } else {
                    return array("status" => 400, "message" => "Scooter is not active . Please try another scooter!");
                }
            } else {
                return array("status" => 101, "message" => "Please maintain your balance.!");
            }
        } else {
            return array("status" => 400, "message" => "Invalid post. Please try later!");
        }
    }

    function scooterReserveCancel() {
        if (!empty($this->input->post())) {
            if (empty($this->input->post('reserveId'))) {
                return array("status" => 400, "message" => "Please post reserve id.");
            }
            if (empty($this->input->post('scooterParkId'))) {
                return array("status" => 400, "message" => "Please post scooterParkId.");
            }
            $chk = $this->db->get_where('scooter_reserve', array("id" => $this->input->post('reserveId'), "rideStatus" => RIDECANCEL))->row_array();
            if (!$chk) {
                //parking table status update
                $where = array(
                    "id" => $this->input->post('scooterParkId')
                );

                $updateArray = array(
                    "reserveUserId" => '0',
                    "scooterStatus" => NOTRESERVE
                );
                $update = $this->db->update($this->db->dbprefix('scooter_parking'), $updateArray, $where);
                //reserve table status update
                $where2 = array(
                    "id" => $this->input->post('reserveId'),
                    "isLockUnlock" => ISLOCK
                );

                $updateArray2 = array(
                    "rideStatus" => RIDECANCEL
                );
                $update2 = $this->db->update($this->db->dbprefix('scooter_reserve'), $updateArray2, $where2);
                if ($update2) {
                    return array("status" => 200, "message" => "Your reservation is cancelled  successfully!");
                } else {
                    return array("status" => 400, "message" => "Your reservation not cancelled. Please try again!");
                }
            } else {
                return array("status" => 100, "message" => "Your reservation is cancelled. please reserve again.");
            }
        } else {
            return array("status" => 400, "message" => "Invalid post. Please try later!");
        }
    }

    function scooterReserveUnlock() {

        if (!empty($this->input->post())) {
            if (empty($this->input->post('scooterNumber'))) {
                return array("status" => 400, "message" => "Please post scooter number .");
            }
            if (empty($this->input->post('reserveId'))) {
                return array("status" => 400, "message" => "Please post reserve id .");
            }

            $scooterNumber = $this->input->post('scooterNumber');

            $scooterRow = $this->Common_model->getTrackingIdbyScooterNumber($scooterNumber);
            if (!$scooterRow) {
                return array("status" => 400, "message" => "Please Enter Valid Scooter Number.");
            }
            /*
             * Check tracker is connected with server
             *               */
//            if (!$this->Common_model->checkIsTrackerConnected($scooterRow->tarckId)) {
//                return array("status" => 400, "message" => "Failed to connect. Please try again!");
//            }

            /*
             * Check scooter battery level before reserver
             */
//            $scooterData = $this->Common_model->getScooterData($scooterRow->tarckId);
//
//            if (!$scooterData) {
//                return array("status" => 400, "message" => "Scooter Is Not Able To Connect Server");
//            }
            //Check for scooter connectivity
            /* $responce = $this->Common_model->write_command($scooterRow->tarckId, 'KEEP_ALIVE');
              if ($responce['status'] == 400) {
              return $responce;
              } */

//            if (round($scooterData['betteryStatus']) <= $this->minvoltage) {
//                return array("status" => 400, "message" => "Scooter batterey is low. Cant reserve.");
//            }


            $reserveId = $this->input->post('reserveId');
            $userId = $this->input->post('userId');
            $scooterstatus = $this->db->get_where('scooter_reserve', array("id" => $reserveId, "status" => ACTIVE))->row_array();
            if ($scooterstatus) {
                $chk = $this->db->get_where('scooter_reserve', array("id" => $reserveId, "rideStatus" => RIDECANCEL))->row_array();
                if (!$chk) {
                    $scooter = $this->db->query("SELECT * FROM `es_scooter_reserve` WHERE id='" . $reserveId . "' and userId='" . $userId . "' and BINARY scooterNumber='" . $scooterNumber . "' and rideStatus='" . RIDEPENDING . "' and isLockUnlock='" . ISLOCK . "'")->row_array();
                    if (is_array($scooter)) {

                        //send command and wait for responce
                        // $responce = $this->Common_model->write_command($scooterRow->tarckId, 'POWER_ON');
//                        if ($responce['status'] == 400) {
//                            return array("status" => 400, "message" => "Failed to unlock Scooter!");
//                        } else {
                        //$string_responce = $responce['data'];
                        #####  update reserve status parking table #####
                        $where = array(
                            "id" => $scooter['scooterParkId'],
                        );
                        $updateArray = array(
                            "isLockUnlock" => ISUNLOCK
                        );
                        $update = $this->db->update($this->db->dbprefix('scooter_parking'), $updateArray, $where);

                        #####  update reserve status reserve table #####
                        $where2 = array(
                            "id" => $reserveId,
                            "rideStatus" => RIDEPENDING,
                            "isLockUnlock" => ISLOCK
                        );
                        $updateArray2 = array(
                            'startDate' => date('Y-m-d'),
                            'startTime' => date('H:i:s'),
                            "rideStatus" => RIDERUNNING,
                            "isLockUnlock" => ISUNLOCK
                        );
                        $update2 = $this->db->update($this->db->dbprefix('scooter_reserve'), $updateArray2, $where2);

                        ##### Add track location data  #####


                        $trackdata = array(
                            'reserveId' => $reserveId,
                            'userId' => $userId,
                            'trackLocation' => $scooter['startLocation'],
                            'tracklat' => $scooter['startLat'],
                            'tracklng' => $scooter['startLng'],
                            //'tracklng' => $string_responce['loc'],
                            'distance' => 0,
                            'isDeleted' => NOTDELETED,
                            'createdDate' => date('Y-m-d H:i:s'),
                        );



                        $trackdata = $this->db->insert('scooter_track_location', $trackdata);
                        // $data = array("reserveId" => $reserveId, "scooterNumber" => $scooterNumber);
                        return array("status" => 200, "message" => "Your scooter unlocked successfully", "reserveId" => $reserveId, "scooterNumber" => $scooterNumber, "lat" => $scooter['startLat'], "lng" => $scooter['startLng']);
                        //}
                    } else {
                        return array("status" => 400, "message" => "Please provide valid scooter number!");
                    }
                } else {
                    return array("status" => 100, "message" => "Your reservation is cancelled. Please reserve again.");
                }
            } else {
                return array("status" => 400, "message" => "Scooter is not active . Please try another scooter!");
            }
        } else {
            return array("status" => 400, "message" => "Invalid post. Please try later!");
        }
    }

    function scooterReserveLock() {
        if (!empty($this->input->post())) {
            if (empty($this->input->post('userId'))) {
                return array("status" => 400, "message" => "Please provide user id .");
            }
            if (empty($this->input->post('reserveId'))) {
                return array("status" => 400, "message" => "Please post reserve id .");
            }
            /* if (empty($this->input->post('endLat')) || empty($this->input->post('endLng')) || empty($this->input->post('endLocation'))) {
              return array("status" => 400, "message" => "Please post end location.");
              } */
            //$scooterNumber = $this->input->post('scooterNumber');

            $reserveId = html_escape($this->input->post('reserveId'));
            $userId = html_escape($this->input->post('userId'));
            $scooterstatus = $this->db->get_where('scooter_reserve', array("id" => $reserveId, "status" => ACTIVE))->row_array();
            if ($scooterstatus) {

                $_message = "";
                if ($this->Common_model->checkIsEnteredInRestrictedArea($reserveId)) {
                    //return array("status" => 400, "message" => "Cannot stop scooter as you have entered into restricted area.");
                    $_message .= "You Are In Restricetd Area. ";
                }

                $responce = $this->doStopScooter($scooterstatus, $userId, $reserveId);

                if ($responce['status'] == 200) {
                    $_message .= "Your Ride Stopped Successfully!";
                    $data = $this->getBillingDetails($userId, $reserveId);
                    return array("status" => 200, "message" => $_message, "info" => $data);
                } else {
                    return $responce;
                }
            } else {
                return array("status" => 400, "message" => "Scooter is not active . Please try another scooter!");
            }
        } else {
            return array("status" => 400, "message" => "Invalid post. Please try later!");
        }
    }

    function userRating() {
        $this->load->helper('url');
        if (!empty($this->input->post())) {
//            if (empty($this->input->post('scooterId'))) {
//                return array("status" => 400, "message" => "Please post scooter ids .");
//            }
            if (empty($this->input->post('reverseId'))) {
                return array("status" => 400, "message" => "Please post reserve id .");
            }
            $data = array(
                'userId' => $this->input->post('userId'),
                'reverseId' => $this->input->post('reverseId'),
                //'scooterId' => $this->input->post('scooterId'),
                'rating' => $this->input->post('rating'),
                'comment' => $this->input->post('comment'),
                'createdDate' => date('Y-m-d H:i:s'),
            );
            $imageArray = array();
            if (isset($_FILES) && !empty($_FILES)) {
                $imageCount = 4;
                for ($i = 1; $i <= $imageCount; $i++) {
                    if (isset($_FILES['image' . $i]) && !empty($_FILES['image' . $i])) {

                        $fileUploadPath = FCPATH . '/resource/user_photos/';
                        $fileRes = $this->Common_model->dofileUpload("image$i", $fileUploadPath);
                        if ($fileRes) {
                            if ($fileRes['status'] == 200) {
                                $imageArray['image' . $i] = base_url("resource/user_photos/{$fileRes['data']}");
                            } else {
                                return $fileRes;
                            }
                        } else {
                            return $fileRes;
                        }
                    }
                }
            }

            $alldata = array_merge($data, $imageArray);
            $alldata = $this->db->insert('user_scooter_rating', $alldata);
            if ($alldata) {
                return array("status" => 200, "message" => "You are rating submit successfully");
            } else {
                return array("status" => 400, "message" => "You are rating not submit");
            }
        } else {
            return array("status" => 400, "message" => "Invalid post. Please try later!");
        }
    }

    function getParking() {
        if (!empty($this->input->post())) {
            $userId = $this->input->post('userId');
            $reserveId = $this->input->post('reserveId');
            $this->db->select("id,trackLocation,trackLat,trackLng");
            $this->db->from($this->db->dbprefix('scooter_track_location'));
            $this->db->where("userId", $userId);
            $this->db->where("reserveId", $reserveId);
            $this->db->order_by('id', 'ASC');
            $track_location = $this->db->get()->result_array();

            $result = $this->db->select("id, parkingName, parkingLocation, parkingLat, parkingLng")
                            ->from($this->db->dbprefix('parking'))
                            ->where(array("status" => ACTIVE, "isDeleted" => NOTDELETED))
                            ->get()->result();

            if ($result) {

                $area = $this->db->select("id as restrictedId, location as restrictedLocation, lat as restrictedLat, lng as restrictedLng")
                                ->from($this->db->dbprefix('scooter_restricted_area'))
                                ->where(array("status" => ACTIVE, "isDeleted" => NOTDELETED))
                                ->get()->result();

                return array("status" => 200, "message" => "All parking places!", "info" => $result, "restricted_area" => $area, "track_location" => $track_location);
            } else {
                return array("status" => 400, "message" => "Not found any parking places");
            }
        } else {
            return array("status" => 400, "message" => "Invalid post. Please try later!");
        }
    }

    function getMyTrip() {
        if (!empty($this->input->post())) {
            if (empty($this->input->post('userId'))) {
                return array("status" => 400, "message" => "Please post user id .");
            }
            $results = $this->db->select("s.id,s.scooterNumber,s.startLocation,s.endLocation,s.startDate,s.startTime,s.endDate,"
                                    . " s.endTime,b.runningTime,b.runningSecond,b.runningDistance,b.basefair,b.distanceBill,b.timeBill,"
                                    . " b.totalBill,b.offerRedeem,b.discountAmount,b.penaltyAmount")
                            ->from($this->db->dbprefix('scooter_reserve') . " AS s")
                            ->join($this->db->dbprefix('bill_summary') . " AS b", "s.id=b.reserveId", "left")
                            ->where(array("s.userId" => $this->input->post('userId'), "s.rideStatus" => RIDECOMPLETE))
                            ->order_by('s.id', 'DESC')
                            ->get()->result_array();

            if ($results) {
                $collection = array();
                foreach ($results as $key => $data) {
                    $startTime = date("g:i a", strtotime($data['startTime']));
                    $endTime = date("g:i a", strtotime($data['endTime']));
                    //convert minutes to hrs
                    $runningTime = $this->Common_model->convertMinutesToHrs($data['runningTime'], $data['runningSecond']);

                    $collection[] = array(
                        "tripid" => $data['id'],
                        "scooterNumber" => $data['scooterNumber'],
                        "startLocation" => $data['startLocation'],
                        "endLocation" => $data['endLocation'],
                        "startDate" => date('d-m-Y', strtotime($data['startDate'])),
                        "startTime" => $startTime,
                        "endDate" => date('d-m-Y', strtotime($data['endDate'])),
                        "endTime" => $endTime,
                        "runningTime" => $runningTime,
                        "runningDistance" => $data['runningDistance'],
                        "totalBill" => $data['totalBill'],
                        "offerRedeem" => $data['offerRedeem'],
                        "discountAmount" => $data['discountAmount'],
                        "penaltyAmount" => $data['penaltyAmount']
                    );
                }
                return array("status" => 200, "message" => "All my completed trips!", "info" => $collection);
            } else {
                return array("status" => 400, "message" => "Not found any my completed trips");
            }
        } else {
            return array("status" => 400, "message" => "Invalid post. Please try later!");
        }
    }

    function getMyTripDetails() {
        if (!empty($this->input->post())) {
            if (empty($this->input->post('userId'))) {
                return array("status" => 400, "message" => "Please post user id .");
            }
            if (empty($this->input->post('tripId'))) {
                return array("status" => 400, "message" => "Please post trip id .");
            }
            $where = array(
                "s.id" => $this->input->post('tripId'),
                "s.userId" => $this->input->post('userId'),
                "s.rideStatus" => RIDECOMPLETE
            );

            $data = $this->db->select("s.id, s.scooterNumber, s.startLocation ,s.endLocation, s.startDate, s.startTime, "
                                    . "s.endDate, s.endTime, b.id as billSummaryId, b.runningTime, b.runningSecond, "
                                    . "b.runningDistance, b.basefair, b.distanceBill, b.timeBill, b.totalBill, "
                                    . "b.offerRedeem, b.discountAmount, b.penaltyAmount, r.rating, t.transctionId")
                            ->from($this->db->dbprefix('scooter_reserve') . " AS s")
                            ->join($this->db->dbprefix('user_scooter_rating') . " AS r", "s.id=r.reverseId ", "LEFT")
                            ->join($this->db->dbprefix('bill_summary') . " AS b", "s.id=b.reserveId", "LEFT")
                            ->join($this->db->dbprefix('user_topup_transactions') . " AS t", "b.id=t.billSummaryId", "LEFT")
                            ->where($where)
                            ->get()->row_array();

            if (isset($data)) {
                $where = array(
                    "reserveId" => $data['id'],
                    "userId" => $this->input->post('userId'),
                    "isDeleted" => NOTDELETED
                );
                $tracking = $this->db->get_where('scooter_track_location', $where)
                        ->result_array();

                if ($tracking) {
                    foreach ($tracking as $key => $tracking) {
                        $tracking2[] = array(
                            "id" => $tracking['id'],
                            "trackLocation" => $tracking['trackLocation'],
                            "trackLat" => $tracking['trackLat'],
                            "trackLng" => $tracking['trackLng']
                        );
                    }
                }
                if (empty($data['rating']) || $data['rating'] == '' || $data['rating'] == NULL) {
                    $data['rating'] = "0";
                }
                $startTime = date("g:i a", strtotime($data['startTime']));
                $endTime = date("g:i a", strtotime($data['endTime']));
                $runningTime = $this->Common_model->convertMinutesToHrs($data['runningTime'], $data['runningSecond']);
                $data = array(
                    "id" => $data['id'],
                    "scooterNumber" => $data['scooterNumber'],
                    "startLocation" => $data['startLocation'],
                    "endLocation" => $data['endLocation'],
                    "startDate" => date('d-m-Y', strtotime($data['startDate'])),
                    "startTime" => $startTime,
                    "endDate" => date('d-m-Y', strtotime($data['endDate'])),
                    "endTime" => $endTime,
                    "runningTime" => $runningTime,
                    "runningDistance" => $data['runningDistance'],
                    "basefair" => $data['basefair'],
                    "distanceBill" => $data['distanceBill'],
                    "timeBill" => $data['timeBill'],
                    "timeBill" => $data['timeBill'],
                    "totalBill" => $data['totalBill'],
                    "offerRedeem" => $data['offerRedeem'],
                    "discountAmount" => $data['discountAmount'],
                    "penaltyAmount" => $data['penaltyAmount'],
                    "rating" => $data['rating'],
                    "transctionId" => $data['transctionId'],
                    "trackingdata" => $tracking2
                );
                return array("status" => 200, "message" => "my completed trip details!", "info" => $data);
            } else {

                return array("status" => 400, "message" => "Not found any my completed trips");
            }
        } else {
            return array("status" => 400, "message" => "Invalid post. Please try later!");
        }
    }

    function addTracking() {
        if (!empty($this->input->post())) {
            if (empty($this->input->post('userId'))) {
                return array("status" => 400, "message" => "Please post user id .");
            }
            if (empty($this->input->post('reserveId'))) {
                return array("status" => 400, "message" => "Please post reserve id .");
            }
//            if (empty($this->input->post('location')) || empty($this->input->post('lat')) || empty($this->input->post('lng'))) {
//                return array("status" => 400, "message" => "Please post location with latitude and longitude.");
//            }
            $trackLocation = $this->db->query("SELECT * FROM `es_scooter_track_location` WHERE reserveId='" . $this->input->post('reserveId') . "' and userId='" . $this->input->post('userId') . "' ORDER BY id DESC LIMIT 1")->row_array();
            if ($trackLocation) {
                $distance = $this->Common_model->distance_haversine($trackLocation['trackLat'], $trackLocation['trackLng'], $this->input->post('lat'), $this->input->post('lng'));
            } else {
                $distance = 0;
            }
            $trackdata = array(
                'reserveId' => $this->input->post('reserveId'),
                'userId' => $this->input->post('userId'),
                'trackLocation' => $this->input->post('location'),
                'tracklat' => $this->input->post('lat'),
                'tracklng' => $this->input->post('lng'),
                'distance' => $distance,
                'isDeleted' => NOTDELETED,
                'createdDate' => date('Y-m-d H:i:s'),
            );
            $trackdata = $this->db->insert('scooter_track_location', $trackdata);
            if ($trackdata) {
                return array("status" => 200, "message" => "Tracking location add successfully!");
            } else {
                return array("status" => 400, "message" => "Tracking location not add. Please try again!");
            }
        } else {
            return array("status" => 400, "message" => "Invalid post. Please try later!");
        }
    }

    function getRideDetails() {
        if (!empty($this->input->post())) {
            if (empty($this->input->post('userId'))) {
                return array("status" => 400, "message" => "Please post user id .");
            }
            if (empty($this->input->post('reserveId'))) {
                return array("status" => 400, "message" => "Please post reserve id .");
            }

            $where = array(
                "s.id" => $this->input->post('reserveId'),
                "s.userId" => $this->input->post('userId'),
                "s.rideStatus" => RIDECOMPLETE
            );
            $scooter = $this->db->select("s.id, s.scooterId, s.scooterNumber, s.startLocation, s.endLocation, "
                                    . "s.startDate, s.startTime, s.endDate, s.endTime, b.runningTime, b.runningSecond, "
                                    . "b.runningDistance, b.basefair, b.distanceBill, b.timeBill, b.offerRedeem, b.discountAmount, "
                                    . "b.penaltyAmount, b.totalBill")
                            ->from($this->db->dbprefix('scooter_reserve') . " AS s")
                            ->join($this->db->dbprefix('bill_summary') . " AS b", "s.id=b.reserveId", "LEFT")
                            ->where($where)
                            ->get()->row_array();

            $runningTime = $this->Common_model->convertMinutesToHrs($scooter['runningTime'], $scooter['runningSecond']);

            $startTime = date("g:i a", strtotime($scooter['startTime']));
            $endTime = date("g:i a", strtotime($scooter['endTime']));

            $data = array(
                "reserveId" => $scooter['id'],
                "scooterId" => $scooter['scooterId'],
                "scooterNumber" => $scooter['scooterNumber'],
                "startLocation" => $scooter['startLocation'],
                "endLocation" => $scooter['endLocation'],
                "startDate" => date('d-m-Y', strtotime($scooter['startDate'])),
                "startTime" => date("g:i a", strtotime($scooter['startTime'])),
                "endDate" => date('d-m-Y', strtotime($scooter['endDate'])),
                "endTime" => date("g:i a", strtotime($scooter['endTime'])),
                "runningTime" => $runningTime,
                "runningDistance" => $scooter['runningDistance'],
                "basefair" => $scooter['basefair'],
                "distanceBill" => $scooter['distanceBill'],
                "timeBill" => $scooter['timeBill'],
                "offerRedeem" => $scooter['offerRedeem'],
                "discountAmount" => $scooter['discountAmount'],
                "penaltyAmount" => $scooter['penaltyAmount'],
                "totalBill" => $scooter['totalBill']
            );

            if ($data) {
                return array("status" => 200, "message" => "Your completed ride details", "info" => $data);
            } else {
                return array("status" => 400, "message" => "Not found ride details. Please try again!");
            }
        } else {
            return array("status" => 400, "message" => "Invalid post. Please try later!");
        }
    }

    function chkReservationStatus() {
        if (!empty($this->input->post())) {
            if (empty($this->input->post('reserveId'))) {
                return array("status" => 400, "message" => "Please post reserve id.");
            }

            $chk = $this->db->get_where('scooter_reserve', array("id" => $this->input->post('reserveId'), "rideStatus" => RIDECANCEL))->row_array();
            if (!$chk) {
                return array("status" => 200, "message" => "Your reservation is not cancelled.");
            } else {
                return array("status" => 400, "message" => "Your reservation is cancelled. please reserve again.");
            }
        } else {
            return array("status" => 400, "message" => "Invalid post. Please try later!");
        }
    }

    function getTimeandDistance() {
        if (!empty($this->input->post())) {
            if (empty($this->input->post('reserveId'))) {
                return array("status" => 400, "message" => "Please post reserve id.");
            }
            if (empty($this->input->post('userId'))) {
                return array("status" => 400, "message" => "Please post user id.");
            }
            $reserveId = html_escape($this->input->post('reserveId'));
            $userId = html_escape($this->input->post('userId'));

            $where = array("id" => $reserveId, "userId" => $userId);

            $scooter = $this->db->get_where('scooter_reserve', $where)->row_array();

            $where = array(
                "reserveId" => $reserveId,
                "userId" => $userId,
            );

            $trackLocations = $this->db->select("distance")
                            ->from($this->db->dbprefix('scooter_track_location'))
                            ->where($where)
                            ->order_by("id", "ASC")
                            ->get()->result_array();


            $runningDistance = '';
            foreach ($trackLocations as $key => $trackLocation) {
                $runningDistance += round($trackLocation['distance'], 2);
            }
            if ($scooter['rideStatus'] == RIDERUNNING) {

                $enteredInTrestricetdArea = $this->Common_model->checkIsEnteredInRestrictedArea($reserveId);
                $runningTime = $this->Common_model->getTimer($scooter['startDate'], $scooter['startTime'], date('Y-m-d'), date('H:i:s'));
                return array(
                    "status" => 200,
                    "message" => "Your Ride Running Time And Distance.",
                    "runningTime" => $runningTime,
                    "runningDistance" => $runningDistance,
                    "enteredInTrestricetdArea" => $enteredInTrestricetdArea,
                );
            } elseif ($scooter['rideStatus'] == RIDECOMPLETE) {

                $data = $this->getBillingDetails($userId, $reserveId);
                return array("status" => 101, "message" => "Ride Stopped.", "info" => $data);
            } else {
                return array("status" => 400, "message" => "Not found ride details. Please try again!");
            }
        } else {
            return array("status" => 400, "message" => "Invalid Post. Please Try Later!");
        }
    }

    //temproaroy
    function getScooterListtemp() {
        if (!empty($this->input->post())) {
            if (empty($this->input->post('latitude')) || empty($this->input->post('longitude'))) {
                return array("status" => 400, "message" => "Please post latitude and longitude .");
            }
            $latitude = $this->input->post('latitude');
            $longitude = $this->input->post('longitude');
            $adminSettings = $this->Common_model->getAdminSetting();
            $predefinedRadius = $adminSettings['scooterRadius']; //20; // Radius in KM
            $radious = $predefinedRadius;
            ###my radious scooter
            $myScooters = $this->db->query("SELECT * , COALESCE((((acos(sin(('" . $latitude . "' *pi()/180)) * sin(( lat *pi()/180))+cos(('" . $latitude . "' *pi()/180)) * cos((lat *pi()/180)) * cos((('" . $longitude . "' - lng)*pi()/180))))*180/pi())*60*1.1515*1.609344),0) as distance FROM es_scooter_parking where status='" . ACTIVE . "' and scooterStatus='" . NOTRESERVE . "' and isDeleted='" . NOTDELETED . "' and COALESCE((((acos(sin(('" . $latitude . "' *pi()/180)) * sin(( lat *pi()/180))+cos(('" . $latitude . "' *pi()/180)) * cos((lat *pi()/180)) * cos((('" . $longitude . "' - lng)*pi()/180))))*180/pi())*60*1.1515*1.609344),0)<'" . $radious . "'");
            $myScooters = $myScooters->result_array();
            //other radious scooter
            $otherScooters = $this->db->query("SELECT * , COALESCE((((acos(sin(('" . $latitude . "' *pi()/180)) * sin(( lat *pi()/180))+cos(('" . $latitude . "' *pi()/180)) * cos((lat *pi()/180)) * cos((('" . $longitude . "' - lng)*pi()/180))))*180/pi())*60*1.1515*1.609344),0) as distance FROM es_scooter_parking where status='" . ACTIVE . "' and scooterStatus='" . NOTRESERVE . "' and isDeleted='" . NOTDELETED . "' and COALESCE((((acos(sin(('" . $latitude . "' *pi()/180)) * sin(( lat *pi()/180))+cos(('" . $latitude . "' *pi()/180)) * cos((lat *pi()/180)) * cos((('" . $longitude . "' - lng)*pi()/180))))*180/pi())*60*1.1515*1.609344),0)>'" . $radious . "'");
            $otherScooters = $otherScooters->result_array();

            $radious = $predefinedRadius * 1000; // Radius in meters

            $myScootersdata = array();
            $otherScooterdata = array();
            if ($myScooters != '' && $otherScooters != '') {

                foreach ($myScooters as $key => $scooter) {
                    $myScootersdata[] = array(
                        "scooterParkId" => $scooter['id'],
                        "scooterId" => $scooter['scooterId'],
                        "scooterNumber" => $scooter['scooterNumber'],
                        "scooterLocation" => $scooter['location'],
                        "scooterLat" => $scooter['lat'],
                        "scooterLng" => $scooter['lng'],
                        "distance" => $scooter['distance'],
                        "rate" => $adminSettings['scooterPerMinChrages']
                    );
                }
                foreach ($otherScooters as $key => $otherScooter) {
                    $otherScooterdata[] = array(
                        "scooterParkId" => $otherScooter['id'],
                        "scooterId" => $otherScooter['scooterId'],
                        "scooterNumber" => $otherScooter['scooterNumber'],
                        "scooterLocation" => $otherScooter['location'],
                        "scooterLat" => $otherScooter['lat'],
                        "scooterLng" => $otherScooter['lng'],
                        "distance" => $otherScooter['distance']
                    );
                }

                return array("status" => 200,
                    "message" => "Scooter list!",
                    "radious" => $radious,
                    "info" => $myScootersdata,
                    "otherInfo" => $otherScooterdata
                );
            } else {
                return array("status" => 400, "message" => "Not found any scooter!");
            }
        } else {
            return array("status" => 400, "message" => "Invalid post. Please try later!");
        }
    }

    public function doStopScooter($scooterstatus = array(), $userId = false, $reserveId = false) {

        $scooterRow = $this->Common_model->getTrackingIdbyScooterNumber($scooterstatus['scooterNumber']);
        if (!$scooterRow) {
            return array("status" => 400, "message" => "No tracking device aligned with this scooter .");
        }
        //send command and wait for responce
        $responce = $this->Common_model->write_command($scooterRow->tarckId, 'POWER_OFF');

        if ($responce['status'] == 400) {
            return array("status" => 400, "message" => "Failed to Stop Scooter!");
        } else {

            $string_responce = $responce['data'];

            $where = array(
                "id" => $reserveId,
                "userId" => $userId,
                "isLockUnlock" => ISUNLOCK,
            );
            $chkscooter = $this->db->get_where('scooter_reserve', $where)->row_array();
            if (is_array($chkscooter)) {

                $row = $this->db->select("trackLat, trackLng")
                                ->from($this->db->dbprefix('scooter_track_location'))
                                ->where("reserveId", $reserveId)
                                ->where("isDeleted", NOTDELETED)
                                ->order_by('id', 'DESC')
                                ->limit(1)
                                ->get()->row();

                $lastAddress = $this->Common_model->getaddress($row->trackLat, $row->trackLng);

                #####  update not reserve status parking table  #####
                $where = array(
                    "id" => $chkscooter['scooterParkId'],
                );
                $updateArray = array(
                    "reserveUserId" => '0',
                    "location" => $lastAddress,
                    "lat" => $row->trackLat,
                    "lng" => $row->trackLng,
                    "scooterStatus" => NOTRESERVE,
                    "isLockUnlock" => ISLOCK
                );
                $update = $this->db->update($this->db->dbprefix('scooter_parking'), $updateArray, $where);

                #####  update reserve status reserve table  #####
                $where_reserve = array(
                    "id" => $reserveId,
                    "userId" => $userId,
                    "rideStatus" => RIDERUNNING,
                    "isLockUnlock" => ISUNLOCK
                );

                $updateArray_reserve = array(
                    'endLocation' => $lastAddress,
                    'endLat' => $row->trackLat,
                    'endLng' => $row->trackLng,
                    'endDate' => date('Y-m-d'),
                    'endTime' => date('H:i:s'),
                    "rideStatus" => RIDECOMPLETE,
                    "isLockUnlock" => ISLOCK,
                    "updatedDate" => date('Y-m-d H:i:s')
                );

                $update2 = $this->db->update($this->db->dbprefix('scooter_reserve'), $updateArray_reserve, $where_reserve);

                ##### Add bill summeary data  #####

                $date = new DateTime($chkscooter['startDate'] . ' ' . $chkscooter['startTime']);
                $date2 = new DateTime($chkscooter['endDate'] . ' ' . $chkscooter['endTime']);
                $num_seconds = $date2->getTimestamp() - $date->getTimestamp();
                $second = $num_seconds % 60;
                $runningMinutes = floor($num_seconds / 60);
                if ($second != 0) {
                    $runningMinutes = $runningMinutes + 1;
                }
                //get distance
                $distance = $this->Common_model->getDistance($reserveId, $userId);
                $adminsetting = $this->Common_model->getAdminSetting();
                //calculate summary
                //$basefair = $adminsetting['scooterBaseFair'];
                $timeBill = $runningMinutes * $adminsetting['scooterPerMinChrages'];
                //$totalAmount = $basefair + $timeBill;
                $totalAmount = $timeBill;
                //actual running minutes
                if ($second != 0) {
                    $netRunningMinutes = $runningMinutes - 1;
                }
                //get offers amount and calculation
                $chkoffers = $this->db->get_where('user_promo_code_offers', array("userId" => $userId, "reserveId" => $reserveId, "status" => ACTIVE, "isDeleted" => NOTDELETED))->row_array();

                if (isset($chkoffers)) {
                    $offers = $this->db->get_where('offers', array("id" => $chkoffers['offerId']))->row_array();
                    $offerTitle = $offers['offerPrice'] . '% OFF';
                    $discountAmount = $offers['offerPrice'] / 100 * $totalAmount;
                    $totalBill = $totalAmount - $discountAmount;
                } else {
                    $offerTitle = '';
                    $discountAmount = 0;
                    $totalBill = $totalAmount;
                }

                $billsummarydata = array(
                    'reserveId' => $reserveId,
                    'userId' => $userId,
                    'scooterNumber' => $chkscooter['scooterNumber'],
                    'runningTime' => $netRunningMinutes,
                    'runningSecond' => $second,
                    'runningDistance' => $distance,
                    //'basefair' => $basefair,
                    'distanceBill' => 0,
                    'timeBill' => $timeBill,
                    'offerRedeem' => $offerTitle,
                    'discountAmount' => $discountAmount,
                    'totalBill' => $totalBill,
                    'status' => ACTIVE,
                    'createdDate' => date('Y-m-d H:i:s'),
                );

                if ($this->db->insert('bill_summary', $billsummarydata)) {
                    ## add transction data ####
                    $billsummaryId = $this->db->insert_id();
                    $val = date("Ymdhis");
                    $transctionId = "RIDT-{$val}";

                    $topupTransactions = array(
                        'userId' => $userId,
                        'billSummaryId' => $billsummaryId,
                        'transctionId' => $transctionId,
                        'price' => $totalBill,
                        'transactionsType' => RIDETRANSCTIONS,
                        'status' => ACTIVE,
                        'isDeleted' => NOTDELETED,
                        'createdDate' => date('Y-m-d H:i:s'),
                    );

                    if ($this->db->insert('user_topup_transactions', $topupTransactions)) {
                        $this->Common_model->sendNotification($userId, 'stopride');
                        return array("status" => 200);
                    } else {

                        return array("status" => 400, "message" => "Transaction not updated");
                    }
                } else {
                    return array("status" => 400, "message" => "Not added bill summary!");
                }
            } else {
                return array("status" => 400, "message" => "Please provide valide reserve id!");
            }
        }
    }

    public function getBillingDetails($userId = false, $reserveId = false) {
        ###### get data #######
        $where = array(
            "s.id" => $reserveId,
            "s.userId" => $userId,
        );
        $scooter = $this->db->select("s.id, s.scooterId, s.scooterNumber, s.startLocation, s.endLocation,"
                                . " s.startDate, s.startTime, s.endDate, s.endTime, b.runningTime, b.runningDistance, "
                                . " b.basefair, b.distanceBill, b.timeBill, b.offerRedeem, b.discountAmount, b.penaltyAmount, b.totalBill")
                        ->from($this->db->dbprefix('scooter_reserve') . " AS s")
                        ->join($this->db->dbprefix('bill_summary') . " AS b", "s.id=b.reserveId", "LEFT")
                        ->where($where)
                        ->get()->row_array();

        return array(
            "reserveId" => $scooter['id'],
            "scooterId" => $scooter['scooterId'],
            "scooterNumber" => $scooter['scooterNumber'],
            "startLocation" => $scooter['startLocation'],
            "endLocation" => $scooter['endLocation'],
            "startDate" => $scooter['startDate'],
            "startTime" => $scooter['startTime'],
            "endDate" => $scooter['endDate'],
            "endTime" => $scooter['endTime'],
            "runningTime" => $scooter['runningTime'],
            "runningDistance" => $scooter['runningDistance'],
            "basefair" => $scooter['basefair'],
            "distanceBill" => $scooter['distanceBill'],
            "timeBill" => $scooter['timeBill'],
            "offerRedeem" => $scooter['offerRedeem'],
            "discountAmount" => $scooter['discountAmount'],
            "penaltyAmount" => $scooter['penaltyAmount'],
            "totalBill" => $scooter['totalBill']
        );
    }

}

?>