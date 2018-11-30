<?php

class UserMaster_model extends CI_Model {

    public function __construct() {
        $this->load->database();
        $this->load->model('api/Common_model', 'Common_model', true);
        $this->load->helper('common_helper');
        $this->load->helper('url');
    }

    function chkUserDetail($mobile) {
        return $this->db->get_where('user_master', array("mobile" => $mobile, "verified" => VERIFIED, "isDeleted" => NOTDELETED))->row_array();
    }

    function chkFacebookId($fbId, $email) {
        return $this->db->get_where('user_master', array("email" => $email, "facebookId" => $fbId, "isDeleted" => NOTDELETED))->row_array();
    }

    function signeUp() {

        if (!empty($this->input->post())) {
            if ($this->input->post('signUpType') == '') {
                return array("status" => 400, "message" => "Please Provide signup type.");
            }

            if ($this->input->post('signUpType') == SIGNUPGEN) {
                /*
                  General user sign up
                 */
                if ($this->input->post('mobile') != '' || !empty($this->input->post('mobile'))) {
                    if ($this->Common_model->chkmobile2($this->input->post('mobile'))) {
                        return array("status" => 400, "message" => "Mobile number entered is exist in system. Please use different mobile number .");
                    }
                }
                if ($this->Common_model->chkemail($this->input->post('email'))) {
                    return array("status" => 400, "message" => "Email id entered is exist in system. Please use different email id .");
                }
                if (strval($this->input->post('password')) != strval($this->input->post('cpassword'))) {
                    return array("status" => 400, "message" => "Confirm password not matching.");
                }

                $randomString = strtoupper($this->Common_model->randomString());
                $data = array(
                    'userName' => $this->input->post('userName'),
                    'email' => $this->input->post('email'),
                    'mobile' => $this->input->post('mobile'),
                    'password' => encode($this->input->post('password')),
                    'aboveYear' => $this->input->post('aboveYear'),
                    'refferralCode' => $randomString,
                    'verified' => NOTVERIFIED,
                    'status' => ACTIVE,
                    'role' => $this->input->post('role'),
                    'signUpType' => SIGNUPGEN,
                    'deviceId' => $this->input->post('deviceId'),
                    'tokenId' => $this->input->post('tokenId'),
                    'deviceType' => $this->input->post('deviceType'),
                    'isDeleted' => NOTDELETED,
                    'createdDate' => date('Y-m-d H:i:s'),
                    'isLogged' => NOTLOGGED,
                );

                if ($this->db->insert('user_master', $data)) {

                    $userId = $this->db->insert_id();

                    $user_profile = $this->db->get_where('user_master', array("id" => $userId))->row_array();
                    $result = $this->Common_model->sendUserOTP(substr($user_profile['mobile'], 3), $userId);
                    //get user current balance in wallet
                    $totalBalance = $this->Common_model->getUserCurrentBalance($user_profile['id']);
                    if ($totalBalance['status'] == 200) {
                        $currentBalance = $totalBalance['currentBalance'];
                    } else {
                        $currentBalance = "0";
                    }
                    $data = array("userId" => $user_profile['id'], "userName" => $user_profile['userName'], "email" => $user_profile['email'], "mobile" => $user_profile['mobile'], "profileImage" => $user_profile['profileImage'], "location" => $user_profile['location'], "refferalCode" => $user_profile['refferralCode'], "verified" => $user_profile['verified'], "role" => $user_profile['role'], "signUpType" => $user_profile['signUpType'], "currentBalance" => "$currentBalance", "depositAmount" => $totalBalance['depositAmount']);
                    if ($result['status'] == 200) {

                        return array("status" => 200, "message" => "User registered successfully!", "info" => $data);
                    } else {
                        return $result;
                    }
                } else {
                    return array("status" => 400, "message" => "User not registered. Please try later!");
                }
            } else {
                /*
                  facebook sign up
                 */

                if ($this->input->post('facebookId') == '' || empty($this->input->post('facebookId'))) {
                    return array("status" => 400, "message" => "Please Provide facebook id.");
                }
                //check already exit or not
                if ($user_profile = $this->chkFacebookId($this->input->post('facebookId'), $this->input->post('email'))) {
                    /*
                      update login date
                     */
                    $updateArray = array(
                        "deviceId" => $this->input->post('deviceId'),
                        "tokenId" => $this->input->post('tokenId'),
                        "deviceType" => $this->input->post('deviceType'),
                        "verified" => VERIFIED,
                        "isLogged" => LOGGED,
                        "loggedDate" => date("Y-m-d H:i:s"),
                    );
                    $this->db->update($this->db->dbprefix('user_master'), $updateArray, array("id" => $user_profile['id']));

                    //get user current balance in wallet
                    $totalBalance = $this->Common_model->getUserCurrentBalance($user_profile['id']);
                    if ($totalBalance['status'] == 200) {
                        // $temp = $totalBalance['currentBalance'];
                        $currentBalance = $totalBalance['currentBalance'];
                    } else {
                        $currentBalance = "0";
                    }
                    $data = array("userId" => $user_profile['id'], "userName" => $user_profile['userName'], "email" => $user_profile['email'], "mobile" => $user_profile['mobile'], "profileImage" => $user_profile['profileImage'], "location" => $user_profile['location'], "refferalCode" => $user_profile['refferralCode'], "verified" => $user_profile['verified'], "role" => $user_profile['role'], "signUpType" => $user_profile['signUpType'], "currentBalance" => "$currentBalance", "depositAmount" => $totalBalance['depositAmount']);


                    return array("status" => 200, "message" => "You are successfully logged in!", "info" => $data);
                } else {

                    if ($this->input->post('mobile') != '' || !empty($this->input->post('mobile'))) {
                        if ($this->Common_model->chkmobile2($this->input->post('mobile'))) {
                            return array("status" => 400, "message" => "Mobile number entered is exist in system. Please use different mobile number .");
                        }
                    }
                    if ($this->input->post('email') != '' || !empty($this->input->post('email'))) {
                        if ($this->Common_model->chkemail($this->input->post('email'))) {
                            return array("status" => 400, "message" => "Email id entered is exist in system. Please use different email id .");
                        }
                    }
                    $randomString = strtoupper($this->Common_model->randomString());
                    $data = array(
                        'userName' => $this->input->post('userName'),
                        'email' => $this->input->post('email'),
                        'mobile' => $this->input->post('mobile'),
                        'aboveYear' => $this->input->post('aboveYear'),
                        'profileImage' => $this->input->post('profileImage'),
                        'refferralCode' => $randomString,
                        'facebookId' => $this->input->post('facebookId'),
                        'verified' => VERIFIED,
                        'status' => ACTIVE,
                        'role' => $this->input->post('role'),
                        'signUpType' => SIGNUPFB,
                        'deviceId' => $this->input->post('deviceId'),
                        'tokenId' => $this->input->post('tokenId'),
                        'deviceType' => $this->input->post('deviceType'),
                        'isDeleted' => NOTDELETED,
                        'createdDate' => date('Y-m-d H:i:s'),
                        'isLogged' => LOGGED,
                        'loggedDate' => date('Y-m-d H:i:s'),
                    );

                    if ($this->db->insert('user_master', $data)) {
                        $userId = $this->db->insert_id();
                        //get data register
                        $user_profile = $this->db->get_where('user_master', array("id" => $userId))->row_array();
                        //get user current balance in wallet
                        $totalBalance = $this->Common_model->getUserCurrentBalance($user_profile['id']);
                        if ($totalBalance['status'] == 200) {
                            // $temp = $totalBalance['currentBalance'];
                            $currentBalance = $totalBalance['currentBalance'];
                        } else {
                            $currentBalance = "0";
                        }

                        $data = array("userId" => $user_profile['id'],
                            "userName" => $user_profile['userName'],
                            "email" => $user_profile['email'],
                            "mobile" => $user_profile['mobile'],
                            "profileImage" => $user_profile['profileImage'],
                            "location" => $user_profile['location'],
                            "refferalCode" => $user_profile['refferralCode'],
                            "verified" => $user_profile['verified'],
                            "role" => $user_profile['role'],
                            "signUpType" => $user_profile['signUpType'],
                            "currentBalance" => "$currentBalance",
                            "depositAmount" => $totalBalance['depositAmount']);

                        return array("status" => 200, "message" => "You are successfully logged in!", "info" => $data);
                    } else {
                        return array("status" => 400, "message" => "You are not logged in. Please try again!");
                    }
                }
            }
        } else {
            return array("status" => 400, "message" => "Invalid post. Please try later!");
        }
    }

