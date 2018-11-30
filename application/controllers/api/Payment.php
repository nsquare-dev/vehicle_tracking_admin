<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

class Payment extends REST_Controller {

    protected $mid;
    protected $secret_key;

    function __construct() {
        // Construct the parent class
        parent::__construct();
        $this->mid = PAYMENT_MERCHANT_NO;
        $this->secret_key = PAYMENT_MERCHANT_SECRETE_KEY;

        $this->methods['paymentOptions_get']['limit'] = 500;
        $this->methods['transactionStatus_get']['limit'] = 500;
        $this->methods['transactionStatusNotify_get']['limit'] = 500;
        $this->methods['transactionClose_get']['limit'] = 500;
        $this->load->model('api/payment_model');
        $this->load->model('api/Mywallet_model');
    }

    public function paymentOptions_get() {

        /* given $params contains the parameters you would like to sign */
        $userId = html_escape($this->input->get('field_user'));
        $topupId = html_escape($this->input->get('field_topup'));

        $row = $this->db->select("price, bonus")
                        ->from($this->db->dbprefix('topup'))
                        ->where("id", $topupId)
                        ->get()->row();
        //array('mid', 'order_id', 'payment_type', 'amount', 'ccy');
        //if (isset($_GET['transaction_amt'])) {
        if (!$row) {
            $return = array("status" => REST_Controller::HTTP_BAD_REQUEST, "message" => "Please select valid topup!");
            $this->response($return);
        } else {
            //$amount = 0.01;
            $amount = $row->price;
        }

        $val = date("Ymdhis");
        $order_id = "ORD-{$val}";

        //Set data in session for transactionStatus

        $insertArray = array(
            'userId' => $userId,
            'topupId' => $topupId,
            'price' => $row->price,
            'bonus' => $row->bonus,
            'transactionsType' => TOPUPTRANSCTIONS,
            'order_id' => $order_id,
            'transaction_state' => TRANS_NEW,
            'status' => NOTACTIVE,
            'isDeleted' => NOTDELETED,
            'createdDate' => date('Y-m-d H:i:s'),
        );
        //Insert record
        $this->db->insert('user_topup_transactions', $insertArray);

        $fields_for_sign = array(
            $this->mid, //mid,
            $order_id, //order_id
            PAYMENT_TYPE, //payment_type
            $amount, //amount
            PAYMENT_CURRENCY //ccy
        );

        $signature = $this->payment_model->sign_generic($this->secret_key, $fields_for_sign);

        $dateNow = date("Y-m-d H:i:s");
        $postArray = array(
            "redirect_url" => base_url('api/payment/transactionStatus'),
            "notify_url" => base_url('api/payment/transactionStatusNotify'),
            "back_url" => base_url('api/payment/transactionClose'),
            "mid" => $this->mid,
            "order_id" => $order_id,
            "amount" => $amount,
            "ccy" => PAYMENT_CURRENCY,
            "api_mode" => PAYMENT_API_MODE,
            "payment_type" => PAYMENT_TYPE,
            "merchant_reference" => "Credits requested dated: $dateNow",
            "signature" => $signature,
        );

        $json_rp = json_encode($postArray);

        // target RDP development server 
        if (PAYMENT_LIVE) {
            $url = PAYMENT_URL_LIVE;
        } else {
            $url = PAYMENT_URL_TEST;
        }

        $curl = curl_init($url);
        curl_setopt_array($curl, array(CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_POST => 1, // using POST method 
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false, // JSON Request Parameters is put in the BODY of request 
            CURLOPT_POSTFIELDS => $json_rp,
            CURLOPT_HTTPHEADER => array('Content-Type: application/json')));

        //This is the JSON response containing transaction information // 
        $json_response = curl_exec($curl);
        $curl_errno = curl_errno($curl);
        $curl_err = curl_error($curl);
        curl_close($curl);
        $resp_array = json_decode($json_response, true);

        // (See Generic Signature section, 
        // Especially for the sign_generic() function definition)
        if (isset($resp_array['signature'])) {

            $calculated_signature = $this->payment_model->sign_generic($this->secret_key, $resp_array);
            if ($calculated_signature != $resp_array['signature']) {
                //throw new Exception('signature wrong! invalid response!');
                $return = array("status" => REST_Controller::HTTP_BAD_REQUEST, "message" => "signature wrong! invalid response!");
            } else {
                echo "signature is fine, continue processing the request.";
                $this->payment_model->response_handling($this->mid, $this->secret_key, $resp_array);
                //$return = array("status" => REST_Controller::HTTP_OK, "message" =>"signature is fine, continue processing the request.");               
            }
        } else {

            // zero response_code means a successful transaction, and definitely has signature 
            if ($resp_array['response_code'] == 0)
            //throw new Exception('signature not found! invalid response!');
            // error / reject transactions might not have any signature in it 
                $return = array("status" => REST_Controller::HTTP_BAD_REQUEST, "message" => "signature not found! Must be an error/invalid request.");
        }
        $this->response($return);
    }

