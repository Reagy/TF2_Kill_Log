<?php 

//Include Config
include "inc/config.php";

// Include database class
include 'inc/database.class.php';

// Instantiate database.
$database = new Database();

$database->query('SELECT name, auth, connect_time, kills, deaths, playtime FROM playerlog WHERE disconnect_time = 0 ORDER BY connect_time ASC');
$player = $database->resultset();

$p_chart = json_encode($player);
?>

<?php include("inc/nav.php"); ?>
		<div class="stats-body">
<?php if (isset($_GET['error'])): ?>
			<div class="alert alert-danger">
				<?php echo $_GET['error']; ?>
			</div>
<?php endif ?>
<?php if ($player): ?>
			<div id='chart' style='height:300px;border-bottom:1px solid #222222;cursor:pointer'></div>
			<table id="players" class="table table-bordered table-striped table-condensed display" style="margin-bottom:0">
				<thead>
					<tr>
						<th width="30%" style="text-align:left;padding-left:10px;">Name</th>
						<th style="text-align:center;">Kills</th>
						<th style="text-align:center;">Deaths</th>
						<th style="text-align:center;">KPD</th>
						<th style="text-align:center;">KPM</th>
						<th width="30%" style="text-align:right;padding-right:10px;">Current Playtime</th>
					</tr>
				</thead>
				<tbody>
<?php foreach($player as $player): ?>
					<tr>
						<td style="text-align:left;vertical-align:middle;padding-left:10px;"><h4><?php echo "<a href='player.php?id=".$player['auth']."'>"?><?php echo htmlentities($player['name']); ?></a></h4></td>
						<td style="text-align:center;vertical-align:middle;"><h4><?php echo number_format($player['kills']); ?></h4></td>
						<td style="text-align:center;vertical-align:middle;"><h4><?php echo number_format($player['deaths']); ?></h4></td>
						<td style="text-align:center;vertical-align:middle;"><h4><?php echo StatCon($player['kills'],$player['deaths']); ?></h4></td>
						<td style="text-align:center;vertical-align:middle;"><h4><?php echo StatCon($player['kills'],($player['playtime']/60)); ?></h4></td>
						<td style="text-align:right;vertical-align:middle;padding-right:10px;"><h4><?php echo PlaytimeCon(time() - $player['connect_time']); ?></h4></td>
					</tr>
<?php endforeach; ?>
				</tbody>
			</table>
<?php else: ?>
	<div style="text-align:center">
		<h1>No Current Players</h1>
	</div>
<?php endif ?>
		</div>
<?php include 'inc/footer.php'; ?>
	</div>

<script type="text/javascript">
Morris.Bar ({
	element: 'chart',
	data: <?php echo $p_chart; ?>,
	xkey: 'name',
	ykeys: ['kills', 'deaths'],
	labels: ['Kills', 'Deaths'],
	gridTextColor: ['#222222'],
	barColors: ['#222222', '#e74c3c', '#c0392b'],
	barRatio: 0.4,
	xLabelAngle: 0,
	hideHover: 'auto',
	resize: true
}).on('click', function(i, row){
	console.log(i, row);
	window.location = "player.php?id="+row['auth'];
});
</script>
</body>
</html>