    function signeIn() {

        if (!empty($this->input->post())) {
            if (empty($this->input->post('mobile'))) {
                return array("status" => 400, "message" => "Please provide registred mobile number.");
            }
            if (empty($this->input->post('password'))) {
                return array("status" => 400, "message" => "Please provide password.");
            }
            $user_profile = $this->db->get_where('user_master', array("mobile" => $this->input->post('mobile'), "password" => encode($this->input->post('password')), "isDeleted" => NOTDELETED))->row_array();
            if (is_array($user_profile)) {
                //get user current balance in wallet
                $totalBalance = $this->Common_model->getUserCurrentBalance($user_profile['id']);
                if ($totalBalance['status'] == 200) {
                    $currentBalance = $totalBalance['currentBalance'];
                } else {
                    $currentBalance = "0";
                }
                $data = array("userId" => $user_profile['id'], "userName" => $user_profile['userName'], "email" => $user_profile['email'], "mobile" => $user_profile['mobile'], "profileImage" => $user_profile['profileImage'], "location" => $user_profile['location'], "refferalCode" => $user_profile['refferralCode'], "verified" => $user_profile['verified'], "role" => $user_profile['role'], "signUpType" => $user_profile['signUpType'], "currentBalance" => "$currentBalance", "depositAmount" => $totalBalance['depositAmount']);

                if (intval($user_profile['verified']) == NOTVERIFIED) {
                    $this->Common_model->sendUserOTP(substr($user_profile['mobile'], 3), $user_profile['id']);
                    return array("status" => 400, "message" => "Account is not verified. Please verify your account!", "info" => $data);
                } else if (intval($user_profile['status']) == NOTACTIVE) {
                    return array("status" => 422, "message" => "User is blocked. Please try later!");
                } else {
                    //check ride start or not
                    if ($this->Common_model->chkRideStatus($user_profile['id'])) {
                        return array("status" => 400, "message" => "You have started ride using another device.");
                    } else {
                        $updateArray = array(
                            'deviceId' => $this->input->post('deviceId'),
                            'tokenId' => $this->input->post('tokenId'),
                            'deviceType' => $this->input->post('deviceType'),
                            "isLogged" => LOGGED,
                            "loggedDate" => date("Y-m-d H:i:s"),
                        );
                        $this->db->update($this->db->dbprefix('user_master'), $updateArray, array("id" => $user_profile['id']));
                        return array("status" => 200, "message" => "You are successfully logged in", "info" => $data);
                    }
                }
            } else {
                return array("status" => 400, "message" => "Please provide registered mobile number and password!");
            }
        } else {
            return array("status" => 400, "message" => "Invalid post. Please try later!");
        }
    }

