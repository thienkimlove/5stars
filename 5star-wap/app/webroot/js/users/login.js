angular.module('userLoginApp.services', []);
angular.module('userLoginApp.directives', []);
angular.module('userLoginApp.filters', []);
angular.module('userLoginApp', ['userLoginApp.services','userLoginApp.directives', 'userLoginApp.filters']).
controller('userLoginCtrl', function($scope, $http, $filter, $window) {	
	
	$scope.user = {};
	$scope.networks = Config.networks;
	$scope.doSubmit = function (event) {
		event.preventDefault();
		if ($scope.userLoginForm.$invalid) {
			$('div#message').show().html('Form invalid');
		} else {
			$('form#userLoginForm').submit();			
		}	
		
	}
	
});