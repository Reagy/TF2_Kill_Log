<?php

if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || !strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
{
	header("Location: ../index.php?error=".urlencode("Direct access no allowed."));
	die();
}

//Include Config
include "config.php";

// Include database class
include 'database.class.php';

// Instantiate database.
$database = new Database();

$database->query('SELECT attacker, victim, object, COUNT(object) AS total FROM objectlog WHERE (attacker = :id OR victim = :id) GROUP BY object ORDER BY total DESC');
$database->bind(':id', $_GET['id']);
$object = $database->resultset();

foreach ($object as $key => $value) {
	if ($value['attacker'] == $_GET['id']) {
		$killer[$key]['object'] = $value['object'];
		$killer[$key]['total'] = $value['total'];
	}
	if ($value['victim'] == $_GET['id']) {
		$victim[$key]['object'] = $value['object'];
		$victim[$key]['total'] = $value['total'];
	}
}
?>

							<div class="row">
								<h1>Buildings Destroyed</h1>
<?php if (isset($killer)): ?>
<?php foreach ($killer as $killer): ?>
								<div class="col-sm-3" style="border:1px solid #000000;height:110px">
									<div class="row">
										<div class="pull-left">
											<span class='fa-stack fa-2x'>
												<i class='fa fa-circle-thin fa-stack-2x'></i>
												<i class='fa-stack-1x' style='color:#222222'><?php echo $killer['total']; ?></i>
											</span>
										</div>
										<div class="pull-right" style="padding:5px 5px 0px">
											<img src="<?php echo 'images/objicons/'.$killer['object'].'.png'; ?>" alt="<?php echo $killer['object']; ?>" width="64px" height="64px">
										</div>
									</div>
									<div style="padding:0px 10px 0px">
										<h4><?php echo $killer['object']; ?></h4>
									</div>
								</div>
<?php endforeach ?>
<?php endif ?>
							</div>

							<div class="row">
								<h1>Buildings Lost</h1>
<?php if (isset($victim)): ?>
<?php foreach ($victim as $victim): ?>
								<div class="col-sm-3" style="border:1px solid #000000;height:110px">
									<div class="row">
										<div class="pull-left">
											<span class='fa-stack fa-2x'>
												<i class='fa fa-circle-thin fa-stack-2x'></i>
												<i class='fa-stack-1x' style='color:#222222'><?php echo $victim['total']; ?></i>
											</span>
										</div>
										<div class="pull-right" style="padding:5px 5px 0px">
											<img src="<?php echo 'images/objicons/'.$victim['object'].'.png'; ?>" alt="<?php echo $victim['object']; ?>" width="64px" height="64px">
										</div>
									</div>
									<div style="padding:0px 10px 0px">
										<h4><?php echo $victim['object']; ?></h4>
									</div>
								</div>
<?php endforeach ?>
<?php endif ?>
							</div>