select agent_id,agent_name,short_name
,sum(gold_due) as total_gold_due
,sum(gold_discount) as total_discount_allowed
,sum(lc_due) as total_lc_due
,sum(lc_discount) as total_lc_discount
from 
(select agent_master.agent_id,agent_master.agent_name,agent_master.short_name,customer_master.cust_id 
,round(get_customer_opening_gold(customer_master.cust_id)+get_customer_sale_gold_total(customer_master.cust_id)-get_customer_gold_received_total(customer_master.cust_id),3) as gold_due
,get_customer_gold_discount_total(customer_master.cust_id) as gold_discount
,get_customer_opening_lc(customer_master.cust_id)+get_customer_sale_lc_total(customer_master.cust_id)-get_customer_lc_received_total(customer_master.cust_id) as lc_due
,get_customer_lc_discount_total(customer_master.cust_id) as lc_discount
from agent_master
inner join agent_to_customer on agent_master.agent_id = agent_to_customer.agent_id
inner join customer_master ON customer_master.cust_id = agent_to_customer.cust_id) as table1
group by agent_id, agent_name, short_name