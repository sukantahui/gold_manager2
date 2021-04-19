select agent_master.agent_id,agent_master.agent_name,agent_master.short_name,customer_master.cust_id
,customer_master.cust_name
,customer_master.city
,round(get_customer_opening_gold(customer_master.cust_id)+get_customer_sale_gold_total(customer_master.cust_id)-get_customer_gold_received_total(customer_master.cust_id),3) as gold_due
,round(get_customer_gold_discount_total(customer_master.cust_id),3) as gold_discount
,get_customer_opening_lc(customer_master.cust_id)+get_customer_sale_lc_total(customer_master.cust_id)-get_customer_lc_received_total(customer_master.cust_id) as lc_due
,get_customer_lc_discount_total(customer_master.cust_id) as lc_discount
from agent_master
inner join agent_to_customer on agent_master.agent_id = agent_to_customer.agent_id
inner join customer_master ON customer_master.cust_id = agent_to_customer.cust_id
where agent_master.agent_id = 'AG2004'
order by customer_master.cust_name