    public function transactionStatus_get() {
        if (isset($_GET['transaction_id'])) {
            $transaction_id = html_escape($_GET['transaction_id']);
            $rp = array(
                'request_mid' => $this->mid,
                'transaction_id' => $transaction_id
            );
            $rp['signature'] = $this->payment_model->sign_generic($this->secret_key, $rp);
            $json_rp = json_encode($rp);
            // target RDP development server 
            if (PAYMENT_LIVE) {
                $url = PAYMENT_QUERY_REDIRECT_URL_LIVE;
            } else {
                $url = PAYMENT_QUERY_REDIRECT_URL_TEST;
            }

            $curl = curl_init($url);
            curl_setopt_array($curl, array(CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_POST => 1, // using POST method 
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false, // JSON Request Parameters is put in the BODY of request 
                CURLOPT_POSTFIELDS => $json_rp,
                CURLOPT_HTTPHEADER => array('Content-Type: application/json')));

            //This is the JSON response containing transaction information // 
            $json_response = curl_exec($curl);
            $curl_errno = curl_errno($curl);
            $curl_err = curl_error($curl);
            curl_close($curl);
            $resp_array = json_decode($json_response, true);

            // (See Generic Signature section, 
            // Especially for the sign_generic() function definition) 
            if (isset($resp_array['signature'])) {

                $calculated_signature = $this->payment_model->sign_generic($this->secret_key, $resp_array);
                if ($calculated_signature != $resp_array['signature']) {
                    //throw new Exception('signature wrong! invalid response!');                         
                    $return = array("status" => REST_Controller::HTTP_BAD_REQUEST, "message" => "signature wrong! invalid response!.");
                } else {

                    if ($resp_array['order_id']) {
                        $where = array(
                            "order_id" => $resp_array['order_id'],
                        );

                        if ($resp_array['response_msg'] == 'successful') {
                            $updateArray = array(
                                'transctionId' => $transaction_id,
                                'acquirer_transaction_id' => $resp_array['order_id'],
                                'response_msg' => $resp_array['response_msg'],
                                'transaction_state' => TRANS_COMPLETED,
                                'status' => ACTIVE,
                            );
                        } else {
                            $updateArray = array(
                                'transctionId' => $transaction_id,
                                'acquirer_transaction_id' => $resp_array['order_id'],
                                'response_msg' => $resp_array['response_msg'],
                                'transaction_state' => TRANS_FAILED,
                                'status' => ACTIVE,
                            );
                        }

                        //Insert record
                        if ($this->db->update('user_topup_transactions', $updateArray, $where)) {
                            $row = $this->db->select("userId")
                                            ->from($this->db->dbprefix('user_topup_transactions'))
                                            ->where($where)
                                            ->get()->row();
                            if ($row) {
                                //Send notifications
                                $this->Common_model->sendNotification($row->userId, 'add_topup');
                                $return = array("status" => REST_Controller::HTTP_OK, "message" => "Your topup successfully!");
                            } else {
                                $return = array("status" => REST_Controller::HTTP_OK, "message" => "Your topup successfully!");
                            }
                        } else {
                            $return = array("status" => REST_Controller::HTTP_BAD_REQUEST, "message" => "Your topup not submit. Please try later!");
                        }
                    }

                    //Store transacrion details here
                    //$return = array("status" => REST_Controller::HTTP_OK, "message" => "signature is fine, continue processing the request.", "responce" => $resp_array);
                }
            } else {
                // zero response_code means a successful transaction, and definitely has signature                 
                if ($resp_array['response_code'] == 0) {
                    //throw new Exception('signature not found! invalid response!'); // error 
                    // reject transactions might not have any signature in it                 
                    $return = array("status" => REST_Controller::HTTP_BAD_REQUEST, "message" => "signature not found! Must be an error/invalid request.");
                } else {
                    $return = array("status" => REST_Controller::HTTP_BAD_REQUEST, "message" => $resp_array['response_msg']);
                }
            }
        } else {
            // it's not from RDP system 
            $return = array("status" => REST_Controller::HTTP_BAD_REQUEST, "message" => "Transaction id not found!");
        }
        $this->response($return);
    }

    /**
     * Description: This sample code is retrieving the result from the BODY of an RDP push notification,
     *  and parse the JSON into array, traverse and print it out to the screen. By
     */
    public function transactionStatusNotify_get() {
        $content = '';
        /* RETRIEVE RESULT FROM BODY */
        $querystring = @file_get_contents('php://input');
        $arrParam = array();
        $prefix = PAYMENT_PREFIX;
        try {
            $arrParam = json_decode($querystring, true);
            $content .= $querystring;
            if (count($arrParam)) {
                foreach ($arrParam as $key => $val) {
                    $content .= $key . '=' . $val . "\n";
                }
            }

            if (isset($arrParam["mid"]))
                $prefix .= $arrParam["mid"];
            $prefix .= "_";
            if (isset($arrParam["transaction_id"]))
                $prefix .= $arrParam["transaction_id"];

            file_put_contents(FCPATH . "notif_log/" . $prefix . "-" . date('Y_m_d_H_i_s') . rand(100, 999) . 'res' . '.txt', $content);
            $return = array("status" => REST_Controller::HTTP_OK, "message" => "Payment processed successfully!", "data" => $content);
        } catch (Exception $e) {
            file_put_contents(FCPATH . "notif_log/" . $prefix . "-" . date('Y_m_d_H_i_s') . rand(100, 999) . '-errorres' . '.txt', $e->getMessage());

            $return = array("status" => REST_Controller::HTTP_BAD_REQUEST, "message" => $e->getMessage());
        }
        $this->response($return);
    }

    public function transactionClose_get() {
        if (isset($_GET['transaction_id'])) {
            $transaction_id = html_escape($_GET['transaction_id']);
            $where = array(
                "order_id" => $transaction_id,
            );

            $updateArray = array(
                'transctionId' => $transaction_id,
                'response_msg' => 'Cancelled By User',
                'transaction_state' => TRANS_CANCELLED,
            );

            $this->db->update('user_topup_transactions', $updateArray, $where);
        }
        $return = array("status" => REST_Controller::HTTP_OK, "message" => "User cancelled payment process!");
        $this->response($return);
    }

}

?>