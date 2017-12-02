<?php require_once('../helpers/admin-security.php');?>

<!DOCTYPE html>
<html>

	<head>
		<title>ADMIN - Scoring System</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" href="../assets/images/uhac.ico" type="image/ico" sizes="32x32">
		<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
		
		<link rel="stylesheet" href="../assets/css/font-awesome.min.css">
		<!--
		<link rel="stylesheet" href="../assets/css/judge-view.css">
	-->
	</head>
	
	<body ng-app="eventApp" ng-controller="eventAppCtrl">
	
		<div class="container text-center" style="padding-top: 30px; margin: 0; font-size: 2em; color: white; height: 100px; width: 100%; background-color: #DB3532;">
			<p>RED Wizards Scoring System</p>
		</div>
	
		<!-- Event List -->
		<div id="event-list">
		
			<h2>Events LIST</h2>
			<button ng-click="showCreateEvent()">NEW EVENT</button>
			<br/><br/><br/>
			<i>List of Events</i>
			
		</div>
	
		
		<div id="create-event" ng-init="initializeView()">
			
			<h2>Event Creation</h2>
			<button ng-click="closeCreateEvent()">CANCEL</button>
			
			<div class="mainview" ng-switch="phase">
			
				<!-- Event info -->
				<div ng-switch-when="phaseOne">

					<form>

						<div class="container">

						<h3>Event Information</h3>
						
						
						<input class="form-control" type="text" placeholder="Event Name" name="event-name"/><br/>
						<input class="form-control" type="text" placeholder="Event Host" name="event-host"/><br/>
						<input class="form-control" type="date" placeholder="Event Date" name="event-date"/><br/>
						<textarea class="form-control" rows="4" placeholder="Event Details" name="event-desc"></textarea><br/>
					
						<br/>
						<br/>

						</div>

					</form>

					<button ng-click="phaseOneBack()">Back</button><button ng-click="phaseOneNext()">Next</button>
					

				</div>
				
				<div ng-switch-when="phaseTwo">


					<br/>

					<h3>Criteria Creation</h3>
		
					<input type="button" value="DEFAULT"/> 
					<input type="button" value="CUSTOM"/><br/><br/>
					
					<p>Criteria here</p>
	
					<br/>
					<br/>

					<button ng-click="phaseTwoBack()">Back</button><button ng-click="phaseTwoNext()">Next</button>

				</div>
				
				<div ng-switch-when="phaseThree">

					<h3>Register Teams</h3>
		
					<input type="text" placeholder="Project Name"/><br/>
					<input type="text" placeholder="Project Desc"/><br/>
					<input type="text" placeholder="Team Name"/><br/>
					<input type="text" placeholder="Member 1"/><br/>
					<input type="text" placeholder="Member 2"/>
					<input type="button" value="ADD MEMBER"/><br/>
					
					<br/>
					<input type="button" value="ADD THIS TEAM"/><br/><br/>

					<button ng-click="phaseThreeBack()">Back</button><button disabled>Finish</button>

				</div>
				
				<div ng-switch-default>
					<h1>Default</h1>
				</div>
				
			</div>
			
		</div>
		
	</body>

	<script src="../assets/js/jquery.js"></script>
	<script src="../assets/js/bootstrap.min.js"></script>
	<script src="../assets/js/angular.min.js"></script>
	<script src="../assets/js/angular-animate.js"></script>
	<script src="../assets/js/angular-route.js"></script>
	<script src="../assets/js/admin-view.js"></script>
	<!--
	<script src="../assets/js/judge.js"></script>
	-->
</html>