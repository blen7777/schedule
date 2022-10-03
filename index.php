<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Schedule a meeting</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Jquery-->
	<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
	<!-- END Jquery -->

	<!-- MATERIAL DESIGN ICONIC FONT -->
	<link rel="stylesheet" href="fonts/material-design-iconic-font/css/material-design-iconic-font.min.css">

	<!-- STYLE CSS -->
	<link rel="stylesheet" href="css/style.css">

	<!-- SELECT2 USED TO SELECT THE USERS -->
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	<!-- END SELECT2 -->
</head>

<body>

	<div class="wrapper" style="background-image: url('images/bg-registration-form-2.jpg');">
		<div class="inner">
			<form id="form-schedule">
				<h3>Schedule a meeting (Expertel)</h3>
				<div class="form-wrapper">
					<label for="">Select the users</label>
					<select class="js-example-basic-multiple form-control" id="users" name="users[]" multiple="multiple" required>
						<option value="1">Jose Lopez</option>
						<option value="2">Pamela Olmedo</option>
						<option value="3">Elias Rosas</option>
						<option value="4">Juan Prado</option>
						<option value="5">Ligia Venedeto</option>
						<option value="6">Antonio Hernandez</option>
						<option value="7">Maria Rivera</option>
					</select>
					<input type="hidden" name="userList" id="userList">
				</div>
				<div class="form-wrapper">
					<label for="">Meeting name</label>
					<input type="text" class="form-control" id="meeting_name" name="meeting_name">
				</div>
				<div class="form-wrapper">
					<label for="">Start time</label>
					<input type="datetime-local" id="start_time" min="now()" name="start_time" class="form-control">
				</div>
				<div class="form-wrapper">
					<label for="">End time</label>
					<input type="datetime-local" id="end_time" name="end_time" class="form-control">
				</div>
				<span id="save-schedule" class="btn-saved">Save meeting</span>
			</form>
			<div class="clearfix"></div>
			<div class="load-conflicts"></div>
		</div>
	</div>

</body>

</html>
<script type="text/javascript">
	
	$(document).ready(function() 
	{
		// disable the end_time
		$("#end_time").hide();

		var today = new Date();
    	var past = new Date(today.setDate(today.getDate())).toISOString().slice(0, 16);
    	var today = new Date().toISOString().slice(0, 16);
		document.getElementsByName("start_time")[0].min = past;

		$('.js-example-basic-multiple').select2();
		$('#users').on("change", function(e) 
		{
            $("#userList").val($("#users").val());
 		});

		$('#start_time').on("change", function(e) 
		{
			$("#end_time").show();
			document.getElementsByName("end_time")[0].min = $("#start_time").val();
 		});

		$("#save-schedule").click(function() 
		{
			var users =  $("#userList").val();
			var start_time = $("#start_time").val();
			var end_time = $("#end_time").val();
			var meeting_name = $("#meeting_name").val();
			if(users=="")
			{
				alert("User is required!");
				return false;
			}
			if(meeting_name=="")
			{
				alert("Meeting name is required!");
				return false;
			}
			if(start_time=="")
			{
				alert("Start time is required!");
				return false;
			}
			if(end_time=="")
			{
				alert("End time is required!");
				return false;
			}
			$.ajax({
				type: "POST",
				url: 'connection/connect.php',
				data: {
					action: 'scheduleMeeting',
					users: users,
					start_time: start_time,
					end_time: end_time,
					meeting_name: meeting_name
				},
				dataType: "html",
				success: function(response) 
				{
					$(".load-conflicts").show();
					$(".load-conflicts").html(response);
					$(".load-conflicts").fadeOut( 9000, function() {
						window.location.reload();
					});
                   
				}
			});
		});

	
	});
</script>