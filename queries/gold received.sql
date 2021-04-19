select
tr_date
,'Less: Gold Received' as particulars
,gold_receipt_id as reference
,gold_value as received_gold
,0 as received_lc
,0 as billed_qty
,0 as billed_gold
,0 as billed_lc
,0 as gold_due
,0 as lc_due
,'gold received' as comment
,-1 as account_type
from gold_receipt_master
where cust_id='S280'