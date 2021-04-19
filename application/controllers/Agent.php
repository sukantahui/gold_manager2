<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agent extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this -> load -> model('person');
        $this -> load -> model('Agent_model');
        //$this -> is_logged_in();
    }

    function is_logged_in() {
		$is_logged_in = $this -> session -> userdata('is_logged_in');
		if (!isset($is_logged_in) || $is_logged_in != 1) {
			echo 'you have no permission to use developer area'. '<a href="">Login</a>';
			die();
		}
	}


    public function get_inforced_agent(){
        $result=$this->Agent_model->select_inforced_agent();
        $report_array['records']=$result['result']->result();
        echo json_encode($report_array,JSON_NUMERIC_CHECK);
    }




}
?>