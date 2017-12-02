<?php require('./helpers/route-scoresheet.php'); ?>
<!doctype html>
<html>

	<head>

		<title>UHAC Manila</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" href="assets/images/uhac.ico" type="image/ico" sizes="32x32">
		<link rel="stylesheet" href="assets/css/bootstrap.min.css">
		
		<link rel="stylesheet" href="assets/css/font-awesome.min.css">
		
		<link rel="stylesheet" href="assets/css/judge-view.css">
		<script src="assets/js/angular.min.js"></script>
		<script src="assets/js/angular-animate.js"></script>
		<script src="assets/js/angular-route.js"></script>

	</head>

	<body ng-app="scoring-sheet">

			<header>
				<div class="text-center">
					<h3 id="scoresheet-name"><b>UHAC</b> Manila</h3>
				</div>
			</header>
			
			<div id="section" ng-controller="sheet-ctrl" ng-init="init()">

				<h3 id="team-list" class="text-center" ng-hide="activeNow"><b>TEAM LIST</b></h3>

				<div class="row row-section">
					<div class="col-md-offset-3 col-md-6">

						<div ng-repeat="team in teams">

							<div ng-hide="activeNow">

								<button type="button" class="btn btn-default team-btn" ng-click="setScore(team)"><span id="btn-team-name" class="pull-left">{{team.team_name | uppercase}}</span> <span id="btn-team-score" class="pull-right"><b>{{team.total}} %</b></span></button>
								
							</div>

							<div ng-show="team.isActive">				
							
								<div>
									<button id="view-btn" ng-click="closeTeam(team)"><span class="glyphicon glyphicon-chevron-left"></span> View All Teams</button>
								</div>
								<br/>			
								
								<div class="text-center">
									<span id="team-lbl"><small style="color:darkgray;">TEAM </small>{{team.team_name | uppercase}}</span>
								</div>
								<br/>

								<div id="team-desc">
											
									<b>Project Name</b> <span>{{team.project_name}}</span><br/>
									<b>Project Description</b>		
										<div class="text-justify" style="padding: 0 10px;">
											<p>{{team.long_desc}}</p>
										</div>
								</div>
								
								<div>
									<form id="main-sheet-{{team.team_id}}">
										
										<h3><i>SCORING SHEET</i></h3>

										<hr/>

										<table style="width: 100%;">

											
											<tr ng-repeat="criteria in team.criteria" id="criteria-box" style="width: 100%; height: 70px;">

													<td style="width: 70%; word-wrap: true;">
														<span><b>{{criteria.criteria_desc}}</b></span><br/>
														<small><i>{{criteria.criteria_longdesc}}</i></small>
													</td>
																		
													<td class="text-right" style="width: 30%; word-wrap: true;">
														<input type="number" class="text-right" name="criteria-team{{team.team_id}}-criteria{{criteria.criteria_id}}" placeholder="0" min="1" max="{{criteria.criteria_weight}}" style="width: 50%;" ng-model="criteria.score_details.score" ng-change="updateScore(team)" value="{{criteria.score_details.score}}"/><span> / {{criteria.criteria_weight}}</span>
													</td>
												
												<!-- <div class="row row-section" class="criteria">
												
													<div class="pull-left" style="width: 70%; word-wrap: true;">
														<span><b>{{criteria.criteria_desc}}</b></span><br/>
														<small><i>{{criteria.criteria_longdesc}}</i></small>
													</div>
																		
													<div class="pull-right" style="width: 30%; word-wrap: true;">
														<h4><input type="number" class="text-right" name="criteria-team{{team.team_id}}-criteria{{criteria.criteria_id}}" placeholder="0" min="1" max="{{criteria.criteria_weight}}" style="width: 4em;" ng-model="criteria.score_details.score" ng-change="updateScore(team)" value="{{criteria.score_details.score}}"/><span> / {{criteria.criteria_weight}}</span></h4>
													</div>
												
												</div> -->

											</tr>

										</table>
											
										<hr/>
											
										<div class="row row-section">
											<h2 class="pull-left">TOTAL</h2><h2 class="pull-right">{{team.total}} %</h2>
											<br/><br/>
											<input type="submit" value="submit" id="submit-btn" ng-click="setScores(team)" />
										</div>
											
										<br/>
											
									</form>
								</div>
								
							</div>
							
						</div>
						
						<div class="text-center" ng-hide="activeNow">
							<a href="./helpers/logout.php"><button id="done-btn">DONE</button></a>
						</div>

					</div>
				</div>

			</div>
			
			
			<div id="footer" class="text-center">
				<small class="sub">POWERED BY</small><br/><strong>RED Wizard Events Management</strong><br/>&copy; 2017
			</div>
			
	</body>

	<script src="assets/js/jquery.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	<script src="assets/js/judge-view.js"></script>
	<script src="assets/js/judge-scoresheet.js"></script>

</html>