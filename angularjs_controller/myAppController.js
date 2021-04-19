
var url=location.href;
var urlAux = url.split('/');

var base_url=urlAux[0]+'/'+urlAux[1]+'/'+urlAux[2]+'/'+urlAux[3]+'/';
var site_url=urlAux[0]+'/'+urlAux[1]+'/'+urlAux[2]+'/'+urlAux[3]+'/index.php/';

// alert(site_url);
// site_url='http://127.0.0.1/gold_manager2/index.php/';
// base_url='http://127.0.0.1/gold_manager2/';.




var app = angular.module("myApp", ["ngRoute","angular-md5","ngMessages","xeditable","720kb.datepicker"]);
app.config(function($routeProvider) {
    $routeProvider
        .when("/", {
            templateUrl : site_url+"base/angular_view_home",
            controller : "loginCtrl"
        }).when("/login", {
            templateUrl : site_url+"base/angular_view_login",
            controller : "loginCtrl"
        }).when("/adminArea", {
            templateUrl : site_url+"admin/angular_view_welcome_admin",
            controller : "adminCtrl"
        }).when("/employeeArea", {
            templateUrl : site_url+"Staff/angular_view_staff_welcome",
            controller : "staffCtrl"
        }).when("/salesReturn", {
            templateUrl : site_url+"admin/angular_view_sales_return",
            controller : "salesReturnCtrl"
        }).when("/staffArea", {
            templateUrl : site_url+"staff/angular_view_welcome",
            controller : "staffCtrl"
        }).when("/customerDiscount", {
            templateUrl : site_url+"customer/angular_view_customer_discount",
            controller : "customerCtrl"
        }).when("/managerArea", {
            templateUrl : site_url+"manager/angular_view_welcome_manager",
            controller : "managerCtrl"
        }).when("/materialTransactionReport", {
            templateUrl : site_url+"report/angular_view_material_transaction_report",
            controller : "managerReportCtrl"
        }).when("/jobReport", {
            templateUrl : site_url+"report/angular_view_job_report",
            controller : "managerReportCtrl"
        }).when("/newJobReport", {
            templateUrl : site_url+"report/angular_view_new_job_report",
            controller : "managerReportCtrl"
        }).when("/jobtoStockDiff", {
            templateUrl : site_url+"report/angular_view_job_to_stock_report",
            controller : "managerReportCtrl"
        }).when("/agentReport", {
            templateUrl : site_url+"report/angular_view_agent_report",
            controller : "managerReportCtrl"
        }).when("/materialConversion", {
            templateUrl : site_url+"admin/angular_view_material_conversion",
            controller : "materialConversionCtrl"
        }).when("/purchase", {
            templateUrl : site_url+"purchase/angular_view_purchase",
            controller : "purchaseCtrl"
        }).when("/order", {
            templateUrl : site_url+"order/angular_view_order_welcome",
            controller : "orderCtrl"
        }).when("/customerReport", {
            templateUrl : site_url+"admin/angular_view_customer_report",
            controller : "adminCtrl"
        }).when("/agentDetails", {
            templateUrl : site_url+"admin/angular_view_customer_report",
            controller : "adminCtrl"
        }).when("/developerArea", {
            templateUrl : site_url+"Developer/angular_view_welcome_developer",
            controller : "developerCtrl"
        }).when("/workingJob", {
            templateUrl : site_url+"staff/angular_view_working_job",
            controller : "staffCtrl"
        }).when("/billReport", {
            templateUrl : site_url+"staff/angular_view_bill_report",
            controller : "staffCtrl"
        }).when("/agentSaleQty", {
            templateUrl : site_url+"report/angular_view_agent_sale_report",
            controller : "managerReportCtrl"
        });
});

app.run(function(editableOptions,editableThemes) {
    editableOptions.theme = 'bs3';
    editableThemes['bs3'].submitTpl = '<button type="submit"><i class="fas fa-check text-success"></i></button>';
    editableThemes['bs3'].cancelTpl = '<button type="submit"><i class="fas fa-ban text-danger"></i></button>';
});



app.directive('a', function() {
    return {
        restrict: 'E',
        link: function(scope, elem, attrs) {
            if(attrs.ngClick || attrs.href === '' || attrs.href === '#'){
                elem.on('click', function(e){
                    e.preventDefault();
                });
            }
        }
    };
});

