<?php 

//Include Config
include "inc/config.php";

// Include database class
include 'inc/database.class.php';

// Instantiate database.
$database = new Database();

$database->query('SELECT *, rank FROM (SELECT *, @n := IF(@g = POINTS, @n, @n + 1) rank, 
		@g := POINTS FROM Player, (SELECT @n := 0) i ORDER BY POINTS DESC) q 
		WHERE LASTONTIME > UNIX_TIMESTAMP(NOW() - INTERVAL 1 MONTH) LIMIT 0,10');
$players = $database->resultset();

$p_chart = json_encode($p_chart);

?>

<?php include("inc/nav.php"); ?>
		<div style="background-color:#f5f5f5;height:100%;border:1px solid #222222">
			<div>
				<h2 style="text-align:center">Top Ten Players</h2>
			</div>
			<div id='1' style='height:300px;border-bottom:1px solid #222222'></div>
			<table class='table table-striped table-bordered table-condensed'>
				<thead>
					<tr>
						<th>Name</th>
						<th>Kills</th>
						<th>Deaths</th>
						<th>Assists</th>
						<th>KPD</th>
						<th>KPM</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							<span class="fa-stack fa-lg">
								<i class="fa fa-circle-thin fa-stack-2x"></i>
								<i class='fa-stack-1x'><?php echo $players['rank']; ?></i>
							</span>
						</td>
						<td><a href="player.php?STEAMID=<?php echo $players['STEAMID']; ?>"><?php echo $players['NAME']; ?></a></td>
						<td><?php echo $players['POINTS']; ?></td>
						<td><?php echo $players['KILLS']; ?></td>
						<td><?php echo $players['Death']; ?></td>
						<td><?php echo $players['KillAssist']; ?></td>
						<td><?php echo StatsHeadshotRatio($players['HeadshotKill'],$SniperKills); ?>%</td>
						<td><?php echo $players['DemoKills']; ?></td>
						<td><?php echo $players['EngiKills']; ?></td>
						<td><?php echo $players['HeavyKills']; ?></td>
						<td><?php echo $players['MedicKills']; ?></td>
						<td><?php echo $players['PyroKills']; ?></td>
						<td><?php echo $players['ScoutKills']; ?></td>
						<td><?php echo $SniperKills; ?></td>
						<td><?php echo $players['SoldierKills']; ?></td>
						<td><?php echo $players['SpyKills']; ?></td>
						<td><?php echo StatsKPD($players['KILLS'],$players['Death']); ?></td>
						<td><?php echo StatsKPM($players['KILLS'],$players['PLAYTIME']); ?></td>
					</tr>
<?php endforeach; ?>
				</tbody>
			</table>
			<div>
				<h2 style="text-align:center">Top Ten Maps</h2>
			</div>
			<div id='2' style='height:300px;border-bottom:1px solid #222222'></div>
			<table class='table table-striped table-bordered table-condensed'>
				<thead>
					<tr>
						<th>Rank</th>
						<th>Name</th>
					</tr>
				</thead>
				<tbody>
<?php foreach($maps as $maps): ?>
					<tr>
						<td>
							<span class="fa-stack fa-lg">
								<i class="fa fa-circle-thin fa-stack-2x"></i>
								<i class='fa-stack-1x'><?php echo $maps['rank']; ?></i>
							</span>
						</td>
						<td><a href='maps.php'><?php echo $maps['NAME']; ?></a></td>
						<td><?php echo $maps['PLAYTIME']; ?></td>
					</tr>
<?php endforeach; ?>
				</tbody>
			</table>
		</div>
<?php include 'inc/footer.php'; ?>
	</div>
<script type="text/javascript">
Morris.Bar ({
  element: '1',
  data: <?php echo $p_chart; ?>,
  xkey: 'NAME',
  ykeys: ['POINTS', 'KILLS', 'Death'],
  labels: ['Points', 'Kills', 'Deaths'],
  barRatio: 0.4,
  xLabelAngle: 0,
  hideHover: 'auto',
  gridTextColor: ['#222222'],
  barColors: ['#222222', '#e74c3c', '#c0392b']
}).on('click', function(i, row){
	console.log(i, row);
	window.location = "player.php?id="+row['STEAMID'];
});
Morris.Bar ({
  element: '2',
  data: <?php echo $m_chart; ?>,
  xkey: 'NAME',
  ykeys: ['PLAYTIME'],
  labels: ['Playtime'],
  barRatio: 0.4,
  xLabelAngle: 0,
  hideHover: 'auto',
  gridTextColor: ['#222222'],
  barColors: ['#222222', '#3c3c3c'],
  yLabelFormat: function (y) { return y.toString() + ' hours';},
});
</script>
</body>
</html>