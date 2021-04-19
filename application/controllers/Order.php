<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
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


    public function angular_view_order_welcome(){
        ?>
        <style>
            .card-header{
                height: 15vh;
            }
            .card-body{
                height: 82vh;
            }
        </style>
        <div class="card">

            <div class="card-header">
                <?php
                $GLOBALS["menu_name"] = "Staff";
                include("menu_all/staff/menu_staff.html");
                ?>
                <!-- Nav tabs -->
                <ul class="nav nav-tabs nav-justified indigo" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link " data-toggle="tab" href="#" role="tab" ng-click="setTab(1)"><i class="fa fa-user" ></i> New Order</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#" role="tab" ng-click="setTab(2)"><i class="fa fa-heart"></i> Ordr List</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#" role="tab" ng-click="setTab(3)"><i class="fa fa-envelope"></i>About Order</a>
                    </li>
                </ul>
                <!-- Tab panels -->
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <!--Panel 1-->
                    <div ng-show="isSet(1)">
                        <div id="my-tab-1">
                            This is tab 1.2
                            <form name="order-form">
                                <div class="d-flex">
                                    <div class="col">
                                        <div class="form-group">
                                                <label class="col-6">Agent</label>
                                                <select class="col-10 form-control"
                                                        ng-model="order.agent"
                                                        ng-options="agent as agent.short_name for agent in agentList" required="yes"
                                                        ng-change="loadCustomers(order.agent)">
                                                </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div>
                                            <span>Agent ID: <strong>{{order.agent.agent_id}}</strong></span>
                                        </div>
                                        <div>
                                            <span>Agent Name: <strong>{{order.agent.agent_name}}</strong></span>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label class="col-10">Customer</label>
                                            <select class="col-10 form-control"
                                                    ng-model="order.customer"
                                                    ng-options="customer as customer.cust_name for customer in customerList" required="yes"
                                                    >
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div>
                                            <span>Customer ID: <strong>{{order.customer.cust_id}}</strong></span>
                                        </div>
                                        <div>
                                            <span>Customer Name: <strong>{{order.customer.cust_name}}</strong></span>
                                        </div>
                                        <div>
                                            <span>Customer Name: <strong>{{order.customer.city}}</strong></span>
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
                        <div id="my-tab-1">
                            This is tab 3
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex developer-area" ng-show="true">
                    <div class="col">
                        <pre>agentList={{agentList | json}}</pre>
                    </div>
                    <div class="col"></div>
                    <div class="col"></div>
                </div>
            </div>
        </div>


        <div class="d-flex col-12">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="vendor-div">


            </div>
        </div>
        <?php
    }

    public function angular_view_working_job(){
        ?>
        <?php
            $GLOBALS["menu_name"] = "Staff Report";
            include("menu_all/staff/menu_staff.html");
        ?>



        <div class="d-flex col-12">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="vendor-div">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs nav-justified indigo" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link " data-toggle="tab" href="#" role="tab" ng-click="setTab(1)"><i class="fa fa-user" ></i>Working Job</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#" role="tab" ng-click="setTab(2)"><i class="fa fa-edit"></i> Edit JOB</a>
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
                            <a href="#" editable-text="user.name" onbeforesave="updateUser(user,$data)">
                                {{ user.name || 'empty' }}
                            </a>
                            <div class="card">
                                <div class="card-header">
                                    <button ng-click="getCurrentWorkingJobList()">Show Current Job</button>
                                    Search: <input type="text" ng-model="test" ng-change="getSelectedJobs(test)">
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>SL</th>
                                                <th>Date</th>
                                                <th>
                                                    <a href="#" ng-click="orderByField='job_id'; reverseSort = !reverseSort">
                                                        Job Id <span ng-show="orderByField == 'job_id'"><span ng-show="!reverseSort">^</span><span ng-show="reverseSort">v</span></span>
                                                    </a>
                                                </th>
                                                <th>
                                                    <a href="#" ng-click="orderByField='order_id'; reverseSort = !reverseSort">
                                                        Order ID <span ng-show="orderByField == 'order_id'"><span ng-show="!reverseSort">^</span><span ng-show="reverseSort">v</span></span>
                                                    </a>
                                                </th>
                                                <th>
                                                    <a href="#" ng-click="orderByField='nick_name'; reverseSort = !reverseSort">
                                                        কারিগর <span ng-show="orderByField == 'nick_name'"><span ng-show="!reverseSort">^</span><span ng-show="reverseSort">v</span></span>
                                                    </a>
                                                </th>
                                                <th>
                                                    <a href="#" ng-click="orderByField='product_code'; reverseSort = !reverseSort">
                                                        Model <span ng-show="orderByField == 'product_code'"><span ng-show="!reverseSort">^</span><span ng-show="reverseSort">v</span></span>
                                                    </a>

                                                </th>
                                                <th>Size</th>
                                                <th>
                                                    <a href="#" ng-click="orderByField='pieces'; reverseSort = !reverseSort">
                                                        Qty <span ng-show="orderByField == 'pieces'"><span ng-show="!reverseSort">^</span><span ng-show="reverseSort">v</span></span>
                                                    </a>
                                                </th>
                                                <th>Gold Send</th>
                                                <th>Dal Send</th>
                                                <th>Pan Used</th>
                                                <th>মাথা কাটা</th>
                                                <th>Nitrick</th>
                                                <th>
                                                    <a href="#" ng-click="orderByField='cust_name'; reverseSort = !reverseSort">
                                                        Customer <span ng-show="orderByField == 'cust_name'"><span ng-show="!reverseSort">^</span><span ng-show="reverseSort">v</span></span>
                                                    </a>
                                                </th>
                                                <th>
                                                    <a href="#" ng-click="orderByField='status_name'; reverseSort = !reverseSort">
                                                        Status <span ng-show="orderByField == 'status_name'"><span ng-show="!reverseSort">^</span><span ng-show="reverseSort">v</span></span>
                                                    </a>
                                                </th>
                                                <th>
                                                    <a href="#" ng-click="orderByField='job_age'; reverseSort = !reverseSort">
                                                        Age <span ng-show="orderByField == 'job_age'"><span ng-show="!reverseSort">^</span><span ng-show="reverseSort">v</span></span>
                                                    </a>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr ng-repeat="x in selectedCurrentWorkingJobList | orderBy:orderByField:reverseSort" ng-init="totalQty = 0">
<!--                                            <tr ng-repeat="x in currentWorkingJobList | filter : test">-->
                                                <td class="text-right"> {{$index + 1}}</td>
                                                <td>{{ x.job_date }}</td>
                                                <td class="text-right">{{ x.job_id }}</td>
                                                <td class="text-left">{{ x.order_id }}</td>
                                                <td class="text-left">{{ x.nick_name }}</td>
                                                <td>{{ x.product_code }}</td>
                                                <td>
                                                    <a href="#" editable-text="x.product_size" onbeforesave="updateSize(x,$data)">
                                                        {{ x.product_size || 'empty' }}
                                                    </a>

                                                </td>
                                                <td class="text-right" ng-init="$parent.totalQty = $parent.totalQty +x.pieces">{{ x.pieces }}</td>
                                                <td class="text-right">{{ x.gold_send | number : 3 }}</td>
                                                <td class="text-right">{{ x.dal_send | number : 3 }}</td>
                                                <td class="text-right">{{ x.pan_send | number : 3 }}</td>
                                                <td class="text-right">{{ x.gold_returned | number : 3 }}</td>
                                                <td class="text-right">{{ x.nitrick_returned | number : 3 }}</td>
                                                <td class="text-left">{{ x.cust_name}}</td>
                                                <td class="text-left">{{ x.status_name}}</td>
                                                <td class="text-right">{{ x.job_age}}</td>
                                                <td>
                                                    <a href="#" ng-click="editJob(x)"><i class="far fa-edit"></i></a>
                                                </td>

                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td class="text-center" colspan="7">Total</td>
                                                <td class="text-right">{{totalQty}}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="card-footer">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div ng-show="isSet(2)">
                        <div class="d-flex">
                            <div class="col">
                                <table class="table-sm">
                                    <tr>
                                        <td>Order</td>
                                        <td><a href="#">{{editableJob.order_id}}</a></td>
                                    </tr>
                                    <tr>
                                        <td>Size</td>
                                        <td><input type="text" ng-model="editableJob.product_size"  class="text-right"> </td>
                                    </tr>
                                    <tr>
                                        <td>Quantity</td>
                                        <td><input type="text" ng-model="editableJob.pieces"  class="text-right"> </td>
                                    </tr>
                                </table>
                                <pre> editableJob={{editableJob | json}} </pre>
                            </div>
                            <div class="col">
                                sdfg sdfg sdfgsdfgsdg
                            </div>
                        </div>

                    </div>
                    <div ng-show="isSet(3)">
                        <div id="my-tab-3">


                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    function get_current_working_job(){
        $result=$this->admin_model->select_current_working_job_list();
        $report_array['records']=$result->result();
        echo json_encode($report_array,JSON_NUMERIC_CHECK);
    }

    function set_product_size(){
        $post_data = json_decode(file_get_contents("php://input"), true);
        $result=$this->admin_model->update_size_in_job($post_data['job_id'],$post_data['product_size']);
        echo json_encode($result, JSON_NUMERIC_CHECK);
    }
}
?>