//it will allow integer values
app.directive('numbersOnly', function () {
    return {
        require: 'ngModel',
        link: function (scope, element, attr, ngModelCtrl) {
            function fromUser(text) {
                if (text) {
                    var transformedInput = text.replace(/[^0-9-]/g, '');
                    if (transformedInput !== text) {
                        ngModelCtrl.$setViewValue(transformedInput);
                        ngModelCtrl.$render();
                    }
                    return transformedInput;
                }
                return undefined;
            }
            ngModelCtrl.$parsers.push(fromUser);
        }
    };
});






app.filter('capitalize', function() {
    return function(input) {
        return (!!input) ? input.split(' ').map(function(wrd){return wrd.charAt(0).toUpperCase() + wrd.substr(1).toLowerCase();}).join(' ') : '';
    }
});
app.run(function($rootScope){
    $rootScope.CurrentDate = Date;
});
////Directive for input maxlength//
app.directive('myMaxlength', function() {
    return {
        require: 'ngModel',
        link: function (scope, element, attrs, ngModelCtrl) {
            var maxlength = Number(attrs.myMaxlength);
            function fromUser(text) {
                if (text.length > maxlength) {
                    var transformedInput = text.substring(0, maxlength);
                    ngModelCtrl.$setViewValue(transformedInput);
                    ngModelCtrl.$render();
                    return transformedInput;
                }
                return text;
            }
            ngModelCtrl.$parsers.push(fromUser);
        }
    };
});
app.directive('goldDecimalPlaces',function(){
    return {
        link:function(scope,ele,attrs){
            ele.bind('keypress',function(e){
                var newVal=$(this).val()+(e.charCode!==0?String.fromCharCode(e.charCode):'');
                if($(this).val().search(/(.*)\.[0-9][0-9][0-9]/)===0 && newVal.length>$(this).val().length){
                    e.preventDefault();
                }
            });
        }
    };
});
//currency decimal places
app.directive('currencyDecimalPlaces',function(){
    return {
        link:function(scope,ele,attrs){
            ele.bind('keypress',function(e){
                var newVal=$(this).val()+(e.charCode!==0?String.fromCharCode(e.charCode):'');
                if($(this).val().search(/(.*)\.[0-9][0-9]/)===0 && newVal.length>$(this).val().length){
                    e.preventDefault();
                }
            });
        }
    };
});
app.directive('numericValue', function () {
    return {
        require: 'ngModel',
        link: function (scope, element, attr, ngModelCtrl) {
            function fromUser(text) {
                if (text) {
                    var transformedInput = text.replace(/[^0-9-.]/g, '');
                    if (transformedInput !== text) {
                        ngModelCtrl.$setViewValue(transformedInput);
                        ngModelCtrl.$render();
                    }
                    return transformedInput;
                }
                return undefined;
            }
            ngModelCtrl.$parsers.push(fromUser);
        }
    };
});
app.run(function($rootScope){
    $rootScope.roundNumber=function(number, decimalPlaces){
        return parseFloat(parseFloat(number).toFixed(decimalPlaces));
    };
});
app.run(function($rootScope) {
    $rootScope.huiPrintDiv = function(printDetails,userCSSFile) {
        var divContents=$('#'+printDetails).html();
        var printWindow = window.open('', '', 'height=400,width=800');

        printWindow.document.write('<!DOCTYPE html>');
        printWindow.document.write('\n<html>');
        printWindow.document.write('\n<head>');
        printWindow.document.write('\n<title>');
        //printWindow.document.write(docTitle);
        printWindow.document.write('</title>');
        printWindow.document.write('\n<link href="'+project_url+'bootstrap-3.3.7-dist/css/bootstrap.min.css" type="text/css" rel="stylesheet" media="all">\n');
        printWindow.document.write('\n<link href="'+project_url+'css/print_style/basic_print.css" type="text/css" rel="stylesheet" media="all">\n');

        printWindow.document.write('\n<link href="'+project_url+'css/print_style/');
        printWindow.document.write(userCSSFile);
        printWindow.document.write('?v='+ Math.random()+'" rel="stylesheet" type="text/css" media="all"/>');


        printWindow.document.write('\n</head>');
        printWindow.document.write('\n<body>');
        printWindow.document.write(divContents);
        printWindow.document.write('\n</body>');
        printWindow.document.write('\n</html>');
        printWindow.document.close();
        printWindow.print();
        // printWindow.close();
    };
});
app.filter('AmountConvertToWord', function() {
    return function(amount) {
        var words = new Array();
        words[0] = '';
        words[1] = 'One';
        words[2] = 'Two';
        words[3] = 'Three';
        words[4] = 'Four';
        words[5] = 'Five';
        words[6] = 'Six';
        words[7] = 'Seven';
        words[8] = 'Eight';
        words[9] = 'Nine';
        words[10] = 'Ten';
        words[11] = 'Eleven';
        words[12] = 'Twelve';
        words[13] = 'Thirteen';
        words[14] = 'Fourteen';
        words[15] = 'Fifteen';
        words[16] = 'Sixteen';
        words[17] = 'Seventeen';
        words[18] = 'Eighteen';
        words[19] = 'Nineteen';
        words[20] = 'Twenty';
        words[30] = 'Thirty';
        words[40] = 'Forty';
        words[50] = 'Fifty';
        words[60] = 'Sixty';
        words[70] = 'Seventy';
        words[80] = 'Eighty';
        words[90] = 'Ninety';
        amount = amount.toString();
        var atemp = amount.split(".");
        var number = atemp[0].split(",").join("");
        var n_length = number.length;
        var words_string = "";
        if (n_length <= 9) {
            var n_array = new Array(0, 0, 0, 0, 0, 0, 0, 0, 0);
            var received_n_array = new Array();
            for (var i = 0; i < n_length; i++) {
                received_n_array[i] = number.substr(i, 1);
            }
            for (var i = 9 - n_length, j = 0; i < 9; i++, j++) {
                n_array[i] = received_n_array[j];
            }
            for (var i = 0, j = 1; i < 9; i++, j++) {
                if (i == 0 || i == 2 || i == 4 || i == 7) {
                    if (n_array[i] == 1) {
                        n_array[j] = 10 + parseInt(n_array[j]);
                        n_array[i] = 0;
                    }
                }
            }
            value = "";
            for (var i = 0; i < 9; i++) {
                if (i == 0 || i == 2 || i == 4 || i == 7) {
                    value = n_array[i] * 10;
                } else {
                    value = n_array[i];
                }
                if (value != 0) {
                    words_string += words[value] + " ";
                }
                if ((i == 1 && value != 0) || (i == 0 && value != 0 && n_array[i + 1] == 0)) {
                    words_string += "Crores ";
                }
                if ((i == 3 && value != 0) || (i == 2 && value != 0 && n_array[i + 1] == 0)) {
                    words_string += "Lakhs ";
                }
                if ((i == 5 && value != 0) || (i == 4 && value != 0 && n_array[i + 1] == 0)) {
                    words_string += "Thousand ";
                }
                if (i == 6 && value != 0 && (n_array[i + 1] != 0 && n_array[i + 2] != 0)) {
                    words_string += "Hundred and ";
                } else if (i == 6 && value != 0) {
                    words_string += "Hundred ";
                }
            }
            words_string = words_string.split("  ").join(" ");
        }
        return "Rupees "+words_string+" Only";
    };
});



app.service('globalInfo', function () {
    var userTypeID = 0;
    var employeeID = 0;

    return {
        getUserTypeID: function () {
            return userTypeID;
        },
        setUserTypeID: function(value) {
            userTypeID = value;
        },
        getEmployeeID: function () {
            return employeeID;
        },
        setEmployeeID: function(value) {
            employeeID = value;
        },
        getProjectIP(){
            return urlAux[0]+'/'+urlAux[1]+'/'+urlAux[2]+'/';
        },
        getMainProjectURL(){
            return urlAux[0]+'/'+urlAux[1]+'/'+urlAux[2]+'/'+'ses_diamond';
        }
    };
});

app.directive('tooltip', function(){
    return {
        restrict: 'A',
        link: function(scope, element, attrs){
            element.hover(function(){
                // on mouseenter
                element.tooltip('show');
            }, function(){
                // on mouseleave
                element.tooltip('hide');
            });
        }
    };
});

app.factory('myFactory', function () {
    var displayFact;
    var addMSG = function (msg) {
        displayFact = ' Hi Guest, Welcome to ' + msg;
    }
    return {
        setMSG: function (msg) {
            addMSG(msg);
        },
        getMSG: function () {
            return displayFact;
        }

    };

});









