app.controller("salesReturnCtrl", function ($scope,$http,$compile,globalInfo) {
    $scope.msg = "This is Sales Return controller";


    $scope.tab = 1;
    $scope.isSet = function(tabNum){
        return $scope.tab === tabNum;
    };
    $scope.setTab = function(newTab){
        $scope.tab = newTab;
    };
    $scope.myObj = {
        "color" : "white",
        "background-color" : "coral",
        "font-size" : "15px",
        "padding" : "5px"
    };
    $scope.agentList={};
    $scope.selectedAgents={};
    $scope.salesReturn={};
    $scope.salesReturn.agent={};
    $scope.loadAllAgents=function(){
        var request = $http({
            method: "post",
            url: site_url+"/Agent/get_inforced_agent",
            data: {}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.agentList=response.data.records;
            $scope.selectedAgents=response.data.records;
            $scope.salesReturn.agent=$scope.selectedAgents[0];
            $scope.loadAllCustomers();
        });
    };//end of loadCustomer
    $scope.loadAllAgents();

    //Loading Models
    $scope.models;
    $scope.loadAllModels=function(){
        var request = $http({
            method: "post",
            url: site_url+"/admin/get_models",
            data: {}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.models=response.data.records;
        });
    };//end of loadCustomer
    $scope.loadAllModels();


    $scope.customertList={};
    $scope.selectedCustomers={};
    $scope.salesReturn.customer={};
    $scope.loadAllCustomers=function(){
        var request = $http({
            method: "post",
            url: site_url+"/Customer/get_customers",
            data: {}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.customertList=response.data.records;
            $scope.selectedCustomers=alasql('select * from ? where agent_id=? ',[$scope.customertList,$scope.salesReturn.agent.agent_id]);
            $scope.salesReturn.customer=$scope.selectedCustomers[0];
        });
    };//end of loadCustomer


    $scope.salesReturn.agentChange=function () {
        $scope.selectedCustomers=alasql('select * from ? where agent_id=? ',[$scope.customertList,$scope.salesReturn.agent.agent_id]);
        $scope.salesReturn.customer=$scope.selectedCustomers[0];
    };



});
