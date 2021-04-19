app.controller("managerReportCtrl", function ($scope,$http,$compile,globalInfo,$q) {
    $scope.msg = "This is Report controller";
    $scope.userTypeID=globalInfo.getUserTypeID();
    $scope.mainProjectURL=globalInfo.getMainProjectURL();
    $scope.agentSaleQuantityListByDate=null;
    $scope.total=0;
    $scope.cdate = new Date();

    $scope.isManager=false;
    $scope.isAdmin=false;
    $scope.isDeveloper=false;
    if($scope.userTypeID==7){
        $scope.isManager=true;
        $scope.isAdmin=false;
        $scope.isDeveloper=false;
    }
    if($scope.userTypeID==2){
        $scope.isManager=false;
        $scope.isAdmin=true;
        $scope.isDeveloper=false;
    }

    $scope.tab = 1;
    $scope.isSet = function(tabNum){
        return $scope.tab === tabNum;
    };
    $scope.setTab = function(newTab){
        $scope.customer=angular.copy($scope.defaultCustomer);
        $scope.tab = newTab;
        if(newTab==1){
            $scope.isUpdateable=false;
        }
    };
    $scope.tabStyleActive=function(tabNum){
        if($scope.tab === tabNum) {
            return {
                'color': 'white',
                'background-color': 'blue',
            }
        }else{
            return {
                'color': 'black',
                'background-color': 'white',
            }
        }
    }

    //$scope.materialInwardOutwardReport={};
    //$scope.materialInwardOutwardReport.numberOfRecord=30;
    $scope.inwardOutwardTransactionList=[];
    $scope.current_date = new Date(new Date().toISOString().split("T")[0])
    $scope.materialInwardOutwardReport={dateTo: $scope.current_date, dateFrom: $scope.current_date,numberOfRecord:30};

    $scope.jobStockDifferenceList=[];
    $scope.loadJobStockDifference=function(){
        var request = $http({
            method: "post",
            url: site_url+"/report/get_job_stock_difference",
            data: {}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.jobStockDifferenceList=response.data.records;
        });
    };//
    $scope.loadJobStockDifference();





    $scope.loadInwardOutwardTransactionList=function(){
        var request = $http({
            method: "post",
            url: site_url+"/report/get_all_inward_outward_material_transactions",
            data: {}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.inwardOutwardTransactionList=response.data.records;
            $scope.tempInwardOutwardTransactionList=alasql('SELECT *  FROM ? LIMIT '+$scope.materialInwardOutwardReport.numberOfRecord+'',[$scope.inwardOutwardTransactionList]);
        });
    };//
    $scope.loadInwardOutwardTransactionList();



    $scope.reloadTempInwardOutwardTransactionList=function () {
        $scope.tempInwardOutwardTransactionList=alasql('SELECT *  FROM ? LIMIT '+$scope.materialInwardOutwardReport.numberOfRecord+'',[$scope.inwardOutwardTransactionList]);
    };
    $scope.selectTempaInwardOutwardTransactionListByDate=function(){

        $scope.tempInwardOutwardTransactionList=alasql('SELECT *  FROM ? WHERE record_date>=? AND record_date <= ? LIMIT '+$scope.materialInwardOutwardReport.numberOfRecord+'',[$scope.inwardOutwardTransactionList,$scope.materialInwardOutwardReport.dateFrom.toString(),$scope.materialInwardOutwardReport.dateTo.toString()]);
    };


    $scope.sort = {
        active: '',
        descending: undefined
    };

    $scope.getIcon = function(column) {

        var sort = $scope.sort;

        if (sort.active == column) {
            return sort.descending? 'fa-user-graduate' : 'sort-down';
        }

        return 'sort-up';
    };

    $scope.changeSorting = function(column) {
        var sort = $scope.sort;
        if (sort.active == column) {
            sort.descending = !sort.descending;
        }
        else {
            sort.active = column;
            sort.descending = false;
        }
    };






    //*************************************************
    $scope.saveToExcel=function (fileName) {
        //$scope.testVendorList=alasql('select SUM(CONVERT(number,state_id)) as tot from ?',[$scope.vendorList]);
        //alasql('SELECT * INTO CSV("Myfile.csv",{headers:true}) FROM ?', [$scope.vendorList]);
        //alasql('SELECT * INTO XLSX("Myfile.xlsx",{headers:true}) FROM ?', [$scope.vendorList]);
        var mystyle = {
            headers:true,
            column: {style:{Font:{Bold:"1"}}}
            /* rows: {3:{style:{Font:{Color:"#FF0077"}}}},
             cells: {1:{1:{
                         style: {Font:{Color:"#00FFFF"}}
                     }}}*/
        };

        alasql('SELECT * INTO XLSXML(?,?) FROM ?',[fileName,mystyle,$scope.customerList]);
    };
    $scope.saveCustomerDayBookToExcel=function (fileName) {
        //$scope.testVendorList=alasql('select SUM(CONVERT(number,state_id)) as tot from ?',[$scope.vendorList]);
        //alasql('SELECT * INTO CSV("Myfile.csv",{headers:true}) FROM ?', [$scope.vendorList]);
        //alasql('SELECT * INTO XLSX("Myfile.xlsx",{headers:true}) FROM ?', [$scope.vendorList]);
        var mystyle = {
            headers:true,
            column: {style:{Font:{Bold:"1"}}}
            /* rows: {3:{style:{Font:{Color:"#FF0077"}}}},
             cells: {1:{1:{
                         style: {Font:{Color:"#00FFFF"}}
                     }}}*/
        };

        alasql('SELECT formated_tr_date as Date,particulars as Particulars,reference as Reference,received_gold as `Gold Received`,received_lc as `Received LC`,billed_qty as `Billed Qty`,billed_gold as `Billed Qty`,billed_lc as `Billed LC`,op_gold_due as `Op. Gold Due`,op_lc_due as `Op LC Due`,current_gold_due as `Current Gold Due`,current_lc_due as `Current LC Due` INTO XLSXML(?,?) FROM ?',[fileName,mystyle,$scope.customerTransactions.records]);
    };
    $scope.saveAdminJobReportToExcel=function (fileName) {
        //$scope.testVendorList=alasql('select SUM(CONVERT(number,state_id)) as tot from ?',[$scope.vendorList]);
        //alasql('SELECT * INTO CSV("Myfile.csv",{headers:true}) FROM ?', [$scope.vendorList]);
        //alasql('SELECT * INTO XLSX("Myfile.xlsx",{headers:true}) FROM ?', [$scope.vendorList]);
        var mystyle = {
            headers:true,
            column: {style:{Font:{Bold:"1"}}}
            /* rows: {3:{style:{Font:{Color:"#FF0077"}}}},
             cells: {1:{1:{
                         style: {Font:{Color:"#00FFFF"}}
                     }}}*/
        };

        alasql('SELECT * INTO XLSXML(?,?) FROM ?',[fileName,mystyle,$scope.adminJobList]);
    };
    $scope.saveToCSV=function (fileName) {
        //$scope.testVendorList=alasql('select SUM(CONVERT(number,state_id)) as tot from ?',[$scope.vendorList]);
        //alasql('SELECT * INTO CSV("Myfile.csv",{headers:true}) FROM ?', [$scope.vendorList]);
        //alasql('SELECT * INTO XLSX("Myfile.xlsx",{headers:true}) FROM ?', [$scope.vendorList]);
        var mystyle = {
            headers:true,
            column: {style:{Font:{Bold:"1"}}}
        };

        alasql('SELECT * INTO CSV(?,?) FROM ? order by person_name',[fileName,mystyle,$scope.customerList]);
    };


    $scope.adminJobList={};
    $scope.showAdminJobListTable=false;
    $scope.selectTAdminJobReportByDate=function(dateFrom,dateTo){
        var request = $http({
                method: "post",
                url: site_url+"/report/get_job_report_by_date",
                data: {date_from: dateFrom, date_to: dateTo}
                ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
                $scope.adminJobList=response.data.records;
                $scope.selectedAdminJobList=response.data.records;
                $scope.showAdminJobListTable=true;
                //$scope.tempInwardOutwardTransactionList=alasql('SELECT *  FROM ? LIMIT '+$scope.materialInwardOutwardReport.numberOfRecord+'',[$scope.inwardOutwardTransactionList]);
        });
    }

    $scope.newAdminJobReportByDate=function(dateFrom,dateTo){
        var request = $http({
            method: "post",
            url: site_url+"/report/get_new_job_report_by_date",
            data: {date_from: dateFrom, date_to: dateTo}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.newAdminJobList=response.data.records;



            $scope.newJobReportQtyTotal=alasql('SELECT sum(pieces) as qty, sum(guine) as guine, sum(bronze_send) as bronze, sum(dal_send) as dal, sum(pan_send) as pan, sum(total_ploss) as ploss, sum(nitric_4) as nitric, sum(total_mv) as mv, sum(lc) as lc  FROM ?' ,[$scope.newAdminJobList])[0];
            console.log($scope.newJobReportQtyTotal);
            // $scope.selectedAdminJobList=response.data.records;
            // $scope.showAdminJobListTable=true;
            //$scope.tempInwardOutwardTransactionList=alasql('SELECT *  FROM ? LIMIT '+$scope.materialInwardOutwardReport.numberOfRecord+'',[$scope.inwardOutwardTransactionList]);
        });
    }

    $scope.agentSaleQuantity=function(dateFrom,dateTo){
        var request = $http({
            method: "post",
            url: site_url+"/report/get_agent_sale_qty_by_date",
            data: {date_from: dateFrom, date_to: dateTo}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.agentSaleQuantityListByDate=response.data.records;


            console.log($scope.agentSaleQuantityListByDate);
            // $scope.selectedAdminJobList=response.data.records;
            // $scope.showAdminJobListTable=true;
            //$scope.tempInwardOutwardTransactionList=alasql('SELECT *  FROM ? LIMIT '+$scope.materialInwardOutwardReport.numberOfRecord+'',[$scope.inwardOutwardTransactionList]);
        });
    }

    $scope.searchJobReport=function(searchText){
        $scope.totalQty=0;
        $scope.totalGoldSend=0;
        $scope.totalDalSend=0;
        $scope.totalPanSend=0;
        $scope.selectedAdminJobList= alasql("select * from ? where job_id like '%" + searchText + "%' or emp_name like '%" + searchText + "%' or nick_name like '%" + searchText + "%' or model_number like '%" + searchText + "%' or bill_status like '%" + searchText + "%'  ", [$scope.adminJobList]);

    }

    // working as on 17/07/2020
    $scope.agentDueList={};
    $scope.agentDueList.records=[];
    $scope.agentDueList.total={};

    $scope.getAgentDues=function(){
        $scope.agentDueListDiffer = $q.defer();
        var request = $http({
            method: "post",
            url: site_url+"/report/get_all_agent_with_dues_and_discount",
            data: {}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.agentDueListDiffer.resolve(response);
            //$scope.agentDueList=response.data.records;
        });
        $scope.agentDueListDiffer.promise.then(function(response){
            $scope.agentDueList.records=response.data.records;
            $scope.agentDueList.total=alasql('SELECT sum(total_gold_due) as gold_due, sum(total_lc_due) as lc_due, sum(total_gold_discount) as gold_discount,sum(total_lc_discount) as  lc_discount FROM ?',[response.data.records])[0];;
        });
    };//
    $scope.getAgentDues();
    $scope.customerDueListByAgent={};
    $scope.customerDueListByAgent.records=[];
    $scope.customerDueListByAgent.total={};
    $scope.showAgentDetailsInSecondTab=function(agent){
        $scope.tab = 2;
        $scope.customerDueListByDiffer = $q.defer();
        var request = $http({
            method: "post",
            url: site_url+"/report/get_all_customer_with_dues_and_discount_by_agent_id",
            data: {agent_id: agent.agent_id}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.customerDueListByDiffer.resolve(response);
        });
        $scope.customerDueListByDiffer.promise.then(function(response){
            $scope.customerDueListByAgent.records=response.data.records;
            $scope.customerDueListByAgent.agent=agent;
            $scope.customerDueListByAgent.total=alasql('SELECT sum(gold_due) as gold_due, sum(lc_due) as lc_due, sum(gold_discount) as gold_discount,sum(lc_discount) as  lc_discount FROM ?',[response.data.records])[0];;
        });
    }
    $scope.customerTransactions={};
    $scope.customerTransactions.records={};
    $scope.customerTransactions.customer={};
    $scope.customerTransactions.currentDue={};
    $scope.showCustomerDetailsByCustomer=function(customer){
        $scope.tab = 3;
        $scope.customerDayBookByIDDiffer = $q.defer();
        var request = $http({
            method: "post",
            url: site_url+"/report/get_customer_day_book_by_id",
            data: {cust_id: customer.cust_id}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.customerDayBookByIDDiffer.resolve(response);
        });
        $scope.customerDayBookByIDDiffer.promise.then(function(response){
            $scope.customerTransactions.records=response.data.records;
            $scope.customerTransactions.customer=customer;
            $scope.customerTransactions.currentDue=_.last($scope.customerTransactions.records);
        });
    }


});
