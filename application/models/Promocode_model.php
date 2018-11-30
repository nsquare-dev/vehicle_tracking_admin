<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Promocode_model extends CI_Model {

    protected $path;

    public function __construct() {
        $this->load->database();
        $this->load->model('Common_model', 'Common_model', true);
        $this->path = FCPATH . "resource/user_photos/";
        $this->display_path = "resource/user_photos/";
    }

    public function getList() {
        $results = $this->db->select("id, offerTitle, offerImage, offerBannerImage, offerDesc,"
                                . " offerPrice, promoCode, DATE_FORMAT(startDate, '%d %M %Y - %H:%i' ) as startDate, "
                                . "DATE_FORMAT(endDate, '%d %M %Y - %H:%i' ) as endDate, status, createdDate", false)
                        ->from($this->db->dbprefix('offers'))
                        ->where("isDeleted", NOTDELETED)
                        ->get()->result();
        foreach ($results as $key => $result) {
            $results[$key]->offerImage = base_url("{$this->display_path}{$result->offerImage}");
            $results[$key]->offerBannerImage = base_url("{$this->display_path}{$result->offerBannerImage}");
        }
        return $results;
    }

    public function updateStatus($row_id = false, $status = false) {
        $where = array(
            "id" => $row_id
        );
        $updateArray = array(
            "status" => $status
        );

        if ($this->db->update($this->db->dbprefix('offers'), $updateArray, $where)) {
            if ($status == ACTIVE) {
                $this->session->set_flashdata('success', "Promo Code activated successfully");
            } else {
                $this->session->set_flashdata('success', "Promo Code De-activated successfullys");
            }

            return true;
        } else {
            $this->session->set_flashdata('error', "Action not performed");
            return false;
        }
    }

    public function removeRecord($row_id = false, $value = false) {
        $where = array(
            "id" => $row_id
        );
        $updateArray = array(
            "isDeleted" => $value
        );

        if ($this->db->update($this->db->dbprefix('offers'), $updateArray, $where)) {
            $this->session->set_flashdata('success', "Promo Code deleted successfully");
            return true;
        } else {
            $this->session->set_flashdata('error', "Action not performed");
            return false;
        }
    }

    public function addRecord($post = array()) {
        try {
            $field_image = 'Null';
            $field_banner = 'Null';
            if (count($_FILES)) {

                $path = $this->path;
                if (isset($_FILES['field_image'])) {
                    $image_data = $this->Common_model->dofileUpload('field_image', $path);
                    if ($image_data['status'] == 400) {
                        return $image_data;
                    } else {
                        $field_image = $image_data['file_name'];
                    }
                }
                //sleep for 1 seconds
                sleep(1);
                if (isset($_FILES['field_banner'])) {
                    $banner_data = $this->Common_model->dofileUpload('field_banner', $path);
                    if ($banner_data['status'] == 400) {
                        return $banner_data;
                    } else {
                        $field_banner = $banner_data['file_name'];
                    }
                }
            }
            $startDate = explode('-', html_escape($post['field_startDate']));
            $endDate = explode('-', html_escape($post['field_endDate']));

            $insertArray = array(
                "offerTitle" => ucwords(html_escape($post['field_title'])),
                "offerImage" => $field_image,
                "offerBannerImage" => $field_banner,
                "offerDesc" => html_escape($post['field_desc']),
                "offerPrice" => html_escape($post['field_price']),
                "promoCode" => strtoupper(html_escape($post['field_code'])),
                "startDate" => date("Y-m-d H:i", strtotime($startDate[0] . trim($startDate[1]) . ":00")),
                "endDate" => date("Y-m-d H:i", strtotime($endDate[0] . trim($endDate[1]) . ":00")),
                "status" => ACTIVE,
                "isDeleted" => NOTDELETED,
                "createdDate" => date("Y-m-d H:i:s"),
            );

            if ($this->db->insert($this->db->dbprefix('offers'), $insertArray)) {
                return array("status" => 200, "message" => "Promo code created successfully!");
            } else {
                return array("status" => 400, "message" => "Failed to create promo code. Please try again!");
            }
        } catch (Exception $ex) {
            return array("status" => 400, "message" => $ex->getMessage());
        }
    }

    public function updateRecord($post = array()) {
        try {
            $row_id = html_escape($post['edit_id']);
            $where = array(
                "id" => $row_id,
            );
            $startDate = explode('-', html_escape($post['field_edit_startDate']));
            $endDate = explode('-', html_escape($post['field_edit_endDate']));
            if (!$this->Common_model->validateDate($startDate[1], "H:i:s")) {
                $startDate[1] = trim($startDate[1]) . ":00";
            }
            if (!$this->Common_model->validateDate($endDate[1], "H:i:s")) {
                $endDate[1] = trim($endDate[1]) . ":00";
            }

            $updateArray = array(
                "offerTitle" => ucwords(html_escape($post['field_edit_title'])),
                "offerDesc" => html_escape($post['field_edit_desc']),
                "offerPrice" => html_escape($post['field_edit_price']),
                "promoCode" => strtoupper(html_escape($post['field_edit_code'])),
                "startDate" => date("Y-m-d H:i", strtotime($startDate[0] . $startDate[1])),
                "endDate" => date("Y-m-d H:i", strtotime($endDate[0] . $endDate[1])),
            );

            if (count($_FILES)) {

                $path = $this->path;
                if (isset($_FILES['field_image']) && !empty($_FILES['field_image'])) {
                    $image_data = $this->Common_model->dofileUpload('field_image', $path);
                    if ($image_data['status'] == 400) {
                        return $image_data;
                    } else {
                        $field_image = $image_data['file_name'];
                        $updateArray = array_merge($updateArray, array("offerImage" => $field_image));
                    }
                }
                //sleep for 1 seconds
                //sleep(1);
                if (isset($_FILES['field_banner']) && !empty($_FILES['field_banner'])) {
                    $banner_data = $this->Common_model->dofileUpload('field_banner', $path);
                    if ($banner_data['status'] == 400) {
                        return $banner_data;
                    } else {
                        $field_banner = $banner_data['file_name'];
                        $updateArray = array_merge($updateArray, array("offerBannerImage" => $field_banner));
                    }
                }
            }


            if ($this->db->update($this->db->dbprefix('offers'), $updateArray, $where)) {
                return array("status" => 200, "message" => "Promo code updated successfully!");
            } else {
                return array("status" => 400, "message" => "Failed to update promo code. Please try again!");
            }
        } catch (Exception $ex) {
            return array("status" => 400, "message" => $ex->getMessage());
        }
    }

}
