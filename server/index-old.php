<!DOCTYPE html>
<html>
	<head>
		<title>Scoring System</title>
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

		<link rel="stylesheet" href="../assets/css/scoring-system.css">

		<link rel="stylesheet" href="../assets/css/style.css">

		<script src="../assets/js/angular.min.js"></script>

		<script src="../assets/js/scoring-system.js"></script>	

	</head>
	<body>
		<header class="container">
			<div class="row text-center">
				<div class="col-md-3" id="head-name">
					EVENTS
				</div>
		
				<div class="col-md-3">
					<button class="btn btn-default" id="new-event-btn">NEW EVENT</button>
				</div>
				
			</div>
			
		</header>
		
		<section class="container">
		
			<div id="events-record" ng-app="score-app" ng-controller="score-ctrl" ng-init="init()">

				<!-- Shows when there is no record of events -->
				<div id="no-event-record" ng-hide="hasRecord">
					<i>No Events Recorded</i>
				</div>
				
				<!-- Shows the list of events recorded -->
				<div class="align-center" id="event-list" ng-show="hasRecord">
					<div id="accordion">
						<div class="panel panel-default" ng-repeat="record in records">
							  <div class="panel-heading">
								<h4 class="panel-title">
								  <a id="" href="#"><b>{{record.event_name}}</b></a><small><i> by {{record.event_host}}</i></small>
								  <a data-toggle="collapse" data-parent="#accordion" href="#{{record.event_id}}" title="Event Description"><span class="glyphicon glyphicon-info-sign pull-right"></span></a>
								</h4>
							  </div>
							  <div id="{{record.event_id}}" class="panel-collapse collapse">
								<div class="panel-body">
									<h6>{{record.event_date}}</h6>
									<br/>
									<h5>{{record.event_desc}}</h5>
								</div>
							  </div>
						</div>
					</div>
				</div>
				
			</div>
			
		</section>

		<footer class="container text-center">
		
			<p><small>Powered by </small><strong>RED Wizard Events Management</strong> &copy; 2017</p>
			
		</footer>
		
	</body>

</html>