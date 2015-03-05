angular.module('userProfileApp.services', []);
angular.module('userProfileApp.directives', []);
angular.module('userProfileApp.filters', []);
angular.module('userProfileApp', ['userProfileApp.services','userProfileApp.directives', 'userProfileApp.filters']).
controller('userProfileCtrl', function($scope, $http, $filter, $window) {
	$scope.process = 0;
	$scope.coin = 0;	
	$scope.params = Config.params;
    $scope.waplink = Config.waplink;

	$scope.processMessage = '';

	$scope.doPayment = function() {
		window.open( $scope.waplink + '/auth/payment?gameId=' + $scope.params.gameId + '&channelId=' + $scope.params.channelId + '&userId=' + $scope.params.authId + '&demo=1');
		$scope.process = 1;  
	}

});