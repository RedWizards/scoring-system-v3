<?php require('./helpers/route-index.php');
?>

<!doctype html>
<html>

	<head>

		<title>UHAC Manila</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" href="assets/images/uhac.ico" type="image/ico" sizes="32x32">
		<link rel="stylesheet" href="assets/css/bootstrap.min.css">
		<link rel="stylesheet" href="assets/css/font-awesome.min.css">
		<link rel="stylesheet" href="assets/css/judge-view.css">

	</head>

	<body>

		<div id="event-cover" onclick="regJudgeOpen()">
			<table>
				<tr>
					<td>
						<div id="p1" class="text-center">
							<h1 id="event-name"><b>UHAC</b> Manila</h1>
							<small id="system-name"><b>SCORING SYSTEM</small>
						</div>
									
						<div id="center-logo">
							
						</div>
									
						<div id="p2" class="text-center">
							<br/>
							<small id="note">CLICK ANYWHERE TO CONTINUE</small>
							<br/><br/>

							<small class="sub">POWERED BY</small><br/>
							<span id="company"><strong>RED Wizard Events Management</strong></span><br/>
							<small class="sub"><i>Created by </i></small>
							<span id="dev-grp">Mamba Codes</span>
						</div>
					</td>
				</tr>
			</table>
		</div>



		<div id="register-judge">
			<table>
				<tr>
					<td>
						<a class="btn btn-simple" id="closeRegBtn" href="javascript:regJudgeClose()">&times;</a>
						<div id="p4" class="text-center">
							<p id="welcome-judge">WELCOME</h3>
							<!--
							<small class="sub">Please enter your name</small>
							-->
						</div>

						<div id="p3" class="text-center">
							<form class="form">
			        			<input placeholder="Please enter your name" type="text" id="input-container" class="form-control text-center" autofocus required><br/>
								<input id="register-button" class="btn" class="form-control" data-loading-text="Please Wait..." type="submit" value="SUBMIT">
			        		</form>
						</div>
					</td>
				</tr>
			</table> 
		</div>

	</body>

	<script src="assets/js/jquery.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	<script src="assets/js/angular.min.js"></script>
	<script src="assets/js/angular-animate.js"></script>
	<script src="assets/js/judge-view.js"></script>
	<script src="assets/js/angular-route.js"></script>

	<script>
	    $(function() { 
		    $("#register-button").click(function(event){
		    	event.preventDefault();
		    	if($('#input-container').val() != ""){
			        //ajax request
			        $.ajax({
					 	method: "POST",
					 	url: "database/create_judge.php",
					 	data: {
					 		judge_name: $('#input-container').val(),
					 		event_id: "1"
					 	}
					})
					.done(function( data ) {
					    $(location).attr('href', 'scoresheet.php');
					})
					.fail(function(xhr, textStatus, errorThrown) {
						alert(errorThrown.filename);
				        alert(xhr.responseText);
				    });
		    	}       
		    });
		});  
	</script>

</html>