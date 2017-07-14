angular.module('createCriteria', []).controller('criteriaCtrl', ['$scope', function($scope) {

		$scope.criterias = [];

		$scope.init = function(){
			var response = $.ajax({
				method: 'get',
				url: '.././database/view_criteria.php',
				async: false,
				data:{
					event_id: 1
				}
			}).responseText;

			var response = JSON.parse(response);

			$scope.criterias = response;
		}

		$scope.addCriteria = function() {
			var response = $.ajax({
				method: 'post',
				url: '.././database/create_criteria.php',
				async: false,
				data:{
					event_id: 1,
					criteria_desc: $scope.new_name,
					criteria_weight: $scope.new_weight,
					criteria_longdesc: $scope.new_desc
				}
			}).responseText;

			var response = JSON.parse(response);

			$scope.criterias.push({'criteria_id': response.id, 'criteria_desc': $scope.new_name, 'criteria_weight': $scope.new_weight, 'criteria_longdesc': $scope.new_desc, 'done':false});
			$scope.new_name = '';
			$scope.new_weight = '';
			$scope.new_desc = '';
		}

		$scope.deleteCriteria = function(index, criteria_id) {	
			$.ajax({
				method: 'post',
				url: '.././database/delete_criteria.php',
				async: false,
				data:{
					criteria_id: criteria_id
				},
				success: function(data){
					if(data == 1){
						$scope.criterias.splice(index, 1);
					}
					else{
						alert(data);	
					}
				}
			});
		}

		$scope.submitCriteria = function() {
			alert('Criterias are Submitted');
			window.location.href = ".././server/index.php";
		}

}]);