<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>TEST</title>
  <link href="../css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/font-awesome.min.css" rel="stylesheet">
  <link href="../css/stats.css" rel="stylesheet">
  <script src="../js/jquery-1.11.1.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
</head>
<body>
	<div class="container">
		<div class="jumbotron">
			<h1>Update & Install Panel</h1>
		</div>
		<div class="stats-body">
			<div class="col-sm-12">
				<br>
				<button id="items" type="button" class="btn btn-primary btn-lg btn-block">Items</button>
				<br>
				<button id="profiles" type="button" class="btn btn-primary btn-lg btn-block">Profiles</button>
				<br>
				<button id="weapons" type="button" class="btn btn-primary btn-lg btn-block">Weapons</button>
			</div>
		</div>
		<?php include '../inc/footer.php'; ?>
	</div>
<script>
	$('#items').on('click', function(event) {
		$("#items").addClass("active");
		$.ajax({
			type: "GET",
			url: "install.php?query=items",
			success: function(msg){
				$("#items").addClass("disabled").removeClass('btn-primary').removeClass('active').addClass('btn-success');;
				alert('Success!');
			}
		});
	});
	$('#profiles').on('click', function(event) {
		$("#profiles").addClass("active");
		$.ajax({
			type: "GET",
			url: "install.php?query=profiles",
			success: function(msg){
				$("#profiles").addClass("disabled").removeClass('btn-primary').removeClass('active').addClass('btn-success');
				alert('Success!');
			}
		});
	});
	$('#weapons').on('click', function(event) {
		$("#profiles").addClass("active");
		$.ajax({
			type: "GET",
			url: "install.php?query=weapons",
			success: function(msg){
				$("#weapons").addClass("disabled").removeClass('btn-primary').removeClass('active').addClass('btn-success');
				alert('Success!');
			}
		});
	});
</script>
</body>
</html>