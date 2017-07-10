<!DOCTYPE html>
<html>
	<head>
		<title>U:Hac Ultimate Pitching</title>
		
		<link rel="icon" href="../../assets/images/uhac.ico" type="image/ico" sizes="32x32">

		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<!-- Bootstrap -->
		<link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
		<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> -->

		<!-- Jquery -->
		<script src="../../assets/js/jquery.min.js"></script>
		<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script> -->

		<!-- Tether JS -->
		<script src="../../assets/js/tether.min.js"></script>
		<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script> -->

		<!-- Bootstrap JS -->
		<script src="../../assets/js/bootstrap.min.js"></script>
		<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->

		<link rel="stylesheet" href="../../assets/css/scoresheet.css">

		<!-- <link rel="stylesheet" href="../assets/css/style.css"> -->

		<script src="../../assets/js/angular.min.js"></script>
		
		<script src="../../assets/js/angular-animate.js"></script>

		<script src="sheet.js"></script>	
		
	</head>
	
	<body>
	<!--<div id="splash" onclick="closeSplash()">-->
	<div id="div-body">
		<header>
			<div id="ename">
			</div>
			<div class="text-center" id="event-name">
				<!-- Name of Event -->
				<span>U:HAC ULTIMATE PITCHING COMPETITION</span>
			</div>
		</header>
		
		<section ng-app="scoring-sheet" ng-controller="sheet-ctrl" ng-init="init()">
			<div id="score-sheet">
				
				<div class="text-center" ng-repeat="team in teams">
						
					<div ng-hide="activeNow" id="list-team">
					
						<div style="padding: 2em;">
							<div class="btn team-btn">
								<div class="row">
									<div class="col-md-3 text-center">
										<img class="img" style="width: 50%; height: 50%;" src="../../assets/images/logo2.png"/>
									</div>
									<div class="col-md-6">
										<h1></h1>
									</div>
									<div class="col-md-3 team-score">
										<h1></h1>
									</div>
								</div>
							</div>
						</div>
						
					</div>
				</div>
			</div>
		</section>
		
		
		<footer class="text-center">
			<div id="foot">
				<small>Powered by </small><strong>RED Wizard Events Management</strong> &copy; 2017
			</div>
		</footer>
		
		</div><!-- div-body -->
		<!--</div>-->
		
	</body>

</html>