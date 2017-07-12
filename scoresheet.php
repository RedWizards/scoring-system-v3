<?php
	require("helpers/security.php"); 
?>

<!doctype html>
<html>

	<head>

		<title>UHAC Cebu</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" href="assets/images/uhac.ico" type="image/ico" sizes="32x32">
		<link rel="stylesheet" href="assets/css/bootstrap.min.css">
		<!--
		<link rel="stylesheet" href="assets/css/font-awesome.min.css">
		-->
		<link rel="stylesheet" href="assets/css/judge-view.css">
		<script src="assets/js/angular.min.js"></script>
		<script src="assets/js/angular-animate.js"></script>
		<script src="assets/js/angular-route.js"></script>

	</head>

	<body ng-app="scoring-sheet">

			<header>
				<div class="text-center">
					<h3 id="scoresheet-name"><b>UHAC</b> Cebu</h3>
				</div>
			</header>
			
			<section ng-controller="sheet-ctrl" ng-init="init()">

				<h3 id="team-list" class="text-center">TEAM LIST</h3>

				<div class="col-md-offset-3 col-md-6" ng-repeat="team in teams">
					
					<div class="btn-team-row">
							<button type="button" class="btn btn-default team-btn"><span id="btn-team-name" class="pull-left">{{team.team_name |  uppercase}}</span> <span id="btn-team-score" class="pull-right"><b>{{team.total}} %</b></span></button>
					</div>

					<!--
					<div ng-repeat="team in teams">
						
						<div ng-hide="activeNow">
						
							<div class="row" id="sheet-panel" class="text-center">
								<button class="btn btn-primary col ng-click="setScore(team)">
									<div class="row">
										<div class="col-x" id="col-lbl">
											<span>{{team.team_name | uppercase}}</span>
										</div>
										<div class="col-md-3 team-score" id="col-lbl">
											<span><b>{{team.total}} %</b></span>
										</div>
										
									</div>
								</button>
							</div>

						
						</div>
						
					</div>
					-->
						<!--
						<div ng-show="team.isActive" id="team-board">				
							
							<div class="row">
								
								<div class="col-md-12">
									<button class="view-btn pull-left" ng-click="closeTeam(team)"><span class="glyphicon glyphicon-chevron-left"></span> View All Teams</button>
								</div>
								
							</div>
							
							
							<div class="row team-desc">
								<span id="team-lbl"><small style="color:darkgray;">TEAM </small>{{team.team_name | uppercase}}</span>
							</div>
						
						
							<div class="row">
							
								<div class="col-md-6 sheet-div">
								
									<div id="team-desc">
										
										<div class="row proj-name">
											<span>{{team.project_name}}</span>
										</div>
										
										<div class="row long-desc text-justify">
											<p>{{team.long_desc}}</p>
										</div>
										
										<div class="row team-members text-left">
											<p><b>DEVELOPERS</b></p>

											<ul>
												
												<li ng-repeat="member in team.members">
													{{member.participant_firstName}} {{member.participant_lastName}}
												</li>
											</ul>
										</div>
									
									</div>
									
								</div>
							
								<div class="col-md-6 sheet-div">
								
									<div class="row team-desc" id="score-title">
										<span>SCORING SHEET</span>
									</div>
									
									<div class="row" id="sheet-title">
									
										<div class="col-md-6">
											<h4>CRITERIA</h4>
										</div>
										
										<div class="col-md-2">
										
										</div>
										
										<div class="col-md-4">
											<h4>SCORE</h4>
										</div>
										
									</div>
									
									<hr/>
									
									
									<div class="row" id="criteria" ng-repeat="criteria in team.criteria">
											
											<div class="col-md-1" id="cri-id">
												<h5>{{criteria.criteria_id}}</h5>
											</div>
											
											<div class="col-md-7 text-left">
												<span><b>{{criteria.criteria_desc}}</b></span><br/>
												<small><i>{{criteria.criteria_longdesc}}</i></small>
											</div>
													
											<div class="col-md-4" style="padding: 1em 0 0 0;">
												<h4><input type="number" class="text-right" name="criteria-{{criteria.criteria_id}}" placeholder="0" max="25" min="0" style="width: 4em;" ng-model="criteria.score_details.score" ng-change="updateScore(team)" value="{{criteria.score_details.score}}"/><span> / {{criteria.criteria_weight}}</span></h4>
											</div>
											
										
									</div>
									
									<hr/>
									
									<div class="row">
									
										<div class="col-md-6">
											<h4>TOTAL</h4>
										</div>
										
										<div class="col-md-2">
										
										</div>
										
										<div class="col-md-4">
											<h2>{{team.total}} %</h2>
										</div>
										
									</div>
									
									<br/>
									
									
									<div class="row">
										<div class="col-md-12" id="remarks-row">
											<textarea placeholder="Remarks" id="remarks" style="padding: 5px; width: 80%; height: 5em;"></textarea>
										</div>
									</div>
									
									<div class="row" >
									
											<button class="submit-score" ng-click="setScores(team)">SUBMIT</button>
										
									</div>
									
									<br/>
									
								</div>
							
							</div>
						
						</div>
					
					</div>
					<div class="text-center" ng-hide="activeNow">
						<a href="./helpers/logout.php"><button id="done-btn">DONE</button></a>
					</div>
					
					
					-->
				</div>

			</section>
			
			
			<footer class="text-center">
				<small class="sub">POWERED BY</small><br/><strong>RED Wizard Events Management</strong><br/>&copy; 2017
			</footer>
			
	</body>

	<script src="assets/js/jquery.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	<script src="assets/js/judge-view.js"></script>
	<script src="assets/js/judge-scoresheet.js"></script>

</html>