    //check OPT
    public function checkOTP() {

        if (!empty($this->input->post())) {
            if (empty($this->input->post('userId'))) {
                return array("status" => 400, "message" => "Please provide user id.");
            }

            $userId = html_escape($this->input->post('userId'));
            $otp = html_escape($this->input->post('otp'));

            $where = array(
                "userId" => $userId,
                "otp" => $otp,
                "verified" => NOTVERIFIED,
            );

            $result = $this->db->select("id")
                            ->from($this->db->dbprefix('user_otp'))
                            ->where($where)
                            ->order_by('createdDate', "desc")
                            ->get()->row_array();

            $lastRow = $this->db->select("MAX(id) as lastest", false)
                            ->from($this->db->dbprefix('user_otp'))
                            ->where("userId", $userId)
                            ->get()->row_array();

            $row = $this->db->select("mobile")
                            ->from($this->db->dbprefix('user_master'))
                            ->where("id", $userId)
                            ->where("isDeleted", NOTDELETED)
                            ->get()->row();

            if ($row) {
                $mobile = strval(substr($row->mobile, 3));
            } else {
                $mobile = 0;
            }
            $otpResult = $this->Common_model->verifyOTP($mobile, $otp);

            if ($result && $otpResult['status'] == 200) {
                //opt update status 
                $userUpdateArray = array("verified" => VERIFIED);
                $userUpdateWhere = array("id" => $result['id'], "verified" => NOTVERIFIED);
                $this->db->update($this->db->dbprefix('user_otp'), $userUpdateArray, $userUpdateWhere);

                //user Upadte status
                $userAccUpdateArray = array("verified" => VERIFIED);
                $userAccUpdateWhere = array("id" => $userId, "verified" => NOTVERIFIED);

                if ($this->db->update($this->db->dbprefix('user_master'), $userAccUpdateArray, $userAccUpdateWhere)) {
                    return array("status" => 200, "message" => "User verified successfully!");
                } else {
                    return array("status" => 400, "message" => "Not confirm OTP. Please try again later!");
                }
            } else {
                return array("status" => 400, "message" => $otpResult['message']);
            }
        } else {
            return array("status" => 400, "message" => "Invalid post. Please try later!");
        }
    }

