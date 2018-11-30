<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Command extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Command_model');
        $this->load->model('Common_model');
        $this->load->model('App_model');
        $this->load->helper('common_helper');
    }

    public function index() {

        if ($this->session->id) {
            $data['command_list'] = $this->Command_model->getList();
            $data['header'] = TRUE;
            $data['sidebar'] = TRUE;
            $data['_view'] = "command/index";
            $data['footer'] = TRUE;
            $data['top_menu'] = "mgt_command";
            $data['sub_menu'] = "dash";
            $data['pagetitle'] = "E-scooter";
            $this->load->view('basetemplate', $data);
        } else
            redirect('login');
    }

    public function add() {

        $this->load->library('form_validation');

        if ($this->session->id) {

            $config = array(
                array(
                    'field' => 'field_command',
                    'label' => 'Command',
                    'rules' => 'trim|required',
                ),
                array(
                    'field' => 'field_title',
                    'label' => 'Title',
                    'rules' => 'trim|required',
                ),
                array(
                    'field' => 'field_command_key',
                    'label' => 'Command Key',
                    'rules' => 'trim|required',
                ),
                array(
                    'field' => 'field_syntax',
                    'label' => 'Syntax',
                    'rules' => 'trim|required',
                ),
                array(
                    'field' => 'field_example',
                    'label' => 'Example',
                    'rules' => 'trim|required',
                ),
            );

            $this->form_validation->set_rules($config);

            if ($this->form_validation->run() == FALSE) {
                $errors = validation_errors();
                $result = array("status" => 400, "message" => nl2br(strip_tags($errors)));
                echo json_encode($result);
            } else {


                $result = array();

                $id = $this->input->post('field_id');
                $title = $this->input->post('field_title');
                $command = $this->input->post('field_command');
                $description = $this->input->post('field_description');
                $command_key = $this->input->post('field_command_key');
                $syntax = $this->input->post('field_syntax');
                $example = $this->input->post('field_example');
                //Convert string to upper case and blank spaces with '_'
                $code_to_upper = strtoupper($command_key);
                $code = str_replace(' ', '_', $code_to_upper);

                if ($id) {
                    if ($this->Command_model->chk_update_command($command, $id)) {
                        $data = array('status' => 400, 'message' => 'Command entered is exist in system. Please use different Command .', 'type' => 'command');
                        echo json_encode($data);
                        die;
                    }
                    if ($this->Command_model->chk_update_code($code, $id)) {
                        $data = array('status' => 400, 'message' => 'Command Code entered is exist in system. Please use different Command Code .', 'type' => 'command code');
                        echo json_encode($data);
                        die;
                    }

                    $update_arr = array();
                    $update_arr['title'] = $title;
                    $update_arr['command'] = $command;
                    $update_arr['description'] = $description;
                    $update_arr['code'] = $code;
                    $update_arr['syntax'] = $syntax;
                    $update_arr['example'] = $example;

                    if ($this->Command_model->update($update_arr, $id)) {
                        $this->session->set_flashdata('success', "Command Updated Successfully");
                        $result['status'] = '200';
                    } else {
                        $result['status'] = '400';
                    }
                } else {

                    $this->form_validation->set_rules('command', 'Command', 'required|is_unique[es_socket_command.command]');
                    $this->form_validation->set_rules('command_key', 'Command Key', 'required|is_unique[es_socket_command.code]');

                    if ($this->Command_model->chk_add_command($command)) {
                        $data = array('status' => 400, 'message' => 'Command entered is exist in system. Please use different Command .', 'type' => 'command');
                        echo json_encode($data);
                        die;
                    }

                    if ($this->Command_model->chk_add_code($code)) {
                        $data = array('status' => 400, 'message' => 'Command Code entered is exist in system. Please use different Command Code .', 'type' => 'command code');
                        echo json_encode($data);
                        die;
                    }

                    $insert_arr = array();
                    $insert_arr['title'] = $title;
                    $insert_arr['command'] = $command;
                    $insert_arr['description'] = $description;
                    $insert_arr['code'] = $code;
                    $insert_arr['syntax'] = $syntax;
                    $insert_arr['example'] = $example;

                    if ($this->Command_model->add($insert_arr)) {
                        $this->session->set_flashdata('success', "Command Added Successfully");
                        $result['status'] = '200';
                    } else {
                        $result['status'] = '400';
                    }
                }
                die(json_encode($result));
            }
            
        } else {
            redirect('login');
        }
    }

    public function edit() {
        $result_arr = array();
        $id = $this->input->post('id');
        if ($id) {
            $record = $this->Command_model->edit($id);
            if (!empty($record)) {
                $result_arr = $record;
            }
        }

        echo json_encode($result_arr);
    }

    public function delete_record($id) {
        if ($id) {
            if ($this->Command_model->delete_record($id)) {
                $this->session->set_flashdata('success', "Record Deleted Successfully");
                redirect('command/index');
            }
        }
    }

    public function command_list() {
        if ($this->session->id) {
            $collection = array(
                "" => '--SELECT--'
            );
            $command_lists = $this->Command_model->getList();
            foreach ($command_lists as $command_list) {
                $collection[$command_list['id']] = $command_list['title'];
            }
            $data['command_list'] = $collection;

            $data['trackers'] = $this->Command_model->getTrackerList();
            $data['globalCnf'] = $this->App_model->getScooterData();
            $data['header'] = TRUE;
            $data['sidebar'] = TRUE;
            $data['_view'] = "command/send_form";
            $data['footer'] = TRUE;
            $data['top_menu'] = "svr_command";
            $data['sub_menu'] = "dash";
            $data['pagetitle'] = "E-scooter";
            $this->load->view('basetemplate', $data);
        } else {
            redirect('login');
        }
    }

    public function execute($command = null) {
        try {
            if (isset($_POST) && !empty($_POST)) {

                $msg = html_escape($_POST['command']);
                //Write action to txt log
                //$log = "$msg".PHP_EOL;
                // file_put_contents('./commands/input/input_' . date("j.n.Y") . '.txt', $log, FILE_APPEND);    


                if ($msg == "shutdown") {

                    shell_exec("sudo kill $(lsof -t -i:" . OPENPORT . ")");

                    $row = $this->db->select("*")
                                    ->from($this->db->dbprefix('push_command'))
                                    ->limit(1)
                                    ->order_by("id", "DESC")
                                    ->get()->row();
                    //assuming you're looking at 5 seconds
                    echo json_encode(array("status" => 400, "message" => "Failed", "data" => $row));
                    die;
                }

                $insertArray = array(
                    "trackerId" => html_escape($_POST['device']),
                    "cmd" => $msg,
                    "createdDate" => date("Y-m-d H:i:s"),
                );

                $this->db->insert($this->db->dbprefix('sent_cmd'), $insertArray);


                $starttime = time();

                do {
                    $row = $this->db->select("*")
                                    ->from($this->db->dbprefix('push_command'))
                                    ->where("command", $msg)
                                    ->where("ack is not null", null, false)
                                    ->limit(1)
                                    ->order_by("id", "DESC")
                                    ->get()->row();

                    if ($row) {
                        echo json_encode(array("status" => 200, "message" => "Success", "data" => $row));
                        break;
                        die();
                    }

                    $now = time() - $starttime;
                    if ($now > 10) {

                        break;
                    }
                } while (true);
            }

            $row = $this->db->select("*")
                            ->from($this->db->dbprefix('push_command'))
                            ->limit(1)
                            ->order_by("id", "DESC")
                            ->get()->row();
            //assuming you're looking at 5 seconds
            echo json_encode(array("status" => 400, "message" => "Failed", "data" => $row));
        } catch (Execption $e) {
            echo json_encode(array("status" => 400, "message" => $e->getMessage()));
        }
    }

    public function read_data() {

        $row = $this->db->select("*")
                        ->from($this->db->dbprefix('push_command'))
                        ->limit(1)
                        ->order_by("updateDate", "desc")
                        ->get()->row();
        if ($row) {

            if ($row->isSent) {
                echo "Connection: " . $_SERVER['REMOTE_ADDR'] . ' - ' . date("F j, Y, g:i:s a", strtotime($row->updateDate)) . PHP_EOL .
                "Type: Sent " . PHP_EOL .
                "Response: {$row->command} " . PHP_EOL .
                "------------------------------------" . PHP_EOL;
            }

            echo "Connection: " . $_SERVER['REMOTE_ADDR'] . ' - ' . date("F j, Y, g:i:s a", strtotime($row->updateDate)) . PHP_EOL .
            "Type: Received " . PHP_EOL .
            "Response:  {$row->ack}  " . PHP_EOL .
            "------------------------------------" . PHP_EOL;

            /* Send instructions. */

            /* if (file_exists('./commands/logs/log_' . date("j.n.Y") . '.txt')) {
              $file = file('./commands/logs/log_' . date("j.n.Y") . '.txt');
              for ($i = max(0, count($file) - 4); $i < count($file); $i++) {
              echo $file[$i];
              } */
        } else {

            echo "No logs available by " . date('Y-m-d H:i:s');
        }

        /* CTL00 = ACT00
          SET11 = ASC11
          id command ack sentDatetime receiveddatetime */
    }

    public function sendCMD() {
        $result = $this->Command_model->submitCMD($this->input->post());
        die(json_encode($result));
    }

    public function readResponce() {

        $row = $this->db->select("*")
                        ->from($this->db->dbprefix('push_command'))
                        ->limit(1)
                        ->order_by("updateDate", "desc")
                        ->get()->row();

        if ($row) {

            if ($row->isSent) {
                echo "Connection  made by: {$row->trackerId}  - " . date("r", strtotime($row->updateDate)) . PHP_EOL .
                "Type: Sent " . PHP_EOL .
                "Response: {$row->command} " . PHP_EOL .
                "------------------------------------" . PHP_EOL;
            }

            echo "Connection  made by: {$row->trackerId}  - " . date("r", strtotime($row->updateDate)) . PHP_EOL .
            "Type: Received " . PHP_EOL .
            "Response:  {$row->ack}  " . PHP_EOL .
            "------------------------------------" . PHP_EOL;
        } else {

            echo "No logs available by " . date('r');
        }
    }

    public function getConfigAll() {
        if ($this->session->id) {
            $data = $this->App_model->getScooterData();
            echo json_encode($data);
        } else {
            redirect('login');
        }
    }

    public function updateConfig() {
        $result = $this->App_model->doUpdateConfig($this->input->post());
        echo json_encode($result);
    }

}
