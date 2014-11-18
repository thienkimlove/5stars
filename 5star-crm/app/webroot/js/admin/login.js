angular.module('userLoginApp.services', []);
angular.module('userLoginApp.directives', []);
angular.module('userLoginApp.filters', []);
angular.module('userLoginApp', ['userLoginApp.services','userLoginApp.directives', 'userLoginApp.filters']).
controller('userLoginCtrl', function($scope, $http, $filter, $window) {    

    $scope.user = {};
    $scope.doLogin = function (event) {
        event.preventDefault();
        if (!$scope.userLoginForm.$invalid) {
            $('form#userLoginForm').submit();                     
        } 
    }   

});