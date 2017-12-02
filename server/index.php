<?php require_once('../helpers/admin-index.php'); ?>

<!doctype html>
<html>

	<head>

		<title>UHAC Manila | ADMIN</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" href="../assets/images/uhac.ico" type="image/ico" sizes="32x32">
		<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
		<link rel="stylesheet" href="../assets/css/judge-view.css">
		<link rel="stylesheet" href="../assets/css/font-awesome.min.css">

	</head>

	<body>

		<div id="register-judge">
			<table>
				<tr>
					<td>
						<div id="p4" class="text-center">
							<h4 class="sub">Authorized Personnel Only</h4>
						</div>

						<div id="p3" class="text-center">
							<form class="form" method='post' action='../helpers/admin-login.php'>
			        			<input placeholder="PASSWORD" type="password" id="password" name="password" class="form-control text-center" autofocus required><br/>
								<input id="register-button" class="btn" class="form-control" data-loading-text="Please Wait..." type="submit" value="ENTER">
			        		</form>
						</div>
					</td>
				</tr>
			</table> 
		</div>

	</body>

	<script src="../assets/js/jquery.js"></script>
	<script src="../assets/js/bootstrap.min.js"></script>
	<script src="../assets/js/angular.min.js"></script>

</html>