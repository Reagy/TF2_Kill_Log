<?php 

$custkill = array('0' => 'None', '1' => 'Headshot', '2' => 'Backstab', '3' => 'Burning', '4' => 'Fix', '5' => 'Minigun', '6' => 'Suicide', '7' => 'Hadouken', '8' => 'Flare', '9' => 'Taunt', '10' => 'Taunt', '11' => 'Team Penetration', '12' => 'Player Penetration', '13' => 'Taunt', '14' => 'Penetration Headshot', '15' => 'Taunt', '16' => 'Telefrag', '17' => 'Burning Arrow', '18' => 'Flying Burn', '19' => 'Pumpkin Bomb', '20' => 'Decapitation', '21' => 'Taunt', '22' => 'Baseball', '23' => 'Charge Impact', '24' => 'Taunt', '25' => 'Air Sticky', '26' => 'Defensive Sticky', '27' => 'Pickaxe', '28' => 'Direct Hit', '29' => 'Taunt', '30' => 'Wrangled', '31' => 'Sticky', '32' => 'Revenge Crit', '33' => 'Taunt', '34' => 'Bleed', '35' => 'Golden', '36' => 'Carry Building', '37' => 'Combo', '38' => 'Taunt', '39' => 'Fish Kill', '40' => 'Hurt', '41' => 'Boss Decapitation', '42' => 'Explosion', '43' => 'Aegis', '44' => 'Flare Explode', '45' => 'Stomp', '46' => 'Plasma', '47' => 'Deflected Plasma', '48' => 'Plasma Gib', '49' => 'Practice Sticky', '50' => 'Eyeball', '51' => 'Hitman Headshot', '52' => 'Taunt', '53' => 'Flare', '54' => 'Cleaver', '55' => 'Crit Cleaver', '56' => 'Red Tape', '57' => 'Player Bomb', '58' => 'Merasmus Grenade', '59' => 'Merasmus Zap', '60' => 'Merasmus Decapitation', '61' => 'Cannonball Push');

//Include Config
include "inc/config.php";

// Include database class
include 'inc/database.class.php';

// Instantiate database.
$database = new Database();

$database->query('SELECT customkill, COUNT(customkill) AS total FROM smalllog WHERE customkill NOT IN(0,3,5,7,9,10,13,15,18,21,24,25,28,29,33,38,39,53,54,61)
	GROUP BY customkill ORDER BY total DESC');
$log = $database->resultset();

foreach ($log as $key => $value) {
	foreach ($custkill as $ck => $cv) {
		if ($ck == $value['customkill']) {
			$log[$key]['name'] = $cv;
		}
	}
}

?>
<?php include("inc/nav.php"); ?>
		<div class="stats-body">
			<div class="row" style="padding:20px">
				<div class="col-md-3 col-sm-4">
					<div class="panel panel-inverse">
						<div class="panel-heading" style="text-align:center">
							<h3 class="panel-title" style="text-align:center">Actions</h3>
						</div>
						<div id="weaponlist">
							<table class="table-weapons" style="margin:0px auto;width:100%">
								<thead>
									<tr>
										<th colspan="2">
											<div class="pull-left" style="width:50%"><h4 style="text-align:center;padding:0px 10px">Action</h4></div>
											<div class="pull-right" style="width:50%"><h4 style="text-align:center;padding:0px 10px">Kills</h4></div>
										</th>
									</tr>
								</thead>
								<tbody>
<?php foreach ($log as $log): ?>
									<tr class="getaction">
										<td>
											<input type="hidden" value="<?php echo $log['customkill']; ?>"/>
											<br>
											<span><?php echo $log['name']; ?></span>
										</td>
										<td>
											<h4><?php echo number_format($log['total']); ?></h4>
										</td>
									</tr>
<?php endforeach ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="col-md-9 col-sm-8">
					<div id="affixed" class="panel panel-inverse" data-spy="affix" data-offset-top="250">
						<div class="panel-heading">
							<h3 id="action"class="panel-title" style="text-align:center">Action Information</h3>
						</div>
						<div id="result" align="center" style="padding:10px">
							<div id="loader">Action information will be displayed here.</div>
						</div>
						<div class="panel-footer-inverse"></div>
					</div>
				</div>
			</div>
		</div>
<?php include 'inc/footer.php'; ?>
	</div>
<script>
$(document).on("click",".getaction",function(){
	$.ajax({
		type: "GET",
		url: "inc/getaction.php",
		data: 'id=' + $(this).find("input").val(),
		success: function(msg){
			$('#result').html(msg);
		}
	});
});

function affixWidth() {
	var affix = $('#affixed');
	var width = affix.width();
	affix.width(width);
}

$(document).ready(function () {
	affixWidth();
});
</script>
</body>
</html>