    /*
     * function : resend OTP to user mobile
     */

    public function resendOTP() {
        if (!empty($this->input->post())) {
            if (empty($this->input->post('userId'))) {
                return array("status" => 400, "message" => "Please provide user id.");
            }
            $user_id = $this->input->post('userId');
            $where = array(
                "id" => $user_id
            );

            $this->db->select("mobile");
            $this->db->from($this->db->dbprefix('user_master'));
            $this->db->where($where);
            $result = $this->db->get()->row_array();

            $mobile = strval($result['mobile']);

            $resCheck = $this->Common_model->chkmobile2($mobile);

            if (empty($resCheck)) {
                return array("status" => 400, "message" => "User id  is not registered.");
            }

            return $this->Common_model->sendUserOTP(substr($mobile, 3), $user_id);
        } else {
            return array("status" => 400, "message" => "Invalid post. Please try later!");
        }
    }

    public function doForgotPass() {
        try {
            if (!empty($this->input->post())) {
                $mobile = strval($this->input->post('mobile'));
                $resCheck = $this->Common_model->chkmobile2($this->input->post('mobile'));
                //print_r($resCheck);die;
                if (empty($resCheck)) {
                    return array("status" => 400, "message" => "$mobile  is not registered.");
                } else if ($resCheck['verified'] == NOTVERIFIED) {

                    return array("status" => 400, "message" => "$mobile  is not verified.", "verified" => NOTVERIFIED);
                } else if (intval($resCheck['status']) == NOTACTIVE) {

                    return array("status" => 400, "message" => "$mobile is blocked by system.");
                } else if (intval($resCheck['signUpType']) == SIGNUPFB) {
                    return array("status" => 400, "message" => "Your login with facebook.do not change password!");
                }/* else if (intval($resCheck['isLogged']) == LOGGED) {
                  return array("status" => 422, "message" => "You've logged in to another device.");
                  } */
                $result = $this->Common_model->sendUserOTP(substr($mobile, 3), $resCheck['id']);
                $user_profile = $resCheck;
                $totalBalance = $this->Common_model->getUserCurrentBalance($user_profile['id']);
                if ($totalBalance['status'] == 200) {
                    $currentBalance = $totalBalance['currentBalance'];
                } else {
                    $currentBalance = "0";
                }
                $data = array("userId" => $user_profile['id'], "userName" => $user_profile['userName'], "email" => $user_profile['email'], "mobile" => $user_profile['mobile'], "profileImage" => $user_profile['profileImage'], "location" => $user_profile['location'], "refferalCode" => $user_profile['refferralCode'], "verified" => $user_profile['verified'], "role" => $user_profile['role'], "signUpType" => $user_profile['signUpType'], "currentBalance" => "$currentBalance", "depositAmount" => $totalBalance['depositAmount']);
                if ($result['status'] = 200) {
                    return array("status" => 200, "message" => "OTP has sent to your mobile number", "data" => $data);
                } else {
                    return $result;
                }
            } else {
                return array("status" => 400, "message" => "Invalid post. Please try later!");
            }
        } catch (Exception $e) {
            return array("status" => 400, "message" => $e->getMessage());
        }
    }

