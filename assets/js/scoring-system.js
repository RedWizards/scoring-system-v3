
	var app = angular.module('score-app', []);

	app.controller('score-ctrl', function($scope) {
		
		$scope.hasRecord = false;
		$scope.records = [];
		
		var records_url = '../database/view_events.php';
		
		function init() {
			$.ajax({
				url: records_url
			}).done(function(data) {
				if (data.length > 0)
					$scope.hasRecord = true;
				$scope.records = data;
				$scope.$apply();
			}).fail(function() { alert('request failed'); });;
		}
		
		init();

	});
	