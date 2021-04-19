app.controller("materialConversionCtrl", function ($scope,$http,$compile,globalInfo,$filter) {
    $scope.msg = "This is Material Conversion controller";
    $scope.panCreationSuccessMessage="Click here to create pan";
    $scope.roughGoldSaveSuccessMessage="Click here to save rough gold";

    $scope.tab = 1;
    $scope.isSet = function(tabNum){
        return $scope.tab === tabNum;
    };
    $scope.setTab = function(newTab){
        $scope.tab = newTab;
        if(newTab==1){

        }
    };

    $scope.panCreationSubmitEnabled=false;


    $scope.panConversion={};
    $scope.panConversion.gold90=0;
    $scope.panConversion.gold92=0;
    $scope.panConversion.totalGold=0;
    $scope.panConversion.dal=0;
    $scope.panConversion.pan=0;
    $scope.panConversion.expectedPan=0;
    $scope.panConversion.oldPan=0;



    $scope.roughGoldCreation={};
    $scope.roughGoldCreation['employee_id']=72;
    $scope.roughGoldCreation['roughGold']=0;
    $scope.roughGoldCreation['rm_id']=49;

    $scope.loadKarigar=function(){
        var request = $http({
            method: "post",
            url: site_url+"/admin/get_karigars",
            data: {}
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(response){
            $scope.karigars=response.data.records;
            //$scope.roughGoldCreation.karigar=$scope.karigars[0];
            var index=$scope.karigars.findIndex(x=>x.emp_name==='Sourav Rajak');
            if(index==-1)
                index=0;
            $scope.roughGoldCreation.karigar=$scope.karigars[index];
            alasql
                .promise('SELECT * FROM ? where emp_id=69',[$scope.karigars])
                .then(function(res){
                    console.log(res);
                }).catch(function(err){
                    console.log('error:', err);
            });


        });
    };//end of loadCustomer
    $scope.loadKarigar();

    // console.log('karigars');



    $scope.panConversion.goldChange=function(){
       var x=Number.parseFloat($scope.panConversion.gold90);
       var y=Number.parseFloat($scope.panConversion.gold92);
       var dal=Number.parseFloat($scope.panConversion.dal);
       var oldPan=Number.parseFloat($scope.panConversion.oldPan);
       $scope.panConversion.totalGold=x+y;
       $scope.panConversion.expectedPan=x+y+dal+oldPan;
    };
    $scope.textToFloat=function (x) {
        return parseFloat(x);
    };
    $scope.panConversion.convertToPan=function(){
       $scope.panConversionData={};
       $scope.panConversionData.ninetyGold=$scope.panConversion.gold90;
       $scope.panConversionData.ninetyTwoGold=$scope.panConversion.gold92;
       $scope.panConversionData.oldPan=$scope.panConversion.oldPan;
       $scope.panConversionData.dal=$scope.panConversion.dal;
       $scope.panConversionData.pan=$scope.panConversion.pan;
       $scope.panConversionData.employeeID=globalInfo.getEmployeeID();
       $scope.panConversionData.karigarID=67;

       var request = $http({
            method: "post",
            url: site_url+"/admin/save_convert_to_pan_details",
            data: {
                pan_data: $scope.panConversionData
            }
            ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
       }).then(function(response){
            $scope.reportArray=response.data;
            if($scope.reportArray.success==1){
                $scope.panConversion.gold90=0;
                $scope.panConversion.gold92=0;
                $scope.panConversion.dal=0;
                $scope.panConversion.pan=0;
                $scope.panCreationSuccessMessage="pan created successfully"
            }
        });
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
    $scope.roughGoldCreation.saveRoughGold=function(rgc){
            var tempData={};
            tempData['employee_id']=rgc.employee_id;
            tempData['karigar_id']=rgc.karigar.emp_id;
            tempData['rm_id']=rgc.rm_id;
            tempData['gold_value']=rgc.roughGold;


            var request = $http({
                method: "post",
                url: site_url+"/admin/save_rough_gold",
                data: {
                    rough_data: tempData
                }
                ,headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
            }).then(function(response){
                $scope.reportArray=response.data;
                if($scope.reportArray.success==1){
                    $scope.roughGoldSaveSuccessMessage="Transaction ID "+$scope.reportArray.material_transformation_id;
                    $scope.roughGoldCreation.roughGold=0;
                    $scope.roughGoldCreation.comment="";
                }
            });

    }

});
