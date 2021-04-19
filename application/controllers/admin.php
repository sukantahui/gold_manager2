<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this -> load -> model('person');
        $this -> load -> model('admin_model');
        //$this -> is_logged_in();
    }

    function is_logged_in() {
		$is_logged_in = $this -> session -> userdata('is_logged_in');
		if (!isset($is_logged_in) || $is_logged_in != 1) {
			echo 'you have no permission to use developer area'. '<a href="">Login</a>';
			die();
		}
	}


    public function angular_view_welcome_admin(){
        ?>
        <?php
            $GLOBALS["menu_name"] = "Admin Area";
            include("menu_all/admin/menu_admin.html");
        ?>

        <div class="d-flex col-12">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="vendor-div">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs nav-justified indigo" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link bg-success" data-toggle="tab" href="#" role="tab" ng-click="setTab(1)"><i class="fa fa-user" ></i> New Product</a>
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
                        <div id="my-tab-1">
                            This is tab 1 test
                            <table border="1">
                                <tr>
                                    <th ng-repeat="(key, val) in pivotTableData[0]">{{ key }}</th>
                                </tr>
                                <tr ng-repeat="row in pivotTableData">
                                    <td ng-repeat="column in row">
                                        {{ column }}
                                    </td>
                                </tr>
                            </table>

                        </div>
                    </div>
                    <div ng-show="isSet(2)">
                        <div id="my-tab-1">
                            This is tab 2
                        </div>
                    </div>
                    <div ng-show="isSet(3)">
                        <div id="my-tab-1">
                            This is tab 3
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    public function angular_view_material_conversion(){
        ?>
        <?php
        $GLOBALS["menu_name"] = "Admin Area";
        include("menu_all/admin/menu_admin.html");
        ?>
        <div class="card">
            <div class="card-header">
                <div class="d-flex">
                    <div class="p-2 my-flex-item col-12">
                        <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
                            <!-- Brand -->
                            <a class="navbar-brand" href="#">Logo</a>

                            <!-- Links -->
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a class="nav-link" href="#!adminArea">Back <i class="fas fa-home"></i></a>
                                </li>
                            </ul>
                            <a class="navbar-btn" href="#">Admin</a>
                        </nav>
                    </div>

                </div>
            </div>
            <div class="card-piller">
                <ul class="nav nav-tabs nav-justified indigo" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link " data-toggle="tab" href="#" role="tab" ng-click="setTab(1)"><i class="fa fa-user" ></i> PAN Creation</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#" role="tab" ng-click="setTab(2)"><i class="fa fa-heart"></i> Product List</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#" role="tab" ng-click="setTab(3)"><i class="fab fa-goodreads"></i>Rauh Gold Entry</a>
                    </li>
                </ul>
            </div>

            <div class="card-body p-0" ng-show="isSet(1)">
                <form name="panConversation">
                    <div class="row">
                        <div class="row col-12 bg-gray-3">
                            <div class="col-6 bg-gray-2">
                                <div class="row">
                                    <label class="col-2">Gold 92% </label>
                                    <input class="col-3 form-control text-right" type="text" ng-blur="panConversion.gold92=textToFloat(panConversion.gold92)" placeholder="92 Gold" ng-model="panConversion.gold92" ng-pattern="/^\d{0,9}(\.\d{1,9})?$/" required ng-change="panConversion.goldChange()" ng-keyup="panConversion.goldChange()">
                                </div>
                                <div class="row">
                                    <label class="col-2">Gold 90%</label>
                                    <input class="col-3 form-control text-right" name="gold90" type="text" ng-blur="panConversion.gold90=textToFloat(panConversion.gold90)"  placeholder="90 Gold" ng-model="panConversion.gold90" ng-pattern="/^\d{0,9}(\.\d{3})?$/" required ng-change="panConversion.goldChange()" ng-keyup="panConversion.goldChange()">
                                    <div class="col-7"  ng-messages="panConversation.gold90.$error" role="alert">
                                        <div class="text-danger" ng-message-exp="['pattern']">Check 90 Gold value</div>
                                        <div class="text-danger" ng-message-exp="['required']">value is required</div>
                                        <span class="col-3" ng-show="panConversation.gold90.$valid">Total Gold: {{panConversion.totalGold | number:"3"}}</span>
                                    </div>
                                </div>
                                <!--getting loose PAN-->
                                <div class="row">
                                    <label class="col-2">Old PAN</label>
                                    <input class="col-3 form-control text-right" name="gold90" type="text" ng-blur="panConversion.oldPan=textToFloat(panConversion.oldPan)"  placeholder="90 Gold" ng-model="panConversion.oldPan" ng-pattern="/^\d{0,9}(\.\d{3})?$/" required ng-change="panConversion.goldChange()" ng-keyup="panConversion.goldChange()">
                                </div>


                                <div class="row">
                                    <label class="col-2">Dal</label>
                                    <input class="col-3 form-control text-right" name="dal" type="text"  placeholder="Dal" ng-blur="panConversion.dal=textToFloat(panConversion.dal)" ng-model="panConversion.dal" ng-pattern="/^\d{0,9}(\.\d{1,9})?$/" required ng-change="panConversion.goldChange()">
                                    <div class="col-7" ng-messages="panConversation.dal.$error" role="alert">
                                        <div class="text-danger" ng-message-exp="['pattern']">Check Dal value</div>
                                        <div class="text-danger" ng-message-exp="['required']">value is required</div>
                                        <span class="col-3" ng-show="panConversation.gold90.$valid">Exp. Dal: {{(panConversion.totalGold*2) | number:"3"}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 bg-gray-3">
                                <div class="row">
                                    <label class="col-3">PAN Created</label>
                                    <input class="col-2 form-control text-right" type="text" placeholder="Dal" ng-blur="panConversion.pan=textToFloat(panConversion.pan)" ng-pattern="/^\d{0,9}(\.\d{1,9})?$/" required ng-model="panConversion.pan" ng-change="" ng-keyup="">
                                    <div class="col-7" ng-messages="panConversation.dal.$error" role="alert">
                                        <div class="text-danger" ng-message-exp="['pattern']">Check Pan value</div>
                                        <div class="text-danger" ng-message-exp="['required']">value is required</div>
                                        <span class="col-3" ng-show="panConversation.dal.$valid">Exp. PAN: {{(panConversion.expectedPan) | number:"3"}}</span>
                                    </div>
                                </div>
                                <!--                                            <pre>panConversion={{panConversion | json}}</pre>-->
                                <!--                                            <pre>karigars={{karigars | json}}</pre>-->
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-body  p-0" ng-show="isSet(2)">
                This is tab 2
            </div>

            <div class="card-body  p-0" ng-show="isSet(3)">
                <!-- Rough Gold-->
                <form name="raughGold">
                    <div class="row">
                        <div class="row col-12 bg-gray-3">
                            <div class="col-12 bg-gray-2">
                                <div class="row">
                                    <label class="col-2">Karigar</label>
                                    <select class="col-2 form-control"
                                            ng-model="roughGoldCreation.karigar"
                                            ng-options="karigar as karigar.emp_name for karigar in karigars" required="yes">
                                    </select>
                                </div>
                                <div class="row">
                                    <label class="col-2">Rough Gold</label>
                                    <input class="col-2 form-control text-right" name="roughGold" type="text" ng-blur="roughGoldCreation.roughGold=textToFloat(roughGoldCreation.roughGold)"  placeholder="Rough Gold" ng-model="roughGoldCreation.roughGold" pattern="^([0-9]*[1-9][0-9]*(\.[0-9]+)?|[0]+\.[0-9]*[1-9][0-9]*)$"  required />
                                    <div class="col-7"  ng-messages="roughGoldCreation.roughGold.$error" role="alert">
                                        <div class="text-danger" ng-message-exp="['pattern']">Check rough gold value</div>
                                        <div class="text-danger" ng-message-exp="['required']">value is required</div>
                                    </div>
                                </div>

                                <div class="row">
                                    <label class="col-2">Comment</label>
                                    <input class="col-8 form-control text-left" name="dal" type="text"  placeholder="Your comment here"  ng-model="roughGoldCreation.comment" required >
                                    <div class="col-5" ng-messages="panConversation.dal.$error" role="alert">
                                        <div class="text-danger" ng-message-exp="['required']">Some text is required</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-5 text-right">{{roughGoldSaveSuccessMessage}}</div>
                                    <button class="col-2 button bg-gray-5"   ng-click="roughGoldCreation.saveRoughGold(roughGoldCreation)">Save</button>
                                </div>
                                <pre>roughGoldCreation={{roughGoldCreation | json}} </pre>
                                <pre>reportArray={{reportArray | json}} </pre>
                            </div>


                        </div>
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-9 text-right">{{panCreationSuccessMessage}}</div>
                    <button class="col-3 button bg-gray-5" ng-disabled="panCreationSubmitEnabled"   ng-click="panConversion.convertToPan()">Convert</button>
                </div>
            </div>
        </div>
        <?php
    }

    public function get_karigars(){
        $result=$this->admin_model->select_karigars();
        $report_array['records']=$result['result']->result();
        echo json_encode($report_array,JSON_NUMERIC_CHECK);
    }

    function save_convert_to_pan_details(){
        $post_data = json_decode(file_get_contents("php://input"), true);
        $result=$this->admin_model->transform_to_pan((object)$post_data['pan_data']);
        //$report_array['records'] = $post_data;
        echo json_encode($result, JSON_NUMERIC_CHECK);
    }

    function save_rough_gold(){
        $post_data = json_decode(file_get_contents("php://input"), true);
        $result=$this->admin_model->insert_rough_gold((object)$post_data['rough_data']);
        //$report_array['records'] = $post_data;
        echo json_encode($result, JSON_NUMERIC_CHECK);
    }


    public function angular_view_sales_return(){
        ?>
        <?php
        $GLOBALS["menu_name"] = "Admin Area";
        include("menu_all/admin/menu_admin.html");
        ?>
        <style type="text/css">
                input[type="number"]::-webkit-outer-spin-button, input[type="number"]::-webkit-inner-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }

            input[type="number"] {
                -moz-appearance: textfield;
            }
            #customer-due-table td, #customer-due-table td{
                padding-top: 0px !important;
                padding-bottom: 0px !important;
            }
            #customer-due-table > tfoot:nth-child(3) > tr:nth-child(1) td{
                border-bottom: 1px solid #0b2e13 !important;
                color: #062c33 !important;
                background-color: #8ba8af !important;
            }
        </style>

        <div class="d-flex col-12">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs nav-justified indigo" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link " ng-style="tab==1 && myObj" data-toggle="tab" href="#" role="tab" ng-click="setTab(1)"><i class="fa fa-box-open" ></i> Return Item</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" ng-style="tab==2 && myObj" data-toggle="tab" href="#" role="tab" ng-click="setTab(2)"><i class="fa list-alt"></i> Return List</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" ng-style="tab==3 && myObj" data-toggle="tab" href="#" role="tab" ng-click="setTab(3)"><i class="fab fa-book"></i>Misc.</a>
                    </li>
                </ul>
                <!-- Tab panels -->
                <div class="tab-content">
                    <!--Panel 1-->
                    <div ng-show="isSet(1)">
                        <div id="my-tab-1">
                            <form name="returnSale">
                                <div class="row">
                                    <div class="row col-12 bg-gray-3">
                                        <div class="col-6 bg-gray-2">
                                            <!--Agent Selection-->
                                            <div class="row">
                                                <label class="col-2">Agent</label>
                                                <select class="col-3 form-control" ng-change="salesReturn.agentChange()"
                                                        ng-model="salesReturn.agent"
                                                        ng-options="agent as agent.short_name for agent in selectedAgents" required="yes">
                                                </select>
                                                <div class="col-7">
                                                    <span>{{salesReturn.agent.agent_name}}, {{selectedCustomers.length}} Customers </span>
                                                </div>
                                            </div>
                                            <!--Customer Selection-->
                                            <div class="row">
                                                <label class="col-2">Customer</label>
                                                <select class="col-5 form-control"
                                                        ng-model="salesReturn.customer"
                                                        ng-options="customer as customer.cust_name for customer in selectedCustomers" required="yes">
                                                </select>
                                            </div>
                                            <!--Model Input-->
                                            <div class="row">
                                                <label class="col-2">Model</label>
                                                <input class="col-3 form-control text-right" name="model" type="text"  placeholder="Model"  ng-model="salesReturn.model"  required">
                                                <select class="col-3 form-control"
                                                        ng-model="salesReturn.model"
                                                        ng-options="model as model.product_code for model in models" required="yes">
                                                </select>
                                            </div>
                                            <!--gold Input-->
                                            <div class="row">
                                                <label class="col-2">Gold</label>
                                                <input class="col-3 form-control text-right" name="gold" type="number"  placeholder="Gold"  ng-model="salesReturn.gold"  required">
                                            </div>
                                            <!--LC Input-->
                                            <div class="row">
                                                <label class="col-2">LC</label>
                                                <input class="col-3 form-control text-right" name="lc" type="number"  placeholder="LC"  ng-model="salesReturn.lc"  required">
                                            </div>

                                            <div class="row">
                                                <div class="col-5 text-right">{{panCreationSuccessMessage}}</div>
                                                <button class="col-3 button bg-gray-5"   ng-click="">Save</button>
                                            </div>
                                        </div>
                                        <div class="col-6 bg-gray-3">
                                            <div class="row">
                                                <label class="col-2">Name: </label>
                                                <label class="col-10">{{salesReturn.customer.mailing_name}}</label>
                                            </div>
                                            <div class="row">
                                                <label class="col-2">Address: </label>
                                                <label class="col-10">{{salesReturn.customer.cust_address}}</label>
                                            </div>
                                            <div class="row">
                                                <div class="table-responsive-sm">
                                                    <table class="table table-bordered table-hover" id="customer-due-table">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">Particulars</th>
                                                                <th class="text-center">Gold</th>
                                                                <th class="text-center">LC</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>Opening</td>
                                                                <td class="text-right">{{salesReturn.customer.opening_gold | number : 3}}</td>
                                                                <td class="text-right">{{salesReturn.customer.opening_lc | number : 2}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Sale</td>
                                                                <td class="text-right">{{salesReturn.customer.sale_gold | number : 3}}</td>
                                                                <td class="text-right">{{salesReturn.customer.sale_lc | number : 2}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Received</td>
                                                                <td class="text-right">{{salesReturn.customer.gold_received | number : 3}}</td>
                                                                <td class="text-right">{{salesReturn.customer.lc_received | number : 2}}</td>
                                                            </tr>
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td class="">Gross Due</td>
                                                                <td class="text-right">{{salesReturn.customer.opening_gold + salesReturn.customer.sale_gold - salesReturn.customer.gold_received | number : 3}}</td>
                                                                <td class="text-right">{{salesReturn.customer.opening_lc + salesReturn.customer.sale_lc - salesReturn.customer.lc_received | number : 2}}</td>
                                                            </tr>

                                                            <tr>
                                                                <td class="">Discount</td>
                                                                <td class="text-right">{{salesReturn.customer.gold_discount | number : 3}}</td>
                                                                <td class="text-right">{{salesReturn.customer.lc_discount | number : 2 }}</td>
                                                            </tr>

                                                            <tr>
                                                                <td class="">Net Due</td>
                                                                <td class="text-right">{{salesReturn.customer.opening_gold + salesReturn.customer.sale_gold - salesReturn.customer.gold_received - salesReturn.customer.gold_discount | number : 3}}</td>
                                                                <td class="text-right">{{salesReturn.customer.opening_lc + salesReturn.customer.sale_lc - salesReturn.customer.lc_received - salesReturn.customer.lc_discount | number : 2 }}</td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                            <pre>models={{models | json}}</pre>
                                            <pre>salesReturn={{salesReturn | json}}</pre>
                                        </div>

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div ng-show="isSet(2)">
                        <div id="my-tab-1">
                            This is tab 2

                        </div>
                    </div>
                    <div ng-show="isSet(3)">
                        <div id="my-tab-3">
                            <!-- Rough Gold-->
                            <form name="raughGold">
                                <div class="row">
                                    <div class="row col-12 bg-gray-3">
                                        <div class="col-12 bg-gray-2">
                                            <div class="row">
                                                <label class="col-2">Karigar</label>
                                                <select class="col-2 form-control"
                                                        ng-model="roughGoldCreation.karigar"
                                                        ng-options="karigar as karigar.emp_name for karigar in karigars" required="yes">
                                                </select>
                                            </div>
                                            <div class="row">
                                                <label class="col-2">Rough Gold</label>
                                                <input class="col-2 form-control text-right" name="roughGold" type="text" ng-blur="roughGoldCreation.roughGold=textToFloat(roughGoldCreation.roughGold)"  placeholder="Rough Gold" ng-model="roughGoldCreation.roughGold" pattern="^([0-9]*[1-9][0-9]*(\.[0-9]+)?|[0]+\.[0-9]*[1-9][0-9]*)$"  required />
                                                <div class="col-7"  ng-messages="roughGoldCreation.roughGold.$error" role="alert">
                                                    <div class="text-danger" ng-message-exp="['pattern']">Check rough gold value</div>
                                                    <div class="text-danger" ng-message-exp="['required']">value is required</div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <label class="col-2">Comment</label>
                                                <input class="col-8 form-control text-left" name="dal" type="text"  placeholder="Your comment here"  ng-model="roughGoldCreation.comment" required >
                                                <div class="col-5" ng-messages="panConversation.dal.$error" role="alert">
                                                    <div class="text-danger" ng-message-exp="['required']">Some text is required</div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-5 text-right">{{roughGoldSaveSuccessMessage}}</div>
                                                <button class="col-2 button bg-gray-5"   ng-click="roughGoldCreation.saveRoughGold(roughGoldCreation)">Save</button>
                                            </div>
                                            <pre>roughGoldCreation={{roughGoldCreation | json}} </pre>
                                            <pre>reportArray={{reportArray | json}} </pre>
                                        </div>


                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    public function get_models(){
        $result=$this->admin_model->select_models();
        $report_array['records']=$result['result']->result();
        echo json_encode($report_array,JSON_NUMERIC_CHECK);
    }

    public function get_material_to_emp_balance(){

        $result=$this->admin_model->get_material_balance();
        $report_array['records']=$result->result();
        echo json_encode($report_array,JSON_NUMERIC_CHECK);
    }

    /******************** Report *********************************/
    public function angular_view_customer_report(){
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
                            <a class="nav-link" href="#">Link 2</a>
                        </li>

                        <!-- Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                                Master
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#">Link 2</a>
                                <a class="dropdown-item" href="#">Link 3</a>
                            </div>
                        </li>
                        <!-- Transaction -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                                Transaction
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#!customerDiscount"><i class="fab fa-apple"></i> Discount</a>
                                <a class="dropdown-item" href="#!materialConversion"><i class="fas fa-cogs"></i> Conversion</a>
                                <a class="dropdown-item" href="#!salesReturn"><i class="fas fa-bolt text-danger"></i> Return</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                                Report
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#!materialTransactionReport"><i class="fab fa-apple"></i> Material</a>
                                <a class="dropdown-item" href="#!customerReport">Customer</a>
                                <a class="dropdown-item" href="#">Link 3</a>
                            </div>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <div class="d-flex col-12">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="vendor-div">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs nav-justified indigo" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link bg-success" data-toggle="tab" href="#" role="tab" ng-click="setTab(1)"><i class="fa fa-user" ></i> Customer Report</a>
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
                        <div id="my-tab-1">
<!--                           This is customer report area-->
                            <form class="example">
                                <input type="text" placeholder="Search.." name="search" ng-model="searchfromTable">
                                <button type="submit"><i class="fa fa-search"></i></button>
                            </form>
                            <table class="table-hover table-condensed">
                                <tr>
                                    <th ng-repeat="(key, val) in customerList[0]">{{ key }}</th>
                                </tr>
                                <tr ng-repeat="row in customerList | filter : searchfromTable">
                                    <td ng-repeat="column in row">
                                        {{ column }}
                                    </td>
                                </tr>
                            </table>


                        </div>
                    </div>
                    <div ng-show="isSet(2)">
                        <div id="my-tab-1">
                            This is tab 2
                        </div>
                    </div>
                    <div ng-show="isSet(3)">
                        <div id="my-tab-1">
                            This is tab 3
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    public function get_admin_customer_list(){
        $result=$this->admin_model->select_admin_customer_list();
        $report_array['records']=$result->result();
        echo json_encode($report_array,JSON_NUMERIC_CHECK);
    }
    public function get_admin_agent_list(){
        $result=$this->admin_model->select_admin_agent_list();
        $report_array['records']=$result->result();
        echo json_encode($report_array,JSON_NUMERIC_CHECK);
    }
    public function get_customers_by_agent(){
        $post_data = json_decode(file_get_contents("php://input"), true);
        $result=$this->admin_model->select_customers_by_agent_id($post_data['agent_id']);
        $report_array['records']=$result->result();
        echo json_encode($report_array,JSON_NUMERIC_CHECK);
    }


}
?>