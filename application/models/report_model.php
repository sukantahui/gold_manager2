<?php
class Report_model extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('huiui_helper');
    }

   function select_job_stock_difference(){
       $sql="call get_non_recorded_stock()";
       $result = $this->db->query($sql,array());
       return $result;
   }
   function select_inward_outward_material_transactions(){
       $sql="select inward_outward_table.*,date(inward_outward_table.record_time) as record_date
            ,rm_master.rm_name 
            ,receiver.emp_name as receiver_name
            ,payer.emp_name as payer_name
            from (select payer_table.payer_id
                            ,payer_table.rm_id
                            ,payer_table.outward as material_value
                            ,payer_table.reference
                            ,payer_table.record_time
                            ,receiver_table.receiver_id
                        from (select employee_id as payer_id,rm_id,outward,reference,record_time from material_transaction where outward>0) as payer_table
            inner join (select employee_id as receiver_id, inward,reference from material_transaction where inward>0) as receiver_table
            on payer_table.reference = receiver_table.reference)as inward_outward_table
            left outer join rm_master on inward_outward_table.rm_id=rm_master.rm_ID
            left outer join employees as receiver on inward_outward_table.receiver_id=receiver.emp_id
            left outer join employees as payer on inward_outward_table.payer_id=payer.emp_id
            order by inward_outward_table.record_time desc limit 500";
       $result = $this->db->query($sql,array());
       return $result;
   }
   //agent due calculation, discount is not adjusted
   function get_all_agent_with_dues_and_discount(){
       $sql="select agent_id
            ,agent_name,short_name
            ,max_gold_limit
            ,sum(gold_due) as total_gold_due
            ,sum(gold_discount) as total_gold_discount
            ,sum(lc_due) as total_lc_due
            ,sum(lc_discount) as total_lc_discount
            from 
            (select agent_master.agent_id,agent_master.agent_name,agent_master.short_name, agent_master.max_gold_limit,customer_master.cust_id 
            ,round(get_customer_opening_gold(customer_master.cust_id)+get_customer_sale_gold_total(customer_master.cust_id)-get_customer_gold_received_total(customer_master.cust_id),3) as gold_due
            ,round(get_customer_gold_discount_total(customer_master.cust_id),3) as gold_discount
            ,get_customer_opening_lc(customer_master.cust_id)+get_customer_sale_lc_total(customer_master.cust_id)-get_customer_lc_received_total(customer_master.cust_id) as lc_due
            ,get_customer_lc_discount_total(customer_master.cust_id) as lc_discount
            from agent_master
            inner join agent_to_customer on agent_master.agent_id = agent_to_customer.agent_id
            inner join customer_master ON customer_master.cust_id = agent_to_customer.cust_id) as table1
            group by agent_id, agent_name, short_name,max_gold_limit
            order by short_name";
       $result = $this->db->query($sql,array());
       return $result;
   }
   function get_all_customer_with_dues_and_discount_by_agent_id($agent_id){
       $sql="select agent_master.agent_id,agent_master.agent_name,agent_master.short_name,customer_master.cust_id
                ,customer_master.cust_name
                ,customer_master.mailing_name
                ,customer_master.city
                ,round(get_customer_opening_gold(customer_master.cust_id)+get_customer_sale_gold_total(customer_master.cust_id)-get_customer_gold_received_total(customer_master.cust_id),3) as gold_due
                ,round(get_customer_gold_discount_total(customer_master.cust_id),3) as gold_discount
                ,get_customer_opening_lc(customer_master.cust_id)+get_customer_sale_lc_total(customer_master.cust_id)-get_customer_lc_received_total(customer_master.cust_id) as lc_due
                ,get_customer_lc_discount_total(customer_master.cust_id) as lc_discount
                from agent_master
                inner join agent_to_customer on agent_master.agent_id = agent_to_customer.agent_id
                inner join customer_master ON customer_master.cust_id = agent_to_customer.cust_id
                where agent_master.agent_id = ?
                order by customer_master.cust_name";
       $result = $this->db->query($sql,array($agent_id));
       return $result;
   }
   function select_customer_day_book_by_cust_id($cust_id){
       $sql="call get_cutomer_recept_payment_by_id(?)";
       $result = $this->db->query($sql,array($cust_id));
       return $result;
   }

   function select_admin_job_report($date_from,$date_to){
        $sql="select 
            tr_time as job_date
            ,date_format(tr_time,'%d-%m-%y') as tr_date
			,job_master.job_id
            ,employees.emp_name as emp_name
            ,employees.nick_name
            ,concat(job_master.product_code,'-',job_master.price_code) as model_number
            ,pieces
            ,job_master.gold_send
			,p_loss
            ,p_loss* pieces as actual_ploss
            ,job_master.bronze_send
            ,job_master.dal_send
            ,job_master.gold_returned
            ,job_master.pan_send
            ,job_master.pan_send-job_master.pan_returned as pan_used
            ,job_master.nitrick_returned
            ,job_master.status
            ,if(job_master.nitrick_returned>0,1,0) as nitrick_dana
            ,job_master.markup_value as mv
            ,job_master.markup_value * pieces as actual_mv
            ,0 as others
            ,rm_master.rm_gold
            ,TRUNCATE(gold_send+((job_master.pan_send-job_master.pan_returned)*.4)-gold_returned-nitrick_returned+(p_loss*pieces)+(job_master.markup_value*pieces),3) as guini
            ,TRUNCATE((gold_send+(pan_send*.4)-gold_returned-nitrick_returned+(p_loss*pieces)+(job_master.markup_value*pieces))*((rm_master.rm_gold)/100),3) as fine
			,price
            ,price*pieces as actual_price
            ,ifnull(bill_details.bill_no,table_status.status_name) as bill_status
          
			from job_master
			inner join rm_master on job_master.rm_id = rm_master.rm_ID
      inner join employees on job_master.emp_id = employees.emp_id
      left outer join bill_details on job_master.job_id = bill_details.job_id
      left outer join table_status on job_master.status=table_status.status_ID";
        $sql.=" where date(tr_time)>=? and date(tr_time)<=? and job_master.status<>4";

        $result=$this->db->query($sql,array($date_from,$date_to));
        if($result->num_rows()>0){
            return $result->result();
        }else{
            return NULL;
        }
    }

    function select_new_admin_job_report($date_from,$date_to){
        $sql="select 
            job_master.tr_time as job_date
            ,DATE_FORMAT(job_master.tr_time, '%d/%m/%y') as formatted_date
            ,DATEDIFF(now(), job_master.tr_time) as ageing
            ,job_master.job_id
            ,customer_master.cust_name
            ,job_master.pieces
            ,get_gini_by_job_id(job_master.job_id) as guine
            ,rm_master.rm_name
            ,job_master.bronze_send
            ,job_master.dal_send
            ,job_master.pan_send
            ,job_master.p_loss*job_master.pieces as total_ploss
            ,job_master.nitrick_returned*.04 as nitric_4
            ,job_master.markup_value*job_master.pieces as total_mv
            ,job_master.price*job_master.pieces as lc
            ,table_status.status_ID
            ,if(table_status.status_ID = 9,get_bill_number_by_job_id(job_master.job_id),concat(table_status.status_name,' ',(DATEDIFF(now(), job_master.tr_time)),' days')) as bill_number
            from job_master 
            inner join order_master on job_master.order_id = order_master.order_id
            inner join customer_master on order_master.cust_id = customer_master.cust_id
            inner join rm_master on job_master.rm_id = rm_master.rm_ID
            inner join table_status on job_master.status = table_status.status_ID
            where date(job_master.tr_time)>=? and date(job_master.tr_time)<=?
            order by job_master.tr_time";

        $result=$this->db->query($sql,array($date_from,$date_to));
        if($result->num_rows()>0){
            return $result->result();
        }else{
            return NULL;
        }
    }
    function select_agent_sale_qty_by_date($date_from,$date_to){
        $sql="select agent_id, agent_name,get_agent_sale_qty_by_date(agent_id, ?, ?) as qty from agent_master";

        $result=$this->db->query($sql,array($date_from,$date_to));
        if($result->num_rows()>0){
            return $result->result();
        }else{
            return NULL;
        }
    }


}//final

?>