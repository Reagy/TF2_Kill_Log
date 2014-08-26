<?php 

if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || !strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
{
	header("Location: ../index.php?error=".urlencode("Direct access no allowed."));
	die();
}

$id = $_GET['id'];

?>
<div class="modal-dialog modal-lg">
 	<div class="modal-content">
		<div class="modal-body">
			<div style="padding:10px">
				<h4 style="text-align:center">Top Killstreaks with <?php echo $id; ?></h4>
				<table id="log" class="table table-bordered table-striped table-condensed" style="cursor:pointer">
					<thead>
						<tr>
							<th>Player</th>
							<th>Auth</th>
							<th>Killstreak</th>
						</tr>
					</thead>
					<tbody>

					</tbody>
				</table>
			</div>		
		<div class="modal-footer">
			<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
		</div>
	</div>
</div>
<script>
$(document).ready(function() {
	var killstreaks = $('#log').DataTable( {
		"processing": false,
		"serverSide": true,
		"ajax": "inc/server_processing.php?id=<?php echo $id; ?>&type=getstreak",
		"pagingType": "full",
		"dom": '<f<t><"pull-left" i>p>',
		"columns": [
			{ "data": "name" },
			{ "data": "attacker", "visible" : false },
			{ "data": "ks" }
		],
		"order": [[2, 'desc']]
	});
	$('#log tbody').on('click', 'tr', function () {
			window.location = "player.php?id="+killstreaks.cell(this, 1).data();
	});
});
</script>