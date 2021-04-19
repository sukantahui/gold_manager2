app.controller("adminCtrl", function ($scope,$http,$filter,$timeout,dateFilter,$rootScope) {
    $scope.msg = "This is play controller";
    //Tab area
    $scope.tab = 1;
    $scope.setTab = function(newTab){
        $scope.tab = newTab;
    };
    $scope.isSet = function(tabNum){
        return $scope.tab === tabNum;
    };
    //End of tab area

    $scope.pivotTableData={};
    $scope.loadPivotTableData=function(){
        var request = $http({
            method: "post",
            url: site_url+"/admin/get_material_to_emp_balance",
            data: {}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.pivotTableData=response.data.records;

        });
    };//end of loadVendors
    $scope.loadPivotTableData();


    $scope.customerList={};
    $scope.loadCustomerList=function(){
        var request = $http({
            method: "post",
            url: site_url+"/admin/get_admin_customer_list",
            data: {}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.customerList=response.data.records;

        });
    };//end of loadVendors
    $scope.loadCustomerList();

});

