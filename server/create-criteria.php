<!doctype html>
<html>

	<head>

		<title>UHAC Cebu</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" href="../assets/images/uhac.ico" type="image/ico" sizes="32x32">
		<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
		<script src="../assets/js/angular.min.js"></script>
		<script src="../assets/js/angular-animate.js"></script>
		<script src="../assets/js/angular-route.js"></script>

	</head>

	<body>

		<div style="margin: 50px;">


			<div class="row">
				<div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
					<h3>CREATE CRITERIA</h3><br/><br/>
					<form action="" method="">
						<div>
							<label for="criteria_desc">Name </label>
							<input type="text" name="criteria_desc" placeholder="..." class="form-control" required/>
							<br/>
						</div>
						<div>
							<label for="criteria_weight">Weight / Value</label>
							<input type="number" name="criteria_weight" placeholder="..." class="form-control" required/>
							<br/>
						</div>
						<div>
							<label for="criteria_longdesc">Description </label>
							<textarea name="criteria_longdesc" placeholder="..." class="form-control" required/></textarea>
							<br/>
						</div>
						<input type="submit" value="ADD CRITERIA" name="submit_criteria" class="form-control btn btn-primary" style="width: 300px;" />
					</form>
				</div>

				<div class="col-md-6 col-sm-6 col-xs-6 col-lg-6 text-center" style="border: dotted 3px #000; height: 500px;">

					<h3>CREATED CRITERIA</h3>
					<br/>
					<br/>

					
					
				</div>

			</div>

		</div>
			
	</body>

	<script src="../assets/js/jquery.js"></script>
	<script src="../assets/js/bootstrap.min.js"></script>

</html>