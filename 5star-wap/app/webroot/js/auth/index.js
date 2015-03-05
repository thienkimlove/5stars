angular.module('userLoginApp.services', []);
angular.module('userLoginApp.directives', [])
.directive('uiValidateEquals', function() {

    return {
        restrict: 'A',
        require: 'ngModel',
        link: function(scope, elm, attrs, ctrl) {

            function validateEqual(myValue, otherValue) {
                if (myValue === otherValue) {
                    ctrl.$setValidity('equal', true);
                    return myValue;
                } else {
                    ctrl.$setValidity('equal', false);
                    return undefined;
                }
            }

            scope.$watch(attrs.uiValidateEquals, function(otherModelValue) {
                validateEqual(ctrl.$viewValue, otherModelValue);               
            });

            ctrl.$parsers.unshift(function(viewValue) {
                return validateEqual(viewValue, scope.$eval(attrs.uiValidateEquals));
            });

            ctrl.$formatters.unshift(function(modelValue) {
                return validateEqual(modelValue, scope.$eval(attrs.uiValidateEquals));                
            });
        }
    };
});
angular.module('userLoginApp.filters', []);
angular.module('userLoginApp', ['userLoginApp.services','userLoginApp.directives', 'userLoginApp.filters']).
controller('userLoginCtrl', function($scope, $http, $filter, $window, $timeout) {    

    $scope.user={login:'',password:''}; 
    $scope.login = true;
    $scope.apiMessage = Config.apiMessage;
    $scope.iOSlogin = false;

    if($.cookie('login') && $.cookie('pass')){
        $scope.user={login:$.cookie('login'),password:$.cookie('pass')};
    }
    //special login for appstore verify
    $scope.coding = angular.copy($scope.user);

    $scope.showTrialButton = false; // using for show or hide Free Trial button.
    $scope.showCodinhTaiKhoanButton = false;
    $scope.showConfirm = false;    // using for show or hide button codinh tai khoan.

    if (Config.gameId == '7' && Config.channelId == '1') {
        $scope.iOSlogin = true;
    }

    $scope.btnName="Đăng Ký 5 Stars ID";
    $scope.fb = {name:"Đăng Nhập Bằng Facebook",checked:false};
    $scope.showFacebookLogin = true;    


    $scope.lost = {btnName:'Xác Nhận',checked:false};
    $scope.change = {btnName:'Xác Nhận',checked:false};

    $scope.showErrorLogin = false;
    $scope.showErrorRegister = false;
    $scope.showErrorLostPassword = false;
    $scope.showErrorChangePassword = false;

    $scope.onSwitchButton = function(){
        if($scope.login==true){
            $scope.btnName="Đăng Nhập";
            $scope.login = false;
            $("#btn-login").removeClass().addClass('btn btn-warning btn-block');
        }else{
            $scope.btnName="Đăng Ký 5 Stars ID";
            $scope.login = true;
            $("#btn-login").removeClass().addClass('btn btn-success btn-block');
        }
    };

    if ($scope.iOSlogin) {	    
        if ($.cookie('login') && $.cookie('pass')) {
            if ($.cookie('codinh') == 1) {
                $scope.showCodinhTaiKhoanButton = true;
            } 
        } else {
            $scope.showTrialButton = true;
        }
    }

    $scope.doTrial = function(event) {        
        event.preventDefault();  
        //generate tai khoan.
        var count = $.cookie('count_codinh');
        if (count > 3) {
            $scope.apiMessage = 'Bạn không thể tạo tài khoản Free Trial quá 3 lần!';
            return;
        }

        $http.get(Config.wap + '/auth/trial').then(function(res){  
            if (res.data.uid) {
                count++;
                $scope.user = angular.copy(res.data); 
                
                $scope.showTrialButton = false;  
                $timeout(function(){

                    $.cookie('codinh', 1, { expires: 7 });
                    $.cookie('count_codinh', count, { expires: 7 });  

                    $.cookie('login', $scope.user.login, { expires: 7 });
                    $.cookie('pass', $scope.user.password, { expires: 7 });

                    }, 0);
            } else {
                $scope.apiMessage = 'Lỗi trong quá trình khởi tạo tài khoản. Xin thử lại!';
            }
        });
    }
    $scope.codingTK = function() {
        $scope.showCodinhTaiKhoanButton = false;
        $scope.showConfirm = true;
        $scope.coding = angular.copy($scope.user);
    }      

    $scope.doCoding = function(event) {
        event.preventDefault();
        // post new username and password.
        $scope.apiMessage = null;
        $http.post(Config.wap + '/auth/change', { user : $scope.user, coding : $scope.coding }).then(function(res){
            if (res.data.status) {
                $scope.user.login = $scope.coding.username;
                $scope.user.password = $scope.coding.password;

                $scope.showConfirm = false;
                $scope.btnName="Đăng Nhập";
                $scope.login = true;

                $timeout(function(){
                    $.cookie('codinh', null);
                    $.cookie('login', $scope.user.login, { expires: 7 });
                    $.cookie('pass', $scope.user.password, { expires: 7 });
                    $("#btn-login").removeClass().addClass('btn btn-warning btn-block');
                });

            } else {
                $scope.apiMessage = res.data.message;
            }
        });
    }
    //special login for appstore verify



    $scope.doLogin = function (event) {
        event.preventDefault();
        //fix the case just user choose email then password fill in but blur event not happened.
        // $('form[name=userLoginForm]>input').trigger('change blur');
        $scope.apiMessage = null;
        // $timeout(function(){
        if ($scope.userLoginForm.$invalid) {
            $scope.showErrorLogin = true;
        } else {
            $('.body-content').addClass('overlay');
            $('#loading').addClass('loading').append('<div class="ball"></div>');
            $scope.showErrorLogin = false;
            $.cookie('login', $scope.user.login, { expires: 7 });
            $.cookie('pass', $scope.user.password, { expires: 7 });
            $('form#userLoginForm').submit();            
        }    
        //   }, 250);


    }
    $scope.doRegister = function (event) {
        event.preventDefault();
        $scope.apiMessage = null;
        if ($scope.userRegisterForm.$invalid) {
            $scope.showErrorRegister = true;
        } else {
            $('.body-content').addClass('overlay');
            $('#loading').addClass('loading').append('<div class="ball"></div>');
            $scope.showErrorRegister = false;
            $('form#userRegisterForm').submit();            
        }    

    };

    $scope.facebook = function(event) {
        event.preventDefault();
        $scope.apiMessage = null;   
        $scope.fb.name = 'Loading...';
        $('.body-content').addClass('overlay');
        $('#loading').addClass('loading').append('<div class="ball"></div>');
        $scope.fb.checked = true;
        window.location = encodeURI("https://www.facebook.com/dialog/oauth?client_id=" + Config.facebookAppId + "&redirect_uri="+ Config.wap +"/auth/?external=1&scope=email,publish_stream&response_type=code");
    }

    $scope.getLostPassword = function(event){
        event.preventDefault();
        if ($scope.userLostPassword.$invalid) {
            $scope.showErrorLostPassword = true;
        }else{
            $scope.showErrorLostPassword = false;
            $scope.lost.checked = true;
            $scope.lost.btnName = 'Loading...';
            $http.post(Config.wap+'/auth/lost', $scope.lost).success(function(data,status){
                if(data.status==1){
                    //$('#lost-password').modal('hide');
                    $scope.showErrorLostPassword = true;
                    $scope.message=data.message;
                    $scope.lost.checked = false;
                    $scope.lost.btnName = 'Xác Nhận';
                }else{
                    $scope.showErrorLostPassword = true;
                    $scope.message=data.message;
                    $scope.lost.checked = false;
                    $scope.lost.btnName = 'Xác Nhận';
                }

            }).error(function(data,status){
                $scope.showErrorLostPassword = true;
                $scope.message='Lỗi kết nối , vui lòng thử lại sau';
                $scope.lost.checked = false;
                $scope.lost.btnName = 'Xác Nhận';
            });
        }
    }

    $scope.changePassword = function(event){
        event.preventDefault();
        if ($scope.userChangePassword.$invalid) {
            $scope.showErrorChangePassword = true;
            $scope.message = null;
        }else{
            $scope.showErrorChangePassword = false;
            $scope.change.checked = true;
            $scope.change.btnName = 'Loading...';
            $http.post(Config.wap+'/auth/changePassword', $scope.change).success(function(data,status){
                if(data.status==1){
                    //$('#lost-password').modal('hide');
                    $scope.showErrorChangePassword = true;
                    $scope.message=data.message;
                    $scope.change.checked = false;
                    $scope.change.btnName = 'Xác Nhận';
                }else{
                    $scope.showErrorChangePassword = true;
                    $scope.message=data.message;
                    $scope.change.checked = false;
                    $scope.change.btnName = 'Xác Nhận';
                }

            }).error(function(data,status){
                $scope.showErrorChangePassword = true;
                $scope.message='Lỗi kết nối , vui lòng thử lại sau';
                $scope.change.checked = false;
                $scope.change.btnName = 'Xác Nhận';
            });
        }
    }

});

