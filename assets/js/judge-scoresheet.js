
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
			console.log(team);
			var success = true;

			for(var i = 0; i < team.criteria.length; i++){

				if(team.criteria[i].score_details.score_id == null){
					//add scores
					var response = $.ajax({
						method: 'post',
						url: '././database/submit_score.php',
						data:{
							judge_id: $scope.session.judge_id,
							criteria_id: team.criteria[i].criteria_id,
							project_id: team.project_id,
							score: team.criteria[i].score_details.score
						}, 
						async: false,

					}).responseText;

					try {
					    var response = JSON.parse(response);
					    console.log('Iteration: '+i);
						console.log("RESPONSE: " + response);
						team.criteria[i].score_details.score_id = parseInt(response);
					}
					catch(err) {
					    success = false;
					}
				}
				else if(team.criteria[i].score_details.score_id != null){
					//update scores
					$.ajax({
						method: 'post',
						url: '././database/update_scores.php',
						data:{
							score_id: team.criteria[i].score_details.score_id,
							score: team.criteria[i].score_details.score
						}
					}).fail(function(xhr, textStatus, errorThrown) {
					    console.log(xhr.responseText);
					    success= false;
					});
				}
			}

			if(success == true){
				alert("Scores submitted!");
				$scope.closeTeam(team);
			}else{
				alert("Some of the scores are not submitted.\nPlease try again with a valid input.");
				window.location.href = './scoresheet.php';
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