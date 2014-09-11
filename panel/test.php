<?php 

include 'inc/config.php';

// Include database class
include 'inc/database.class.php';

// Instantiate database.
$database = new Database();


$ids = $_GET['id'];
$ids = explode(',', $_GET['id']);

$params = implode(',', array_fill(0, count($ids), '?'));

print_r($ids);

$database->query('SELECT `playerlog`.`name`, `smalllog`.* FROM `smalllog`
	INNER JOIN `playerlog`
	ON `playerlog`.`auth` = `smalllog`.`attacker`
	WHERE `attacker` IN ('.$params.')
	ORDER BY `playtime` DESC');
foreach ($ids as $k => $id)
    $database->bind(($k+1), $id);

$database->execute();
$log = $database->resultset();

?>

<?php 

echo "<pre>";
print_r($log);
echo "</pre>";


 ?>