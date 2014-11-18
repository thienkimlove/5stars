angular.module('userPaymentApp.services', []);
angular.module('userPaymentApp.directives', []);
angular.module('userPaymentApp.filters', []);
angular.module('userPaymentApp', ['userPaymentApp.services','userPaymentApp.directives', 'userPaymentApp.filters']).
controller('userPaymentCtrl', function($scope, $http, $filter, $window) {    
	
	$scope.user = {};
	
	$scope.showErrorPayment = false;  
	
	$scope.doPayment = function (event) {
		event.preventDefault();
		if ($scope.userPaymentForm.$invalid) {
			$scope.showErrorPayment = true;
		} else {
			$scope.showErrorPayment = false;
			$('form#userPaymentForm').submit();            
		}    
		
	}
	
});