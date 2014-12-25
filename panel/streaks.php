<?php 

//Include Config
include "inc/config.php";

// Include database class
include 'inc/database.class.php';

// Instantiate database.
$database = new Database();

$database->query('SELECT s.*, playerlog.`name` AS player FROM smalllog s INNER JOIN (SELECT `attacker`, `weapon`, MAX(`ks`) AS ks FROM smalllog WHERE `ks` >= 1 GROUP BY `weapon`) w ON s.`weapon` = w.`weapon` AND s.`ks` = w.`ks` INNER JOIN playerlog ON s.`attacker` = playerlog.`auth` ORDER BY `s`.`ks` DESC');
$log = $database->resultset();

$database->query('SELECT name, weapon, image FROM weapons');
$weapons = $database->resultset();

foreach ($log as $key => $value) {
	$log[$key]['name'] = $value['weapon'];
	$log[$key]['image'] = "images/weaponicons/Killicon_skull.png";
	foreach ($weapons as $wk => $wv) {
		if ($value['weapon'] == $wv['weapon']) {
			$log[$key]['name'] = $wv['name'];
			$log[$key]['image'] = $wv['image'];
		}
	}
}
?>
<?php include "inc/nav.php"; ?>
	<div class="stats-body">
		<div class="panel-body" style="padding:0px;">
<?php foreach ($log as $log): ?>
			<div class="col-sm-4 getstreak">
				<input type="hidden" value="<?php echo $log['weapon']; ?>"/>
				<div class="row">
					<div class="pull-left">
						<span class='fa-stack fa-2x'>
							<i class='fa fa-circle-thin fa-stack-2x'></i>
							<i class='fa-stack-1x' style='color:#222222'><?php echo number_format($log['ks']); ?></i>
						</span>
					</div>
					<div class="pull-right" style="padding-right: 10px;display:inline-block; vertical-align:top">
						<h4><?php echo htmlentities($log['player']); ?></h4>
					</div>
				</div>
				<div style="text-align:center">
					<img src="<?php echo $log['image']; ?>" alt="<?php echo $log['name']; ?>">
					<h4><?php echo $log['name']; ?></h4>
				</div>
			</div>
<?php endforeach ?>
		</div>
	</div>
<?php include "inc/footer.php"; ?>
</div>
<!-- Modal -->
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="Streaks" aria-hidden="true">

</div>
<script>
$(document).on("click",".getstreak",function(){
	$('#modal').modal('show');
	$.ajax({
		type: "GET",
		url: "inc/getstreak.php",
		data: 'id=' + $(this).find("input").val(),
		success: function(msg){
			$('#modal').html(msg);
		}
	});
});
</script>
</body>
</html>