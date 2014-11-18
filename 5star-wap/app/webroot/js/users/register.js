angular.module('userRegisterApp.services', []);
angular.module('userRegisterApp.directives', []);
angular.module('userRegisterApp.filters', []);
angular.module('userRegisterApp', ['userRegisterApp.services','userRegisterApp.directives', 'userRegisterApp.filters']).
controller('userRegisterCtrl', function($scope, $http, $filter, $window) {    
	
	$scope.user = {};
	$scope.networks = Config.networks;
	$scope.doSubmit = function (event) {
		event.preventDefault();
		if ($scope.userRegisterForm.$invalid) {
			$('div#message').show().html('Form invalid');
		} else {
			$('form#userRegisterForm').submit();            
		}    
		
	}
	
});