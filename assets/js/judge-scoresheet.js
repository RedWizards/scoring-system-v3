
	var app = angular.module('scoring-sheet', []);

	app.controller('sheet-ctrl', ['$scope' , 'Session', function($scope, Session) {

		$scope.activeNow =  false;
		$scope.isActive = false;			


		$scope.init = function(){
			Session.then(function(response){
				$scope.session = response;

				$.ajax({
					method: 'GET',
					url: '././database/initial_data.php',
					data:{
						event_id: 1,
						judge_id: $scope.session.judge_id
					}
				})
				.done(function(data){
					$scope.teams = data;
					$scope.$apply();
				})
				.fail(function(xhr, textStatus, errorThrown) {
				    console.log(xhr.responseText);
				});
			});
		}

		$scope.setScores = function(team){
			var sheet_url= '../../database/update_score.php';
			
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

	}]);

	app.factory('Session', function($http){
		return $http.get('././helpers/session.php').then(function(result){
			return result.data;
		});
	});