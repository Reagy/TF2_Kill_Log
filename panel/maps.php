<?php 

include 'inc/config.php';

// Include database class
include 'inc/database.class.php';

// Instantiate database.
$database = new Database();

$database->query('SELECT * FROM `maplog` ORDER BY `playtime` DESC');
$log = $database->resultset();

$i = 1;
?>

<?php include "inc/nav.php"; ?>
	<div class="stats-body">
		<div class="panel-body" style="padding:0px;">
<?php foreach ($log as $log): ?>
			<div class="col-sm-12 getmap">
				<input type="hidden" value="<?php echo $log['name']; ?>"/>
				<div class="row">
					<div class="pull-left">
						<span class='fa-stack fa-2x'>
							<i class='fa fa-circle-thin fa-stack-2x'></i>
							<i class='fa-stack-1x' style='color:#222222'><?php echo $i++;?></i>
						</span>
					</div>
					<div class="pull-right" style="padding-right: 10px;display:inline-block; vertical-align:top">
						<h1><?php echo htmlentities($log['name']); ?></h1>
					</div>
				</div>
				<div class="row" style="padding:10px">
					<div class="pull-left" style="padding-right: 10px;display:inline-block; vertical-align:top">
						<h4><?php echo "Kills: ".number_format($log['kills'])." Assists: ".number_format($log['assists'])." Dominations: ".number_format($log['dominations'])." Revenges: ".number_format($log['revenges'])." Playtime: ".PlaytimeCon($log['playtime'])." hrs"; ?></h4>
					</div>
				</div>
			</div>
<?php endforeach ?>
		</div>
	</div>
<?php include "inc/footer.php"; ?>
</div>
<!-- Modal -->
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="Maps" aria-hidden="true">

</div>
<script>
$(document).on("click",".getmap",function(){
	$('#modal').modal('show');
	$.ajax({
		type: "GET",
		url: "inc/getmap.php",
		data: 'id=' + $(this).find("input").val(),
		success: function(msg){
			$('#modal').html(msg);
		}
	});
});
</script>
</body>
</html>