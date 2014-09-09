<?php 

if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || !strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
{
	header("Location: ../index.php?error=".urlencode("Direct access no allowed."));
	die();
}

//Database Info
include 'config.php';

// Include database class
include 'database.class.php';

if (!isset($_GET['id'])) {
	$_GET['id'] = date("Y-m-d", strtotime('-7 day')).",".date("Y-m-d");
}

$date = explode(",", $_GET['id']);

// Instantiate database.
$database = new Database();

$database->query('SELECT playerlog.`name`, playerlog.`auth`, COUNT(killlog.`attacker`) AS kills
	FROM `killlog` 
	INNER JOIN playerlog
	ON killlog.`attacker` = playerlog.`auth`
	WHERE killlog.`attacker` != killlog.`victim` AND DATE(FROM_UNIXTIME(killlog.`killtime`)) BETWEEN  :start AND :end GROUP BY killlog.`attacker` ORDER BY kills DESC LIMIT 0,10');
$database->bind(':start', $date[0]);
$database->bind(':end', $date[1]);
$killers = $database->resultset();

print_r(json_encode($killers));

?>