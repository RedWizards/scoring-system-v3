angular.module('createCriteria', []).controller('criteriaCtrl', ['$scope', function($scope) {

		$scope.criterias = [];

		$scope.addCriteria = function() {
			$scope.criterias.push({'name': $scope.new_name, 'weight': $scope.new_weight, 'desc': $scope.new_desc, 'done':false});
			$scope.new_name = '';
			$scope.new_weight = '';
			$scope.new_desc = '';
		}

		$scope.deleteCriteria = function(index) {	
			$scope.criterias.splice(index, 1);
		}

		$scope.submitCriteria = function() {
			alert($scope.criterias);
		}

}]);