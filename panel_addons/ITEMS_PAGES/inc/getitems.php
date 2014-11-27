<?php 

if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || !strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
{
	header("Location: ../index.php?error=".urlencode("Direct access not allowed."));
	die();
}

$id = $_GET['id'];

include 'config.php';

// Include database class
include 'database.class.php';

// Instantiate database.
$database = new Database();

// Instantiate database.
$database = new Database();

$database->query('SELECT `name` FROM items WHERE `index` = :id');
$database->bind(':id', $_GET['id']);
$item = $database->single();

?>
<div class="modal-dialog modal-lg">
 	<div class="modal-content">
		<div class="modal-body">
			<div style="padding:10px">
				<h4 style="text-align:center"><?php echo $item['name']; ?></h4>
				<table id="list" class="table table-bordered table-striped table-condensed" style="cursor:pointer">
					<thead>
						<tr>
							<th>Player</th>
							<th>Auth</th>
							<th>Quality</th>
							<th>Method</th>
							<th>Time</th>
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
	var list = $('#list').DataTable( {
		"processing": false,
		"serverSide": true,
		"ajax": "inc/server_processing.php?id=<?php echo $id; ?>&type=getitems",
		"pagingType": "full",
		"dom": '<f<t><"pull-left" i>p>',
		"columns": [
			{ "data": "name" },
			{ "data": "auth", "visible" : false },
			{ "data": "quality_text" },
			{ "data": "method_text" },
			{ "data": "time" }
		],
		"order": [[4, 'desc']]
	});
	$('#list tbody').on('click', 'tr', function () {
			window.location = "player.php?id="+list.cell(this, 1).data();
	});
});
</script>