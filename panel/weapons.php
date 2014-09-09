<?php 

//Include Config
include "inc/config.php";

// Include database class
include 'inc/database.class.php';

// Instantiate database.
$database = new Database();

$database->query('SELECT weapon, SUM(kills) AS kills FROM smalllog
	GROUP BY weapon ORDER BY kills DESC');
$log = $database->resultset();

$database->query('SELECT name, weapon, image FROM weapons');
$weapons = $database->resultset();

foreach ($log as $key => $value) {
	$log[$key]['name'] = $value['weapon'];
	$log[$key]['image'] = "images/weaponicons/Killicon_skull.png";
	foreach ($weapons as $wk => $wv) {
		if ($value['weapon'] == $wv['weapon']) {
			$log[$key]['name'] = $wv['name'];
			$log[$key]['image'] = $wv['image'];
		}
	}
}

?>
<?php include("inc/nav.php"); ?>
		<div class="stats-body">
			<div class="row" style="padding:20px">
				<div class="col-md-3 col-sm-4">
					<div class="panel panel-inverse">
						<div class="panel-heading" style="padding:0">
							<div class="btn-group btn-block btn-input clearfix" style="padding:0px 20px">
								<button type="submit" class="btn btn-block btn-black dropdown-toggle" data-toggle="dropdown">
									<span data-bind="label">Select One</span>
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu" role="menu" style="width:100%">
									<li><a href="#">All Weapons</a></li>
									<li class="divider"></li>
									<li><a href="#">Primary</a></li>
									<li><a href="#">Secondary</a></li>
									<li><a href="#">Melee</a></li>
									<li class="divider"></li>
									<li><a href="#">PDA</a></li>
									<li><a href="#">Taunt</a></li>
									<li><a href="#">Other</a></li>
									<li class="divider"></li>
									<li><a href="#">Demoman</a></li>
									<li><a href="#">Engineer</a></li>
									<li><a href="#">Heavy</a></li>
									<li><a href="#">Medic</a></li>
									<li><a href="#">Pyro</a></li>
									<li><a href="#">Scout</a></li>
									<li><a href="#">Sniper</a></li>
									<li><a href="#">Soldier</a></li>
									<li><a href="#">Spy</a></li>
								</ul>
							</div>
						</div>
						<div id="weaponlist">
							<table class="table-weapons" style="margin:0px auto;width:100%">
								<thead>
									<tr>
										<th colspan="2">
											<div class="pull-left" style="width:50%"><h4 style="text-align:center;padding:0px 10px">Weapon</h4></div>
											<div class="pull-right" style="width:50%"><h4 style="text-align:center;padding:0px 10px">Kills</h4></div>
										</th>
									</tr>
								</thead>
								<tbody>
<?php foreach ($log as $log): ?>
									<tr class="getweapon">
										<td>
											<img src="<?php echo $log['image']; ?>" alt="<?php echo $log['weapon']; ?>">
											<input type="hidden" value="<?php echo $log['weapon']; ?>"/>
											<br>
											<span><?php echo $log['name']; ?></span>
										</td>
										<td>
											<h4><?php echo number_format($log['kills']); ?></h4>
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
							<h3 id="action" class="panel-title" style="text-align:center">Weapon Information</h3>
						</div>
						<div id="result" align="center" style="padding:10px">
							<div id="loader">Weapon information will be displayed here.</div>
						</div>
						<div class="panel-footer-inverse"></div>
					</div>
				</div>
			</div>
		</div>
<?php include 'inc/footer.php'; ?>
	</div>
<script>
$(document).on("click",".getweapon",function(){
	$.ajax({
		type: "GET",
		url: "inc/getwep.php",
		data: 'id=' + $(this).find("input").val(),
		success: function(msg){
			$('#result').html(msg);
		}
	});
});

$(document.body).on('click', '.dropdown-menu li', function(event) {
	var $target = $(event.currentTarget);
	$target.closest('.btn-group')
		.find('[data-bind="label"]').text($target.text())
		.end()
		.children('.dropdown-toggle').dropdown('toggle');
		$.ajax({
			type: "GET",
			url: "inc/getwepinfo.php",
			data: 'id=' + $target.text(),
			success: function(msg){
			$('#weaponlist').html(msg);
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
<script>

function affixWidth() {
	var affix = $('#affixed');
	var width = affix.width();
	affix.width(width);
}

$(window).on('load resize', function(){
	$('#affixed').affix({
		offset: { 
			top: $('#body').offset().top,
			bottom: 120
		}
	});

	affixWidth();
	var w = $(window).width();
	var h = $(window).height();

	$('#result').height(h);
	$('#affixed').height(h);
});
</script>
</body>
</html>