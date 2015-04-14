<?php 

if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || !strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
{
	header("Location: ../index.php?error=".urlencode("Direct access no allowed."));
	die();
}

$player = $_GET['id']; 

?>
<div style="padding:10px">
	<h4 style="text-align:center">Recent Activity</h4>
	<table id="log" class="table table-bordered table-striped table-condensed" style="cursor:pointer">
		<thead>
			<tr>
				<th>Attacker</th>
				<th>a_id</th>
				<th>Class</th>
				<th>Victim</th>
				<th>v_id</th>
				<th>Class</th>
				<th>Weapon</th>
				<th>Crit</th>
				<th>Streak</th>
			</tr>
		</thead>
		<tbody>

		</tbody>
	</table>
</div>
<script>
$(document).ready(function() {
	var players = $('#log').DataTable( {
		"processing": false,
		"serverSide": true,
		"ajax": {
			"url": "inc/server_processing.php",
			"type": "POST",
			"data": {
				"type": "getplayer",
				"id": "<?php echo $player ?>"
			}
		},
		"pagingType": "full",
		"dom": '<f<t><"pull-left" i>p>',
		"columns": [
			{ "data": "a_name" },
			{ "data": "attacker", "visible" : false },
			{ "data": "aclass" },
			{ "data": "v_name" },
			{ "data": "victim", "visible" : false },
			{ "data": "vclass" },
			{ "data": "weapon" },
			{ "data": "crit" },
			{ "data": "wep_ks" },
			{ "data": "time", "visible" : false }
		],
		"order": [[9, 'desc']]
	});
	$('#log tbody').on('click', 'tr', function () {
		window.location = "player.php?id="+players.cell(this, 4).data();
	});
});
</script>