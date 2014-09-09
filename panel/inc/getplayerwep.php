<?php

if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || !strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
{
	header("Location: ../index.php?error=".urlencode("Direct access no allowed."));
	die();
}

$custkill = array('0' => 'None', '1' => 'Headshot', '2' => 'Backstab', '3' => 'Burning', '4' => 'Fix', '5' => 'Minigun', '6' => 'Suicide', '7' => 'Hadouken', '8' => 'Flare', '9' => 'Taunt', '10' => 'Taunt', '11' => 'Team Penetration', '12' => 'Plaer Penetration', '13' => 'Taunt', '14' => 'Penetration Headshot', '15' => 'Taunt', '16' => 'Telefrag', '17' => 'Burning Arrow', '18' => 'Flying Burn', '19' => 'Pumpkin Bomb', '20' => 'Decapitation', '21' => 'Taunt', '22' => 'Baseball', '23' => 'Charge Impact', '24' => 'Taunt', '25' => 'Air Sticky', '26' => 'Defensive Sticky', '27' => 'Pickaxe', '28' => 'Direct Hit', '29' => 'Taunt', '30' => 'Wrangled', '31' => 'Sticky', '32' => 'Revenge Crit', '33' => 'Taunt', '34' => 'Bleed', '35' => 'Golden', '36' => 'Carry Building', '37' => 'Combo', '38' => 'Taunt', '39' => 'Fish Kill', '40' => 'Hurt', '41' => 'Boss Decapitation', '42' => 'Explosion', '43' => 'Aegis', '44' => 'Flare Explode', '45' => 'Stomp', '46' => 'Plasma', '47' => 'Deflected Plasma', '48' => 'Plasma Gib', '49' => 'Practice Sticky', '50' => 'Eyeball', '51' => 'Hitman Headshot', '52' => 'Taunt', '53' => 'Flare', '54' => 'Cleaver', '55' => 'Crit Cleaver', '56' => 'Red Tape', '57' => 'Player Bomb', '58' => 'Merasmus Grenade', '59' => 'Merasmus Zap', '60' => 'Merasmus Decapitation', '61' => 'Cannonball Push');

//Include Config
include "config.php";

// Include database class
include 'database.class.php';

// Instantiate database.
$database = new Database();

$database->query('SELECT * FROM weapons WHERE weapon = :wep');
$database->bind(':wep', $_GET['wep']);
$weapon = $database->single();

$database->query('SELECT attacker, ateam, victim, vteam, weapon, dominated, revenge FROM killlog WHERE (attacker = :id OR victim = :id) AND weapon = :wep');
$database->bind(':id', $_GET['id']);
$database->bind(':wep', $_GET['wep']);
$log = $database->resultset();

