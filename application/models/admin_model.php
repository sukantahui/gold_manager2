<?php
class Admin_model extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('huiui_helper');
    }

    function transform_to_pan($pan_data){


        $return_array=array();
        $financial_year=get_financial_year();
        try{
            $this->db->trans_start();
            //insert into maxtable

            $sql="insert into maxtable (table_name, mainfield, financial_year,prefix)
            	values('material_transformation',1,?,'MTR')
				on duplicate key UPDATE table_id=last_insert_id(table_id), mainfield=mainfield+1";
            $result = $this->db->query($sql,array($financial_year));
            if($result==FALSE){
                throw new Exception('Increasing Maxtable for material_transformation pan');
            }

            //getting from maxtable
            $sql="select * from maxtable where table_id=last_insert_id()";
            $result = $this->db->query($sql);
            if($result==FALSE){
                throw new Exception('error getting maxtable');
            }
            $material_transform=$result->row();
            $return_array['material_transformation_id']=$material_transform->prefix.'-'.$material_transform->mainfield.'-'.$material_transform->financial_year;
            //insert into person

            $sql="insert into material_transformation_master (
				   material_transformation_master_id
				  ,employee_id
				  ,karigar_id
				  ,comment
				) VALUES (?,?,?,?)";
            $result = $this->db->query($sql,array($return_array['material_transformation_id']
            //,$this->session->emp_id
            ,$pan_data->employeeID
            ,$pan_data->karigarID
            ,'PAN converted'
            ));
            if($result==FALSE){
                throw new mysqli_sql_exception('error adding new material transformation');
            }

            //recording 92 gold
            $sql="insert into material_transformation_details (
				   material_transformation_details_id
				  ,material_transformation_master_id
				  ,rm_id
				  ,rm_value
				  ,tr_type
				) VALUES (?,?,?,?,-1)";
            $result = $this->db->query($sql,array(NULL
            ,$return_array['material_transformation_id']
            ,48
            ,$pan_data->ninetyTwoGold
            ));
            if($result==FALSE){
                throw new mysqli_sql_exception('error adding new material transformation details for 92 gold reducing');
            }

           //recording 90 gold
           $sql="insert into material_transformation_details (
                  material_transformation_details_id
                 ,material_transformation_master_id
                 ,rm_id
                 ,rm_value
                 ,tr_type
               ) VALUES (?,?,?,?,-1)";
           $result = $this->db->query($sql,array(NULL
           ,$return_array['material_transformation_id']
           ,42
           ,$pan_data->ninetyGold
           ));
           if($result==FALSE){
               throw new mysqli_sql_exception('error adding new material transformation details for 90 gold reducing');
           }


            //recording oldPan gold
            $sql="insert into material_transformation_details (
                  material_transformation_details_id
                 ,material_transformation_master_id
                 ,rm_id
                 ,rm_value
                 ,tr_type
               ) VALUES (?,?,?,?,-1)";
            $result = $this->db->query($sql,array(NULL
            ,$return_array['material_transformation_id']
            ,31
            ,$pan_data->oldPan
            ));
            if($result==FALSE){
                throw new mysqli_sql_exception('error adding new material transformation details for old Pan reducing');
            }



           //recording dal
           $sql="insert into material_transformation_details (
                  material_transformation_details_id
                 ,material_transformation_master_id
                 ,rm_id
                 ,rm_value
                 ,tr_type
               ) VALUES (?,?,?,?,-1)";
           $result = $this->db->query($sql,array(NULL
           ,$return_array['material_transformation_id']
           ,33
           ,$pan_data->dal
           ));
           if($result==FALSE){
               throw new mysqli_sql_exception('error adding new material transformation details for dal reducing');
           }


           //Adding PAN
           $sql="insert into material_transformation_details (
                  material_transformation_details_id
                 ,material_transformation_master_id
                 ,rm_id
                 ,rm_value
                 ,tr_type
               ) VALUES (?,?,?,?,1)";
           $result = $this->db->query($sql,array(NULL
           ,$return_array['material_transformation_id']
           ,31
           ,$pan_data->pan
           ));
           if($result==FALSE){
               throw new mysqli_sql_exception('error adding new material transformation details for pan increasing');
           }

          //adding record to material to employee balance for 92 gold
          $sql="update material_to_employee_balance set outward=outward+?,closing_balance=closing_balance-? where emp_id=? and rm_id=?";
          $result = $this->db->query($sql,array(
           $pan_data->ninetyTwoGold
          ,$pan_data->ninetyTwoGold
          ,$pan_data->employeeID
          ,48
          ));
          if($result==FALSE){
              throw new mysqli_sql_exception('error adding material to employee balance, pure gold');
          }

          //adding record to material to employee balance for 90 gold
          $sql="update material_to_employee_balance set outward=outward+?,closing_balance=closing_balance-? where emp_id=? and rm_id=?";
          $result = $this->db->query($sql,array(
           $pan_data->ninetyGold
          ,$pan_data->ninetyGold
          ,$pan_data->employeeID
          ,42
          ));
          if($result==FALSE){
              throw new mysqli_sql_exception('error adding ninety gold');
          }


          //adding record to material to employee balance for old pan
          $sql="update material_to_employee_balance set outward=outward+?,closing_balance=closing_balance-? where emp_id=? and rm_id=?";
          $result = $this->db->query($sql,array(
             $pan_data->oldPan
            ,$pan_data->oldPan
            ,$pan_data->employeeID
            ,31
          ));
          if($result==FALSE){
                throw new mysqli_sql_exception('error adding old pan');
          }


          //adding record to material to employee balance for Dal
          $sql="update material_to_employee_balance set outward=outward+?,closing_balance=closing_balance-? where emp_id=? and rm_id=?";
          $result = $this->db->query($sql,array(
               $pan_data->dal
            ,$pan_data->dal
            ,$pan_data->employeeID
            ,33
          ));
          if($result==FALSE){
               throw new mysqli_sql_exception('error adding ninety dal');
          }

          //adding record to material to employee balance for pan
          $sql="update material_to_employee_balance set inward=inward+?,closing_balance=closing_balance+? where emp_id=? and rm_id=?";
          $result = $this->db->query($sql,array(
                $pan_data->pan
            ,$pan_data->pan
            ,$pan_data->employeeID
            ,31
          ));
          if($result==FALSE){
                throw new mysqli_sql_exception('error adding ninety pan');
          }

          $this->db->trans_complete();
          $return_array['success']=1;
          $return_array['message']='Successfully recorded';
        }catch(mysqli_sql_exception $e){
            //$err=(object) $this->db->error();
            $err=(object) $this->db->error();
            $return_array['database_error']=$err;
            $return_array['db_error_code']=$err->code;
            $return_array['db_error_message']=$err->message;
            $return_array['error']=create_log($err->code,$this->db->last_query(),'admin_model','transform_to_ninety_two',"log_file.csv");
            $return_array['custom_message']=$e->getMessage();
            $return_array['success']=0;
            $return_array['message']='test';
            $this->db->query("ROLLBACK");
        }catch(Exception $e){
            $err=(object) $this->db->error();
            $return_array['error']=create_log($err->code,$this->db->last_query(),'admin_model','transform_to_ninety_two',"log_file.csv");
            // $return_array['error']=mysql_error;
            $return_array['db_error_code']=$err->code;
            $return_array['db_error_message']=$err->message;
            $return_array['custom_message']=$e->getMessage();
            $return_array['success']=0;
            $return_array['message']=$err->message;
            $this->db->query("ROLLBACK");
        }
        return (object)$return_array;
    }//end of conver_to_pan

    function select_karigars(){
        $return_array=array();
        try {
            $this->db->trans_start();
            $sql="select emp_id,emp_name from employees where department_id=15 and inforce=1 order by emp_name";
            $result = $this->db->query($sql,array());
            if($result==FALSE){
                throw new mysqli_sql_exception('error getting Karigar');
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
            $return_array['error_log']=create_log($err->code,$this->db->last_query(),'admin_model','getting karigar',"log_file.csv");
            $return_array['custom_message']=$e->getMessage();
            $return_array['success']=0;
            $return_array['message']='test';
            $this->db->query("ROLLBACK");
        }catch(Exception $e){
            $err=(object) $this->db->error();
            $return_array['error_log']=create_log($err->code,$this->db->last_query(),'admin_model','transform_to_ninety_two',"log_file.csv");
            $return_array['db_error_code']=$err->code;
            $return_array['db_error_message']=$err->message;
            $return_array['custom_message']=$e->getMessage();
            $return_array['success']=0;
            $return_array['message']=$err->message;
            $this->db->query("ROLLBACK");
        }
        return $return_array;
    }
    function insert_rough_gold($data){


        $return_array=array();
        $financial_year=get_financial_year();
        try{
            $this->db->trans_start();
            //insert into maxtable

            $sql="insert into maxtable (table_name, mainfield, financial_year,prefix)
            	values('material_transformation',1,?,'MTR')
				on duplicate key UPDATE table_id=last_insert_id(table_id), mainfield=mainfield+1";
            $result = $this->db->query($sql,array($financial_year));
            if($result==FALSE){
                throw new Exception('Increasing Maxtable for material_transformation rough gold');
            }

            //getting from maxtable
            $sql="select * from maxtable where table_id=last_insert_id()";
            $result = $this->db->query($sql);
            if($result==FALSE){
                throw new Exception('error getting maxtable');
            }
            $material_transform=$result->row();
            $return_array['material_transformation_id']=$material_transform->prefix.'-'.$material_transform->mainfield.'-'.$material_transform->financial_year;
            //insert into person

            $sql="insert into material_transformation_master (
				   material_transformation_master_id
				  ,employee_id
				  ,karigar_id
				  ,comment
				) VALUES (?,?,?,?)";
            $result = $this->db->query($sql,array($return_array['material_transformation_id']
            ,$data->employee_id
            ,$data->karigar_id
            ,'rough gold entered'
            ));
            if($result==FALSE){
                throw new mysqli_sql_exception('error adding new material transformation');
            }

            //recording rough gold
            $sql="insert into material_transformation_details (
				   material_transformation_details_id
				  ,material_transformation_master_id
				  ,rm_id
				  ,rm_value
				  ,tr_type
				) VALUES (?,?,?,?,-1)";
            $result = $this->db->query($sql,array(NULL
            ,$return_array['material_transformation_id']
            ,$data->rm_id
            ,$data->gold_value
            ));
            if($result==FALSE){
                throw new mysqli_sql_exception('error adding new material transformation details for 92 gold reducing');
            }


           //adding record to material to employee balance for rough gold
           $sql="update material_to_employee_balance set inward=inward+?,closing_balance=closing_balance+? where emp_id=? and rm_id=?";
           $result = $this->db->query($sql,array(
               $data->gold_value
           ,$data->gold_value
           ,$data->employee_id
           ,$data->rm_id
           ));
           if($result==FALSE){
               throw new mysqli_sql_exception('error adding rough gold');
           }

            $this->db->trans_complete();
            $return_array['success']=1;
            $return_array['message']='Successfully recorded';
        }catch(mysqli_sql_exception $e){
            //$err=(object) $this->db->error();
            $err=(object) $this->db->error();
            $return_array['database_error']=$err;
            $return_array['db_error_code']=$err->code;
            $return_array['db_error_message']=$err->message;
            $return_array['error']=create_log($err->code,$this->db->last_query(),'admin_model','transform_to_ninety_two',"log_file.csv");
            $return_array['custom_message']=$e->getMessage();
            $return_array['success']=0;
            $return_array['message']='test';
            $this->db->query("ROLLBACK");
        }catch(Exception $e){
            $err=(object) $this->db->error();
            $return_array['error']=create_log($err->code,$this->db->last_query(),'admin_model','transform_to_ninety_two',"log_file.csv");
            // $return_array['error']=mysql_error;
            $return_array['db_error_code']=$err->code;
            $return_array['db_error_message']=$err->message;
            $return_array['custom_message']=$e->getMessage();
            $return_array['success']=0;
            $return_array['message']=$err->message;
            $this->db->query("ROLLBACK");
        }
        return (object)$return_array;
    }//end of conver_to_pan

    function select_models(){
        $return_array=array();
        try {
            $this->db->trans_start();
            $sql="select product_code,product_description,price_code from product_master order by product_code";
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
            $return_array['error_log']=create_log($err->code,$this->db->last_query(),'admin_model','getting models',"log_file.csv");
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
    function get_material_balance(){
        //$sql="select * from material_to_employee_balance";
        //$sql="call  huitech_generate_pivot_table('select emp_id,rm_id,closing_balance from material_to_employee_balance')";
        $sql="call  huitech_generate_pivot_table(\"";
        /*
        $sql.=    "select concat(`employees`.`emp_name`,material_to_employee_balance.emp_id) AS `emp_name`
                  ,concat(`rm_master`.`rm_name`,material_to_employee_balance.rm_id) AS `rm_name`
                  ,round(`material_to_employee_balance`.`closing_balance`,3) AS `closing_balance` from ((`material_to_employee_balance` join `employees` on((`material_to_employee_balance`.`emp_id` = `employees`.`emp_id`))) join `rm_master` on((`material_to_employee_balance`.`rm_id` = `rm_master`.`rm_ID`)))";
       */
        $sql.="select cust_id,date_format(tr_time,'%M') as bill_month,sum(bill_labour_charge) as lc from bill_master group by cust_id, tr_time";
        $sql.="\")";
        $result = $this->db->query($sql,array());
        $err=(object) $this->db->error();
        create_log($err->code,$this->db->last_query(),'purchase_model','insert_opening',"log_file.csv");
        // $return_array['error']=mysql_error;
        return $result;
    }

    function select_admin_customer_list(){
        $sql="select customer_master.cust_id as ID, cust_name as Customer
                ,agent_master.short_name as Agent
                , city, markup_value as mv from customer_master
                inner join agent_to_customer on agent_to_customer.cust_id = customer_master.cust_id
                inner join agent_master ON agent_master.agent_id = agent_to_customer.agent_id
                order by agent_to_customer.agent_id,customer_master.cust_name
                ";
        $result = $this->db->query($sql,array());
        return $result;
    }

    function select_customers_by_agent_id($agent_id){
        $sql=" select cust_id
       ,cust_name
       ,mailing_name
       ,city
       ,cust_address
       ,cust_pin
       ,cust_phone
       ,(select category from cust_category where id = p_cat) as cust_category
        from customer_master where cust_id in(
        select cust_id from agent_to_customer where agent_id=?)
        order by cust_name
        ";
        $result = $this->db->query($sql,array($agent_id));
        return $result;
    }

    function select_admin_agent_list(){
        $sql="select agent_id, agent_name, short_name, agent_address, agent_phone from agent_master where inforce=1 order by short_name";
        $result = $this->db->query($sql,array());
        return $result;
    }
    function select_current_working_job_list(){
        $sql="select
              date_format(job_master.tr_time,'%d-%m-%y') as job_date
            , job_master.tr_time  
            , job_master.job_id
            , order_master.order_id
            , job_master.product_code
            , job_master.product_size
            , job_master.pieces
            , job_master.gold_send
            , job_master.dal_send
            , job_master.emp_id
            , job_master.pan_send
            , job_master.gold_returned
            , job_master.nitrick_returned
            , customer_master.cust_name
            , employees.emp_name
            , employees.nick_name
            , table_status.status_name
            , TIMESTAMPDIFF(MINUTE, job_master.tr_time, CURRENT_TIMESTAMP()) as minutes
            ,DATEDIFF(CURRENT_TIMESTAMP(), job_master.tr_time) as days_elapsed
            ,IF(DATEDIFF(CURRENT_TIMESTAMP(), job_master.tr_time)<1, if(TIMESTAMPDIFF(HOUR, job_master.tr_time, CURRENT_TIMESTAMP())<1,concat(TIMESTAMPDIFF(MINUTE, job_master.tr_time, CURRENT_TIMESTAMP()),' Minutes'),concat(TIMESTAMPDIFF(HOUR, job_master.tr_time, CURRENT_TIMESTAMP()),' Hours')), concat(DATEDIFF(CURRENT_TIMESTAMP(), job_master.tr_time),' Days')) as job_age
            from job_master 
            inner join order_master on order_master.order_id = job_master.order_id
            inner join customer_master on order_master.cust_id = customer_master.cust_id
            inner join rm_master on rm_master.rm_ID = job_master.rm_id
            inner join table_status on job_master.status=table_status.status_ID
            inner join employees on job_master.emp_id = employees.emp_id
            where job_master.status in(5,6,7,51,8) and  date(job_master.tr_time)>='2019-04-01'
            order by TIMESTAMPDIFF(MINUTE, job_master.tr_time, CURRENT_TIMESTAMP()),job_master.job_id desc";
        $result = $this->db->query($sql,array());
        return $result;
    }

    function update_size_in_job($job_id,$product_size){


        $return_array=array();
        $return_array['job_id']=$job_id;
        $return_array['product_size']=$product_size;
        return (object)$return_array;
        $financial_year=get_financial_year();
        try{
            $this->db->trans_start();
            //insert into maxtable

            $sql="insert into maxtable (table_name, mainfield, financial_year,prefix)
            	values('material_transformation',1,?,'MTR')
				on duplicate key UPDATE table_id=last_insert_id(table_id), mainfield=mainfield+1";
            $result = $this->db->query($sql,array($financial_year));
            if($result==FALSE){
                throw new Exception('Increasing Maxtable for material_transformation rough gold');
            }

            //getting from maxtable
            $sql="select * from maxtable where table_id=last_insert_id()";
            $result = $this->db->query($sql);
            if($result==FALSE){
                throw new Exception('error getting maxtable');
            }
            $material_transform=$result->row();
            $return_array['material_transformation_id']=$material_transform->prefix.'-'.$material_transform->mainfield.'-'.$material_transform->financial_year;
            //insert into person

            $sql="insert into material_transformation_master (
				   material_transformation_master_id
				  ,employee_id
				  ,karigar_id
				  ,comment
				) VALUES (?,?,?,?)";
            $result = $this->db->query($sql,array($return_array['material_transformation_id']
            ,$data->employee_id
            ,$data->karigar_id
            ,'rough gold entered'
            ));
            if($result==FALSE){
                throw new mysqli_sql_exception('error adding new material transformation');
            }

            //recording rough gold
            $sql="insert into material_transformation_details (
				   material_transformation_details_id
				  ,material_transformation_master_id
				  ,rm_id
				  ,rm_value
				  ,tr_type
				) VALUES (?,?,?,?,-1)";
            $result = $this->db->query($sql,array(NULL
            ,$return_array['material_transformation_id']
            ,$data->rm_id
            ,$data->gold_value
            ));
            if($result==FALSE){
                throw new mysqli_sql_exception('error adding new material transformation details for 92 gold reducing');
            }


            //adding record to material to employee balance for rough gold
            $sql="update material_to_employee_balance set inward=inward+?,closing_balance=closing_balance+? where emp_id=? and rm_id=?";
            $result = $this->db->query($sql,array(
                $data->gold_value
            ,$data->gold_value
            ,$data->employee_id
            ,$data->rm_id
            ));
            if($result==FALSE){
                throw new mysqli_sql_exception('error adding rough gold');
            }

            $this->db->trans_complete();
            $return_array['success']=1;
            $return_array['message']='Successfully recorded';
        }catch(mysqli_sql_exception $e){
            //$err=(object) $this->db->error();
            $err=(object) $this->db->error();
            $return_array['database_error']=$err;
            $return_array['db_error_code']=$err->code;
            $return_array['db_error_message']=$err->message;
            $return_array['error']=create_log($err->code,$this->db->last_query(),'admin_model','transform_to_ninety_two',"log_file.csv");
            $return_array['custom_message']=$e->getMessage();
            $return_array['success']=0;
            $return_array['message']='test';
            $this->db->query("ROLLBACK");
        }catch(Exception $e){
            $err=(object) $this->db->error();
            $return_array['error']=create_log($err->code,$this->db->last_query(),'admin_model','transform_to_ninety_two',"log_file.csv");
            // $return_array['error']=mysql_error;
            $return_array['db_error_code']=$err->code;
            $return_array['db_error_message']=$err->message;
            $return_array['custom_message']=$e->getMessage();
            $return_array['success']=0;
            $return_array['message']=$err->message;
            $this->db->query("ROLLBACK");
        }
        return (object)$return_array;
    }//end of conver_to_pan
}//final

?>