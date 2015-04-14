<?php include "inc/config.php"; ?>
<?php include "inc/nav.php"; ?>
		<div class="stats-body">
			<div style="padding:10px">
				<table id="players" class="table table-bordered table-striped table-condensed display" style="cursor:pointer">
					<thead>
						<tr>
							<th>Name</th>
							<th>Auth</th>
							<th>Kills</th>
							<th>Deaths</th>
							<th>Assists</th>
							<th>KPD</th>
							<th>KPM</th>
							<th>Playtime</th>
							<th>Last On</th>
						</tr>
					</thead>
					<tbody>

					</tbody>
				</table>
			</div>
		</div>
<?php include 'inc/footer.php'; ?>
	</div>
</body>
<script>
$(document).ready(function() {
	var players = $('#players').DataTable( {
		"processing": false,
		"serverSide": true,
		"ajax": {
			"url": "inc/server_processing.php",
			"type": "POST",
			"data": {
				"type": "getplayers"
			}
		},
		"pagingType": "full",
		"columns": [
			{ "data": "name" },
			{ "data": "auth", "visible" : false },
			{ "data": "kills" },
			{ "data": "deaths" },
			{ "data": "assists" },
			{ "data": "kpd" },
			{ "data": "kpm" },
			{ "data": "playtime" },
			{ "data": "disconnect_time" }
		],
		"order": [[2, 'desc']]
	});
	$('#players tbody').on('click', 'tr', function () {
			window.location = "player.php?id="+players.cell(this, 1).data();
	});
});
</script>
</html>