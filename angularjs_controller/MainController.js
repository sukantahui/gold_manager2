app.controller('MainController', function($scope,$q,md5,$timeout,$http,$q,$window) {



    $scope.huiPrintDivAdvanced = function (printSectionId,CSSFile,documentTitle,numberOfCopies) {

        var innerContents = document.getElementById(printSectionId).innerHTML;

        if (navigator.userAgent.toLowerCase().indexOf('chrome') > -1) {
            var popupWinindow = window.open('', '_blank', 'width=600,height=600,scrollbars=no,menubar=no,toolbar=no,location=no,status=no,titlebar=no');
            popupWinindow.window.focus();
            popupWinindow.document.write('<!DOCTYPE html>');
            popupWinindow.document.write('<html>');
            popupWinindow.document.write('<head>');
            popupWinindow.document.write('<title>');
            popupWinindow.document.write(documentTitle+"-"+(new Date().valueOf()));
            popupWinindow.document.write('</title>');
            popupWinindow.document.write('<style>');
            popupWinindow.document.write('');
            popupWinindow.document.write('</style>');
            // popupWinindow.document.write('<link rel="stylesheet" type="text/css" href="node_modules/bootstrap/dist/css/bootstrap.css" />');
            popupWinindow.document.write('<link rel="stylesheet" type="text/css" href="style/printable/'+CSSFile+'.css?v=1.4" />');

            popupWinindow.document.write('</head>');
            popupWinindow.document.write('<body onload="window.print()">');
            popupWinindow.document.write(innerContents);
            if(numberOfCopies==2) {
                popupWinindow.document.write('<hr>');
                popupWinindow.document.write(innerContents);
            }
            popupWinindow.onbeforeunload = function (event) {
                popupWinindow.close();
                return '.\n';
            };
            popupWinindow.onabort = function (event) {
                popupWinindow.document.close();
                popupWinindow.close();
            }
        } else {
            var popupWinindow = window.open('', '_blank', 'width=800,height=600');
            popupWinindow.document.open();
            popupWinindow.document.write('<!DOCTYPE html>');
            popupWinindow.document.write('<html>');
            popupWinindow.document.write('<head>');
            popupWinindow.document.write('<title>');
            popupWinindow.document.write(documentTitle+"-"+(new Date().valueOf()));
            popupWinindow.document.write('</title>');
            popupWinindow.document.write('<style>');
            popupWinindow.document.write('');
            popupWinindow.document.write('</style>');

            popupWinindow.document.write('<link rel="stylesheet" type="text/css" href="style/printable/'+CSSFile+'.css" />');
            popupWinindow.document.write('<link rel="stylesheet" type="text/css" href="node_modules/bootstrap/dist/css/bootstrap.css" />');
            popupWinindow.document.write('</head>');
            popupWinindow.document.write('<body onload="window.print()">');
            popupWinindow.document.write(innerContents);
            if(numberOfCopies==2) {
                popupWinindow.document.write('<hr>');
                popupWinindow.document.write(innerContents);
            }
            popupWinindow.document.close();
            //popupWinindow.close();
        }
        popupWinindow.document.close();

        return true;
    }


});