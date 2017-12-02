<?php require_once('../helpers/admin-security.php');?>

<!DOCTYPE html>
<html>

	<head>

		<title>UHAC Manila</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" href="../assets/images/uhac.ico" type="image/ico" sizes="32x32">
		<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
		<script src="../assets/js/angular.min.js"></script>
		<script src="../assets/js/angular-animate.js"></script>
		<script src="../assets/js/angular-route.js"></script>
		<link rel="stylesheet" href="../assets/css/admin-view.css">
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
				<h2 class="text-center">ADD <strong>TEAM</strong></h2>

				<br/><br/>

				<div class="col-md-offset-3 col-md-6">

					<div>
						<form id="reg-form">
							<div>
								<label for="team_name">Team Name </label>
								<input type="text" name="team_name" placeholder="..." class="form-control input-team" required/>
								<br/><br/>
							</div>
							<div>
								<label for="project_name">Project Name </label>
								<input type="text" name="project_name" placeholder="..." class="form-control input-team" required/>
								<br/><br/>
							</div>
							<div>
								<label for="project_type">Project Type</label>
								<input type="text" name="project_type" placeholder="..." class="form-control input-team" required/>
								<br/><br/>
							</div>
							<div>
								<label for="short_desc">Short Description</label>
								<textarea name="short_desc" placeholder="..." class="form-control input-team" required></textarea>
								<br/><br/>
							</div>
							<div>
								<label for="long_desc">Long Description</label>
								<textarea name="long_desc" placeholder="..." class="form-control input-team" required></textarea>
								<br/><br/>
							</div>
							<br/>
							<div class="text-center">
								<input type="submit" value="ADD TEAM" name="reg_team" id="submit-team" class="form-control btn btn-primary" />
							</div>
						</form>
					</div>


				</div>

			</div>

			<div id="footer" class="text-center">
				<small class="sub">POWERED BY</small><br/><strong>RED Wizard Events Management</strong><br/>&copy; 2017
			</div>
			
	</body>

	<script src="../assets/js/jquery.js"></script>
	<script src="../assets/js/bootstrap.min.js"></script>
	<script type="text/javascript">
		$("#reg-form").submit(function(e) {
			e.preventDefault(); // avoid to execute the actual submit of the form.

		    var url = "../database/register_team.php"; // the script where you handle the form input.

		    var formData = $("#reg-form").serialize();

		    $.ajax({
		        type: "POST",
		        url: url,
		        data: formData, // serializes the form's elements.
		    })
		    .done(function(data){

		    	formData = formData + "&team_id=" + JSON.parse(data).id + "&event_id=1";

				$.ajax({
			        type: "POST",
			        url: '../database/add_project.php',
			        data: formData , // serializes the form's elements.
			    })
			    .done(function(data){
					alert('Successfully added Team');
					$("#reg-form").trigger("reset");
				})
				.fail(function(xhr, textStatus, errorThrown) {
					console.log(textStatus);
					console.log(errorThrown);
				    console.log(xhr.responseText);
				});

			})
			.fail(function(xhr, textStatus, errorThrown) {
				console.log(textStatus);
				console.log(errorThrown);
			    console.log(xhr.responseText);
			});

		    
		});
	</script>

</html>	