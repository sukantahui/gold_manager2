<?php
class Customer_model extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('huiui_helper');
    }

    function insert_new_customer($customer){
        $return_array=array();
        $financial_year=get_financial_year();
        try{
            //$this->db->query("START TRANSACTION");
            $this->db->trans_start();
            //insert into maxtable
            $sql="insert into maxtable (subject_name, current_value, financial_year,prefix)
            	values('customer',1,?,'C')
				on duplicate key UPDATE id=last_insert_id(id), current_value=current_value+1";
            $result=$this->db->query($sql,array($financial_year));
            if($result==FALSE){
                throw new Exception('Increasing Maxtable for Customer_id');
            }
            //Getting from max_table
            $sql="select * from maxtable where id=last_insert_id()";
            $result=$this->db->query($sql);
            if($result==FALSE){
                throw new Exception('error getting maxtable');
            }
            $customer_id=$result->row()->prefix.'-'.leading_zeroes($result->row()->current_value,3).'-'.$financial_year;
            $return_array['person_id']=$customer_id;
            $sql = "insert into person (
               person_id
              ,person_cat_id
              ,person_name
              ,mailing_name
              ,mobile_no
              ,phone_no
              ,email
              ,aadhar_no
              ,pan_no
              ,address1
              ,city
              ,district_id
              ,post_office
              ,pin
              ,gst_number
              ,inforce
              ,state_id
            ) VALUES (?,4,?,?,?,?,?,?,?,?,?,?,?,?,?,1,?)";
            $result=$this->db->query($sql,array(
                $customer_id
            ,$customer->person_name
            ,$customer->mailing_name
            ,$customer->mobile_no
            ,$customer->phone_no
            ,$customer->email
            ,$customer->aadhar_no
            ,$customer->pan_no
            ,$customer->address1
            ,$customer->city
            ,$customer->district_id
            ,$customer->post_office
            ,$customer->pin
            ,$customer->gst_number
            ,$customer->state_id
            ));
            $return_array['dberror']=$this->db->error();
            if($result==FALSE){
                throw new Exception('error adding new customer');
            }
            $this->db->trans_complete();
            $return_array['success']=1;
            $return_array['message']='Successfully recorded';
        }catch(mysqli_sql_exception $e){
            //$err=(object) $this->db->error();

            $err=(object) $this->db->error();
            $return_array['error']=create_log($err->code,$this->db->last_query(),'purchase_model','insert_opening',"log_file.csv");
            $return_array['success']=0;
            $return_array['message']='test';
            $this->db->query("ROLLBACK");
        }catch(Exception $e){
            $err=(object) $this->db->error();
            $return_array['error']=create_log($err->code,$this->db->last_query(),'purchase_model','insert_opening',"log_file.csv");
            // $return_array['error']=mysql_error;
            $return_array['success']=0;
            $return_array['message']=$err->message;
            $this->db->query("ROLLBACK");
        }
        return (object)$return_array;
    }
    function select_customers(){
        $sql="select customer_master.cust_id
                    ,get_customer_opening_gold(customer_master.cust_id) as opening_gold
                    ,get_customer_opening_lc(customer_master.cust_id) as opening_lc
                    ,get_customer_sale_gold_total(customer_master.cust_id) as sale_gold
                    ,get_customer_sale_lc_total(customer_master.cust_id) as sale_lc
                    ,get_customer_gold_received_total(customer_master.cust_id) as gold_received
                    ,get_customer_lc_received_total(customer_master.cust_id) as lc_received
                    ,get_customer_gold_discount_total(customer_master.cust_id) as gold_discount
                    ,get_customer_lc_discount_total(customer_master.cust_id) as lc_discount
                    ,customer_master.cust_name, customer_master.mailing_name, customer_master.city, customer_master.cust_address, customer_master.cust_pin, customer_master.cust_phone, agent_master.agent_id, agent_master.agent_name, agent_master.short_name from customer_master 
            left outer join agent_to_customer on agent_to_customer.cust_id = customer_master.cust_id
            left outer join agent_master ON agent_master.agent_id = agent_to_customer.agent_id
            order by cust_name";
        $result = $this->db->query($sql,array());
        return $result;
    }
    function update_vendor_by_vendor_id($customer){
        $return_array=array();
        try{
            $this->db->trans_start();
            //update Customer
            $sql="update person set
                  person_name=?
                , mailing_name=?
                , mobile_no=?
                , phone_no=?
                , email=?
                , aadhar_no=?
                , pan_no=?
                , address1=?
                , city=?
                , district_id=?
                , post_office=?
                , pin=?
                , gst_number=?
                , state_id=? where person_id=?";
            $result=$this->db->query($sql,array(
                $customer->person_name
            ,$customer->mailing_name
            ,$customer->mobile_no
            ,$customer->phone_no
            ,$customer->email
            ,$customer->aadhar_no
            ,$customer->pan_no
            ,$customer->address1
            ,$customer->city
            ,$customer->district_id
            ,$customer->post_office
            ,$customer->pin
            ,$customer->gst_number
            ,$customer->state_id
            ,$customer->person_id
            ));
            if($result==FALSE){
                throw new mysqli_sql_exception('error updating vendor');
            }
            // adding customer completed
            $this->db->trans_complete();
            $return_array['success']=1;
            $return_array['message']='Successfully Updated';
        }catch(mysqli_sql_exception $e){
            //$err=(object) $this->db->error();
            $err=(object) $this->db->error();
            $return_array['error']=create_log($err->code,$this->db->last_query(),'person model','update_person',"log_file.csv");
            $return_array['success']=0;
            $return_array['message']='test';
            $this->db->query("ROLLBACK");
        }
        return (object)$return_array;
    }//End of update_vendor_by_vendor_id()

    function insert_new_discount_to_customer($discount_data){
        $return_array=array();
        $financial_year=get_financial_year();
        try{
            //$this->db->query("START TRANSACTION");
            $this->db->trans_start();
            //insert into maxtable
            $sql="insert into maxtable (table_name, mainfield, financial_year,prefix)
            	values('customer_discount',1,?,'DISC')
				on duplicate key UPDATE table_id=last_insert_id(table_id), mainfield=mainfield+1";
            $result = $this->db->query($sql, array($financial_year));
            if($result==FALSE){
                throw new Exception('Increasing Maxtable for discount_table');
            }

            //getting from maxtable
            $sql="select * from maxtable where table_id=last_insert_id()";
            $result = $this->db->query($sql);
            if($result==FALSE){
                throw new Exception('error getting maxtable');
            }
            $discount_table_id=$result->row()->prefix.'-'.leading_zeroes($result->row()->mainfield,4).'-'.$financial_year;
            $return_array['discount_id']=$discount_table_id;



            $sql="insert into customer_discount (
                   customer_discount_id
                  ,cust_id
                  ,agent_id
                  ,emp_id
                  ,amount
                  ,gold
                  ,inforce
                  ,comment
                ) VALUES (?,?,?,?,?,?,1,?)";
            $result=$this->db->query($sql,array(
                $discount_table_id
                ,$discount_data->cust_id
                ,$discount_data->agent_id
                ,72
                ,$discount_data->lc
                ,$discount_data->gold
                ,$discount_data->description
            ));
            $return_array['dberror']=$this->db->error();

            if($result==FALSE){
                throw new Exception('error adding sale master');
            }
            // adding bill_master completed

            $this->db->trans_complete();
            $return_array['success']=1;
            $return_array['message']='Successfully recorded';
        }catch(mysqli_sql_exception $e){
            //$err=(object) $this->db->error();

            $err=(object) $this->db->error();
            $return_array['error']=create_log($err->code,$this->db->last_query(),'purchase_model','insert_opening',"log_file.csv");
            $return_array['success']=0;
            $return_array['message']='test';
            $this->db->query("ROLLBACK");
        }catch(Exception $e){
            $err=(object) $this->db->error();
            $return_array['error']=create_log($err->code,$this->db->last_query(),'purchase_model','insert_opening',"log_file.csv");
            // $return_array['error']=mysql_error;
            $return_array['success']=0;
            $return_array['message']=$err->message;
            $this->db->query("ROLLBACK");
        }
        return (object)$return_array;
    }//end of function

    function select_customer_discounts(){
        $sql="select 
                customer_discount.customer_discount_id
                , customer_discount.cust_id
                , customer_discount.agent_id
                , customer_discount.amount
                , customer_discount.gold
                , customer_discount.record_time
                , customer_discount.comment
                , customer_master.cust_name
                , customer_master.city
                , customer_master.cust_phone
                , agent_master.short_name
                from customer_discount 
                inner join customer_master ON customer_master.cust_id = customer_discount.cust_id
                inner join agent_master on customer_discount.agent_id = agent_master.agent_id
                order by customer_discount.record_time desc
                ";
        $result = $this->db->query($sql,array());
        return $result;
    }//end of function select_customer_discounts

    function select_customer_net_dues(){
        $sql="select 
                cust_id
                ,cust_name
                ,get_customer_opening_gold(cust_id) as opening_gold
                ,get_customer_sale_gold_total(cust_id) as billed_gold
                ,get_customer_gold_received_total(cust_id) as received_gold
                ,get_customer_gold_discount_total(cust_id) as discount_gold
                ,get_customer_opening_gold(cust_id)+get_customer_sale_gold_total(cust_id) -get_customer_gold_received_total(cust_id) as current_gold_due
                ,get_customer_opening_lc(cust_id) as opening_lc
                ,get_customer_sale_lc_total(cust_id) as billed_lc
                ,get_customer_lc_received_total(cust_id) as received_lc
                ,get_customer_lc_discount_total(cust_id) as discount_lc
                ,get_customer_opening_lc(cust_id) + get_customer_sale_lc_total(cust_id) - get_customer_lc_received_total(cust_id)  as current_lc_due
                from customer_master";
        $result = $this->db->query($sql,array());
        return $result;
    }//end of function select_customer_discounts

}//final

?>