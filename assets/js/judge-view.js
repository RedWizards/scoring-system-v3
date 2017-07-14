function regJudgeOpen() {
	document.getElementById("event-cover").style.height = 0;
	document.getElementById("event-cover").style.opacity = 0;
}

function regJudgeClose() {
	document.getElementById("event-cover").style.height = "100%";
	document.getElementById("event-cover").style.opacity = 1;
}

function reset(){
	var response = confirm('Are you sure to reset the scoring?');

	if (response == true) {
	    window.location.href = '.././database/reset.php';
	} else {
	    txt = "You pressed Cancel!";
	}
}
/*
function submitName(){
	if($('#input-container').val() == ""){

	} else {
		$(this).button('loading').queue(function() {
			            
		}); 
	}       
}
*/