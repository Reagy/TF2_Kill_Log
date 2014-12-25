<?php 

if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || !strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
{
	header("Location: ../index.php?error=".urlencode("Direct access no allowed."));
	die();
}

$id = $_GET['id'];

//Include Config
include "config.php";

// Include database class
include 'database.class.php';

// Instantiate database.
$database = new Database();

$database->query('SELECT `cclass`, COUNT(`cclass`) AS total FROM teamlog WHERE map = :id GROUP BY `cclass` ORDER BY total DESC');
$database->bind(':id', $id);
$captures = $database->resultset();

$database->query('SELECT `dclass`, COUNT(`dclass`) AS total FROM teamlog WHERE map = :id GROUP BY `dclass` ORDER BY total DESC');
$database->bind(':id', $id);
$defends = $database->resultset();

?>

<div class="modal-dialog modal-lg">
 	<div class="modal-content">
		<div class="modal-body">
			<div style="padding:10px">
				<h4 style="text-align:center">Map Stats for <?php echo $id; ?></h4>
			</div>
			<div class="row">
				<div class="col-sm-6" style="text-align:center">
					<h1>Attacking</h1>
					<div>
						<table class="table table-bordered table-striped table-hover table-condensed display">
							<thead>
								<tr>
									<th>Class</th>
									<th>Captures</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($captures as $key => $value): ?>
									<tr>
										<td><img src="images/classicons/<?php echo $value['cclass']; ?>.png"></td>
										<td><?php echo $value['total']; ?></td>
									</tr>
								<?php endforeach ?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="col-sm-6" style="text-align:center">
					<h1>Defending</h1>
					<div>
						<table class="table table-bordered table-striped table-hover table-condensed display">
							<thead>
								<tr>
									<th>Class</th>
									<th>Defends</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($defends as $key => $value): ?>
									<tr>
										<td><img src="images/classicons/<?php echo $value['dclass']; ?>.png"></td>
										<td><?php echo $value['total']; ?></td>
									</tr>
								<?php endforeach ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
		</div>
	</div>
</div>