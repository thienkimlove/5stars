angular.module('userLoginApp.services', []);
angular.module('userLoginApp.directives', []);
angular.module('userLoginApp.filters', []);
angular.module('userLoginApp', ['userLoginApp.services','userLoginApp.directives', 'userLoginApp.filters']).
controller('userLoginCtrl', function($scope, $http, $filter, $window) {	

	$scope.message = '';
	$scope.buttonName = 'Login/Register';
	$scope.params = Config.params;
    $scope.waplink = Config.waplink;
	$scope.checkLogin  = function () {
		$http({method: 'GET', url: baseUrl +  '/demos/checkLogin'}).
		success(function(response) {
			if (response.status === 'open') {
				window.open($scope.waplink + '/auth/?gameId=' + $scope.params.gameId + '&channelId=' + $scope.params.channelId + '&token=' + response.token + '&demo=1', '_blank');
				$scope.message = 'Please login at WAPsite before click Go button';
				$scope.buttonName = 'GO';				
			}  else {
				if (response.status === 'error') {
				  $scope.message = 'Please login at WAPsite before click Go button';  
				} else {
					window.location.href = baseUrl +  '/demos/profile';
				}
			}
		}).
		error(function(data, status, headers, config) {
			
		});
	}
});