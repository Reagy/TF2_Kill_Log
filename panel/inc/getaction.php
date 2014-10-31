<?php

if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || !strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
{
	header("Location: ../index.php?error=".urlencode("Direct access no allowed."));
	die();
}

$custkill = array('0' => 'None', '1' => 'Headshot', '2' => 'Backstab', '3' => 'Burning', '4' => 'Fix', '5' => 'Minigun', '6' => 'Suicide', '7' => 'Hadouken', '8' => 'Flare', '9' => 'Taunt', '10' => 'Taunt', '11' => 'Team Penetration', '12' => 'Plaer Penetration', '13' => 'Taunt', '14' => 'Penetration Headshot', '15' => 'Taunt', '16' => 'Telefrag', '17' => 'Burning Arrow', '18' => 'Flying Burn', '19' => 'Pumpkin Bomb', '20' => 'Decapitation', '21' => 'Taunt', '22' => 'Baseball', '23' => 'Charge Impact', '24' => 'Taunt', '25' => 'Air Sticky', '26' => 'Defensive Sticky', '27' => 'Pickaxe', '28' => 'Direct Hit', '29' => 'Taunt', '30' => 'Wrangled', '31' => 'Sticky', '32' => 'Revenge Crit', '33' => 'Taunt', '34' => 'Bleed', '35' => 'Golden', '36' => 'Carry Building', '37' => 'Combo', '38' => 'Taunt', '39' => 'Fish Kill', '40' => 'Hurt', '41' => 'Boss Decapitation', '42' => 'Explosion', '43' => 'Aegis', '44' => 'Flare Explode', '45' => 'Stomp', '46' => 'Plasma', '47' => 'Deflected Plasma', '48' => 'Plasma Gib', '49' => 'Practice Sticky', '50' => 'Eyeball', '51' => 'Hitman Headshot', '52' => 'Taunt', '53' => 'Flare', '54' => 'Cleaver', '55' => 'Crit Cleaver', '56' => 'Red Tape', '57' => 'Player Bomb', '58' => 'Merasmus Grenade', '59' => 'Merasmus Zap', '60' => 'Merasmus Decapitation', '61' => 'Cannonball Push', '70' => 'Fireball Spell','67' => 'Pumpkin MIRV Spell','73' => 'Ball O\'Bats Spell','71' => 'MONOCULUS Spell','66' => 'Skeletons Spell','68' => 'Meteor Shower Spell','74' => 'Minify Spell','69' => 'Tesla Bolt Spell','65' => 'Teleport Spell');
$action = $_GET['id'];

?>

<?php echo "<h3>".$custkill[$action]."</h3>"; ?>
<table id="players" class="table table-bordered table-striped table-hover table-condensed display" style="cursor:pointer">
	<thead>
		<tr>
			<th>Name</th>
			<th>t</th>
			<th>Kills</th>
			<th>Deaths</th>
		</tr>
	</thead>
	<tbody>
		
	</tbody>
</table>
<script>
$(document).ready(function() {
	var test = $('#players').DataTable( {
		"processing": true,
		"serverSide": true,
		"ajax": "inc/server_processing.php?id=<?php echo $action; ?>&type=getaction",
		"pagingType": "full",
		"dom": '<f<t><"pull-left" i>p>',
		"columns": [
			{ "data": "name" },
			{ "data": "attacker", "visible" : false },
			{ "data": "kills" },
			{ "data": "deaths" }
		],
		"order": [[2, 'desc']]
	});
	$('#players tbody').on('click', 'tr', function () {
			window.location = "player.php?id="+test.cell(this, 1).data();
	});
});
</script>