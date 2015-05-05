<?php 

if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || !strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
{
	header("Location: ../index.php?error=".urlencode("Direct access no allowed."));
	die();
}

$player = $_GET['id']; 

?>
<div style="padding:10px">
	<h4 style="text-align:center">MGE Duels</h4>
	<table id="log" class="table table-bordered table-striped table-condensed" style="cursor:pointer">
		<thead>
			<tr>
				<th>Winner</th>
				<th>w_id</th>
				<th>Loser</th>
				<th>l_id</th>
				<th>Score</th>
				<th>Score</th>
				<th>Limit</th>
				<th>Time</th>
				<th>Map</th>
				<th>Area</th>
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
				"type": "getplayermge",
				"id": "<?php echo $player ?>"
			}
		},
		"pagingType": "full",
		"dom": '<f<t><"pull-left" i>p>',
		"columns": [
			{ "data": "w_name" },
			{ "data": "winner", "visible" : false },
			{ "data": "l_name" },
			{ "data": "loser", "visible" : false },
			{ "data": "winnerscore" },
			{ "data": "loserscore" },
			{ "data": "winlimit" },
			{ "data": "gametime"},
			{ "data": "mapname"},
			{ "data": "arenaname"}
		],
		"order": [[7, 'desc']]
	});
});
</script>