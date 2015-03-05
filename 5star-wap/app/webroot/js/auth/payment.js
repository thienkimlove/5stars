angular.module('userPaymentApp.services', []);
angular.module('userPaymentApp.directives', []);
angular.module('userPaymentApp.filters', []);
angular.module('userPaymentApp', ['userPaymentApp.services','userPaymentApp.directives', 'userPaymentApp.filters']).
controller('userPaymentCtrl', function($scope, $http, $filter, $window) {    	
	$scope.user = {};	
	$scope.normalForm = 1;
	$scope.showErrorPayment = false; 
	$scope.showErrorPayment1 = false; 
	$scope.doPayment = function (event) {
		event.preventDefault();
		if ($scope.userPaymentForm.$invalid) {
			$scope.showErrorPayment = true;
		} else {
			$('.body-content').addClass('overlay');
	      	$('#loading').addClass('loading').append('<div class="ball"></div>');
			$scope.showErrorPayment = false;
			$('form#userPaymentForm').submit();            
		}    		
	}
	$scope.doPayment1 = function (event) {
		event.preventDefault();
		if ($scope.userPaymentForm1.$invalid) {
			$scope.showErrorPayment1 = true;
		} else {
			$('.body-content').addClass('overlay');
	      	$('#loading').addClass('loading').append('<div class="ball"></div>');
			$scope.showErrorPayment1 = false;
			$('form#userPaymentForm1').submit();            
		}    
	};
	
});

