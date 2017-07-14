
	var app = angular.module('teams', []);

	app.controller('teamsInit', ['$scope', 'Session', function($scope, Session) {

		$scope.activeNow =  false;
		$scope.isActive = false;			


		$scope.init = function(){
			Session.then(function(response){
				$scope.session = response;
				$.ajax({
					method: 'GET',
					url: '.././database/view_teams.php',
					data:{
						event_id: 1,
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

		$scope.removeTeam = function(index, team_id){
			console.log(team_id);
			$.ajax({
				method: 'post',
				url: '.././database/delete_team.php',
				async: false,
				data:{
					team_id: team_id
				},
				success: function(data){
					if(data == 1){
						$scope.teams.splice(index, 1);
					}
					else{
						alert(data);	
					}
				}
			});
		}
}]);


	app.factory('Session', function($http){
		return $http.get('.././helpers/session.php').then(function(result){
			return result.data;
		});
	});