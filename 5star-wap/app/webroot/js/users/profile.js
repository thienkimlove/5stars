angular.module('userProfileApp.services', []);
angular.module('userProfileApp.directives', []);
angular.module('userProfileApp.filters', []);
angular.module('userProfileApp', ['userProfileApp.services','userProfileApp.directives', 'userProfileApp.filters']).
controller('userProfileCtrl', function($scope, $http, $filter, $window) {
	
	$scope.user = Config.user;
	
	$scope.errorMessage = '';
	
	$scope.doPayment = function (coin) {
		$http({
			method: 'POST',
			url: baseUrl + "/users/payment",
			headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			data:  [{ coin : coin }]
		}).success(function (response) {
			if (response.status === false) {
			  $scope.errorMessage = response.message; 
			} else {                
			   $scope.user.User.coin = parseInt(response.message);
			   $scope.errorMessage = '';
			}             
		}).error(function (response) {                      
			$scope.errorMessage = 'An error occured on submitting the info - please try again!';
		});
	}
	
});