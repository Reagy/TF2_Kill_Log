<?php

if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || !strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
{
	header("Location: ../index.php?error=".urlencode("Direct access no allowed."));
	die();
}

//Include Config
include "config.php";

// Include database class
include 'database.class.php';

// Instantiate database.
$database = new Database();

$database->query('SELECT weapon, SUM(`kills`) AS total FROM smalllog WHERE attacker = :id AND kills != 0 GROUP BY weapon ORDER BY total DESC');
$database->bind(':id', $_GET['id']);
$log = $database->resultset();

$database->query('SELECT name, weapon, image, class, slot FROM weapons');
$weapons = $database->resultset();

foreach ($log as $key => $value) {
	$log[$key]['name'] = $value['weapon'];
	$log[$key]['image'] = "images/weaponicons/Killicon_skull.png";

	foreach ($weapons as $wk => $wv) {
		if ($value['weapon'] == $wv['weapon']) {
			$log[$key]['name'] = $wv['name'];
			$log[$key]['image'] = $wv['image'];
			$log[$key]['class'] = $wv['class'];
			$log[$key]['slot'] = $wv['slot'];
		}
	}
}

?>

<?php $i=1; ?>
<?php foreach ($log as $log): ?>
								<div class="col-md-3 col-sm-4 col-xs-6 getweapon" data-toggle="tooltip" data-placement="top" title="<?php echo $log['name']; ?>">
									<div class="row">
										<div class="pull-left">
											<span class='fa-stack fa-2x'>
												<i class='fa fa-circle-thin fa-stack-2x'></i>
												<i class='fa-stack-1x' style='color:#222222'><?php echo $i++; ?></i>
											</span>
										</div>
										<div class="pull-right" style="padding-right:10px">
											<h3><?php echo number_format($log['total'])." Kills"; ?></h3>
										</div>
									</div>
									<div>
										<img class="img-responsive" style="display:block;margin-left:auto;margin-right:auto;" src="<?php echo $log['image']; ?>" alt="<?php echo $log['name']; ?>">
										<input type="hidden" value="<?php echo $log['weapon']; ?>"/>
									</div>
								</div>
<?php endforeach ?>
<!-- Modal -->
	<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

	</div>
<script>
$(document).on("click",".getweapon",function(){
	//alert($(this).find("input").val());
	$('#modal').modal('show');
	$.ajax({
		type: "GET",
		url: "inc/getplayerwep.php",
		data: 'id=<?php echo $_GET["id"] ?>&wep=' + $(this).find("input").val(),
		success: function(msg){
			$('#modal').html(msg);
		}
	});
});
$('[data-toggle="tooltip"]').tooltip({'placement': 'top'});
</script>