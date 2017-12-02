<?php require_once('../helpers/admin-security.php');?>

<!DOCTYPE html>
<html>

	<head>

		<title>UHAC Manila | ADMIN</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" href="../assets/images/uhac.ico" type="image/ico" sizes="32x32">
		<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
		<link rel="stylesheet" href="../assets/css/admin-view.css">
		<script src="../assets/js/angular.min.js"></script>
		<script src="../assets/js/angular-animate.js"></script>
		<script src="../assets/js/angular-route.js"></script>
		<!-- <link rel="stylesheet" href="../assets/css/judge.css"> -->
		<script src="../assets/js/judge.js"></script>
        <link rel="stylesheet" href="../assets/css/font-awesome.min.css">

	</head>

	<body ng-app="view" ng-controller="judges-score">

			<header>
				<div class="text-center">
					<h3 id="scoresheet-name"><b>ADMINISTRATOR</b> VIEW</h3>
				</div>
			</header>

			<div class="row outer-row">

				<span class="pull-left">
					<a href="index.php"><button id="back"><span class="glyphicon glyphicon-chevron-left"></span> BACK</button></a>
				</span>
				<br/><br/>
				<h2 class="text-center">TEAM<strong> SCORES</strong></h2>

				<br/><br/>

				<div class="col-md-offset-1 col-md-10">

					<div class="row" style="padding: 0;">
		
						<div class="col-md-3 col-sm-3">
							<h2>JUDGES</h2>
							<div id="judges-list">
								<ul class="nav nav-pills nav-stacked">
									<li ng-repeat="judge in scores"><a class="judge-btn" data-toggle="pill" href="#table-{{judge.judge_id}}" ng-click="toggle_table(judge)">{{judge.judge_name}}</a></li>
					 			</ul>
				 			</div>
						</div>
						
						<div class="col-md-9 col-sm-9">

							<a href="../database/export_score_breakdown.php" target="_top" class="pull-right"><button id="export-btn">EXPORT SCORES</button></a>

							<div class="tab-content row" ng-repeat="judge in scores" ng-show="judge.active" style="padding: 0;">
								<br/><br/>
								<div id="table-{{judge.judge_id}}" class="tab-pane fade in active judge-style" ng-init="judge.active = false" ng-show="judge.active">

							                    <div class="col-md-12">

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

			<div id="footer" class="text-center">
				<small class="sub">POWERED BY</small><br/><strong>RED Wizard Events Management</strong><br/>&copy; 2017
			</div>
			
	</body>

	<script src="../assets/js/jquery.js"></script>
	<script src="../assets/js/bootstrap.min.js"></script>
	<script src="../assets/js/judge-view.js"></script>
	<script src="../assets/js/judge-scoresheet.js"></script>

</html>	