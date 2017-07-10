
	var app = angular.module('scoring-sheet', []);
	//var app = angular.module('scoring-sheet', ['ngAnimate']);

	app.controller('sheet-ctrl', function($scope) {
		
		$scope.activeNow =  false;
		$scope.isActive = false;
		
		var sheet_url= '../../database/initial_data.php';
		$scope.scoreSheet = [];
		
		function init() {
			$.ajax({
				url: sheet_url,
				data:{
					judge_id: 1,
					event_id:1
				}
			}).done(function(data) {
				$scope.teams = data;
				$scope.$apply();
			});
		}
		
		init();		
		
		$scope.updateScore = function(team) {
			team.total = 0;
			for (var i = 0; i < team.criteria.length; i++) {
				team.total += team.criteria[i].score;
			}
		}
		
		$scope.setScore = function(team) {
			team.isActive = true;
			$scope.activeNow = true;
		}
		
		$scope.closeTeam = function(team) {
			team.isActive = false;
			$scope.activeNow = false;
		}
		
	});
	/*
	function closeSplash() {
		document.getElementById("#splash").fadeout();;
	}
	*/
	