    public function resetPassword() {

        try {
            if (!empty($this->input->post()) && !empty($this->input->post('userId'))) {
                $pass = strval($this->input->post('newPassword'));
                $pass_confirm = strval($this->input->post('confirmPassword'));
                $userId = $this->input->post('userId');

                if ($pass != $pass_confirm) {
                    return array("status" => 400, "message" => "Confirm password is not matching.");
                } else {

                    $where = array(
                        "id" => $userId
                    );

                    $updateArray = array(
                        "password" => encode($pass)
                    );

                    $res = $this->db->update($this->db->dbprefix('user_master'), $updateArray, $where);

                    if ($res) {
                        /*
                         * Update last_updated_password to user table
                         */
//						$updateArray = array(				
//							"last_updated_password"=> $pass,
//							"password_changed_by"=> date("Y-m-d H:i:s"),
//						);	
//						
//						$this->db->update($this->db->dbprefix('user'), $updateArray, array("id"=> $this->user));

                        return array("status" => 200, "message" => "Password changed successfully!");
                    } else {
                        return array("status" => 400, "message" => "Password not changed. Please try later!");
                    }
                }
            } else {
                return array("status" => 400, "message" => "Invalid post. Please try later!");
            }
        } catch (Exception $e) {
            return array("status" => 400, "message" => $e->getMessage());
        }
    }

    public function changePassword() {

        try {
            if (!empty($this->input->post()) && !empty($this->input->post('userId'))) {
                $oldPass = strval($this->input->post('oldPassword'));
                $newPass = strval($this->input->post('newPassword'));
                $confirmPass = strval($this->input->post('confirmPassword'));
                $userId = $this->input->post('userId');
                $result = $this->Common_model->chkUser($userId);
                //die($result);
                if ($result['password'] != encode($oldPass)) {
                    return array("status" => 400, "message" => "Old password is not correct.");
                }
                if ($newPass != $confirmPass) {
                    return array("status" => 400, "message" => "Confirm password is not matching.");
                } else {

                    $where = array(
                        "id" => $userId
                    );

                    $updateArray = array(
                        "password" => encode($newPass)
                    );

                    $res = $this->db->update($this->db->dbprefix('user_master'), $updateArray, $where);

                    if ($res) {
                        return array("status" => 200, "message" => "Password changed successfully!");
                    } else {
                        return array("status" => 400, "message" => "Password not changed. Please try later!");
                    }
                }
            } else {
                return array("status" => 400, "message" => "Invalid post. Please try later!");
            }
        } catch (Exception $e) {
            return array("status" => 400, "message" => $e->getMessage());
        }
    }

