<?php
class Agent_model extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('huiui_helper');
    }


    function select_inforced_agent(){
        $return_array=array();
        try {
            $this->db->trans_start();
            $sql="select agent_id,agent_name,short_name from agent_master where inforce order by agent_name";
            $result = $this->db->query($sql,array());
            if($result==FALSE){
                throw new mysqli_sql_exception('error getting Agents');
            }

            $this->db->trans_complete();

            $return_array['result']=$result;
            $return_array['success']=1;
            $return_array['message']='Successfully record fetched';
        }catch(mysqli_sql_exception $e){
            $err=(object) $this->db->error();
            $return_array['database_error']=$err;
            $return_array['db_error_code']=$err->code;
            $return_array['db_error_message']=$err->message;
            $return_array['error_log']=create_log($err->code,$this->db->last_query(),'admin_model','getting Agents',"log_file.csv");
            $return_array['custom_message']=$e->getMessage();
            $return_array['success']=0;
            $return_array['message']='test';
            $this->db->query("ROLLBACK");
        }catch(Exception $e){
            $err=(object) $this->db->error();
            $return_array['error_log']=create_log($err->code,$this->db->last_query(),'agent_model','transform_to_ninety_two',"log_file.csv");
            $return_array['db_error_code']=$err->code;
            $return_array['db_error_message']=$err->message;
            $return_array['custom_message']=$e->getMessage();
            $return_array['success']=0;
            $return_array['message']=$err->message;
            $this->db->query("ROLLBACK");
        }
        return $return_array;
    }
}//final

?>