<!DOCTYPE html>
<html>
<head>
	<title>Judge Score Sheet</title>
		<!--
		<link rel="icon" href="images/icon.ico" type="image/png" sizes="32x32">
		-->
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- Bootstrap -->
		<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
		<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous"> -->

		<!-- Jquery -->
		<script src="../assets/js/jquery.min.js"></script>
		<!-- <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script> -->
		
		<!-- Tether JS -->
		<script src="../assets/js/tether.min.js"></script>
		<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script> -->

		<!-- Bootstrap JS -->
		<script src="../assets/js/bootstrap.min.js"></script>
		<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script> -->

		<!-- Font Awesome -->
		<link rel="stylesheet" href="../assets/css/font-awesome.min.css">
		<!-- <link href="http://netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet"> -->

		<link rel="stylesheet" href="../assets/css/judge.css">
		<script src="../assets/js/angular.min.js"></script>
		<script src="../assets/js/judge.js"></script>
	
</head>

<body ng-app="view" ng-controller="judges-score">

	<header class="container-fluid">
		
		<div class="row">

			<div class="col-md-3 text-center">
				<a href="../database/export_score_breakdown.php" target="_top"><button><img id="image" src="../assets/images/export-icon.png"> EXPORT DATA</button></a>
			</div>
		
			<div class="col-md-9 text-center" id="head-name">
					SCORES SUMMARY
			</div>

		</div>

	</header>

	<div class="container-fluid" >
	
		<div class="row" id="judge-panel">
		
			<div class="col-md-3" class="judges-list">
				<h2 id="choose-judge-text">JUDGES</h2>
				<ul class="nav nav-pills nav-stacked">
					<!-- on ng click->toggle just pass the whole "judge" and set the active property to true -->
					<li ng-repeat="judge in scores"><a data-toggle="pill" href="#table-{{judge.judge_id}}" ng-click="toggle_table(judge)">{{judge.judge_name}}</a></li>
	 			</ul>
			</div>
			
			<div class="col-md-9" id="col"> 
				<!-- Initialize a new property active on "judge" set it initally to false, active will on be true if toggled" -->
				<div class="tab-content row" ng-repeat="judge in scores" ng-show="judge.active">
					<div id="table-{{judge.judge_id}}" class="tab-pane fade in active judge-style" ng-init="judge.active = false" ng-show="judge.active">
						<div class="content">

				            <div class="container-fluid">

				                <div class="row">

				                    <div class="col-md-12">

				                        <div class="card">

				                            <div class="header">

				                                <h1 class="judge-name">{{judge.judge_name}}</h1>

				                            </div>

				                            <div class="content table-responsive table-full-width">

				                                <table class="table table-striped">

				                                    <thead id="thead">

				                                        <th>Team Name</th>

				                                    	<th class="text-center" ng-repeat="criteria in judge.teams[0].criteria">{{criteria.criteria_desc}}</th>

				                                    </thead>

				                                    <tbody>

				                                        <tr ng-repeat="team in judge.teams">

				                                        	<td>{{team.team_name}}</td>

				                                        	<td class="text-center" ng-repeat="criteria in team.criteria">{{criteria.score}}</td>
				                                        </tr>

				                                    </tbody>

				                                </table>



				                            </div>

				                        </div>

				                    </div>
			    			</div>
						</div>	
					</div>
				</div>
			</div>
		</div>

</body>

</html>