    public function chkRefferalCode() {
        try {
            if (!empty($this->input->post('refferalCode')) && !empty($this->input->post('userId'))) {

                $userId = $this->input->post('userId');
                $refferalCode = addslashes(strtoupper($this->input->post('refferalCode')));
                $where = array(
                    "id !=" => $userId,
                    "refferralCode" => $refferalCode,
                    "verified" => VERIFIED,
                    "isDeleted" => NOTDELETED
                );
                $user_profile = $this->db->get_where('user_master', $where)->row_array();
                if (isset($user_profile)) {

                    $insertRefferral = array(
                        "user_id" => $user_profile['id'], //another user id
                        "refferral_id" => $userId, //own user id
                        "refferral_code" => $refferalCode,
                        "createdDate" => date("Y-m-d H:i:s"),
                    );

                    $res = $this->db->insert($this->db->dbprefix('user_refferral'), $insertRefferral);

                    if (isset($res)) {
                        $refferralAmount = $this->Common_model->getAdminSetting();
                        $val = date("Ymdhis");
                        $transctionId = "REDT-{$val}";
                        //add reffreal transction own user
                        $topupTransactions = array(
                            'userId' => $this->input->post('userId'),
                            'transctionId' => $transctionId,
                            'price' => $refferralAmount['ownRefferralAmount'],
                            'transactionsType' => REFFERALTRANSCTIONS,
                            'status' => ACTIVE,
                            'isDeleted' => NOTDELETED,
                            'createdDate' => date('Y-m-d H:i:s'),
                        );
                        $this->db->insert('user_topup_transactions', $topupTransactions);
                        $val = date("Ymdhis");
                        $transctionId = "REDTO-{$val}";
                        //add reffreal transction another user
                        $topupTransactions = array(
                            'userId' => $user_profile['id'],
                            'transctionId' => $transctionId,
                            'price' => $refferralAmount['anotherRefferralAmount'],
                            'transactionsType' => REFFERALTRANSCTIONS,
                            'status' => ACTIVE,
                            'isDeleted' => NOTDELETED,
                            'createdDate' => date('Y-m-d H:i:s'),
                        );
                        $this->db->insert('user_topup_transactions', $topupTransactions);
                        return array("status" => 200, "message" => "Referral code validated successfully.");
                    } else {
                        return array("status" => 200, "message" => "Referral code not submited.Please try later!");
                    }
                } else {
                    return array("status" => 400, "message" => "Please enter valid referral code!");
                }
            } else {
                return array("status" => 400, "message" => "Please provide valid post. Please try later!");
            }
        } catch (Exception $e) {
            return array("status" => 400, "message" => $e->getMessage());
        }
    }

    public function addFeedback() {
        if (!empty($this->input->post())) {
            if (empty($this->input->post('userId'))) {
                return array("status" => 400, "message" => "Please post user id.");
            }
            if (empty($this->input->post('comment'))) {
                return array("status" => 400, "message" => "Please post comment.");
            }
            $data = array(
                'userId' => $this->input->post('userId'),
                'comment' => $this->input->post('comment'),
                'status' => ACTIVE,
                'isDeleted' => NOTDELETED,
                'createdDate' => date('Y-m-d H:i:s'),
            );

            if ($this->db->insert('user_feedback', $data)) {
                return array("status" => 200, "message" => "Your feedback submitted successfully!");
            } else {
                return array("status" => 400, "message" => "Your feedback not submitted!");
            }
        } else {
            return array("status" => 400, "message" => "Invalid post. Please try later!");
        }
    }

    public function addFavouriteLocation() {
        if (!empty($this->input->post())) {
            if (empty($this->input->post('userId'))) {
                return array("status" => 400, "message" => "Please post user id.");
            }
            if (empty($this->input->post('location'))) {
                return array("status" => 400, "message" => "Please post location.");
            }
            if (empty($this->input->post('positionNumber'))) {
                return array("status" => 400, "message" => "Please post position number.");
            }
            if (empty($this->input->post('latitude')) || empty($this->input->post('longitude'))) {
                return array("status" => 400, "message" => "Please post latitude and longitude.");
            }
            $chkloaction = $this->db->get_where('user_favourite_location', array("userId=" => $this->input->post('userId'), "positionNumber" => $this->input->post('positionNumber'), "isDeleted" => NOTDELETED))->row_array();
            if ($chkloaction || isset($chkloaction)) {
                $where = array(
                    "id" => $chkloaction['id']
                );

                $updateArray = array(
                    'fLocation' => $this->input->post('location'),
                    'fLat' => $this->input->post('latitude'),
                    'fLng' => $this->input->post('longitude'),
                    'updatedDate' => date('Y-m-d H:i:s')
                );

                $result = $this->db->update($this->db->dbprefix('user_favourite_location'), $updateArray, $where);
            } else {
                $data = array(
                    'userId' => $this->input->post('userId'),
                    'fLocation' => $this->input->post('location'),
                    'fLat' => $this->input->post('latitude'),
                    'fLng' => $this->input->post('longitude'),
                    'positionNumber' => $this->input->post('positionNumber'),
                    'status' => ACTIVE,
                    'isDeleted' => NOTDELETED,
                    'createdDate' => date('Y-m-d H:i:s'),
                    'updatedDate' => date('Y-m-d H:i:s')
                );
                $result = $this->db->insert('user_favourite_location', $data);
            }
            if ($result) {
                return array("status" => 200, "message" => "Your favourite location added successfully!");
            } else {
                return array("status" => 400, "message" => "Your favourite location not added! Please try later");
            }
        } else {
            return array("status" => 400, "message" => "Invalid post. Please try later!");
        }
    }

