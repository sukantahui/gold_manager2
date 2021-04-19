SET @runtot:=0;

select 
tr_date
, particulars
, reference
, received_gold
, received_lc
, billed_qty
, billed_gold
, billed_lc
, op_gold_due
, op_lc_due
, comment
, account_type
,(@runtot := @runtot +op_gold_due+billed_gold-received_gold) AS current_gold_due
from(
  select 
  op_date as tr_date
  ,'Start' as particulars
  ,'opening' as reference
  ,0 as received_gold
  ,0 as received_lc
  ,0 as billed_qty
  ,0 as billed_gold
  ,0 as billed_lc
  ,round(opening_gold,3) as op_gold_due
  ,opening_lc as op_lc_due
  ,'opening' as comment
  ,1 as account_type
  from customer_balance where cust_id='S280'
  
  union
  
  select 
  max(bill_master.tr_time) as tr_date
  ,'Add: Bill' as particulars,bill_details.bill_no as reference
  ,0 as received_gold
  ,0 as received_lc
  ,sum(qty) as billed_qty
  ,round(sum(fine_gold),3) as billed_gold
  ,sum(labour_charge) as billed_lc 
  ,0 as op_gold_due
  ,0 as op_lc_due
  ,max(if(bill_master.comments='NONE','Order','Readymade')) as comment
  ,1 as account_type
  from bill_details
  inner join bill_master on bill_master.bill_no = bill_details.bill_no
  where bill_master.cust_id='S280'
  group by bill_details.bill_no

  union

  select
  tr_date
  ,'Less: Gold Received' as particulars
  ,gold_receipt_id as reference
  ,gold_value as received_gold
  ,0 as received_lc
  ,0 as billed_qty
  ,0 as billed_gold
  ,0 as billed_lc
  ,0 as op_gold_due
  ,0 as op_lc_due
  ,'gold received' as comment
  ,-1 as account_type
  from gold_receipt_master
  where cust_id='S280'

  union

  select 
  lc_receipt_date as tr_date
  ,'Less: Lc Received' as particulars
  , lc_receipt_no
  , 0 as received_gold
  , amount as received_lc
  , 0 as billed_qty
  , 0 as billed_gold
  , 0 as billed_lc
  , 0 as op_gold_due
  , 0 as op_lc_due
  , 'received lc ' as comment
  , -1 as account_type
  from lc_receipt_master
  where cust_id='S280'
) as table1
order by tr_date