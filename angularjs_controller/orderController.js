app.controller("orderCtrl", function ($scope,$http,$filter,$timeout,dateFilter,$rootScope) {
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

    $scope.order={};
    $scope.agentList={};
    $scope.loadAgents=function(){
        var request = $http({
            method: "post",
            url: site_url+"/admin/get_admin_agent_list",
            data: {}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.agentList=response.data.records;

        });
    };//end of loadVendors
    $scope.loadAgents();
    $scope.customerList={};

    $scope.loadCustomers=function(agent){
        var request = $http({
            method: "post",
            url: site_url+"/admin/get_customers_by_agent",
            data: {agent_id: agent.agent_id}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.customerList=response.data.records;

        });
    };//end of loadVendors


});