    function getFavouriteLocation() {
        if (!empty($this->input->post())) {
            if (empty($this->input->post('userId'))) {
                return array("status" => 400, "message" => "Please post user id.");
            }
            $data2 = array();
            $data = $this->db->get_where('user_favourite_location', array("userId" => $this->input->post('userId'), "status" => ACTIVE, "isDeleted" => NOTDELETED))
                    //->order_by('id','desc')
                    ->result_array();
            if (is_array($data)) {
                foreach ($data as $key => $data) {
                    $data2[] = array("id" => $data['id'], "location" => $data['fLocation'], "latitude" => $data['fLat'], "longitude" => $data['fLng'], "positionNumber" => $data['positionNumber']);
                }
                if ($data2) {
                    return array("status" => 200, "message" => "your favourite location!", "info" => $data2);
                } else {
                    return array("status" => 400, "message" => "Not found any Favourite Location");
                }
            } else {
                return array("status" => 200, "message" => "Not found any Favourite Location");
            }
        } else {
            return array("status" => 400, "message" => "Invalid post. Please try later!");
        }
    }

    public function editUserProfile() {
        if (!empty($this->input->post())) {
            if (empty($this->input->post('userId'))) {
                return array("status" => 400, "message" => "Please post user id.");
            }
            if (empty($this->input->post('userName'))) {
                return array("status" => 400, "message" => "Please post user name.");
            }
            if ($this->input->post('mobile') != '' || !empty($this->input->post('mobile'))) {
                if ($this->Common_model->chkmobileeditprofile($this->input->post('mobile'), $this->input->post('userId'))) {
                    return array("status" => 400, "message" => "Mobile number entered is exist in system. Please use different mobile number .");
                }
            }
            if ($this->input->post('email') != '' || !empty($this->input->post('email'))) {

                if ($this->Common_model->chkemaileditprofile($this->input->post('email'), $this->input->post('userId'))) {
                    return array("status" => 400, "message" => "Email id entered is exist in system. Please use different email id .");
                }
            }
            $where = array(
                "id" => $this->input->post('userId'),
            );
            $updateArray = array(
                'userName' => $this->input->post('userName'),
                'email' => $this->input->post('email'),
                'mobile' => $this->input->post('mobile'),
            );
            $profileImageArray = array();
            if (isset($_FILES) && !empty($_FILES)) {

                if (isset($_FILES['profileImage']) && !empty($_FILES['profileImage'])) {
                    $fileUploadPath = FCPATH . 'resource/user_photos/';
                    $fileRes = $this->Common_model->dofileUpload('profileImage', $fileUploadPath);
                    if (is_array($fileRes) && !empty($fileRes)) {
                        if ($fileRes['status'] == 200) {
                            $profileImageArray['profileImage'] = base_url("resource/user_photos/{$fileRes['data']}");
                        } else {
                            return $fileRes;
                        }
                    }
                }
            }
            $allupdatedata = array_merge($updateArray, $profileImageArray);
            $update = $this->db->update($this->db->dbprefix('user_master'), $allupdatedata, $where);
            $user_profile = $this->db->get_where('user_master', array("id" => $this->input->post('userId'), "isDeleted" => NOTDELETED))->row_array();
            if (is_array($user_profile)) {
                $data = array("userId" => $user_profile['id'], "userName" => $user_profile['userName'], "email" => $user_profile['email'], "mobile" => $user_profile['mobile'], "profileImage" => $user_profile['profileImage'], "location" => $user_profile['location'], "refferalCode" => $user_profile['refferralCode'], "verified" => $user_profile['verified'], "role" => $user_profile['role'], "signUpType" => $user_profile['signUpType']);
            }
            if ($update) {
                return array("status" => 200, "message" => "Your profile updated successfully!", "info" => $data);
            } else {
                return array("status" => 400, "message" => "Your profile not updated. Please try later.");
            }
        } else {
            return array("status" => 400, "message" => "Invalid post. Please try later!");
        }
    }

