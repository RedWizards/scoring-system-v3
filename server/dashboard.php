<?php require_once('../helpers/admin-security.php');?>

<!doctype html>
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
		<script src="../assets/js/judge-view.js"></script>
		<link rel="stylesheet" href="../assets/css/font-awesome.min.css">

	</head>

	<body>


			<header>
				<div class="text-center">
					<h3 id="scoresheet-name"><b>ADMINISTRATOR</b> VIEW</h3>
				</div>
			</header>

			<div class="row outer-row">

				<h2 class="text-center">UHAC <strong>Manila</strong></h2>
				<br/><br/>

				<div class="col-md-offset-3 col-md-6">

					<div>
						<div class="col-md-4 col-sm-4 col-xs-12 text-center option-outline">
							<a href="criteria.php">
								<div class="inside-option">
									<span class="option-title">CRITERIA</span>
								</div>
							</a>
						</div>

						<div class="col-md-4 col-sm-4 col-xs-12 text-center option-outline">
							<a href="team.php">
								<div class="inside-option">
									<span class="option-title">TEAM</span>
								</div>
							</a>
						</div>

						<div class="col-md-4 col-sm-4 col-xs-12 text-center option-outline">
							<a href="scoreboard.php">
								<div class="inside-option">
									<span class="option-title">SCORE BOARD</span>
								</div>
							</a>
						</div>

						<div class="col-md-4 col-sm-4 col-xs-12 text-center option-outline">
							<a href="pitch.php">
								<div class="inside-option">
									<span class="option-title">ORDER OF PITCHING</span>
								</div>
							</a>

						</div>

						<div class="col-md-4 col-sm-4 col-xs-12 text-center option-outline">
							<a href="javascript:reset()">
								<div class="inside-option">
									<span class="option-title">RESET</span>
								</div>
							</a>
						</div>

						<div class="col-md-4 col-sm-4 col-xs-12 text-center option-outline">
							<a href="./logout.php">
								<div class="inside-option">
									<span class="option-title">LOG OUT</span>
								</div>
							</a>
						</div>

					</div>

						<!-- <div class="col-md-6 col-sm-6 col-xs-12 text-center option-outline">
							<a href="#">
								<div class="inside-option">
									<span class="option-title">OPERATIONS</span>
								</div>
							</a>
						</div> -->

				</div>

			</div>

			
			<div id="footer" class="text-center">
				<small class="sub">POWERED BY</small><br/><strong>RED Wizard Events Management</strong><br/>&copy; 2017
			</div>
			
	</body>

	<script src="../assets/js/jquery.js"></script>
	<script src="../assets/js/bootstrap.min.js"></script>
	<script src="../assets/js/judge-scoresheet.js"></script>

</html>