$database->query('SELECT victim, COUNT(victim) AS total, playerlog.`name` AS name FROM killlog INNER JOIN playerlog
ON killlog.`victim` = playerlog.`auth` WHERE attacker = :id AND weapon = :wep GROUP BY victim ORDER BY total DESC');
$database->bind(':id', $_GET['id']);
$database->bind(':wep', $_GET['wep']);
$highkills = $database->single();

$database->query('SELECT attacker, COUNT(attacker) AS total, playerlog.`name` AS name FROM killlog INNER JOIN playerlog
ON killlog.`attacker` = playerlog.`auth` WHERE victim = :id AND weapon = :wep GROUP BY attacker ORDER BY total DESC');
$database->bind(':id', $_GET['id']);
$database->bind(':wep', $_GET['wep']);
$highdeaths = $database->single();

$database->query('SELECT victim, COUNT(victim) AS total, playerlog.`name` AS name FROM killlog INNER JOIN playerlog
ON killlog.`victim` = playerlog.`auth` WHERE attacker = :id AND weapon = :wep AND dominated = 1 GROUP BY victim ORDER BY total DESC');
$database->bind(':id', $_GET['id']);
$database->bind(':wep', $_GET['wep']);
$highdom = $database->single();

$database->query('SELECT wep_ks FROM killlog WHERE attacker = :id AND weapon = :wep AND wep_ks >= 1 ORDER BY wep_ks DESC');
$database->bind(':id', $_GET['id']);
$database->bind(':wep', $_GET['wep']);
$highks = $database->single();

$database->query('SELECT customkill, COUNT(customkill) AS total FROM killlog WHERE attacker = :id AND weapon = :wep GROUP BY customkill ORDER BY total DESC');
$database->bind(':id', $_GET['id']);
$database->bind(':wep', $_GET['wep']);
$customkill = $database->resultset();

$attacker = array('kills' => 0,'dominated' => 0,'revenge' => 0,'red' => 0,'blue' => 0);
$victim = array('dominated' => 0,'revenge' => 0,'deaths' => 0,'red' => 0,'blue' => 0);

foreach ($log as $key => $value) {
	if ($value['attacker'] == $_GET['id']) {
		$attacker['kills']++;
		$attacker['dominated'] += $value['dominated'];
		$attacker['revenge'] += $value['revenge'];
		if ($value['ateam'] == '2') {
			$attacker['red']++;
		}
		if ($value['ateam'] == '3') {
			$attacker['blue']++;
		}
	}
	if ($value['victim'] == $_GET['id']) {
		$victim['deaths']++;
		$victim['dominated'] += $value['dominated'];
		$victim['revenge'] += $value['revenge'];
		if ($value['vteam'] == '2') {
			$victim['red']++;
		}
		if ($value['vteam'] == '3') {
			$victim['blue']++;
		}
	}
}

foreach ($customkill as $key => $value) {
	foreach ($custkill as $ck => $cv) {
		if ($value['customkill'] == $ck) {
			$customkill[$key]['customkill'] = $cv;
		}
	}
}
?>

<div class="modal-dialog modal-lg">
 	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">
				<span aria-hidden="true">&times;</span>
				<span class="sr-only">Close</span>
			</button>
			<h4 class="modal-title" id="myModalLabel"><?php echo "Recent Activity for ".$weapon['name']; ?></h4>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="col-sm-8">
					<table class="table table-bordered table-striped table-condensed">
						<tbody class="playermodal">
							<tr>
								<td><h5>Most Kills on</h5></td>
								<td><h5><a href="player.php?id=<?php echo $highkills['victim']; ?>"><?php echo htmlentities($highkills['name']); ?></a></h5></td>
								<td><h5><?php echo number_format($highkills['total']); ?></h5></td>
							</tr>
							<tr>
								<td><h5>Most Dominations on</h5></td>
								<td><h5><a href="player.php?id=<?php echo $highdom['victim']; ?>"><?php echo htmlentities($highdom['name']); ?></a></h5></td>
								<td><h5><?php echo number_format($highdom['total']); ?></h5></td>
							</tr>
							<tr>
								<td><h5>Most Deaths by</h5></td>
								<td><h5><a href="player.php?id=<?php echo $highdeaths['attacker']; ?>"><?php echo htmlentities($highdeaths['name']); ?></a></h5></td>
								<td><h5><?php echo number_format($highdeaths['total']); ?></h5></td>
							</tr>
							<tr>
								<td><h5>Highest Killstreak</h5></td>
								<td colspan="2"><h5><?php echo number_format($highks['wep_ks']); ?></h5></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="col-sm-4">
					<table class="table table-bordered table-striped table-condensed">
						<tbody class="playermodal">
<?php foreach ($customkill as $customkill): ?>
							<tr>
								<td><?php echo $customkill['customkill']; ?></td>
								<td><?php echo number_format($customkill['total']); ?></td>
								<td><?php echo round($customkill['total']/$attacker['kills']*100,2)."%"; ?></td>
							</tr>
<?php endforeach ?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6">
				<h4>Player</h4>
					<table class="table table-bordered table-striped table-condensed">
						<tbody class="playermodal">
							<tr><td><h5>Total Kills</h5></td><td><h5><?php echo number_format($attacker['kills']); ?></h5></td></tr>
							<tr><td><h5>Dominations</h5></td><td><h5><?php echo number_format($attacker['dominated']); ?></h5></td></tr>
							<tr><td><h5>Revenges</h5></td><td><h5><?php echo number_format($attacker['revenge']); ?></h5></td></tr>
							<tr><td><h5>Kills on Red</h5></td><td><h5><?php echo number_format($attacker['red']); ?></h5></td></tr>
							<tr><td><h5>Kills on Blue</h5></td><td><h5><?php echo number_format($attacker['blue']); ?></h5></td></tr>
						</tbody>
					</table>
				</div>
				<div class="col-sm-6">
				<h4>Enemy</h4>
					<table class="table table-bordered table-striped table-condensed">
						<tbody class="playermodal">
							<tr><td><h5>Total Deaths</h5></td><td><h5><?php echo number_format($victim['deaths']); ?></h5></td></tr>
							<tr><td><h5>Dominated</h5></td><td><h5><?php echo number_format($victim['dominated']); ?></h5></td></tr>
							<tr><td><h5>Revenge Taken</h5></td><td><h5><?php echo number_format($victim['revenge']); ?></h5></td></tr>
							<tr><td><h5>Deaths on Red</h5></td><td><h5><?php echo number_format($victim['red']); ?></h5></td></tr>
							<tr><td><h5>Deaths on Blue</h5></td><td><h5><?php echo number_format($victim['blue']); ?></h5></td></tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>