    function getUserProfile() {
        if (!empty($this->input->post())) {
            if (empty($this->input->post('userId'))) {
                return array("status" => 400, "message" => "Please enter user id.");
            }
            $userId = $this->input->post('userId');
            $user_profile = $this->db->get_where('user_master', array("id" => $userId, "status" => ACTIVE, "isDeleted" => NOTDELETED))->row_array();
            $summary = $this->db->query("SELECT sum(runningTime) as totalRunningTime,sum(runningSecond) as runningSecond,sum(runningDistance) as totalRunningDistance FROM `es_bill_summary` WHERE userId='" . $userId . "'");
            $summary = $summary->row_array();
            if ($summary['totalRunningDistance'] == '' || empty($summary['totalRunningDistance'])) {
                $distance = 0 . ' Km';
            } else {
                $distance = $summary['totalRunningDistance'] . ' Km';
            }
            $time = $this->Common_model->convertMinutesToHrs($summary['totalRunningTime'], $summary['runningSecond']);
//            $minutes = $summary['totalRunningTime'];
//            $d = floor($minutes / 1440);
//            $h = floor(($minutes - $d * 1440) / 60);
//            $min = $minutes - ($d * 1440) - ($h * 60);
//            if ($d != 0) {
//                $time = "{$d} Days, {$h} Hrs, {$min} Minutes";
//            } else {
//                if ($h != 0) {
//                    $time = "{$h} Hrs, {$min} Minutes";
//                } else {
//                    $time = "{$min} Minutes";
//                }
//            }
            if (is_array($user_profile)) {
                $data = array("userId" => $user_profile['id'], "userName" => $user_profile['userName'], "email" => $user_profile['email'], "mobile" => $user_profile['mobile'], "profileImage" => $user_profile['profileImage'], "location" => $user_profile['location'], "totalRunningTime" => $time, "totalRunningDistance" => $distance);
                return array("status" => 200, "message" => "view user profile!", "info" => $data);
            } else {
                return array("status" => 200, "message" => "Not found user profile");
            }
        } else {
            return array("status" => 400, "message" => "Invalid post. Please try later!");
        }
    }

    public function doLogout() {
        try {
            if (!empty($this->input->post())) {
                $userId = $this->input->post('userId');
                if (!$this->Common_model->chkUser($userId)) {
                    return array("status" => 400, "message" => "User not exists in system. Please try later!");
                }
                /*
                 *  logout time all reserve scooter cancelled                  
                 */
                $chk = $this->db->get_where('scooter_reserve', array("userId" => $userId, "rideStatus" => RESERVE))->row_array();
                if ($chk) {
                    //parking table status update
                    $where = array(
                        "id" => $chk['scooterParkId']
                    );

                    $updateArray = array(
                        "reserveUserId" => '0',
                        "scooterStatus" => NOTRESERVE
                    );
                    $update = $this->db->update($this->db->dbprefix('scooter_parking'), $updateArray, $where);
                    //reserve table status update
                    $where2 = array(
                        "id" => $chk['id'],
                    );

                    $updateArray2 = array(
                        "rideStatus" => RIDECANCEL
                    );
                    $update2 = $this->db->update($this->db->dbprefix('scooter_reserve'), $updateArray2, $where2);
                }
                $updateArray = array(
                    "isLogged" => NOTLOGGED,
                    "logoutDate" => date("Y-m-d H:i:s"),
                );
                $this->db->update($this->db->dbprefix('user_master'), $updateArray, array("id" => $userId));
                return array("status" => 200, "message" => "Logged out successfully!");
            } else {
                return array("status" => 400, "message" => "Please provide valid post. Please try later!");
            }
        } catch (Exception $e) {
            return array("status" => 400, "message" => $e->getMessage());
        }
    }

}

?>