<?php 

if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || !strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
{
	header("Location: ../index.php?error=".urlencode("Direct access not allowed."));
	die();
}

$player = $_GET['id']; 

//Include Config
include "config.php";

// Include database class
include 'database.class.php';

// Instantiate database.
$database = new Database();

$database->query('SELECT attacker, COUNT(attacker) AS kills, SUM(dominated) AS dominations, SUM(revenge+assister_revenge) AS revenges,
	DATE(FROM_UNIXTIME(`killtime`)) AS date FROM `killlog` WHERE `attacker` = :id GROUP BY date LIMIT 0,7');
$database->bind(':id', $_GET['id']);
$history = $database->resultset();

$database->query('SELECT victim, COUNT(victim) AS deaths, DATE(FROM_UNIXTIME(`killtime`)) AS date 
	FROM `killlog` WHERE `victim` = :id GROUP BY date LIMIT 0,7');
$database->bind(':id', $_GET['id']);
$deaths = $database->resultset();

foreach ($history as $key => $value) {
	foreach ($deaths as $k => $v) {
		if ($value['date'] == $v['date']) {
			$history[$key]['deaths'] = $v['deaths'];
		}
	}
}

$history = json_encode($history);

?>
<div id='chart' style='height:300px;border-bottom:1px solid #222222;cursor:pointer'></div>

</div>
<script type="text/javascript">
Morris.Bar ({
	element: 'chart',
	data: <?php echo $history; ?>,
	xkey: 'date',
	ykeys: ['kills', 'deaths', 'dominations', 'revenges'],
	labels: ['Kills', 'Deaths', 'Dominations', 'Revenges'],
	gridTextColor: ['#222222'],
	barColors: ['#222222', '#e74c3c', '#c0392b'],
	barRatio: 0.4,
	xLabelAngle: 0,
	hideHover: 'auto',
	resize: true
})
</script>