select 
max(bill_master.tr_time) as tr_date
,'Add: Bill' as particulars,bill_details.bill_no as reference
,0 as received_gold
,0 as received_lc
,sum(qty) as billed_qty
,round(sum(fine_gold),3) as billed_gold
,sum(labour_charge) as billed_lc 
,0 as gold_due
,0 as lc_due
,max(if(bill_master.comments='NONE','Order','Readymade')) as comment
,1 as account_type
from bill_details
inner join bill_master on bill_master.bill_no = bill_details.bill_no
where bill_master.cust_id='S280'
group by bill_details.bill_no
