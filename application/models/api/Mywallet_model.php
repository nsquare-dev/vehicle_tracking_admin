<?php

class Mywallet_model extends CI_Model {

    public function __construct() {
        $this->load->database();
        $this->load->model('api/Common_model', 'Common_model', true);
    }

    function getTopupList() {
        $where = array(
            "status" => ACTIVE,
            "isDeleted" => NOTDELETED
        );
        $results = $this->db->from($this->db->dbprefix('topup'))
                        ->where($where)
                        ->order_by("price", "ASC")
                        ->get()->result();
        if ($results) {
            return array("status" => 200, "message" => "Top Up price list!", "info" => $results);
        } else {
            return array("status" => 400, "message" => "Not availble any Top Up !");
        }
    }

    function addTopupList() {
        if (!empty($this->input->post())) {
            if (empty($this->input->post('userId'))) {
                return array("status" => 400, "message" => "Please post user id.");
            }
            $randomString = strtoupper($this->Common_model->randomString());
            $userId = $this->input->post('userId');
            $transctionId = $this->input->post('transctionId');
            $topupId = $this->input->post('topupId');
            $topUpDetails = $this->db->select("price, bonus")
                            ->from($this->db->dbprefix('topup'))
                            ->where("id", $topupId)
                            ->get()->row();

            $data = array(
                'userId' => $userId,
                'topupId' => $topupId,
                'transctionId' => $transctionId,
                'price' => $topUpDetails->price,
                'bonus' => $topUpDetails->bonus,
                'transactionsType' => TOPUPTRANSCTIONS,
                'status' => ACTIVE,
                'isDeleted' => NOTDELETED,
                'createdDate' => date('Y-m-d H:i:s'),
            );
            //Insert record
            if ($this->db->insert('user_topup_transactions', $data)) {
                //Send notifications
                if ($this->Common_model->sendNotification($userId, 'add_topup')) {
                    return array("status" => 200, "message" => "Your topup successfully!");
                } else {
                    return array("status" => 200, "message" => "Your topup successfully! but notification not sent");
                }
            } else {
                return array("status" => 400, "message" => "Your topup not submit. Please try later!");
            }
        } else {
            return array("status" => 400, "message" => "Invalid post. Please try later!");
        }
    }

    function getTransactions() {
        if (!empty($this->input->post())) {
            if (empty($this->input->post('userId'))) {
                return array("status" => 400, "message" => "Please post user id.");
            }
            $totalprice = '';
            $totalbonus = '';            
            $transactions =  $this->db->select("id,transctionId, price,bonus,transactionsType,createdDate")
                    ->from($this->db->dbprefix('user_topup_transactions'))
                    ->where("userId", $this->input->post('userId'))
                    ->where("status", ACTIVE)
                    ->where("isDeleted", NOTDELETED)
                    ->order_by('id', 'DESC')
                    ->get()->result_array();

            if ($transactions) {
                foreach ($transactions as $key => $transactions) {
                    $totalprice += $transactions['price'];
                    $totalbonus += $transactions['bonus'];
                    $dates = date('d-m-Y', strtotime($transactions['createdDate']));
                    $time = date('g:i a', strtotime($transactions['createdDate']));
                    $data[] = array(
                        "id" => $transactions['id'],
                        "transctionId" => $transactions['transctionId'],
                        "price" => $transactions['price'],
                        "bonus" => $transactions['bonus'],
                        "transactionsType" => $transactions['transactionsType'],
                        "date" => $dates, "time" => $time
                    );
                }
                $totalBalance = $totalprice + $totalbonus;
                return array("status" => 200, "message" => "your transaction list!", "depositAmount" => '50', "totalBalance" => $totalBalance, "info" => $data);
            } else {
                return array("status" => 400, "message" => "Not available any transaction!");
            }
        } else {
            return array("status" => 400, "message" => "Invalid post. Please try later!");
        }
    }

    function getUserCurrentBalance() {
        if (empty($this->input->post('userId'))) {
            return array("status" => 400, "message" => "Please post user id.");
        }
        ##Topup amount##
        $topupAmount = $this->db->query("SELECT (SUM(price)+SUM(bonus)) as totalTopupAmount FROM `es_user_topup_transactions` WHERE userId='" . $this->input->post('userId') . "' and transactionsType='" . TOPUPTRANSCTIONS . "' and status='" . ACTIVE . "' and isDeleted='" . NOTDELETED . "'");
        $topupAmount = $topupAmount->row_array();
        if ($topupAmount) {
            $totalTopupAmount = $topupAmount['totalTopupAmount'];
        } else {
            $totalTopupAmount = "0";
        }
        ##Refferal amount##
        $RefferalAmount = $this->db->query("SELECT SUM(price) as totalRPrices FROM `es_user_topup_transactions` WHERE userId='" . $this->input->post('userId') . "' and transactionsType='" . REFFERALTRANSCTIONS . "' and status='" . ACTIVE . "' and isDeleted='" . NOTDELETED . "'");
        $RefferalAmount = $RefferalAmount->row_array();
        if ($RefferalAmount) {
            $totalRefferalAmount = ($RefferalAmount['totalRPrices']) ? $RefferalAmount['totalRPrices'] : 0;
        } else {
            $totalRefferalAmount = "0";
        }
        ##ride amount##
        $rideAmount = $this->db->query("SELECT SUM(price) as totalPrice FROM `es_user_topup_transactions` WHERE userId='" . $this->input->post('userId') . "' and transactionsType='" . RIDETRANSCTIONS . "' and status='" . ACTIVE . "' and isDeleted='" . NOTDELETED . "'");
        $rideAmount = $rideAmount->row_array();
        if ($rideAmount) {
            $TotalRideAmount = $rideAmount['totalPrice'];
        } else {
            $TotalRideAmount = "0";
        }

        $adminSettings = $this->Common_model->getAdminSetting();
        if (isset($adminSettings)) {

            $depositAmount = $adminSettings['depositAmount'];

            if (($totalTopupAmount + $totalRefferalAmount) <= $depositAmount) {
                $currentBalance = 0;
                $depositAmount = $totalTopupAmount + $totalRefferalAmount;
            } else {
                $currentBalance = round(($totalTopupAmount + $totalRefferalAmount) - ($TotalRideAmount + $depositAmount), 2);
            }
            return array("status" => 200, "message" => "Your account balance!", "currentBalance" => $currentBalance, "depositAmount" => $depositAmount);
        } else {
            return array("status" => 400, "message" => "Not found deposit amount !");
        }
    }

}

?>