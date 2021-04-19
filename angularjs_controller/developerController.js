app.controller("developerCtrl", function ($scope,$http,$filter,$timeout,dateFilter,$rootScope,myFactory) {
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

    /************************************* Division *************************************************/
    $scope.division = "topDiv";
    $scope.setDivision = function(divisionName){
        $scope.division = divisionName;
    };
    $scope.isDivisionSet = function(divisionName){
        return $scope.division === divisionName;
    };
    /************************************* Division End*************************************************/
    myFactory.setMSG("Srikrishna Bangle Jewellers");
   $scope.msg=myFactory.getMSG();
});

