<?php 

if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || !strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
{
	header("Location: ../index.php?error=".urlencode("Direct access not allowed."));
	die();
}

$id = $_GET['id'];

include 'config.php';

// Include database class
include 'database.class.php';

// Instantiate database.
$database = new Database();

$database->query('SELECT itemlog.*, items.*, COUNT(itemlog.`index`) AS found FROM itemlog INNER JOIN items
	ON itemlog.`index` = items.`index` WHERE `method` = :id GROUP BY itemlog.`index`, itemlog.`quality` ORDER BY time DESC');
$database->bind(':id', $_GET['id']);
$log = $database->resultset();

?>
<div style="text-align:center">
	<h1>Last 500 Recorded Items <?php echo Method($id); ?></h1>
</div>
<?php foreach ($log as $log): ?>
<div class="col-sm-2 getitem" style="cursor:pointer;">
	<input type="hidden" value="<?php echo $log['index']; ?>"/>
	<div class="row">
		<div style="background-color:<?php echo Quality($log['quality']); ?>;border-radius:4px;border:2px solid #222222;margin:5px">
			<img width="80" height="80" src="<?php echo $log['image']; ?>">
			<span class="fa-stack fa-lg fa-2x">
				<i class="fa fa-circle-thin fa-stack-2x"></i>
				<i style='font-size:18px;color:#ecf0f1' class='fa-stack-1x'><?php echo $log['found']; ?></i>
			</span>
		</div>
	</div>
</div>
<?php endforeach ?>