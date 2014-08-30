<?php 
date_default_timezone_set('America/Chicago');
error_reporting(E_ALL);

include 'inc/config.php';

// Include database class
include 'inc/database.class.php';

// Instantiate database.
$database = new Database();

/*$database->query('SELECT `map`, `ateam`, COUNT(`attacker`) AS `kills`, SUM(`dominated`+`assister_dominated`) AS `dominations`,
	SUM(`revenge`+`assister_revenge`) AS `revenges`, SUM(`crit`) AS `critkill` FROM `killlog`
	GROUP BY `map`, `ateam` ORDER BY `kills` DESC');
$log = $database->resultset();*/

$database->query('SELECT * FROM `maplog` ORDER BY `playtime` DESC');
$log = $database->resultset();

$i = 1;
?>

<?php include "inc/nav.php"; ?>
	<div style="background-color:#f5f5f5;height:100%;border:1px solid #222222">
		<div class="panel-body" style="padding:0px;">
<?php foreach ($log as $log): ?>
			<div class="col-sm-12 getstreak" style="border:1px solid #000000;height:125px;cursor:pointer">
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
						<h4><?php echo "Kills: ".$log['kills']." Assists: ".$log['assists']." Dominations: ".$log['dominations']." Revenges: ".$log['revenges']." Playtime: ".PlaytimeCon($log['playtime'])." hrs"; ?></h4>
					</div>
				</div>
			</div>
<?php endforeach ?>
		</div>
<?php include "inc/footer.php"; ?>
	</div>
</div>