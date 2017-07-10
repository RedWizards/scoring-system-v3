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
		<!--
		<link rel="stylesheet" href="../assets/css/style.css">
		-->
		<script src="../assets/js/angular.min.js"></script>

		<script src="../assets/js/ranking-view.js"></script>	

	</head>
	
	<body>
		<header class="container">
			<div class="row text-center">
				<div class="col-md-12" id="head-name">
					RANKING OF TEAMS
				</div>
			</div>
			
		</header>
		
		<section class="container" background-color>
		
			<div id="events-record" ng-app="ranking-view" ng-controller="view-ctrl">

				
				
			</div>
			
		</section>

		<footer class="container text-center">
		
			<p><small>Powered by </small><strong>RED Wizard Events Management</strong> &copy; 2017</p>
			
		</footer>
		
	</body>

</html>