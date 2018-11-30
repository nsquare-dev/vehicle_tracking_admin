<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct() {

        parent::__construct();

        $this->load->model('api/common_model','Common_model');
    }

    public function index() {
        
        
        /*
         *$row = (object)$this->Common_model->convertDMStoDec("N0121.4175E10345.6641");
        $lastAddress = $this->Common_model->getaddress($row->lat, $row->long);
        echo "$lastAddress";die;
        $_ackResponce = str_replace(" ", "", "+#RPT00,20180503,A,180607081649,N0119.5367E10353.7703,000,000,110.0,41.2,1241A044");
        
        if (strlen($_ackResponce) >= 6) {
            
            $string_responce = explode(',', $_ackResponce);

            $_packetArray = array(
                "packetAndEvent" => $string_responce[0],
                "deviceNumber" => $string_responce[1],
                "gpsValid" => $string_responce[2],
                "dateAndTime" => $string_responce[3],
                "loc" => $string_responce[4],
                "speed" => $string_responce[5],
                "dir" => $string_responce[6],
                "mileage" => $string_responce[7],
                "betteryStatus" => $string_responce[8],
            );
            
            if (isset($string_responce[9])){
                
                $_packetArray["optional_message"] = base_convert($string_responce[9], 16, 10);
                
            }else{
                $_packetArray["optional_message"] = false;
            }
 var_dump($_packetArray);
            //Check tracker battery status
            if($_packetArray['betteryStatus'] <= "25.1"){                  
              echo "LOWBATTERY";
              
            //Check Battery low
            }else if((bool)($_packetArray['optional_message'] & 1)){
                echo 'LOWBATTERY Status ';
                echo intval($_packetArray['optional_message'] & 1);
            //Switch on/off
            }else if((bool)($_packetArray['optional_message'] & 3)){
                echo 'SWITCH_STATUS ';
                echo intval($_packetArray['optional_message'] & 3);
            //Alarm enable    
            }else if((bool)($_packetArray['optional_message'] & 5)){
                 echo 'ALARM_ENABLED ';
                 echo intval($_packetArray['optional_message'] & 5);
            //Arming   
            }else if((bool)($_packetArray['optional_message'] & 6)){
                echo 'ALARMING ';
                echo intval($_packetArray['optional_message'] & 6);
            //Check tracker status power cut off    
            }else if((bool)($_packetArray['optional_message'] & 16)){
                 
                echo 'DISCONNECTED ';
                echo intval($_packetArray['optional_message'] & 16);
                //Thief alarm
            }else if((bool)($_packetArray['optional_message'] & 17)){
                echo 'THIEF_ALARAM ';
                echo intval($_packetArray['optional_message'] & 17);
                //Tow alarm 
            }else if((bool)($_packetArray['optional_message'] & 18)){
               echo 'TOW_ALARM';
               echo intval($_packetArray['optional_message'] & 19);
            }else{
              echo "None";
            }
               
        } 
        die;*/
        if ($this->session->id) {      // $data = $this->data;
            redirect('admin');
        } else {
            $this->load->view('login');
        }
    }

}
