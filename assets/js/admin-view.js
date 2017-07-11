var eventApp = angular.module('eventApp',['ngRoute', 'ngAnimate']);

eventApp.controller('eventAppCtrl', function($scope){
	
	$('#event-list').show();
	$('#create-event').hide();
	
	$scope.showCreateEvent = function() {
		$('#event-list').hide(0.5, function() {
			$('#create-event').fadeIn();
		});
	} // $scope.showCreateEvent = function()
	
	$scope.closeCreateEvent = function() {
		$('#create-event').hide(0.5, function() {
			$('#event-list').fadeIn();
		});
	} // $scope.closeCreateEvent = function()
	
	$scope.initializeView = function() {
		$scope.phase = "phaseOne";
	}
	
	$scope.phaseOneBack = function() {
		$('#event-list').show();
		$('#create-event').hide();
	}
	
	$scope.phaseOneNext = function() {
		$scope.phase = "phaseTwo";
	}
	
	$scope.phaseTwoBack = function() {
		$scope.phase = "phaseOne";
	}
	
	$scope.phaseTwoNext = function() {
		$scope.phase = "phaseThree";
	}
	
	$scope.phaseThreeBack = function() {
		$scope.phase = "phaseTwo";
	}
	
	/*
	$scope.phaseThreeNext = function() {
		$scope.phase = "";
	}
	*/
	
});

/*
eventApp.config(function($routeProvider) {

    $routeProvide
	.when("/", {
        templateUrl : "event-info.html"
    })
	.when("/event-criteria", {
        templateUrl : "event-criteria.html"
    })
	.when("/event-team", {
        templateUrl : "views/event-team.html"
    })
	.otherwise({
        templateUrl : "event-info.html"
    });
	
});
*/