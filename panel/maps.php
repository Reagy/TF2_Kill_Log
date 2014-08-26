<?php 
date_default_timezone_set('America/Chicago');
error_reporting(E_ALL);

$Home = 'potato';

// Include database class
include 'database.class.php';

// Define configuration
define("DB_HOST", "localhost");
define("DB_USER", "");
define("DB_PASS", "");
define("DB_NAME", "test");

// Instantiate database.
$database = new Database();

$database->query('SELECT `map`, `ateam`, COUNT(`attacker`) AS `kills`, SUM(`dominated`+`assister_dominated`) AS `dominations`,
	SUM(`revenge`+`assister_revenge`) AS `revenges`, SUM(`crit`) AS `critkill` FROM `killlog`
	GROUP BY `map`, `ateam` ORDER BY `kills` DESC');
$log = $database->resultset();

?>

<?php include("inc/nav.php"); ?>
		<div style="background-color:#f5f5f5;height:100%;border:1px solid #222222">
			<div class="row" style="padding:20px">
			<pre><?php print_r($log); ?></pre>