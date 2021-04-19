app.controller("loginCtrl", function ($scope,$http,$filter,md5,$window,globalInfo) {
    $scope.msg = " This is Log in controller";
    $scope.loginData={};
    $scope.login=function (loginData) {
        var psw=md5.createHash($scope.loginData.user_password || '');
        var request = $http({
            method: "post",
            url: site_url+"/base/validate_credential",
            data: {
                userId: loginData.user_id
                ,userPassword: psw
            }
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){

            $scope.loginDatabaseResponse=response.data;
            globalInfo.setUserTypeID($scope.loginDatabaseResponse.user_type_id);
            globalInfo.setEmployeeID($scope.loginDatabaseResponse.employee_id);

            if($scope.loginDatabaseResponse.user_type_id==1){
                $window.location.href = base_url+'#!/developerArea';
            }else if($scope.loginDatabaseResponse.user_type_id==2) {
                $window.location.href = base_url + '#!/adminArea';
            }else if($scope.loginDatabaseResponse.user_type_id==5){
                $window.location.href = base_url + '#!/employeeArea';
            }else if($scope.loginDatabaseResponse.user_type_id==7){
                $window.location.href = base_url + '#!/managerArea';
            }else{
                alert("Check user Id or Password");
            }
        });
    };

    $scope.searchItem=function(){
      alert("Work in progress")
    };
    $scope.pop = function(){
        toaster.pop('info', "title", "text");
    };

});

