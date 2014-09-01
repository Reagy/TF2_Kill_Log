<?php 

include 'inc/config.php';

// Include database class
include 'inc/database.class.php';

// Instantiate database.
$database = new Database();

$database->query('SELECT itemlog.*, items.*, COUNT(itemlog.`index`) AS found FROM itemlog INNER JOIN items
	ON itemlog.`index` = items.`index` GROUP BY itemlog.`index`, itemlog.`quality` ORDER BY time DESC LIMIT 0,500');
$log = $database->resultset();

?>

<?php include("inc/nav.php"); ?>
		<div style="background-color:#f5f5f5;height:100%;border:1px solid #222222">
			<table class="table table-condensed" style="text-align:center;margin-top:10px">
				<tbody>
					<tr>
						<td style="border:0;cursor:pointer;" class='getitemlist'><input type="hidden" value="0"><i class="fa fa-cloud-download fa-4x"></i></td>
						<td style="border:0;cursor:pointer;" class='getitemlist'><input type="hidden" value="1"><i class="fa fa-wrench fa-4x"></i></td>
						<td style="border:0;cursor:pointer;" class='getitemlist'><input type="hidden" value="2"><i class="fa fa-exchange fa-4x"></i></td>
						<td style="border:0;cursor:pointer;" class='getitemlist'><input type="hidden" value="4"><i class="fa fa-dropbox fa-4x"></i></td>
						<td style="border:0;cursor:pointer;" class='getitemlist'><input type="hidden" value="5"><i class="fa fa-gift fa-4x"></i></td>
						<td style="border:0;cursor:pointer;" class='getitemlist'><input type="hidden" value="3"><i class="fa fa-usd fa-4x"></i></td>
					</tr>
				</tbody>
			</table>
			<div id="content" class="panel-body" style="padding:0px;">
			<div style="text-align:center">
				<h1>Last 500 Recorded Items</h1>
			</div>
<?php foreach ($log as $log): ?>
				<div class="col-sm-2 getitem" style="cursor:pointer;">
					<input type="hidden" value="<?php echo $log['index']; ?>"/>
					<div class="row">
						<div style="background-color:<?php echo Quality($log['quality']); ?>;border-radius:4px;border:2px solid #222222;margin:5px">
							<img width="80" height="80" src="<?php echo $log['image']; ?>">
							<span class="fa-stack fa-lg fa-2x">
								<i class="fa fa-circle-thin fa-stack-2x"></i>
								<i style='font-size:18px;color:#ecf0f1' class='fa-stack-1x'><?php echo $log['found']; ?></i>
							</span>
						</div>
					</div>
				</div>
<?php endforeach ?>
			</div>
		</div>
<?php include "inc/footer.php"; ?>
	</div>
</div>
<!-- Modal -->
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

</div>
<script>
$(document).on("click",".getitem",function(){
	$('#modal').modal('show');
	$.ajax({
		type: "GET",
		url: "inc/getitems.php",
		data: 'id=' + $(this).find("input").val(),
		success: function(msg){
			$('#modal').html(msg);
		}
	});
});
</script>
<script>
$(document).on("click",".getitemlist",function(){
	$.ajax({
		type: "GET",
		url: "inc/getitemlist.php",
		data: 'id=' + $(this).find("input").val(),
		success: function(msg){
			$('#content').html(msg);
		}
	});
});
</script>
</body>
</html>