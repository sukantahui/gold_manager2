app.controller("staffCtrl", function ($scope,$http,$filter,$timeout,dateFilter,$rootScope) {
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

    $scope.toSQLDate=function(str){
        var temp=str.split("-");
        return temp[2]+"-"+temp[1]+"-"+temp[0];
    }

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
    $scope.currentWorkingJobList={};
    $scope.getCurrentWorkingJobList=function(){
        var request = $http({
            method: "post",
            url: site_url+"/staff/get_current_working_job",
            data: {}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.currentWorkingJobList=response.data.records;
            $scope.selectedCurrentWorkingJobList = alasql("select * from ? ", [$scope.currentWorkingJobList]);
        });
    }



    $scope.orderByField = 'job_id';
    $scope.reverseSort = false;

    $scope.getSelectedJobs=function(searchTest){
        $scope.totalQty=0;
        $scope.selectedCurrentWorkingJobList = alasql("select * from ? where cust_name like '%" + searchTest + "%' or status_name like '%" + searchTest + "%' or nick_name like '%" + searchTest + "%' or order_id like '%" + searchTest + "%' or product_code like '%" + searchTest + "%'  ", [$scope.currentWorkingJobList]);
    }

    $scope.editableJob={};
    $scope.editJob=function(x){
        angular.copy(x,$scope.editableJob={});
        $scope.tab=2;
    }


    $scope.user = {
        name: 'awesome user',
        dob: new Date(1984, 0,4)
    };


    $scope.updateSize = function(job,data) {

        return  request = $http({
            method: "post",
            url: site_url+"/staff/set_product_size",
            data: {job_id: job.job_id
                   ,product_size:data
                  }
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            var x=response.data.records;

        });
        return true;
    };


});

