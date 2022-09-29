<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Schedule a meeting</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<!-- Jquery-->
		<script src="https://code.jquery.com/jquery-3.6.1.slim.js"></script>
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
				<form action="">
					<h3>Schedule a meeting</h3>
						<div class="form-wrapper">
							<label for="">Select the users</label>
							<select class="js-example-basic-multiple form-control" name="users[]" multiple="multiple" required>
								<option value="1">Jose Lopez</option>
								<option value="2">Pamela Olmedo</option>
								<option value="3">Elias Rosas</option>
								<option value="4">Juan Prado</option>
								<option value="5">Ligia Venedeto</option>
								<option value="6">Antonio Hernandez</option>
								<option value="7">Maria Rivera</option>
							</select>
						</div>
					<div class="form-wrapper">
						<label for="">Meeting name</label>
						<input type="text" class="form-control" id="meeting_name" name="meeting_name" required>
					</div>
					<div class="form-wrapper">
						<label for="">Start time</label>
						<input type="datetime-local" id="start_time" name="start_time" class="form-control" required>
					</div>
					<div class="form-wrapper">
						<label for="">End time</label>
						<input type="datetime-local" id="end_time" name="end_time" class="form-control" required>
					</div>
					<button>Save meeting</button>
				</form>
			</div>
		</div>
		
	</body>
</html>
<script type="text/javascript">
$(document).ready(function() {
    $('.js-example-basic-multiple').select2();
});
</script>