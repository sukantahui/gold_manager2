<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this -> load -> model('report_model');
        //$this -> is_logged_in();
    }
    function is_logged_in() {
		$is_logged_in = $this -> session -> userdata('is_logged_in');
		if (!isset($is_logged_in) || $is_logged_in != 1) {
			echo 'you have no permission to use developer area'. '<a href="">Login</a>';
			die();
		}
	}

    function get_job_stock_difference(){
        $result=$this->report_model->select_job_stock_difference()->result_array();
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);
    }
    //working here
	public function angular_view_job_to_stock_report(){
        ?>

        <?php
        $GLOBALS["menu_name"] = "Admin Report";
        include("menu_all/admin/menu_admin.html");
        ?>


        <div id="menu-manager" ng-show="isManager">
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
                                <a class="nav-link" href="#!managerArea">Back <i class="fas fa-home"></i></a>
                            </li>
                        </ul>
                        <a class="navbar-btn" href="#">Manager</a>
                    </nav>
                </div>

            </div>
        </div>
        <!--        end of menu manager-->

        <div class="d-flex col-12">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="">
                <!-- Nav tabs -->

                <ul class="nav nav-tabs nav-justified indigo" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link " data-toggle="tab" href="#" role="tab" ng-click="setTab(1)"><i class="fas fa-user-graduate"></i> Material Transaction</a>
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
                            <div class="">
                                <form name="discountForm" class="" id="">
                                    <div class="d-flex">
                                        <div class="row col-4 bg-gray-3"><input class="col-10 form-control bg-gray-1" type="text" ng-model="materialInwardOutwardReport.searchItem"/><button><i class="fas fa-search"></i></button></div>
                                        <div class="row col-2 bg-gray-4 pr-1"><span class="">Show</span><input ng-change="reloadTempInwardOutwardTransactionList()" class="ml-1 mr-1 bg-gray-4 text-right col-4 form-control pl-0 pr-0" ng-model="materialInwardOutwardReport.numberOfRecord" type="number" min="1" max="500"><span class="ml-1 mr-2">records</span></div>
                                        <div class="row col-5 bg-gray-3"><span class="mr-1"> Date between </span><input class="col-4 form-control" ng-model="materialInwardOutwardReport.dateFrom" type="date" ng-change="materialInwardOutwardReport.dateFrom=(materialInwardOutwardReport.dateFrom | date: 'yyyy-MM-dd')"> <span class="ml-1 mr-1"> to </span><input class="col-4 form-control" ng-model="materialInwardOutwardReport.dateTo" type="date" ng-change="materialInwardOutwardReport.dateTo=(materialInwardOutwardReport.dateTo | date: 'yyyy-MM-dd')"><button ng-click="selectTempaInwardOutwardTransactionListByDate()"><i class="fas fa-search"></i></button></div>
                                    </div>
                                </form>
                                <div id="material-input-output-table-div">
                                    <table cellpadding="0" cellspacing="0" class="table table-bordered">
                                        <thead>
                                        <tr class="bg-gray-7 text-white">
                                            <th>SL></th>
                                            <th ng-click="changeSorting('tr_time')">Date<i class="glyphicon" ng-class="getIcon('record_date')"></i></th>
                                            <th ng-click="changeSorting('bill_no')">Bill No<i class="glyphicon" ng-class="getIcon('reference')"></i></th>
                                            <th ng-click="changeSorting('job_id')">Job Id<i ng-class="fa fa-heart"></i></th>
                                            <th ng-click="changeSorting('job_qty')">Job Qty<i class="glyphicon" ng-class="getIcon('receiver_name')"></i></th>
                                            <th ng-click="changeSorting('job_gold')">Job Gols<i class="glyphicon" ng-class="getIcon('rm_name')"></i></th>
                                            <th ng-click="changeSorting('labour_charge')">Job Lc<i class="glyphicon" ng-class="getIcon('material_value')"></i></th>
                                            <th ng-click="changeSorting('stock_qty')">Stock Qty<i class="glyphicon" ng-class="getIcon('material_value')"></i></th>
                                            <th ng-click="changeSorting('stock_gold')">Stock Gold<i class="glyphicon" ng-class="getIcon('material_value')"></i></th>
                                            <th ng-click="changeSorting('stock_lc')">Stock LC<i class="glyphicon" ng-class="getIcon('material_value')"></i></th>
                                        </tr>
                                        </thead>
                                        <tbody ng-repeat="tr in jobStockDifferenceList">
                                        <tr ng-class-even="'bg-gray-2'" ng-class-odd="'bg-gray-4'">
                                            <td class="pt-0 pb-1">{{ $index+1}}</td>
                                            <td class="pt-0 pb-1">{{tr.tr_time | date : 'fullDate'}}</td>
                                            <td class="pt-0 pb-1">{{tr.bill_no}}</td>
                                            <td class="pt-0 pb-1">{{tr.job_id}}</td>
                                            <td class="pt-0 pb-1">{{tr.job_qty}}</td>
                                            <td class="pt-0 pb-1">{{tr.job_gold}}</td>
                                            <td class="text-right pt-0 pb-0">{{tr.labour_charge | number:2}}</td>
                                            <td class="text-right pt-0 pb-0">{{tr.stock_qty | number:2}}</td>
                                            <td class="text-right pt-0 pb-0">{{tr.stock_gold | number:2}}</td>
                                            <td class="text-right pt-0 pb-0">{{tr.stock_lc | number:2}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!--                                <pre>materialInwardOutwardReport={{materialInwardOutwardReport | json}} </pre>-->

                            </div>

                        </div> <!--//End of my tab1//-->
                    </div>
                    <div ng-show="isSet(2)">
                        <div id="my-tab-2">
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
    public function angular_view_material_transaction_report(){
        ?>

        <?php
        $GLOBALS["menu_name"] = "Admin Report";
        include("menu_all/admin/menu_admin.html");
        ?>


        <div id="menu-manager" ng-show="isManager">
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
                                <a class="nav-link" href="#!managerArea">Back <i class="fas fa-home"></i></a>
                            </li>
                        </ul>
                        <a class="navbar-btn" href="#">Manager</a>
                    </nav>
                </div>

            </div>
        </div>
<!--        end of menu manager-->

        <div class="d-flex col-12">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="">
                <!-- Nav tabs -->

                <ul class="nav nav-tabs nav-justified indigo" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link " data-toggle="tab" href="#" role="tab" ng-click="setTab(1)"><i class="fas fa-user-graduate"></i> Material Transaction</a>
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
                            <div class="">
                                <form name="discountForm" class="" id="">
                                    <div class="d-flex">
                                        <div class="row col-4 bg-gray-3"><input class="col-10 form-control bg-gray-1" type="text" ng-model="materialInwardOutwardReport.searchItem"/><button><i class="fas fa-search"></i></button></div>
                                        <div class="row col-2 bg-gray-4 pr-1"><span class="">Show</span><input ng-change="reloadTempInwardOutwardTransactionList()" class="ml-1 mr-1 bg-gray-4 text-right col-4 form-control pl-0 pr-0" ng-model="materialInwardOutwardReport.numberOfRecord" type="number" min="1" max="500"><span class="ml-1 mr-2">records</span></div>
                                        <div class="row col-5 bg-gray-3"><span class="mr-1"> Date between </span><input class="col-4 form-control" ng-model="materialInwardOutwardReport.dateFrom" type="date" ng-change="materialInwardOutwardReport.dateFrom=(materialInwardOutwardReport.dateFrom | date: 'yyyy-MM-dd')"> <span class="ml-1 mr-1"> to </span><input class="col-4 form-control" ng-model="materialInwardOutwardReport.dateTo" type="date" ng-change="materialInwardOutwardReport.dateTo=(materialInwardOutwardReport.dateTo | date: 'yyyy-MM-dd')"><button ng-click="selectTempaInwardOutwardTransactionListByDate()"><i class="fas fa-search"></i></button></div>
                                    </div>
                                </form>
                                <div id="material-input-output-table-div">
                                    <table cellpadding="0" cellspacing="0" class="table table-bordered">
                                        <thead>
                                        <tr class="bg-gray-7 text-white">
                                            <th>SL></th>
                                            <th ng-click="changeSorting('record_date')">Date<i class="glyphicon" ng-class="getIcon('record_date')"></i></th>
                                            <th ng-click="changeSorting('reference')">Transaction<i class="glyphicon" ng-class="getIcon('reference')"></i></th>
                                            <th ng-click="changeSorting('payer_name')">Sender<i ng-class="fa fa-heart"></i></th>
                                            <th ng-click="changeSorting('receiver_name')">Receiver<i class="glyphicon" ng-class="getIcon('receiver_name')"></i></th>
                                            <th ng-click="changeSorting('rm_name')">Material<i class="glyphicon" ng-class="getIcon('rm_name')"></i></th>
                                            <th ng-click="changeSorting('material_value')">Value<i class="glyphicon" ng-class="getIcon('material_value')"></i></th>
                                        </tr>
                                        </thead>
                                        <tbody ng-repeat="tr in tempInwardOutwardTransactionList | filter : materialInwardOutwardReport.searchItem | orderBy:sort.active:sort.descending">
                                        <tr ng-class-even="'bg-gray-2'" ng-class-odd="'bg-gray-4'">
                                            <td class="pt-0 pb-1">{{ $index+1}}</td>
                                            <td class="pt-0 pb-1">{{tr.record_date | date : 'fullDate'}}</td>
                                            <td class="pt-0 pb-1">{{tr.reference}}</td>
                                            <td class="pt-0 pb-1">{{tr.payer_name}}</td>
                                            <td class="pt-0 pb-1">{{tr.receiver_name}}</td>
                                            <td class="pt-0 pb-1">{{tr.rm_name}}</td>
                                            <td class="text-right pt-0 pb-0">{{tr.material_value | number:3}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

<!--                                <pre>materialInwardOutwardReport={{materialInwardOutwardReport | json}} </pre>-->

                            </div>

                        </div> <!--//End of my tab1//-->
                    </div>
                    <div ng-show="isSet(2)">
                        <div id="my-tab-2">
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

    public function angular_view_agent_report(){
        ?>
            <style>
                tfoot tr{
                    border-top: 2px solid black;
                    border-bottom: 2px solid black;
                }

                /*Spinner*/
                lds-spinner {
                    color: black;
                    display: inline-block;
                    position: center;
                    width: 300px;
                    height: 300px;
                    /*border: 1px solid black;*/
                }
                .lds-spinner div {
                    transform-origin: 40px 40px;
                    animation: lds-spinner 1.2s linear infinite;
                }
                .lds-spinner div:after {
                    content: " ";
                    display: block;
                    position: absolute;
                    top: 3px;
                    left: 37px;
                    width: 6px;
                    height: 18px;
                    border-radius: 20%;
                    background: #0b2e13;
                }
                .lds-spinner div:nth-child(1) {
                    transform: rotate(0deg);
                    animation-delay: -1.1s;
                }
                .lds-spinner div:nth-child(2) {
                    transform: rotate(30deg);
                    animation-delay: -1s;
                }
                .lds-spinner div:nth-child(3) {
                    transform: rotate(60deg);
                    animation-delay: -0.9s;
                }
                .lds-spinner div:nth-child(4) {
                    transform: rotate(90deg);
                    animation-delay: -0.8s;
                }
                .lds-spinner div:nth-child(5) {
                    transform: rotate(120deg);
                    animation-delay: -0.7s;
                }
                .lds-spinner div:nth-child(6) {
                    transform: rotate(150deg);
                    animation-delay: -0.6s;
                }
                .lds-spinner div:nth-child(7) {
                    transform: rotate(180deg);
                    animation-delay: -0.5s;
                }
                .lds-spinner div:nth-child(8) {
                    transform: rotate(210deg);
                    animation-delay: -0.4s;
                }
                .lds-spinner div:nth-child(9) {
                    transform: rotate(240deg);
                    animation-delay: -0.3s;
                }
                .lds-spinner div:nth-child(10) {
                    transform: rotate(270deg);
                    animation-delay: -0.2s;
                }
                .lds-spinner div:nth-child(11) {
                    transform: rotate(300deg);
                    animation-delay: -0.1s;
                }
                .lds-spinner div:nth-child(12) {
                    transform: rotate(330deg);
                    animation-delay: 0s;
                }
                @keyframes lds-spinner {
                    0% {
                        opacity: 1;
                    }
                    100% {
                        opacity: 0;
                    }
                }

                /*Spinner end*/
            </style>
        <?php
        $GLOBALS["menu_name"] = "Manager Agent Report";
        ?>
        <?php require_once("menu_all/manager/menu_manager.php");?>

        <div class="d-flex col-12">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="">
                <!-- Nav tabs -->

                <ul class="nav nav-tabs nav-justified indigo" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link " ng-style="tabStyleActive(1)" data-toggle="tab" href="#" role="tab" ng-click="setTab(1)"><i class="fas fa-user-graduate"></i> Material Transaction</a>
                    </li>
                    <li class="nav-item" ng-style="tabStyleActive(2)">
                        <a class="nav-link" data-toggle="tab" href="#" role="tab" ng-click="setTab(2)"><i class="fa fa-heart"></i> Customer List</a>
                    </li>
                    <li class="nav-item" ng-style="tabStyleActive(3)">
                        <a class="nav-link" data-toggle="tab" href="#" role="tab" ng-click="setTab(3)"><i class="fa fa-envelope"></i>About Product</a>
                    </li>
                </ul>
                <!-- Tab panels -->
                <div class="tab-content">
                    <!--Panel 1-->
                    <div ng-show="isSet(1)">
                        <div id="my-tab-1">
                            <div class="card">
                                <div class="card-header">

                                </div>
                            </div>
                            <div class="card-body">
                                <lds-spinner ng-if="agentDueList.records.length==0">
                                    <div class="lds-spinner">
                                        <div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div>
                                    </div>
                                </lds-spinner>
                                <style>
                                    .table th, .table td{
                                        padding: 0 !important;
                                    }
                                    .btn{
                                        padding: 0 !important;
                                    }
                                </style>
                                <div id="printable-agent-due-list" ng-if="agentDueList.records.length>0">
                                    <table class="table table-striped  table-responsive" >
                                    <thead>
                                        <caption>Agents Due List as on {{cdate | date:'dd/MM/yyyy hh:mm:ss a'}}, {{ cdate | date:'EEEE'}}</caption>
                                        <tr>
                                            <th class="text-center">SL</th>
                                            <th class="text-center">Agent ID</th>
                                            <th class="text-center">Name</th>
                                            <th class="text-center">Short</th>
                                            <th class="text-center">Limit</th>
                                            <th class="text-center">Gold Due</th>
                                            <th class="text-center">LC Due</th>
                                            <th class="text-center d-print-none">Gold Discount</th>
                                            <th class="text-center d-print-none">LC Discount</th>
                                            <th class="text-center d-print-none">
                                                <button class="d-print-none" type="button btn-primary" ng-click="huiPrintDivAdvanced('printable-agent-due-list','customer_day_book','Customers of '+customerDueListByAgent.agent.agent_id,1)">
                                                    <span class="glyphicon glyphicon-print"></span>
                                                </button>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <tr ng-repeat="agent in agentDueList.records">
                                        <td class="text-center"> {{$index + 1}}</td>
                                        <td class="text-center"> {{agent.agent_id}}</td>
                                        <td class="text-left"> {{agent.agent_name}}</td>
                                        <td class="text-center"> {{agent.short_name}}</td>
                                        <td class="text-center"> {{agent.max_gold_limit}}</td>
                                        <td class="text-right"> {{agent.total_gold_due |  number:3}}</td>
                                        <td class="text-right"> {{agent.total_lc_due |  currency:"&#8377;"}}</td>
                                        <td class="text-right d-print-none"> {{agent.total_gold_discount}}</td>
                                        <td class="text-right d-print-none"> {{agent.total_lc_discount |  currency:"&#8377;"}}</td>
                                        <td class="text-center d-print-none"> <button class="btn btn-primary" ng-click="showAgentDetailsInSecondTab(agent)">Details</button></td>
                                        <td ng-if="agent.max_gold_limit>agent.total_gold_due" class="text-center d-print-none"> <button class="btn btn-primary" ng-click="">Enable</button></td>
                                    </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="4">Total</td>
                                            <td class="text-right">{{agentDueList.total.gold_due | number: 3}}</td>
                                            <td class="text-right">{{agentDueList.total.lc_due |  currency:"&#8377;"}}</td>
                                            <td class="text-right d-print-none">{{agentDueList.total.gold_discount | number: 3}}</td>
                                            <td class="text-right d-print-none">{{agentDueList.total.lc_discount |  currency:"&#8377;"}}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                                </div>
                            </div>

                        </div>

                    </div> <!--//End of my tab1//-->
                </div>
                <div ng-show="isSet(2)">
                    <div id="my-tab-2">
                        <div class="card-body">
                            <lds-spinner ng-if="customerDueListByAgent.records.length==0">
                                <div class="lds-spinner">
                                    <div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div>
                                </div>
                            </lds-spinner>

                            <style>
                                .table th, .table td{
                                    padding: 0 !important;
                                    font-size: x-small;
                                }
                                .btn{
                                    padding: 0 !important;
                                }
                            </style>

                            <div ng-if="customerDueListByAgent.records.length>0">
                                <h4>Agent: {{customerDueListByAgent.agent.agent_name}}</h4>
                                <table ng-show="customerDueListByAgent.records">
                                <caption>Customers of {{customerDueListByAgent.agent.agent_name}}: Due List as on {{cdate | date:'dd/MM/yyyy hh:mm:ss a'}}, {{ cdate | date:'EEEE'}}</caption>
                                <thead>
                                <tr>
                                    <th class="text-center">SL</th>
                                    <th class="text-center">ID</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">City</th>
                                    <th class="text-center">Gold Due</th>
                                    <th class="text-center">LC Due</th>
                                    <th class="text-center d-print-none">God Discount</th>
                                    <th class="text-center d-print-none">LC Discount</th>
                                    <th class="text-center d-print-none">
                                        <button class="d-print-none" type="button btn-primary" ng-click="huiPrintDivAdvanced('my-tab-2','customer_day_book','Customers of '+customerDueListByAgent.agent.agent_id,1)">
                                            <span class="glyphicon glyphicon-print"></span>
                                        </button>
                                    </th>

                                </tr>
                                </thead>
                                <tbody>
                                <tr ng-repeat="customer in customerDueListByAgent.records">
                                    <td class="text-center"> {{$index + 1}}</td>
                                    <td class="text-center"> {{customer.cust_id}}</td>
                                    <td class="text-left"> {{customer.cust_name}}</td>
                                    <td class="text-left"> {{customer.city}}</td>
                                    <td class="text-right"> {{customer.gold_due==0 ? '' : customer.gold_due}}</td>
                                    <td class="text-right"> {{customer.lc_due == 0 ? '' : (customer.lc_due |  currency:"&#8377;")}}</td>
                                    <td class="text-right d-print-none"> {{customer.gold_discount==0 ? '' : customer.gold_discount}}</td>
                                    <td class="text-right d-print-none"> {{customer.lc_discount==0 ? '' : (customer.lc_discount |  currency:"&#8377;")}}</td>

                                    <td class="text-center d-print-none"> <button class="btn btn-primary " ng-click="showCustomerDetailsByCustomer(customer)">Details</button></td>

                                </tr>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="4">Total</td>
                                    <td class="text-right">{{customerDueListByAgent.total.gold_due | number: 3}}</td>
                                    <td class="text-right">{{customerDueListByAgent.total.lc_due |  currency:"&#8377;"}}</td>
                                    <td class="text-right d-print-none">{{customerDueListByAgent.total.gold_discount | number: 3}}</td>
                                    <td class="text-right d-print-none">{{customerDueListByAgent.total.lc_discount |  currency:"&#8377;"}}</td>
                                </tr>
                                </tfoot>
                            </table>
                            </div>
                        </div>


                    </div>
                </div>
                <div ng-show="isSet(3)">
                    <div id="my-tab-3">
                        <div class="card-body">
                            <div>
                                <h2>{{customerTransactions.customer.mailing_name}}</h2>

                                <div class="d-flex flex-row bd-highlight mb-3">
                                    <div class="p-2 bd-highlight">
                                        City: {{customerTransactions.customer.city}}
                                    </div>
                                    <div class="p-2 bd-highlight">
                                        <button class="d-print-none" type="button btn-primary" ng-click="huiPrintDivAdvanced('my-tab-3','customer_day_book','Transaction-'+customerTransactions.customer.cust_id,1)">
                                            <span class="glyphicon glyphicon-print"></span>
                                        </button>
                                    </div>
                                    <div class="p-2 bd-highlight">
                                        <button class="d-print-none" type="button btn-primary" ng-click="saveCustomerDayBookToExcel('DayBook-'+customerTransactions.customer.cust_id)">
                                            Excel
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <style>
                                .table th, .table td{
                                    padding: 0 !important;
                                    font-size: x-small;
                                }
                                .btn{
                                    padding: 0 !important;
                                }
                            </style>

                            <table id="customer-table" class="table table-striped  table-responsive" ng-show="customerDueListByAgent">
                                <thead>
                                    <tr>
                                        <th class="text-center">SL</th>
                                        <th class="text-center">Date</th>
                                        <th class="text-center">Particulars</th>
                                        <th class="text-center">Reference</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-center">Billed Gold</th>
                                        <th class="text-center">Billed LC</th>
                                        <th class="text-center">Received Gold</th>
                                        <th class="text-center">Received LC</th>
                                        <th class="text-center">Current Gold</th>
                                        <th class="text-center">Current LC</th>

                                    </tr>
                                </thead>
                                <tbody>
                                <tr ng-repeat="transaction in customerTransactions.records">
                                    <td class="text-center"> {{$index + 1}}</td>
                                    <td class="text-center"> {{transaction.formated_tr_date}}</td>
                                    <td class="text-left"> {{transaction.particulars}}</td>
                                    <td class="text-left"> {{transaction.reference}}</td>
                                    <td class="text-right"> {{transaction.billed_qty==0? '' : transaction.billed_qty}}</td>
                                    <td class="text-right"> {{transaction.billed_gold==0? '' : (transaction.billed_gold | number: 3)}}</td>
                                    <td class="text-right"> {{transaction.billed_lc==0? '' : (transaction.billed_lc |  currency:"&#8377;")}}</td>
                                    <td class="text-right"> {{transaction.received_gold==0? '' : (transaction.received_gold | number: 3)}}</td>
                                    <td class="text-right"> {{transaction.received_lc == 0? '' : (transaction.received_lc |  currency:"&#8377;")}}</td>
                                    <td class="text-right"> {{transaction.current_gold_due | number: 3}}</td>
                                    <td class="text-right"> {{transaction.current_lc_due |  currency:"&#8377;"}}</td>

                                    <td class="text-center d-print-none"> <button class="btn btn-primary" ng-click="" ng-show="false">Details</button></td>

                                </tr>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="9">Due as on {{ cdate | date:'dd/MM/yyyy hh:mm:ss a' }}</td>
                                    <td class="text-right">{{customerTransactions.currentDue.current_gold_due | number: 3}}</td>
                                    <td class="text-right">{{customerTransactions.currentDue.current_lc_due |  currency:"&#8377;"}}</td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>


                    </div>
                </div>
            </div>
        </div>
        </div>
        <?php
    }

    public function angular_view_job_report(){
        ?>

        <?php
        $GLOBALS["menu_name"] = "Admin Job Report";
        include("menu_all/admin/menu_admin.html");
        ?>


        <div class="d-flex col-12">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="">
                <!-- Nav tabs -->

                <ul class="nav nav-tabs nav-justified indigo" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link " data-toggle="tab" href="#" role="tab" ng-click="setTab(1)"><i class="fas fa-user-graduate"></i> Material Transaction</a>
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
                            <div class="card">
                                <div class="card-header">
                                    <form name="discountForm" class="" id="">
                                        <div class="d-flex">
                                            <div class="row col-4 bg-gray-3"><input class="col-5 form-control bg-gray-1" type="text" ng-model="searchText" ng-change="searchJobReport(searchText)" /><button><i class="fas fa-search"></i></button></div>
<!--                                            <div class="row col-2 bg-gray-4 pr-1"><span class="">Show</span><input ng-change="reloadTempInwardOutwardTransactionList()" class="ml-1 mr-1 bg-gray-4 text-right col-4 form-control pl-0 pr-0" ng-model="materialInwardOutwardReport.numberOfRecord" type="number" min="1" max="500"><span class="ml-1 mr-2">records</span></div>-->
                                            <div class="row col-4 bg-gray-3"><span class="mr-1"> Date between </span><input class="col-4 form-control" ng-model="materialInwardOutwardReport.dateFrom" type="date" ng-change="materialInwardOutwardReport.dateFrom=(materialInwardOutwardReport.dateFrom | date: 'yyyy-MM-dd')"> <span class="ml-1 mr-1"> to </span><input class="col-4 form-control" ng-model="materialInwardOutwardReport.dateTo" type="date" ng-change="materialInwardOutwardReport.dateTo=(materialInwardOutwardReport.dateTo | date: 'yyyy-MM-dd')">
                                                <button ng-click="selectTAdminJobReportByDate(materialInwardOutwardReport.dateFrom,materialInwardOutwardReport.dateTo)"><i class="fas fa-search"></i></button>
                                            </div>
                                            <button ng-click="saveAdminJobReportToExcel('test.xls')">Excel</button></div>
                                        </div>

                                    </form>
                                </div>
                                <div class="card-body">
                                    <a href="#0" title="My Tooltip!" data-toggle="tooltip" data-placement="top" tooltip>My Tooltip Link</a>
                                    <table class="table table-bordered" ng-show="showAdminJobListTable">
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
                                                <a href="#" ng-click="orderByField='nick_name'; reverseSort = !reverseSort">
                                                    কারিগর <span ng-show="orderByField == 'nick_name'"><span ng-show="!reverseSort">^</span><span ng-show="reverseSort">v</span></span>
                                                </a>
                                            </th>
                                            <th>
                                                <a href="#" ng-click="orderByField='product_code'; reverseSort = !reverseSort">
                                                    Model <span ng-show="orderByField == 'product_code'"><span ng-show="!reverseSort">^</span><span ng-show="reverseSort">v</span></span>
                                                </a>

                                            </th>

                                            <th>
                                                <a href="#" ng-click="orderByField='pieces'; reverseSort = !reverseSort">
                                                    Qty <span ng-show="orderByField == 'pieces'"><span ng-show="!reverseSort">^</span><span ng-show="reverseSort">v</span></span>
                                                </a>
                                            </th>
                                            <th>Gold Send</th>
                                            <th>Dal Send</th>
                                            <th>Pan Used</th>
                                            <th title="My Tooltip!" data-toggle="tooltip">মাথা কাটা</th>
                                            <th>Nitrick</th>
                                            <th>PLOSS</th>
                                            <th>actualPLOSS</th>
                                            <th>
                                                <a href="#" ng-click="orderByField='actual_mv'; reverseSort = !reverseSort">
                                                    Actual MV <span ng-show="orderByField == 'actual_mv'"><span ng-show="!reverseSort">^</span><span ng-show="reverseSort">v</span></span>
                                                </a>
                                            </th>
                                            <th>
                                                <a href="#" ng-click="orderByField='guini'; reverseSort = !reverseSort">
                                                    Guini <span ng-show="orderByField == 'guini'"><span ng-show="!reverseSort">^</span><span ng-show="reverseSort">v</span></span>
                                                </a>
                                            </th>
                                            <th>
                                                <a href="#" ng-click="orderByField='fine'; reverseSort = !reverseSort">
                                                    Fine <span ng-show="orderByField == 'fine'"><span ng-show="!reverseSort">^</span><span ng-show="reverseSort">v</span></span>
                                                </a>
                                            </th>
                                            <th>
                                                <a href="#" ng-click="orderByField='price'; reverseSort = !reverseSort">
                                                    Price <span ng-show="orderByField == 'price'"><span ng-show="!reverseSort">^</span><span ng-show="reverseSort">v</span></span>
                                                </a>
                                            </th>
                                            <th>Status</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr ng-repeat="x in selectedAdminJobList | orderBy:orderByField:reverseSort">
                                            <!--                                            <tr ng-repeat="x in currentWorkingJobList | filter : test">-->
                                            <td class="text-right"> {{$index + 1}}</td>
                                            <td>{{ x.tr_date }}</td>
                                            <td class="text-right">{{ x.job_id }}</td>
                                            <td class="text-left">{{ x.nick_name }}</td>
                                            <td>{{ x.model_number }}</td>
                                            <td class="text-right" ng-init="$parent.totalQty = $parent.totalQty +x.pieces">{{ x.pieces }}</td>
                                            <td class="text-right" ng-init="$parent.totalGoldSend = $parent.totalGoldSend +x.gold_send">{{ x.gold_send | number : 3 }}</td>
                                            <td class="text-right" ng-init="$parent.totalDalSend = $parent.totalDalSend +x.dal_send">{{ x.dal_send | number : 3 }}</td>
                                            <td class="text-right" ng-init="$parent.totalPanSend = $parent.totalPanSend +x.pan_send">{{ x.pan_send | number : 3 }}</td>
                                            <td class="text-right">{{ x.gold_returned | number : 3 }}</td>
                                            <td class="text-right">{{ x.nitrick_returned | number : 3 }}</td>
                                            <td class="text-right">{{ x.p_loss | number : 3 }}</td>
                                            <td class="text-right">{{ x.actual_ploss | number : 3 }}</td>

                                            <td class="text-right">{{ x.actual_mv | number : 3 }}</td>
                                            <td class="text-right">{{ x.guini | number : 3 }}</td>
                                            <td class="text-right">{{ x.fine | number : 3 }}</td>
                                            <td class="text-right">{{ x.actual_price | number : 0 }}</td>
                                            <td class="text-right">
                                                <a ng-show="x.status==9" target="_blank" href="{{mainProjectURL}}/index.php/bill_controller/display_bill?bill_no={{ x.bill_status}}">{{ x.bill_status}}</a>
                                                <a ng-show="x.status!=9" target="_blank" href="#">{{ x.bill_status}}</a>
                                            </td>

                                        </tr>
                                        </tbody>
<!--                                        <tfoot>-->
<!--                                        <tr>-->
<!--                                            <td class="text-center" colspan="5">Total</td>-->
<!--                                            <td class="text-right">{{totalQty}}</td>-->
<!--                                            <td class="text-right">{{totalGoldSend | number : 3}}</td>-->
<!--                                            <td class="text-right">{{totalDalSend | number : 3}}</td>-->
<!--                                            <td class="text-right">{{totalPanSend | number : 3}}</td>-->
<!--                                        </tr>-->
<!--                                        </tfoot>-->
                                    </table>
                                </div>
                                <!--                                <pre>materialInwardOutwardReport={{materialInwardOutwardReport | json}} </pre>-->

                            </div>

                        </div> <!--//End of my tab1//-->
                    </div>
                    <div ng-show="isSet(2)">
                        <div id="my-tab-2">
                            <pre>
                                adminJobList={{adminJobList | json}}
                            </pre>
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

    public function angular_view_new_job_report(){
        ?>

        <?php
        $GLOBALS["menu_name"] = "New Job Report";
        include("menu_all/admin/menu_admin.html");
        ?>
            <!--Working here-->
            <h1>Report will be here</h1>
        <form name="discountForm" class="" id="">
            <div class="d-flex">
                <!--                                            <div class="row col-2 bg-gray-4 pr-1"><span class="">Show</span><input ng-change="reloadTempInwardOutwardTransactionList()" class="ml-1 mr-1 bg-gray-4 text-right col-4 form-control pl-0 pr-0" ng-model="materialInwardOutwardReport.numberOfRecord" type="number" min="1" max="500"><span class="ml-1 mr-2">records</span></div>-->
                <div class="row col-4 bg-gray-3"><span class="mr-1"> Date between </span><input class="col-4 form-control" ng-model="materialInwardOutwardReport.dateFrom"  type="date" ng-change="materialInwardOutwardReport.dateFrom=(materialInwardOutwardReport.dateFrom)"> <span class="ml-1 mr-1"> to </span><input class="col-4 form-control" ng-model="materialInwardOutwardReport.dateTo" type="date" ng-change="materialInwardOutwardReport.dateTo=(materialInwardOutwardReport.dateTo)">
                    <button ng-click="newAdminJobReportByDate(materialInwardOutwardReport.dateFrom,materialInwardOutwardReport.dateTo)"><i class="fas fa-search"></i></button>
                </div>
                <button class="d-print-none" type="button btn-primary" ng-click="huiPrintDivAdvanced('new-job-report','customer_day_book','Transaction-'+100,1)">
                    <span class="glyphicon glyphicon-print"></span>
                </button>
            </div>
            <div id="new-job-report-div">
                <div class="card">
                    <div class="card-header">

                    </div>
                    <div class="card-body" id="new-job-report">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Job</th>
                                    <th>Customer</th>
                                    <th>Pieces</th>
                                    <th>Gini</th>
<!--                                    <th>Type</th>-->
                                    <th>Bronze</th>
                                    <th>Dal</th>
                                    <th>Pan</th>
                                    <th>Ploss</th>
                                    <th>Nitric4</th>
                                    <th>MV</th>
                                    <th>LC</th>
                                    <th>Bill</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="x in newAdminJobList">
                                    <td>{{x.formatted_date}}</td>
                                    <td class="text-right">{{x.job_id}}</td>
                                    <td>{{x.cust_name}}</td>
                                    <td class="text-right">{{x.pieces}}</td>
                                    <td class="text-right">{{x.guine}}</td>
<!--                                    <td>{{x.rm_name}}</td>-->
                                    <td class="text-right">{{x.bronze_send}}</td>
                                    <td class="text-right">{{x.dal_send}}</td>
                                    <td class="text-right">{{x.pan_send}}</td>
                                    <td class="text-right">{{x.total_ploss | number:3}}</td>
                                    <td class="text-right">{{x.total_ploss | number:3}}</td>
                                    <td class="text-right">{{x.nitric_4 | number:3}}</td>
                                    <td class="text-right">{{x.lc | number:2}}</td>
                                    <td class="text-right">
                                        <a class="no-print" ng-show="x.status_ID==9" target="_blank" href="{{mainProjectURL}}/index.php/bill_controller/display_bill?bill_no={{ x.bill_number}}">{{ x.bill_number}}</a>
                                        <span ng-show="x.status_ID!=9" target="_blank" href="#">{{ x.bill_number}}</span>
                                    </td>
                                </tr>
                            </tbody>
                            <footer>
                                <tr>
                                   <td colspan="3">Total</td>
                                   <td class="text-right">{{newJobReportQtyTotal.qty}}</td>
                                   <td class="text-right">{{newJobReportQtyTotal.guine | number:3}}</td>
                                   <td class="text-right">{{newJobReportQtyTotal.bronze | number:3}}</td>
                                   <td class="text-right">{{newJobReportQtyTotal.dal | number:3}}</td>
                                   <td class="text-right">{{newJobReportQtyTotal.pan | number:3}}</td>
                                   <td class="text-right">{{newJobReportQtyTotal.ploss | number:3}}</td>
                                   <td class="text-right">{{newJobReportQtyTotal.nitric | number:3}}</td>
                                   <td class="text-right">{{newJobReportQtyTotal.mv | number:3}}</td>
                                   <td class="text-right">{{newJobReportQtyTotal.lc | number:3}}</td>
                                </tr>
                            </footer>
                        </table>
                    </div>
                </div>
            </div>
<!--            <pre>-->
<!--                newAdminJobList={{newAdminJobList | json}}-->
<!--            </pre>-->
        </form>

        <?php
    }

    public function get_job_report_by_date(){
        $post_data =json_decode(file_get_contents("php://input"), true);
        $date_from=$post_data['date_from'];
        $date_to=$post_data['date_to'];

        $result=$this->report_model->select_admin_job_report($date_from,$date_to);
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);
    }

    public function get_new_job_report_by_date(){
        $post_data =json_decode(file_get_contents("php://input"), true);
        $date_from=$post_data['date_from'];
        $date_to=$post_data['date_to'];

        $result=$this->report_model->select_new_admin_job_report($date_from,$date_to);
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);
    }


    function get_all_inward_outward_material_transactions(){
        $result=$this->report_model->select_inward_outward_material_transactions()->result_array();
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);
    }
    function get_all_agent_with_dues_and_discount(){
        $result=$this->report_model->get_all_agent_with_dues_and_discount()->result_array();
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);
    }
    function get_all_customer_with_dues_and_discount_by_agent_id(){
        $post_data =json_decode(file_get_contents("php://input"), true);
        $result=$this->report_model->get_all_customer_with_dues_and_discount_by_agent_id($post_data['agent_id'])->result_array();
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);
    }
    function get_customer_day_book_by_id(){
        $post_data =json_decode(file_get_contents("php://input"), true);
        $result=$this->report_model->select_customer_day_book_by_cust_id($post_data['cust_id'])->result_array();
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);
    }

    public function angular_view_agent_sale_report(){
        ?>

        <?php
        $GLOBALS["menu_name"] = "New Job Report";
        include("menu_all/admin/menu_admin.html");
        ?>
        <!--Working here-->
        <h1>Report will be here</h1>
        <form name="discountForm" class="" id="">
            <div class="d-flex">
                <!--                                            <div class="row col-2 bg-gray-4 pr-1"><span class="">Show</span><input ng-change="reloadTempInwardOutwardTransactionList()" class="ml-1 mr-1 bg-gray-4 text-right col-4 form-control pl-0 pr-0" ng-model="materialInwardOutwardReport.numberOfRecord" type="number" min="1" max="500"><span class="ml-1 mr-2">records</span></div>-->
                <div class="row col-4 bg-gray-3"><span class="mr-1"> Date between </span><input class="col-4 form-control" ng-model="materialInwardOutwardReport.dateFrom"  type="date" ng-change="materialInwardOutwardReport.dateFrom=(materialInwardOutwardReport.dateFrom)"> <span class="ml-1 mr-1"> to </span><input class="col-4 form-control" ng-model="materialInwardOutwardReport.dateTo" type="date" ng-change="materialInwardOutwardReport.dateTo=(materialInwardOutwardReport.dateTo)">
                    <button ng-click="agentSaleQuantity(materialInwardOutwardReport.dateFrom,materialInwardOutwardReport.dateTo)"><i class="fas fa-search"></i></button>
                </div>
                <button class="d-print-none" type="button btn-primary" ng-click="huiPrintDivAdvanced('sale-qty-report','agent_sale_report','Transaction-'+100,1)">
                    <span class="glyphicon glyphicon-print"></span>
                </button>
            </div>
            <div id="sale-qty-report">
                <div class="card">
                    <div class="card-header">

                    </div>
                    <div class="card-body" id="agent-sale-report" >
                        <table class="table table-condensed table-hover table-bordered table-striped" style="width: 50vw;">
                            <thead>
                            <tr>
                                <th class="w-5">SL</th>
                                <th class="w-5">ID</th>
                                <th class="w-15">Name</th>
                                <th class="w-5">Quantity</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="x in agentSaleQuantityListByDate">
                                    <td class="text-right"> {{$index + 1}}</td>
                                    <td class="text-center">{{x.agent_id}}</td>
                                    <td class="text-left">{{x.agent_name}}</td>
                                    <td class="text-right">{{x.qty}}</td>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--            <pre>-->
            <!--                newAdminJobList={{newAdminJobList | json}}-->
            <!--            </pre>-->
        </form>

        <?php
    }
    public function get_agent_sale_qty_by_date(){
        $post_data =json_decode(file_get_contents("php://input"), true);
        $date_from=$post_data['date_from'];
        $date_to=$post_data['date_to'];

        $result=$this->report_model->select_agent_sale_qty_by_date($date_from,$date_to);
        $report_array['records']=$result;
        echo json_encode($report_array,JSON_NUMERIC_CHECK);
    }

}
?>