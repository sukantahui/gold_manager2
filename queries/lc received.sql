select 
lc_receipt_date as tr_date
,'Less: Lc Received' as particulars
, lc_receipt_no
, 0 as received_gold
, amount as received_lc
, 0 as billed_qty
, 0 as billed_gold
, 0 as billed_lc
, 0 as gold_due
, 0 as lc_due
, 'received lc ' as comment
, -1 as account_type
from lc_receipt_master
where cust_id='S280'