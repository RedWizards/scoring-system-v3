<?php require_once('../helpers/admin-security.php');?>

<!doctype html>
<html>

	<head>

		<title>UHAC Manila</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" href="../assets/images/uhac.ico" type="image/ico" sizes="32x32">
		<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
		<script src="../assets/js/angular.min.js"></script>
		<script src="../assets/js/angular-animate.js"></script>
		<script src="../assets/js/angular-route.js"></script>

	</head>

	<body>

		<div style="margin: 50px;">

			<h3>TEAM REGISTRATION</h3><br/><br/>

			<a href="./index.php">Score Board</a>

			<div>
				<form id="reg-form">
					<div>
						<label for="team_name">Team Name </label>
						<input type="text" name="team_name" placeholder="..." class="form-control" required/>
						<br/>
					</div>
					<div>
						<label for="project_name">Project Name </label>
						<input type="text" name="project_name" placeholder="..." class="form-control" required/>
						<br/>
					</div>
					<div>
						<label for="project_type">Project Type</label>
						<input type="text" name="project_type" placeholder="..." class="form-control" required/>
						<br/>
					</div>
					<div>
						<label for="short_desc">Short Description</label>
						<textarea name="short_desc" placeholder="..." class="form-control" required></textarea>
						<br/>
					</div>
					<div>
						<label for="long_desc">Long Description</label>
						<textarea name="long_desc" placeholder="..." class="form-control" required></textarea>
						<br/>
					</div>
					<br/>
					<input type="submit" value="ADD TEAM" name="reg_team" class="form-control btn btn-primary" style="width: 300px;" />
				</form>
			</div>

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