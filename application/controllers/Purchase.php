<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this -> load -> model('student_model');
        $this -> load -> model('purchase_model');

    }
    function is_logged_in() {
		$is_logged_in = $this -> session -> userdata('is_logged_in');
		if (!isset($is_logged_in) || $is_logged_in != 1) {
			echo 'you have no permission to use developer area'. '<a href="">Login</a>';
			die();
		}
	}


    public function angular_view_purchase(){
        ?>
        <style type="text/css">
            .td-input{
                padding: 2px;
                margin-left: 0px;
                margin-right: 0px;
                text-align: right;
            }
            #purchase-table th, #purchase-table tr td{
                border: 0;
            }
        </style>
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
                            <a class="nav-link" href="#!staffArea">Back <i class="fas fa-home"></i></a>
                        </li>
                    </ul>
                </nav>
            </div>

        </div>
        <div class="d-flex col-12">
            <div class="col-12">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs nav-justified indigo" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link " data-toggle="tab" href="#" role="tab" ng-click="setTab(1)"><i class="fas fa-user-graduate"></i></i> New Purchase</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#" role="tab" ng-click="setTab(2)"><i class="fa fa-heart"></i> Product List</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#" role="tab" ng-click="setTab(3)"><i class="fa fa-envelope"></i>About Product</a>
                    </li>
                </ul>
                <!-- Tab panels -->
                <div class="tab-content">
                    <!--Panel 1-->
                    <div ng-show="isSet(1)">
                        <div id="row my-tab-1">
                            <div class="row d-flex col-12 bg-gray-5">
                                <div class="col-4 bg-gray-3">
                                    <div class="d-flex col-12">
                                        <?php echo get_time_value();?>
                                        <div class="col-4">Vendor</div>
                                        <div class="6">
                                            <select
                                                    class="form-control"
                                                    data-ng-model="purchaseMaster.vendor"
                                                    data-ng-options="vendor as vendor.person_name for vendor in vendorList" ng-change="setGstFactor()">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <span ng-show="purchaseMaster.vendor.mailing_name.length>0">{{purchaseMaster.vendor.mailing_name}}</span>
                                        <div ng-show="purchaseMaster.vendor.address1.length>0">Address: {{purchaseMaster.vendor.address1}}</div>
                                        <div ng-show="purchaseMaster.vendor.gst_number.length>0">GST: {{purchaseMaster.vendor.gst_number}}</div>
                                    </div>
                                    <div class="d-flex col-12 mt-1 pl-0">
                                        <label class="col-4">Invoice number</label>
                                        <div class="col-5">
                                            <input type="text" class="textinput textInput form-control capitalizeWord" ng-model="purchaseMaster.invoice_no" />
                                        </div>
                                    </div>
                                    <div class="d-flex col-12 mt-1 pl-0">
                                        <label  class="col-4">Date</label>
                                        <div class="col-5">
                                            <input class="form-control" type="text"  name="purchaseDate"  ng-model="purchaseMaster.purchase_date"  date-format="yyyy-MM-dd"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4 bg-gray-2">
                                    <div class="d-flex col-12 mt-1">
                                        <label  class="col-4">Vehicle Fare</label>
                                        <div class="col-6">
                                            <input type="text" class="form-control capitalizeWord" ng-model="purchaseMaster.vehicle_fare" />
                                        </div>
                                    </div>
                                    <div class="d-flex col-12 mt-1">
                                        <label  class="col-4">Truck No</label>
                                        <div class="col-6">
                                            <input type="text" class="form-control capitalizeWord" ng-model="purchaseMaster.truck_no" />
                                        </div>
                                    </div>
                                    <div class="d-flex col-12 mt-1">
                                        <label  class="col-4">Bilty No</label>
                                        <div class="col-6">
                                            <input type="text" class="form-control capitalizeWord" ng-model="purchaseMaster.bilty_no" />
                                        </div>
                                    </div>
                                    <div class="d-flex col-12 mt-1 mb-1">
                                        <label  class="col-4">Transport Name</label>
                                        <div class="col-6">
                                            <input type="text" class="textinput textInput form-control capitalizeWord" ng-model="purchaseMaster.transport_name"/>
                                        </div>
                                    </div>
                                    <div class="d-flex col-12 mt-1 mb-1">
                                        <label  class="col-4">Transport Mob</label>
                                        <div class="col-6">
                                            <input type="text" class="textinput textInput form-control capitalizeWord" ng-model="purchaseMaster.transport_mobile"/>
                                        </div>
                                    </div>
                                    <div class="d-flex col-12 mt-1 mb-1">
                                        <label  class="col-4">Licence No</label>
                                        <div class="col-6">
                                            <input type="text" class="textinput textInput form-control capitalizeWord" ng-model="purchaseMaster.licence_no"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4 bg-gray-4">
                                    <div class="d-flex col-12 mt-1">
                                        <label  class="col-4">E-WayBill No</label>
                                        <div class="col-6">
                                            <input type="text" class="textinput textInput form-control capitalizeWord" ng-model="purchaseMaster.eway_bill_no" />
                                        </div>
                                    </div>
                                    <div class="d-flex col-12 mt-1">
                                        <label  class="col-4">E-WayBill Date</label>
                                        <div class="col-6">
                                            <input class="form-control" type="text"  name="purchaseDate"  ng-model="purchaseMaster.eway_bill_date"  date-format="yyyy-MM-dd"/>
                                        </div>
                                    </div>
                                    <div class="d-flex col-12 mt-1">
                                        <label  class="col-4">Valid From</label>
                                        <div class="col-6">
                                            <input type="text" class="textinput textInput form-control capitalizeWord" ng-model="purchaseMaster.valid_from" />
                                        </div>
                                    </div>
                                    <div class="d-flex col-12 mt-1">
                                        <label  class="col-4">Valid To</label>
                                        <div class="col-6">
                                            <input type="text" class="form-control capitalizeWord" ng-model="purchaseMaster.valid_to" />
                                        </div>
                                    </div>
                                    <div class="d-flex col-12 mt-1 mb-1" ng-show="isUpdateable">
                                        <label  class="col-4">Purchase Id</label>
                                        <div class="col-6">
                                            <input type="text" class="textinput textInput form-control capitalizeWord" ng-model="purchaseMaster.purchase_master_id" disabled/>
                                        </div>
                                        <div class="col-2"><a title="Show Bill" href="#"><i class="far fa-file-alt" style="font-size: 40px"></i></a></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row d-flex col-12 bg-gray-5">
                                <label  class="col-1">Purchase Name</label>
                                <label  class="col-2">Quantity</label>
                                <label  class="col-2">Rate</label>
                                <label  class="col-1">Discount</label>
                                <label  class="col-1">Amount</label>
                                <label  class="col-1">SGST &nbsp;{{purchaseDetails.sgst_rate}}%</label>
                                <label  class="col-1">CGST &nbsp;{{purchaseDetails.cgst_rate}}%</label>
                                <label  class="col-1">IGST &nbsp;{{purchaseDetails.igst_rate}}%</label>
                            </div>
                            <div class="row d-flex col-12 bg-gray-5">
                                <div class="col-1">
                                    <select
                                            ng-model="purchaseDetails.product"
                                            ng-options="product as product.product_name for product in prductList" ng-change="gstRateChangeOfProduct();setGst()">
                                    </select>
                                </div>
                                 <div class="col-2">
                                     <input  type="text" class="td-input col-5" id="purchase-quantity" name="purchaseQuantity" ng-keyup="setAmount()" ng-model="purchaseDetails.quantity" ng-change="setGst()">
                                     <a href="#" ng-bind="purchaseDetails.product.unit_name"></a>
                                 </div>
                                <div class="col-2">
                                    <input  type="text" class="td-input col-5" id="purchase-rate" name="purchaseRate" ng-keyup="setAmount()" ng-change="setGst()" ng-model="purchaseDetails.rate">&nbsp;&nbsp;Per&nbsp;{{purchaseDetails.unit==null?purchaseDetails.product.unit_name:purchaseDetails.unit.unit_name}}
                                </div>
                                <div class="col-1">
                                    <input class="td-input col-12" type="text" id="purchase-discount" ng-keyup="setAmount();setGstFactor()" name="purchaseDiscount" ng-init="0.00" step="0.01" ng-model="purchaseDetails.discount">
                                </div>
                                <div class="col-1">
                                    <span id="purchase-amount" name="purchaseAmount"  ng-bind="(purchaseDetails.amount | number)"></span>
                                </div>
                                <div class="col-1">
                                    <span id="purchase-sgst" name="purchaseSgst"   ng-bind="(purchaseDetails.sgst | number:2)"></span>
                                </div>
                                <div class="col-1">
                                    <span   id="purchase-cgst" name="purchaseCgst"  ng-bind="(purchaseDetails.cgst | number:2)"></span>
                                </div>
                                <div class="col-1">
                                    <span   id="purchase-igst" name="purchaseIgst"   ng-bind="(purchaseDetails.igst | number:2)"></span>
                                </div>
                                <div class="col-1">
                                    <input type="button" value="Add" ng-click="addPurchaseDetailsData(purchaseDetails)">
                                </div>
                            </div>
                            <div class="row d-flex col-12 bg-gray-4 table-responsive mt-2">
                                <table class="table" id="purchase-table">
                                    <thead>
                                    <tr>
                                        <th class="text-center">SL</th>
                                        <th class="text-center">Product</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-center">Rate</th>
                                        <th class="text-center">Discount(%)</th>
                                        <th class="text-center">amt.disc</th>
                                        <th class="text-center">SGST</th>
                                        <th>CGST</th>
                                        <th>IGST</th>
                                        <th>Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody ng-repeat="p in purchaseDetailsDataList">
                                    <tr>
                                        <td>{{$index+1}}</td>
                                        <td class="text-center">{{p.product.product_name}}</td>
                                        <td>{{p.quantity}}&nbsp;{{p.product.unit_name}}</td>
                                        <td class="text-right"><i class="fas fa-rupee-sign "></i></i> {{p.rate | number}} Per {{p.product.unit_name}}</td>
                                        <td class="text-right">{{p.discount}}</td>
                                        <td class="text-right">{{getDiscount() | number:2}}</td>
                                        <td class="text-right">{{p.sgst | number:2}}</td>
                                        <td class="text-right">{{p.cgst | number:2}}</td>
                                        <td class="text-right">{{p.igst | number:2}}</td>
                                        <td class="text-right">{{p.amount | number:2}}</td>
                                        <td> <a href="#" data-ng-click="removeRow($index)"><span class="glyphicon glyphicon-remove"></span> Remove </a></td>
                                    </tr>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td>Total:</td>
                                        <td colspan="6" class="text-right">{{purchaseTableFooter[0].totalSgst | number:2}}</td>
                                        <td class="text-right">{{purchaseTableFooter[0].totalCgst | number:2}}</td>
                                        <td class="text-right">{{purchaseTableFooter[0].totalIgst | number:2}}</td>
                                        <td class="text-right">{{purchaseTableFooter[0].totalPurchaseAmount | number:2}}</td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="row d-flex col-12">
                                <div class="col-4"></div>
                                <div class="col-4">
                                   <span ng-show="submitStatus">Record successfully added</span>
                                  <span ng-show="updateStatus">Update successful</span>
                                </div>
                                <div class="col-4">
                                    <input type="button" class="btn btn-outline-primary float-right" id="save-purchase" ng-click="savePurchaseDetails(purchaseMaster,purchaseDetailToSave)" ng-disabled="btnSubmitDisable" value="Save" ng-show="!isUpdateable"/>
                                    <input type="button" class="btn btn-outline-primary float-right" id="reset-purchase" ng-click="resetPurchaseDetails()" value="Reset" ng-disabled="purchaseForm.$pristine"/>
                                    <input type="button" class="btn btn-outline-primary float-right" id="update-purchase" ng-click="updatePurchaseDetails(purchaseMaster,purchaseDetailToSave)" value="Update" ng-show="isUpdateable" ng-disabled="purchaseForm.$pristine"/>
                                </div>
                            </div>
                            <div class="row d-flex col-12">
                                <pre>purchaseMaster={{purchaseMaster | json}}</pre>
                            </div>

                        </div> <!--//End of my tab1//-->
                    </div>
                    <div ng-show="isSet(2)">
                        <div id="my-tab-2">
                            <div class="row d-flex col-12">
                                <p><input type="text" ng-model="searchItem"><span class="glyphicon glyphicon-search"></span> Search </p>
                                <table cellpadding="0" cellspacing="0" class="table table-bordered">
                                    <tr>
                                        <th>SL></th>
                                        <th ng-click="changeSorting('vendor_id')">ID<i class="glyphicon" ng-class="getIcon('vendor_id')"></i></th>
                                        <th ng-click="changeSorting('vendor_name')">Vendor Name<i class="glyphicon" ng-class="getIcon('vendor_name')"></i></th>
                                        <th ng-click="changeSorting('total_purchase_amount')">Amount<i class="glyphicon" ng-class="getIcon('total_purchase_amount')"></i></th>
                                        <th ng-click="changeSorting('purchase_date')">Purchase Date<i class="glyphicon" ng-class="getIcon('purchase_date')"></i></th>
                                        <th>Action</th>
                                    </tr>
                                    <tbody ng-repeat="purchase in allPurchaseList | filter : searchItem  | orderBy:sort.active:sort.descending">
                                    <tr ng-class-even="'banana'" ng-class-odd="'bee'">
                                        <td>{{ $index+1}}</td>
                                        <td>{{purchase.purchase_master_id}}</td>
                                        <td>{{purchase.vendor_name}}</td>
                                        <td class="text-right">{{purchase.total_purchase_amount}}</td>
                                        <td class="text-right">{{purchase.purchase_date}}</td>
                                        <td style="padding-left: 20px;" ng-click="getPurchaseFromTableForUpdate(purchase)"><a href="#"><i class="fa fa-angle-double-right"></i></a></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div ng-show="isSet(3)">
                        <div id="my-tab-3">
                            <pre>purchaseMaster={{purchaseMaster | json}}</pre>
                            <pre>vendorList={{vendorList | json}}</pre>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    function save_new_purchase(){
        $post_data =json_decode(file_get_contents("php://input"), true);
        $purchase_master=(object)$post_data['purchase_master'];
        $purchase_details_list=(object)$post_data['purchase_details_list'];
        $result=$this->purchase_model->insert_new_purchase($purchase_master,$purchase_details_list);
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);
    }
    function update_saved_purchase(){
        $post_data =json_decode(file_get_contents("php://input"), true);
        $purchase_master=(object)$post_data['purchase_master'];
        $purchase_details_list=(object)$post_data['purchase_details_list'];
        $result=$this->purchase_model->update_purchase_master_details($purchase_master,$purchase_details_list);
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);

    }
    function get_all_purchase(){
        $result=$this->purchase_model->select_all_purchase()->result_array();
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);

    }
    function get_purchase_details_by_purchase_master_id(){
        $post_data =json_decode(file_get_contents("php://input"), true);
        $result=$this->purchase_model->select_purchase_details_by_purchase_master_id($post_data['purchase_master_id'])->result_array();
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);

    }
    function get_purchase_master_by_purchase_master_id(){
        $post_data =json_decode(file_get_contents("php://input"), true);
        $result=$this->purchase_model->select_purchase_master_by_purchase_master_id($post_data['purchase_master_id']);
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);

    }
}
?>