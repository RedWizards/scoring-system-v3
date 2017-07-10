
	var app = angular.module('scoring-sheet', []);

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

		$scope.setScores = function(team){
			var sheet_url= '../../assets/database/update_score.php';
			
			var success = true;

			for(var i = 0; i < team.criteria.length; i++){
				$.ajax({
					url: sheet_url,
					data:{
						score_id: team.criteria[i].score_details.score_id,
						score: team.criteria[i].score_details.score
					}
				}).error(function(){
					success = false;
				});
			}

			if(success == true){
				alert("Scores submitted!");
				$scope.closeTeam(team);
			}else{
				alert("Error submitting scovres.");
			}
			
		}		
		
		$scope.updateScore = function(team) {
			team.total = 0;
			for (var i = 0; i < team.criteria.length; i++) {
				team.total += team.criteria[i].score_details.score;
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