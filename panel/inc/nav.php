<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo $Title; ?></title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/font-awesome.css" rel="stylesheet">
	<link href="css/morris.css" rel="stylesheet">
	<link href="css/dataTables.bootstrap.css" rel="stylesheet">
	<link href="css/stats.css" rel="stylesheet">
	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<!-- DataTables JS -->
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap.js"></script>
	<!-- Morris.js -->
	<script src="js/raphael-min.js"></script>
	<script src="js/morris.min.js"></script>
</head>
<body>
	<div class="container">
		<div class="jumbotron">
			<div class="row">
				<div class="col-sm-11">
					<div class="pull-left"><h1><?php echo "Stats - ".ucwords(basename($_SERVER['SCRIPT_FILENAME'], ".php")); ?></h1></div>
				</div>
			</div>
		</div>
		<nav class="navbar navbar-inverse" role="navigation">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="<?php echo $Home; ?>">Home</a>
			</div>
			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="menu">
				<ul class="nav navbar-nav">
					<li><a href="index.php">Current</a></li>
					<li><a href="players.php">Players</a></li>
					<li><a href="weapons.php">Weapons</a></li>
					<li><a href="actions.php">Actions</a></li>
					<li><a href="streaks.php">Killstreaks</a></li>
					<li><a href="maps.php">Maps</a></li>
				</ul>
			</div><!-- /.navbar-collapse -->
		</nav>