app.controller("customerCtrl", function ($scope,$http,$compile) {
    $scope.msg = "This is Customer controller";
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



    $scope.sort = {
        active: '',
        descending: undefined
    };




    $scope.isUpdateable=false;
    $scope.message = "Add new record";
    $customerIndex=-1;


    $scope.isUpdateable=false;

    $scope.sort = {
        active: '',
        descending: undefined
    };




    $scope.reportArray={};
    $scope.searchText="";
    $scope.searchTextAgent="";
    $scope.customerList={};
    $scope.customerDiscount={};
    $scope.loadAllCustomers=function(){
        var request = $http({
            method: "post",
            url: site_url+"/customer/get_customers",
            data: {}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.customerList=response.data.records;
            $scope.selectedCustomers=response.data.records;
            $scope.customerDiscount.customer=$scope.selectedCustomers[0];
        });
    };//end of loadCustomer
    $scope.loadAllCustomers();
    $scope.searchCustomers=function(){

        if($scope.customerDiscount.searchText.length>2) {
            $scope.customerDiscount.searchTextAgent="";
            $scope.selectedCustomers = alasql("select * from ? where cust_name like '%" + $scope.customerDiscount.searchText + "%' order by cust_name", [$scope.customerList]);
            $scope.customerDiscount.customer = $scope.selectedCustomers[0];
        }
    };
    $scope.searchCustomersByagent=function(){
        if($scope.customerDiscount.searchTextAgent.length>2) {
            $scope.customerDiscount.searchText="";

            $scope.selectedCustomers = alasql("select * from ? where agent_name like '%" + $scope.customerDiscount.searchTextAgent + "%' or short_name like '%" + $scope.customerDiscount.searchTextAgent + "%' order by cust_name", [$scope.customerList]);
            $scope.customerDiscount.customer = $scope.selectedCustomers[0];
        }
    };
    $scope.saveDiscount=function(customerDiscount){
        var tempDiscountData={};
        tempDiscountData.cust_id=customerDiscount.customer.cust_id;
        tempDiscountData.agent_id=customerDiscount.customer.agent_id;
        tempDiscountData.description=customerDiscount.description;
        tempDiscountData.gold=customerDiscount.gold;
        tempDiscountData.lc=customerDiscount.lc;

        var newSavedRecord={};
        newSavedRecord.cust_id=customerDiscount.customer.cust_id;
        newSavedRecord.cust_name=customerDiscount.customer.cust_name;
        newSavedRecord.short_name=customerDiscount.customer.short_name;
        newSavedRecord.gold=customerDiscount.gold;
        newSavedRecord.amount=customerDiscount.lc;

        var request = $http({
            method: "post",
            url: site_url+"/customer/save_new_discount",
            data: {
                discount_data: tempDiscountData
            }
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.reportArray=response.data.records;
            if($scope.reportArray.success==1){
                $scope.customerIndex=$scope.customerList.indexOf(customerDiscount.customer);
                $scope.message="Record saved "+  $scope.reportArray.discount_id;
                customerDiscount.customer.gold_discount=customerDiscount.customer.gold_discount+customerDiscount.gold;
                customerDiscount.customer.lc_discount=customerDiscount.customer.lc_discount+customerDiscount.lc;
                //$scope.customerDiscount={};
                $scope.customerDiscount.gold=0;
                $scope.customerDiscount.lc=0;
                // $scope.customerDiscount.description="";
                $scope.discountForm.$setPristine()
                $scope.customerDiscountList.unshift(newSavedRecord);
            }
        });

    };

    //GET ALL Discounts
    $scope.selectAllDiscounts=function(){
        var request = $http({
            method: "post",
            url: site_url+"/customer/get_customer_discounts",
            data: {}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.customerDiscountList=response.data.records;
        });
    };
    $scope.selectAllDiscounts();
    $scope.selectAllCustomerWithNetDues=function(){
        var request = $http({
            method: "post",
            url: site_url+"/customer/get_customer_net_dues",
            data: {}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.customerWithNetDues=response.data.records;
            $scope.customerWithNetDuesTotal=alasql('SELECT sum(current_gold_due) as total_gold_due, sum(discount_gold) as total_gold_discount, sum(current_lc_due) as total_current_lc_due, sum(discount_lc) as total_lc_discount  from ?',[$scope.customerWithNetDues]);
        });
    };
    $scope.selectAllCustomerWithNetDues();
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

});
