<?php
class Product_model extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('huiui_helper');
    }

   function select_inforce_products(){
       $sql="select product.product_id, product.hsn_code, product.product_name, product.default_unit_id, hsn_table.gst_rate, units.unit_name from product
inner join hsn_table on product.hsn_code=hsn_table.hsn_code
inner join units on product.default_unit_id = units.unit_id where product.inforce=1";
       $result = $this->db->query($sql,array());
       return $result;
   }

}//final

?>