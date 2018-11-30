<?php

class Offers_model extends CI_Model {

    protected $path;

    public function __construct() {
        $this->load->database();
        $this->load->model('api/Common_model', 'Common_model', true);
        $this->display_path = "resource/user_photos/";
    }

    function getOffersList() {
        $offer = $this->db->get_where('offers', array("status" => ACTIVE, "isDeleted" => NOTDELETED))->result_array();
        if ($offer) {
            foreach ($offer as $key => $offers) {
                $data[] = array(
                    "offerId" => $offers['id'],
                    "offerTitle" => $offers['offerTitle'],
                    "offerImage" => base_url("{$this->display_path}{$offers['offerImage']}"),
                );
            }
            return array("status" => 200, "message" => "Your offer list!", "info" => $data);
        } else {
            return array("status" => 400, "message" => "Not availble any offer !");
        }
    }

    function getOffersDetails() {
        if (empty($this->input->post('offerId'))) {
            return array("status" => 400, "message" => "Please post offer id .");
        }
        $offers = $this->db->get_where('offers', array("id" => $this->input->post('offerId'), "status" => ACTIVE, "isDeleted" => NOTDELETED))->row_array();
        if (isset($offers)) {
            $data = array(
                "offerId" => $offers['id'],
                "offerTitle" => $offers['offerTitle'],
                "offerBannerImage" => base_url("{$this->display_path}{$offers['offerBannerImage']}"),
                "promoCode" => $offers['promoCode'],
                "offerDesc" => $offers['offerDesc'],
            );
            return array("status" => 200, "message" => "Your offer list!", "info" => $data);
        } else {
            return array("status" => 400, "message" => "Not availble any offer !");
        }
    }

    function chkPromoCode() {
        if (empty($this->input->post('userId'))) {
            return array("status" => 400, "message" => "Please post user id .");
        }
        if (empty($this->input->post('promoCode'))) {
            return array("status" => 400, "message" => "Please enter promo code .");
        }
        if (empty($this->input->post('reserveId'))) {
            return array("status" => 400, "message" => "Please post reserveId.");
        }

          $chkPromo=$this->db->query("SELECT * FROM `es_offers` WHERE BINARY promoCode='" . $this->input->post('promoCode') . "' and status='" . ACTIVE . "' and isDeleted='" . NOTDELETED . "'")->row_array();
        //$chkPromo = $this->db->get_where('offers', array("promoCode BINARY" => $this->input->post('promoCode'), "status" => ACTIVE, "isDeleted" => NOTDELETED))->row_array();
        if (isset($chkPromo)) {

            if ($chkPromo['endDate'] >= date('Y-m-d H:i:s')) {

                $addpromodata = array(
                    'userId' => $this->input->post('userId'),
                    'reserveId' => $this->input->post('reserveId'),
                    'offerId' => $chkPromo['id'],
                    'status' => ACTIVE,
                    'isDeleted' => NOTDELETED,
                    'createdDate' => date('Y-m-d H:i:s'),
                );

                if ($this->db->insert('user_promo_code_offers', $addpromodata)) {
                    return array("status" => 200, "message" => "Promo code applied successfully!");
                } else {
                    return array("status" => 400, "message" => "Promo code not applied. Please try later!");
                }
            } else {
                return array("status" => 400, "message" => "Entered promo code is expired. Please try different promo code.");
            }
        } else {
            return array("status" => 400, "message" => "Entered promo code is invalid. Please try different promo code");
        }
    }

}

?>