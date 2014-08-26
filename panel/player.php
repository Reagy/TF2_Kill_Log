<?php 

//Include Config
include "inc/config.php";

// Include database class
include 'inc/database.class.php';

// Instantiate database.
$database = new Database();

$database->query('SELECT * FROM playerlog WHERE auth = :id');
$database->bind(':id', $_GET['id']);
$player = $database->resultset();

foreach ($player as $player) {
	foreach ($player as $key => $value) {
		$player[$key] = $value;
	}
}

$communityID = SteamTo64($player['auth']);

$database->query('SELECT * FROM profiles WHERE communityid = :id');
$database->bind(':id', $communityID);
$profile = $database->single();

if (!$profile || ($profile['time'] < strtotime("-1 week"))) {
	$info = GetPlayerInformation($communityID);

	$database->query('INSERT INTO profiles SET communityid = :id, avatar = :avatar, time = UNIX_TIMESTAMP() ON DUPLICATE KEY UPDATE avatar = :avatar, time = UNIX_TIMESTAMP()');
	$database->bind(':id', $communityID);
	$database->bind(':avatar', $info['avatarfull']);
	$database->execute();

	$database->query('SELECT * FROM profiles WHERE communityid = :id');
	$database->bind(':id', $communityID);
	$profile = $database->single();
}

$url = "http://steamcommunity.com/profiles/".$communityID;

?>
<?php include("inc/nav.php"); ?>
		<div id="body" style="background-color:#f5f5f5;height:100%;border:1px solid #222222;margin-bottom:-10px">
			<div class="row">
				<div class="col-sm-3 hidden-xs">
					<div id="affixed" class="profile-nav">
						<div class="profile-stats-summary">
							<h2>Player</h2>
						</div>
						<div class="profile-avatar" style="cursor:pointer">
							<img class="desaturate img-circle avatar" src="<?php echo $profile['avatar']; ?>">
							<h4><?php echo htmlentities($player['name']); ?></h4>
						</div>
						<div class="profile-info">
							<h4>SteamID</h4>
							<span class="badge-profile"><?php echo $player['auth']; ?></span>
							<h4>Location</h4>
							<span class="badge-profile"><?php echo $player['cc']; ?></span>
							<h4>Playtime</h4>
							<span class="badge-profile"><?php echo PlaytimeCon($player['playtime'])." hrs"; ?></span>
							<h4>Last On</h4>
							<span class="badge-profile"><?php echo date('m/d/y H:i', $player['disconnect_time']); ?></span>
						</div>
					</div>
				</div>
        <div class="col-sm-3 visible-xs">
					<div class="row" style="background-color:#222222;margin:0;color:#ffffff;border-bottom:1px solid #222222">
						<div class="col-xs-6" style="margin:0;padding:0">
							<div class="profile-avatar" style="cursor:pointer">
								<img class="desaturate img-circle avatar" src="<?php echo $profile['avatar']; ?>">
								<h4><?php echo htmlentities($player['name']); ?></h4>
							</div>
						</div>
						<div class="col-xs-6">
							<div class="profile-info">
								<h4>SteamID</h4>
								<span class="badge-profile"><?php echo $player['auth']; ?></span>
								<h4>Location</h4>
								<span class="badge-profile"><?php echo $player['cc']; ?></span>
								<h4>Playtime</h4>
								<span class="badge-profile"><?php echo PlaytimeCon($player['playtime'])." hrs"; ?></span>
								<h4>Last On</h4>
								<span class="badge-profile"><?php echo date('m/d/y H:i', $player['disconnect_time']); ?></span>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-9">
					<div style="padding-top:20px;padding-right:25px">
						<ul class="nav nav-tabs nav-justified" role="tablist" data-tabs="tabs">
							<li id="getplayerstats" class="active"><a href="#" data-toggle="tab">Player Stats</a></li>
							<li id="getplayerweplist"><a href="#" data-toggle="tab">Weapons</a></li>
							<li id="getplayerobject"><a href="#" data-toggle="tab">Buildings</a></li>
							<li id="getplayerteam"><a href="#" data-toggle="tab">Team</a></li>
							<li id="getplayerlog"><a href="#" data-toggle="tab">Kill Log</a></li>
						</ul>
						<div>
							<div id="content" class="panel-body" style="padding:0px">
								<table class="table table-bordered table-striped table-hover table-condensed display">
									<tbody>
										<tr>
											<td>Total Kills</td>
											<td><?php echo $player['kills']; ?></td>
										</tr>
										<tr>
											<td>Total Deaths</td>
											<td><?php echo $player['deaths']; ?></td>
										</tr>
										<tr>
											<td>Total Assists</td>
											<td><?php echo $player['assists']; ?></td>
										</tr>
										<tr>
											<td>Total Dominations</td>
											<td><?php echo $player['dominations']; ?></td>
										</tr>
										<tr>
											<td>Total Revenges</td>
											<td><?php echo $player['revenges']; ?></td>
										</tr>
										<tr>
											<td>Total Headshots</td>
											<td><?php echo $player['headshots']; ?></td>
										</tr>
										<tr>
											<td>Total Backstabs</td>
											<td><?php echo $player['backstabs']; ?></td>
										</tr>
										<tr>
											<td>Total Objects Built</td>
											<td><?php echo $player['obj_built']; ?></td>
										</tr>
										<tr>
											<td>Total Objects Destroyed</td>
											<td><?php echo $player['obj_destroy']; ?></td>
										</tr>
										<tr>
											<td>Total Players Teleported</td>
											<td><?php echo $player['tele_player']; ?></td>
										</tr>
										<tr>
											<td>Total CTF Picked</td>
											<td><?php echo $player['flag_pick']; ?></td>
										</tr>
										<tr>
											<td>Total CTF Captured</td>
											<td><?php echo $player['flag_cap']; ?></td>
										</tr>
										<tr>
											<td>Total CTF Defended</td>
											<td><?php echo $player['flag_def']; ?></td>
										</tr>
										<tr>
											<td>Total CP Captured</td>
											<td><?php echo $player['cp_cap']; ?></td>
										</tr>
										<tr>
											<td>Total CP Blocked</td>
											<td><?php echo $player['cp_block']; ?></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
<?php include "inc/footer.php"; ?>
	</div>
<script>
$('.nav-tabs').on("click","li",function(){
	$.ajax({
		type: "GET",
		url: "inc/"+$(this).attr('id')+".php",
		data: 'id=<?php echo $_GET["id"] ?>',
		success: function(msg){
			$('#content').html(msg);
		}
	});
});
$('.profile-avatar').on('click', function () {
	window.location = "<?php echo $url; ?>";
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
			top: $('#body').offset().top
		}
	});

	affixWidth();
	var w = $(window).width();
	var h = $(window).height();

	$('#content').height(h);
	$('#affixed').height(h);
});

</script>
</body>
</html>