<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this -> load -> model('customer_model');
        //$this -> is_logged_in();
    }
    function is_logged_in() {
		$is_logged_in = $this -> session -> userdata('is_logged_in');
		if (!isset($is_logged_in) || $is_logged_in != 1) {
			echo 'you have no permission to use developer area'. '<a href="">Login</a>';
			die();
		}
	}


    public function angular_view_customer_discount(){
        ?>
        <div class="d-flex">
            <div class="p-2 my-flex-item col-12">
                <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
                    <!-- Brand -->
                    <a class="navbar-brand" href="#">Logo</a>

                    <!-- Links -->
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="#">Link 1</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#!adminArea">Back <i class="fas fa-home"></i></a>
                        </li>
                    </ul>
                </nav>
            </div>

        </div>
        <div class="d-flex col-12">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs nav-justified indigo" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link " data-toggle="tab" href="#" role="tab" ng-click="setTab(1)"><i class="fas fa-user-graduate"></i> Discountt</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#" role="tab" ng-click="setTab(2)"><i class="fa fa-heart"></i> Customer List</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#" role="tab" ng-click="setTab(3)"><i class="fa fa-envelope"></i>About Product</a>
                    </li>
                </ul>
                <!-- Tab panels -->
                <div class="tab-content">
                    <!--Panel 1-->
                    <div ng-show="isSet(1)">
                        <div id="my-tab-1">
                            <form name="discountForm" class="" id="">
                                <div class="row">
                                    <div class="row col-12 bg-gray-3">
                                        <div class="col-6 bg-gray-2">
                                            <div class="row">
                                                <label class="col-lg-2">Search:</label>
                                                <input class="col-4 form-control" type="text" placeholder="By Customer" ng-model="customerDiscount.searchText" ng-keyup="searchCustomers()">
                                                <label class="col-lg-2">Search:</label>
                                                <input class="col-4 form-control" type="text" placeholder="By Agent" ng-model="customerDiscount.searchTextAgent" ng-keyup="searchCustomersByagent()">
                                            </div>
                                            <div class="row">
                                                <label class="col-lg-2">Customer</label>
                                                <select class="col-7 form-control"
                                                        ng-model="customerDiscount.customer"
                                                        ng-options="customer as customer.cust_name for customer in selectedCustomers" required="yes">
                                                </select>
                                            </div>
                                            <div class="row bg-gray-3 mt-3" ng-show="customerDiscount.customer.agent_name.length>0">
                                                <label   class="control-label  col-2">Agent</label>
                                                <label   class="control-label  col-10">{{customerDiscount.customer.agent_name}}</label>
                                            </div>
                                            <div class="row bg-gray-3 mt-2">
                                                <div class="col-12">
                                                    <span ng-show="customerDiscount.customer.cust_id>0">{{customerDiscount.customer.cust_id}}</span>
                                                    <span ng-show="customerDiscount.customer.cust_address.length>0">{{customerDiscount.customer.cust_address}}</span>
                                                    <span ng-show="customerDiscount.customer.city.length>0">, City - {{customerDiscount.customer.city}}</span>
                                                    <span ng-show="customerDiscount.customer.cust_pin.length>0">, PIN - {{customerDiscount.customer.cust_pin}}</span>
                                                    <span ng-show="customerDiscount.customer.cust_phone.length>0">, Phone - {{customerDiscount.customer.cust_phone}}</span>
                                                </div>
                                            </div>
                                            <div class="pt-4">
                                                <div class="row">
                                                    <label  class="control-label  col-2" required="yes">Descripton</label>
                                                    <input class="col-7 form-control" type="text" name="description" ng-model="customerDiscount.description" required>
                                                    <div class="col-3" ng-messages="discountForm.description.$error" role="alert">
                                                        <div ng-message-exp="['required']">Required</div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <label  class="control-label  col-2">Gold</label>
                                                    <input type="number" min="0" value="0" step="0.001" ng-model="customerDiscount.gold" class="col-4 text-right form-control" required="yes">
                                                </div>
                                                <div class="row">
                                                    <label  class="control-label  col-2">LC</label>
                                                    <input type="number" min="0" value="0" step="1" ng-model="customerDiscount.lc" class="text-right col-4 form-control" required="yes">
                                                </div>
                                                <div class="row">
                                                    <label ng-show="discountForm.$pristine" class="control-label col-8">{{message}}</label>
                                                    <label ng-show="discountForm.$dirty" class="control-label  col-8">Fresh  Entry</label>
                                                    <input ng-disabled="discountForm.$pristine || discountForm.$invalid" type="button" class="btn-primary pull-right col-2" value="Submit" ng-click="saveDiscount(customerDiscount)">
                                                    <div class="text-danger" ng-show="discountForm.$pristine || discountForm.$invalid" class="pl-3"><h3><i class="fas fa-times-circle"></i></h3></div>
                                                    <div class="text-success" ng-show="!(discountForm.$pristine || discountForm.$invalid)" class="pl-3"><h3><i class="fas fa-check-circle "></i></h3></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 bg-gray-2">
                                            <!--Right Div-->
                                            <div class="col-10">
                                                <table class="table table-bordered bg-gray-4 mt-3 rounded">
                                                    <thead>
                                                    <tr class="bg-black text-white">
                                                        <th>Description</th>
                                                        <th>Gold</th>
                                                        <th>LC</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td>Opening</td>
                                                        <td class="text-right">{{customerDiscount.customer.opening_gold | number : 3}}</td>
                                                        <td class="text-right"><i class="fas fa-rupee-sign mr-1"></i>{{customerDiscount.customer.opening_lc | number : 2}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Sold</td>
                                                        <td class="text-right">{{customerDiscount.customer.sale_gold | number : 3}}</td>
                                                        <td class="text-right"><i class="fas fa-rupee-sign mr-1"></i>{{customerDiscount.customer.sale_lc | number : 2}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Received</td>
                                                        <td class="text-right">{{customerDiscount.customer.gold_received | number : 3}}</td>
                                                        <td class="text-right"><i class="fas fa-rupee-sign mr-1"></i>{{customerDiscount.customer.lc_received | number : 2}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Discount Allowed</td>
                                                        <td class="text-right">{{customerDiscount.customer.gold_discount | number : 3}}</td>
                                                        <td class="text-right"><i class="fas fa-rupee-sign mr-1"></i>{{customerDiscount.customer.lc_discount | number : 2}}</td>
                                                    </tr>
                                                    <tr class="success">
                                                        <td class="">Current Balance</td>
                                                        <td class="text-right">{{(customerDiscount.customer.opening_gold+customerDiscount.customer.sale_gold-customerDiscount.customer.gold_received-customerDiscount.customer.gold_discount) | number : 3}}</td>
                                                        <td class="text-right"><i class="fas fa-rupee-sign mr-1"></i>{{(customerDiscount.customer.opening_lc+customerDiscount.customer.sale_lc-customerDiscount.customer.lc_received-customerDiscount.customer.lc_discount) | number : 2}}</td>
                                                    </tr>
                                                    <tr class="">
                                                        <td class="">Current Discount</td>
                                                        <td class="text-right">{{(customerDiscount.gold) | number : 3}}</td>
                                                        <td class="text-right"><i class="fas fa-rupee-sign mr-1"></i>{{customerDiscount.lc | number : 2}}</td>
                                                    </tr>
                                                    <tr class="info bg-blue text-white">
                                                        <td class="">New Balance</td>
                                                        <td class="text-right">{{(customerDiscount.customer.opening_gold+customerDiscount.customer.sale_gold-customerDiscount.customer.gold_received-customerDiscount.customer.gold_discount-customerDiscount.gold) | number : 3}}</td>
                                                        <td class="text-right"><i class="fas fa-rupee-sign mr-1"></i>{{(customerDiscount.customer.opening_lc+customerDiscount.customer.sale_lc-customerDiscount.customer.lc_received-customerDiscount.customer.lc_discount-customerDiscount.lc) | number : 2}}</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!--End of Right Div-->

                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div>
                                <div id="discount-report-table">

                                    <table cellpadding="0" cellspacing="0" class="table table-bordered">
                                        <tr>
                                            <th>SL></th>
                                            <th ng-click="changeSorting('person_id')">ID<i class="glyphicon" ng-class="getIcon('person_id')"></i></th>
                                            <th ng-click="changeSorting('person_name')">Customer<i class="glyphicon" ng-class="getIcon('person_name')"></i></th>
                                            <th ng-click="changeSorting('mobile_no')">Agent<i class="glyphicon" ng-class="getIcon('mobile_no')"></i></th>
                                            <th ng-click="changeSorting('address1')">City<i class="glyphicon" ng-class="getIcon('address1')"></i></th>
                                            <th ng-click="changeSorting('Area')">Comment<i class="glyphicon" ng-class="getIcon('area')"></i></th>
                                            <th ng-click="changeSorting('gst_number')">Gold<i class="glyphicon" ng-class="getIcon('gst_number')"></i></th>
                                            <th ng-click="changeSorting('aadhar_no')">Amount<i class="glyphicon" ng-class="getIcon('aadhar_no')"></i></th>
                                            <th>Edit</th>
                                        </tr>
                                        <tbody ng-repeat="customer in customerDiscountList | filter : searchItem  | orderBy:sort.active:sort.descending">
                                        <tr ng-class-even="'banana'" ng-class-odd="'bee'">
                                            <td>{{ $index+1}}</td>
                                            <td>{{customer.cust_id}}</td>
                                            <td>{{customer.cust_name}}</td>
                                            <td>{{customer.short_name}}</td>
                                            <td>{{customer.city}}</td>
                                            <td>{{customer.comment}}</td>
                                            <td class="text-right">{{customer.gold}}</td>
                                            <td class="text-right">{{customer.amount}}</td>
                                            <td ng-click="updateCustomerFromTable(customer)"><a href="#"><i class="glyphicon glyphicon-edit"></i></a></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div> <!--//End of my tab1//-->
                    </div>
                    <div ng-show="isSet(2)">
                        <div id="my-tab-2">
                            <div id="customer-net-due-table">
                                <!--                                <pre>customerWithNetDuesTotal={{customerWithNetDuesTotal | json}}</pre>-->
                                <!--                                <pre>customerWithNetDues={{customerWithNetDues | json}}</pre>-->
                                <table cellpadding="0" cellspacing="0" class="table table-bordered">
                                    <tr class="bg-gray-5">
                                        <th>SL></th>
                                        <th ng-click="changeSorting('person_id')">ID<i class="glyphicon" ng-class="getIcon('person_id')"></i></th>
                                        <th ng-click="changeSorting('person_name')">Customer<i class="glyphicon" ng-class="getIcon('person_name')"></i></th>
                                        <th ng-click="changeSorting('mobile_no')">Current Gold<i class="glyphicon" ng-class="getIcon('mobile_no')"></i></th>
                                        <th ng-click="changeSorting('address1')">Discount<i class="glyphicon" ng-class="getIcon('address1')"></i></th>
                                        <th ng-click="changeSorting('Area')">Net Gold<i class="glyphicon" ng-class="getIcon('area')"></i></th>
                                        <th ng-click="changeSorting('gst_number')">Current LC<i class="glyphicon" ng-class="getIcon('gst_number')"></i></th>
                                        <th ng-click="changeSorting('aadhar_no')">Discount<i class="glyphicon" ng-class="getIcon('aadhar_no')"></i></th>
                                        <th ng-click="changeSorting('aadhar_no')">Current Due<i class="glyphicon" ng-class="getIcon('aadhar_no')"></i></th>
                                        <th>Edit</th>
                                    </tr>
                                    <tbody ng-repeat="customer in customerWithNetDues | filter : searchItem  | orderBy:sort.active:sort.descending">
                                    <tr ng-class-even="'banana'" ng-class-odd="'bee'">
                                        <td>{{ $index+1}}</td>
                                        <td>{{customer.cust_id}}</td>
                                        <td>{{customer.cust_name}}</td>
                                        <td class="text-right">{{customer.current_gold_due | number:3}}</td>
                                        <td class="text-right">{{customer.discount_gold | number:3}}</td>
                                        <td class="text-right">{{(customer.current_gold_due-customer.discount_gold) | number:3}}</td>
                                        <td class="text-right">{{customer.current_lc_due | number:2}}</td>
                                        <td class="text-right">{{customer.discount_lc | number:2}}</td>
                                        <td class="text-right">{{(customer.current_lc_due-customer.discount_lc) | number:2 }}</td>
                                        <td ng-click="updateCustomerFromTable(customer)"><a href="#"><i class="glyphicon glyphicon-edit"></i></a></td>
                                    </tr>
                                    </tbody>
                                    <tfoot>
                                    <tr class="bg-blue text-white">
                                        <td colspan="3">Total</td>
                                        <td class="text-right">{{customerWithNetDuesTotal[0].total_gold_due | number:"3"}}</td>
                                        <td class="text-right">{{customerWithNetDuesTotal[0].total_gold_discount | number:"3"}}</td>
                                        <td class="text-right">{{(customerWithNetDuesTotal[0].total_gold_due-customerWithNetDuesTotal[0].total_gold_discount) | number:"3"}}</td>

                                        <td class="text-right">{{customerWithNetDuesTotal[0].total_current_lc_due | number:"2"}}</td>
                                        <td class="text-right">{{customerWithNetDuesTotal[0].total_lc_discount | number:"2"}}</td>
                                        <td class="text-right">{{(customerWithNetDuesTotal[0].total_current_lc_due-customerWithNetDuesTotal[0].total_lc_discount) | number:"2"}}</td>

                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div ng-show="isSet(3)">
                        <div id="my-tab-3">
                            <pre>Customer={{customerList | json}}</pre>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    public function get_customers(){
        $result=$this->customer_model->select_customers()->result_array();
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);
    }
    function save_new_discount(){
        $post_data =json_decode(file_get_contents("php://input"), true);
        $discount_data=(object)$post_data['discount_data'];
        $result=$this->customer_model->insert_new_discount_to_customer($discount_data);
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);
    }

    public function get_customer_discounts(){
        $result=$this->customer_model->select_customer_discounts()->result_array();
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);
    }
    public function get_customer_net_dues(){
        $result=$this->customer_model->select_customer_net_dues()->result_array();
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);
    }
}
?>