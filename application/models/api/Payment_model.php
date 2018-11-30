<?php

class Payment_model extends CI_Model {

    public function __construct() {
        $this->load->database();
        $this->load->model('api/Common_model', 'Common_model', true); 
    }
    
    
    function sign_generic($secret_key, $params) {
        // a copy-passing, so it's not altering the original  unset($params['signature']); $params
        unset($params['signature']); 
        $data_to_sign = "";         
        $data_to_sign = $this->recursive_generic_array_sign($params, $data_to_sign); 
        $data_to_sign .= $secret_key; 
        
        return hash(PAYMENT_HASH_KEY, $data_to_sign); 
    }
 
    /* RECURSIVE FUNCTION DEFINITION */
    // use reference-passing to update the variable directly
    public function recursive_generic_array_sign(&$params, &$data_to_sign) {

	// sort the parameters based on its key 
        ksort($params);
	// Traverse through each component
	// And generate the concatenated string to sign 
        foreach ($params as $v) {
            if (is_array($v)) {
                    // In case of array traverse inside
                    // And build further the string to sign
                    $this->recursive_generic_array_sign($v, $data_to_sign); 
            } else {
                    // Not an array means this is a key=>value map, 
                    // Concatenate the value to data to sign 
                    $data_to_sign .= $v; 
            }
        }
        return $data_to_sign;
    }
    
    /** 
     * Name: response_handling()
     * Description: handle responce received from RDP 
     * Param: $mid number
     * Param: $secret_key string
     * Param: $array_response array()
     * Return: none
     *      */
    
    public function response_handling($mid, $secret_key, $array_response) {
        //$array_response = json_decode($json_response, true);
        if ($array_response['response_code'] == 0) {
            // Successfull transaction // 
            // Calculate signature using sign generic function // 
            $calculated_signature = $this->sign_generic($secret_key, $array_response);
            // Validate the received transaction signature // 
            if ($calculated_signature == $array_response['signature']) {
                if (!empty($array_response['payment_url'])) {
                    // Redirect customers to payment page // 
                    header('Location: ' . $array_response['payment_url']);
                    exit;
                } else {
                    // Empty payment URL in succesful txn (should not happen) // 
                    throw new Exception('Invalid response, no payment_url');
                }
            } else {
                // Invalid signature, the response might not come from RDP //
                throw new Exception('Invalid signature!');
            }
        } else {
            throw new Exception('Invalid request : ' . $array_reponse['response_msg']);
        }
    }
}