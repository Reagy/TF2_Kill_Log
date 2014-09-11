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

$database->query('SELECT * , COUNT(event) AS total FROM teamlog WHERE (capper = :id OR defender = :id) GROUP BY event, map ORDER BY total DESC');
$database->bind(':id', $_GET['id']);
$events = $database->resultset();

foreach ($events as $key => $value) {
	if ($value['event'] == "cp_captured") {
		$cp[$key]['event'] = $value['event'];
		$cp[$key]['map'] = $value['map'];
		$cp[$key]['total'] = $value['total'];
	}
	if (preg_match("/(flag_)/", $value['event'])) {
		if ($value['event'] == "flag_cap") {
			$ctf[$key]['event'] = "Captures";
		}
		if ($value['event'] == "flag_def") {
			$ctf[$key]['event'] = "Defends";
		}
		$ctf[$key]['map'] = $value['map'];
		$ctf[$key]['total'] = $value['total'];
	}
}
?>

							<div class="row">
								<h1>Control Point</h1>
<?php if (isset($cp)): ?>
<?php foreach ($cp as $cp): ?>
								<div class="col-sm-3 getweapon">
									<div class="row">
										<div class="pull-left">
											<span class='fa-stack fa-2x'>
												<i class='fa fa-circle-thin fa-stack-2x'></i>
												<i class='fa-stack-1x' style='color:#222222'><?php echo $cp['total']; ?></i>
											</span>
										</div>
										<div class="pull-right" style="padding-right:10px">
											<h3><?php echo "Captures"; ?></h3>
										</div>
									</div>
									<div>
										<h4><?php echo $cp['map']; ?></h4>
									</div>
								</div>
<?php endforeach ?>
<?php endif ?>
							</div>

							<div class="row">
								<h1>Capture the Flag</h1>
<?php if (isset($ctf)): ?>
<?php foreach ($ctf as $ctf): ?>
								<div class="col-sm-3" style="border:1px solid #000000;height:100px">
									<div class="row">
										<div class="pull-left">
											<span class='fa-stack fa-2x'>
												<i class='fa fa-circle-thin fa-stack-2x'></i>
												<i class='fa-stack-1x' style='color:#222222'><?php echo $ctf['total']; ?></i>
											</span>
										</div>
										<div class="pull-right" style="padding-right:10px">
											<h3><?php echo $ctf['event']; ?></h3>
										</div>
									</div>
									<div>
										<h4><?php echo $ctf['map']; ?></h4>
									</div>
								</div>
<?php endforeach ?>
<?php endif ?>
							</div>
