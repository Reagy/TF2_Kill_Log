<?php

if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || !strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
{
	header("Location: ../index.php?error=".urlencode("Direct access not allowed."));
	die();
}

//Include Config
include "config.php";

// Include database class
include 'database.class.php';

// Instantiate database.
$database = new Database();

$database->query('SELECT * FROM playerlog WHERE auth = :id');
$database->bind(':id', $_GET['id']);
$player = $database->resultset();

foreach ($player as $player) {
	foreach ($player as $key => $value) {
		$player[$key] = $value;
	}
}

$database->query('SELECT `weapons`.`slot` AS label, SUM(`kills`) AS value FROM `smalllog` 
	INNER JOIN `weapons` ON `weapons`.`weapon` = `smalllog`.`weapon` 
	WHERE `attacker` = :id GROUP BY `weapons`.`slot` ORDER BY value');
$database->bind(':id', $_GET['id']);
$slot = $database->resultset();

$s_total = 0;
foreach ($slot as $key => $value) {
	$s_total += $value['value'];
}

foreach ($slot as $key => $value) {
	if ($value['value'] != 0) {
		$slot[$key]['value'] = number_format($value['value']/$s_total*100,2);
	}
}

$database->query('SELECT `weapons`.`class` AS label, SUM(`kills`) AS value FROM `smalllog` 
	INNER JOIN `weapons` ON `weapons`.`weapon` = `smalllog`.`weapon` 
	WHERE `attacker` = :id GROUP BY `weapons`.`class` HAVING value > 0 ORDER BY value');
$database->bind(':id', $_GET['id']);
$class = $database->resultset();

$c_total = 0;
foreach ($class as $key => $value) {
	$c_total += $value['value'];
}

foreach ($class as $key => $value) {
	if ($value['value'] != 0) {
		$class[$key]['value'] = number_format($value['value']/$c_total*100,2);
	}
}

$slots = json_encode($slot);
$classes = json_encode($class);
?>

								<div class="col-sm-12" style="padding:0px">
									<table class="table table-bordered table-striped table-hover table-condensed display">
										<thead>
											<tr>
												<th>Stat</th>
												<th>Total</th>
											</tr>
										</thead>
										<tbody>
										<tr>
												<td>Total Plytime</td>
												<td><?php echo PlaytimeCon($player['playtime']); ?></td>
											</tr>
											<tr>
												<td>Total Kills</td>
												<td><?php echo number_format($player['kills']); ?></td>
											</tr>
											<tr>
												<td>Total Deaths</td>
												<td><?php echo number_format($player['deaths']); ?></td>
											</tr>
											<tr>
												<td>Total Assists</td>
												<td><?php echo number_format($player['assists']); ?></td>
											</tr>
											<tr>
												<td>Total Dominations</td>
												<td><?php echo number_format($player['dominations']); ?></td>
											</tr>
											<tr>
												<td>Total Revenges</td>
												<td><?php echo number_format($player['revenges']); ?></td>
											</tr>
											<tr>
												<td>Total Headshots</td>
												<td><?php echo number_format($player['headshots']); ?></td>
											</tr>
											<tr>
												<td>Total Backstabs</td>
												<td><?php echo number_format($player['backstabs']); ?></td>
											</tr>
											<tr>
												<td>Total Objects Built</td>
												<td><?php echo number_format($player['obj_built']); ?></td>
											</tr>
											<tr>
												<td>Total Objects Destroyed</td>
												<td><?php echo number_format($player['obj_destroy']); ?></td>
											</tr>
											<tr>
												<td>Total Players Teleported</td>
												<td><?php echo number_format($player['tele_player']); ?></td>
											</tr>
											<tr>
												<td>Total CTF Picked</td>
												<td><?php echo number_format($player['flag_pick']); ?></td>
											</tr>
											<tr>
												<td>Total CTF Captured</td>
												<td><?php echo number_format($player['flag_cap']); ?></td>
											</tr>
											<tr>
												<td>Total CTF Defended</td>
												<td><?php echo number_format($player['flag_def']); ?></td>
											</tr>
											<tr>
												<td>Total CP Captured</td>
												<td><?php echo number_format($player['cp_cap']); ?></td>
											</tr>
											<tr>
												<td>Total CP Blocked</td>
												<td><?php echo number_format($player['cp_block']); ?></td>
											</tr>
										</tbody>
									</table>
								</div>
								<div class="col-sm-6" style="text-align:center">
									<h1>Weapon Slot</h1>
									<div id="slot">
										
									</div>
								</div>
								<div class="col-sm-6" style="text-align:center">
									<h1>Weapon Class</h1>
									<div id="class">
										
									</div>
								</div>
								<script type="text/javascript">
									Morris.Donut({
										element: 'slot',
										data: <?php echo $slots; ?>,
										colors: [
											'#112F41',
											'#068587',
											'#6FB07F',
											'#FCB03C',
											'#FC5B3F',
											'#D73117'
								  	],
										formatter: function (y) { return y + "%" ;}
									});
								</script>
								<script type="text/javascript">
									Morris.Donut({
										element: 'class',
										data: <?php echo $classes; ?>,
										colors: [
											'#112F41',
											'#068587',
											'#6FB07F',
											'#FCB03C',
											'#FC5B3F',
											'#D73117'
								  	],
										formatter: function (y) { return y + "%" ;}
									});
								</script>
