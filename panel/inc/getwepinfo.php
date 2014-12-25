<?php

if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || !strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
{
	header("Location: ../index.php?error=".urlencode("Direct access no allowed."));
	die();
}

$id = $_GET['id'];
$slot = array('Primary','Secondary','Melee','PDA','Taunt','Other');
$class = array('Demoman','Engineer','Heavy','Medic','Pyro','Scout','Sniper','Soldier','Spy');

//Include Config
include "config.php";

// Include database class
include 'database.class.php';

// Instantiate database.
$database = new Database();

$database->query('SELECT weapon, SUM(kills) AS kills FROM smalllog
	GROUP BY weapon ORDER BY kills DESC');
$log = $database->resultset();

if (in_array($id, $slot)) {
	$database->query('SELECT name, weapon, image, class, slot FROM weapons WHERE slot = :id');
	$database->bind(':id', $_GET['id']);
	$weapons = $database->resultset();
}

elseif (in_array($id, $class)) {
	$database->query('SELECT name, weapon, image, class, slot FROM weapons WHERE class = :id');
	$database->bind(':id', $_GET['id']);
	$weapons = $database->resultset();
}

else {
	$database->query('SELECT name, weapon, image, class, slot FROM weapons');
	$weapons = $database->resultset();
}

foreach ($weapons as $key => $value) {
	foreach ($log as $lk => $lv) {
		if ($value['weapon'] == $lv['weapon']) {
			$list[$key]['weapon'] = $value['weapon'];
			$list[$key]['name'] = $value['name'];
			$list[$key]['image'] = $value['image'];
			$list[$key]['class'] = $value['class'];
			$list[$key]['slot'] = $value['slot'];
			$list[$key]['kills'] = $lv['kills'];
		}
	}
}

if (isset($list)) {
	usort($list, function($a, $b) {
	    return $b['kills'] - $a['kills'];
	});
}
?>

<?php if (isset($list)): ?>
<table class="table-weapons" style="margin:0px auto;width:100%">
	<thead>
		<tr>
			<th colspan="2">
				<div class="pull-left" style="width:50%"><h4 style="text-align:center;padding:0px 10px">Weapon</h4></div>
				<div class="pull-right" style="width:50%"><h4 style="text-align:center;padding:0px 10px">Kills</h4></div>
			</th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($list as $list): ?>
		<tr class="weplist">
			<td>
				<img src="<?php echo $list['image']; ?>" alt="<?php echo $list['weapon']; ?>">
				<input type="hidden" value="<?php echo $list['weapon']; ?>"/>
				<br>
				<span><?php echo $list['name']; ?></span>
			</td>
			<td>
				<h4><?php echo number_format($list['kills']); ?></h4>
			</td>
		</tr>
<?php endforeach ?>
	</tbody>
</table>
<?php else: echo "<h3 style='text-align:center'>No Data for ".$id."</h3>"; ?>
<?php endif ?>