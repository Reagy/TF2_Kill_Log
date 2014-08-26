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

$database->query('SELECT * FROM playerlog WHERE auth = :id');
$database->bind(':id', $_GET['id']);
$player = $database->resultset();

foreach ($player as $player) {
	foreach ($player as $key => $value) {
		$player[$key] = $value;
	}
}

?>

<table class="table table-bordered table-striped table-hover table-condensed display">
	<tbody>
		<tr>
			<td>Total Kills</td>
			<td><?php echo $player['kills']; ?></td>
		</tr>
		<tr>
			<td>Total Deaths</td>
			<td><?php echo $player['deaths']; ?></td>
		</tr>
		<tr>
			<td>Total Assists</td>
			<td><?php echo $player['assists']; ?></td>
		</tr>
		<tr>
			<td>Total Dominations</td>
			<td><?php echo $player['dominations']; ?></td>
		</tr>
		<tr>
			<td>Total Revenges</td>
			<td><?php echo $player['revenges']; ?></td>
		</tr>
		<tr>
			<td>Total Headshots</td>
			<td><?php echo $player['headshots']; ?></td>
		</tr>
		<tr>
			<td>Total Backstabs</td>
			<td><?php echo $player['backstabs']; ?></td>
		</tr>
		<tr>
			<td>Total Objects Built</td>
			<td><?php echo $player['obj_built']; ?></td>
		</tr>
		<tr>
			<td>Total Objects Destroyed</td>
			<td><?php echo $player['obj_destroy']; ?></td>
		</tr>
		<tr>
			<td>Total Players Teleported</td>
			<td><?php echo $player['tele_player']; ?></td>
		</tr>
		<tr>
			<td>Total CTF Picked</td>
			<td><?php echo $player['flag_pick']; ?></td>
		</tr>
		<tr>
			<td>Total CTF Captured</td>
			<td><?php echo $player['flag_cap']; ?></td>
		</tr>
		<tr>
			<td>Total CTF Defended</td>
			<td><?php echo $player['flag_def']; ?></td>
		</tr>
		<tr>
			<td>Total CP Captured</td>
			<td><?php echo $player['cp_cap']; ?></td>
		</tr>
		<tr>
			<td>Total CP Blocked</td>
			<td><?php echo $player['cp_block']; ?></td>
		</tr>
	</tbody>
</table>