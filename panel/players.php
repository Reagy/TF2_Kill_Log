<?php include "inc/config.php"; ?>
<?php include "inc/nav.php"; ?>
		<div style="background-color:#f5f5f5;height:100%;border:1px solid #222222">
			<div style="padding:10px">
				<table id="players" class="table table-bordered table-striped table-condensed display" style="cursor:pointer">
					<thead>
						<tr>
							<th>Name</th>
							<th>Auth</th>
							<th>Kills</th>
							<th>Deaths</th>
							<th>Assists</th>
							<th>Playtime</th>
							<th>Last On</th>
						</tr>
					</thead>
					<tbody>

					</tbody>
				</table>
			</div>
<?php include 'inc/footer.php'; ?>
		</div>
	</div>
</body>
<script>
$(document).ready(function() {
	var players = $('#players').DataTable( {
		"processing": false,
		"serverSide": true,
		"ajax": "inc/server_processing.php?type=getplayers",
		"pagingType": "full",
		"columns": [
			{ "data": "name" },
			{ "data": "auth", "visible" : false },
			{ "data": "kills" },
			{ "data": "deaths" },
			{ "data": "assists" },
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