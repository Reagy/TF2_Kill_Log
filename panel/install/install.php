<?php 

//Database Info
include '../inc/config.php';

// Include database class
include '../inc/database.class.php';

// Instantiate database.
$database = new Database();

if ($_GET['query'] == 'items') {

	$datafields = array('index','name','image','class','slot','type');

	$schema = file_get_contents('http://git.optf2.com/schema-tracking/plain/Team%20Fortress%202%20Schema?h=teamfortress2');
	$schema = json_decode($schema, true);
	$schema = $schema['result']['items'];

	foreach ($schema as $key => $value) {
		if (empty($value['image_url'])) {
			continue;
		}
		if (strpos($value['name'], 'Paint Can') !== FALSE) {
			$name = $value['item_name'];
		}
		else {
			$name = $value['item_name'];
		}
		if (!empty($value['used_by_classes'])) {
			$class = implode(', ', $value['used_by_classes']);
		}
		else {
			$class = 'All';
		}
		if (!empty($value['tool']['type'])) {
			$type = $value['tool']['type'];
		}
		else {
			$type = $value['item_type_name'];
		}
		if (!isset($value['item_slot'])) {
			$slot = $value['item_class'];
		}
		else {
			$slot = $value['item_slot'];
		}
		
		$data[] = array('index' => $value['defindex'], 'name' => addslashes($name), 'image' => $value['image_url'], 'class' => $class, 'slot' => $slot, 'type' => addslashes($type));
	}
	$database->query('DROP TABLE IF EXISTS `items`;');
	$database->execute();

	$database->query('CREATE TABLE IF NOT EXISTS `items` (
		`index` int(6) NOT NULL DEFAULT "0",
		`name` varchar(64) DEFAULT NULL,
		`image` varchar(128) DEFAULT NULL,
		`class` varchar(72) DEFAULT NULL,
		`slot` varchar(24) DEFAULT NULL,
		`type` varchar(72) NOT NULL,
		PRIMARY KEY (`index`),
		KEY `class` (`class`),
		KEY `slot` (`slot`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;');
	$database->execute();

	$database->query('INSERT INTO `items` (`index`,`name`,`image`,`class`,`slot`,`type`) VALUES (:index, :name, :image, :class, :slot, :type)');

	foreach($data as $key => $value){
		$database->bind(':index', $value['index']);
		$database->bind(':name', $value['name']);
		$database->bind(':image', $value['image']);
		$database->bind(':class', $value['class']);
		$database->bind(':slot', $value['slot']);
		$database->bind(':type', $value['type']);
		$database->execute();
	}

	$database->query('DROP TABLE IF EXISTS `items_method`;');
	$database->execute();

	$database->query('CREATE TABLE IF NOT EXISTS `items_method` (
		`method_type` int(2) NOT NULL,
		`method_text` text NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
	$database->execute();

	$database->query('INSERT INTO `items_method` (`method_type`, `method_text`) VALUES
		(0, "Found"),
		(1, "Crafted"),
		(2, "Traded"),
		(3, "Bought"),
		(4, "Unboxed"),
		(5, "Gifted"),
		(8, "Earned"),
		(9, "Refunded"),
		(10, "Wrapped"),
		(15, "Earned"),
		(16, "MvM"),
		(18, "Holiday Gift"),
		(20, "MvM"),
		(21, "MvM");');
	$database->execute();

	$database->query('DROP TABLE IF EXISTS `items_quality`;');
	$database->execute();

	$database->query('CREATE TABLE IF NOT EXISTS `items_quality` (
		`quality_type` int(2) NOT NULL,
		`quality_text` varchar(24) NOT NULL,
		`quality_color` varchar(7) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
	$database->execute();

	$database->query('INSERT INTO `items_quality` (`quality_type`, `quality_text`, `quality_color`) VALUES
		(1, "Genuine", "#4D7455"),
		(3, "Vintage", "#476291"),
		(5, "Unusual", "#8650AC"),
		(6, "Unique", "#FFD700"),
		(7, "Community", "#70B04A"),
		(9, "Self-Made", "#70B04A"),
		(11, "Strange", "#CF6A32"),
		(13, "Haunted", "#38F3AB"),
		(14, "Collectors", "#AA0000");');
	$database->execute();

}

if ($_GET['query'] == 'profiles') {
	$database->query('CREATE TABLE IF NOT EXISTS `profiles` (
		`communityid` varchar(18) NOT NULL,
		`avatar` varchar(128) NOT NULL,
		`time` int(11) NOT NULL,
		UNIQUE KEY `communityid` (`communityid`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
	$database->execute();
}

if ($_GET['query'] == 'weapons') {
	$database->query('DROP TABLE IF EXISTS `weapons`;');
	$database->execute();

	$database->query('CREATE TABLE IF NOT EXISTS `weapons` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`index` int(6) DEFAULT NULL,
		`name` varchar(150) DEFAULT NULL,
		`weapon` varchar(150) DEFAULT NULL,
		`slot` varchar(12) DEFAULT NULL,
		`class` varchar(70) DEFAULT NULL,
		`image` varchar(150) DEFAULT NULL,
		PRIMARY KEY (`id`),
		KEY `weapon` (`weapon`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;');
	$database->execute();

	$weapons = array(
	  array('index' => '0','name' => 'Bat','weapon' => 'bat','slot' => 'Melee','class' => 'Scout','image' => 'images/weaponicons/Killicon_bat.png'),
	  array('index' => '1','name' => 'Bottle','weapon' => 'bottle','slot' => 'Melee','class' => 'Demoman','image' => 'images/weaponicons/Killicon_bottle.png'),
	  array('index' => '2','name' => 'Fire Axe','weapon' => 'fireaxe','slot' => 'Melee','class' => 'Pyro','image' => 'images/weaponicons/Killicon_fireaxe.png'),
	  array('index' => '3','name' => 'Kukri','weapon' => 'club','slot' => 'Melee','class' => 'Sniper','image' => 'images/weaponicons/Killicon_kukri.png'),
	  array('index' => '4','name' => 'Knife','weapon' => 'knife','slot' => 'Melee','class' => 'Spy','image' => 'images/weaponicons/Killicon_knife.png'),
	  array('index' => '5','name' => 'Fists','weapon' => 'fists','slot' => 'Melee','class' => 'Heavy','image' => 'images/weaponicons/Killicon_fists.png'),
	  array('index' => '6','name' => 'Shovel','weapon' => 'shovel','slot' => 'Melee','class' => 'Soldier','image' => 'images/weaponicons/Killicon_shovel.png'),
	  array('index' => '7','name' => 'Wrench','weapon' => 'wrench','slot' => 'Melee','class' => 'Engineer','image' => 'images/weaponicons/Killicon_wrench.png'),
	  array('index' => '8','name' => 'Bonesaw ','weapon' => 'bonesaw','slot' => 'Melee','class' => 'Medic','image' => 'images/weaponicons/Killicon_bonesaw.png'),
	  array('index' => '9','name' => 'Engineer\'s Shotgun','weapon' => 'shotgun_primary','slot' => 'Secondary','class' => 'Engineer','image' => 'images/weaponicons/Killicon_shotgun.png'),
	  array('index' => '10','name' => 'Soldier\'s Shotgun','weapon' => 'shotgun_soldier','slot' => 'Secondary','class' => 'Soldier','image' => 'images/weaponicons/Killicon_shotgun.png'),
	  array('index' => '11','name' => 'Heavy\'s Shotgun','weapon' => 'shotgun_hwg','slot' => 'Secondary','class' => 'Heavy','image' => 'images/weaponicons/Killicon_shotgun.png'),
	  array('index' => '12','name' => 'Pyro\'s Shotgun','weapon' => 'shotgun_pyro','slot' => 'Secondary','class' => 'Pyro','image' => 'images/weaponicons/Killicon_shotgun.png'),
	  array('index' => '13','name' => 'Scattergun','weapon' => 'scattergun','slot' => 'Primary','class' => 'Scout','image' => 'images/weaponicons/Killicon_scattergun.png'),
	  array('index' => '14','name' => 'Sniper Rifle','weapon' => 'sniperrifle','slot' => 'Primary','class' => 'Sniper','image' => 'images/weaponicons/Killicon_sniperrifle.png'),
	  array('index' => '15','name' => 'Minigun','weapon' => 'minigun','slot' => 'Primary','class' => 'Heavy','image' => 'images/weaponicons/Killicon_minigun.png'),
	  array('index' => '16','name' => 'SMG','weapon' => 'smg','slot' => 'Secondary','class' => 'Sniper','image' => 'images/weaponicons/Killicon_smg.png'),
	  array('index' => '17','name' => 'Syringe Gun','weapon' => 'syringegun_medic','slot' => 'Primary','class' => 'Medic','image' => 'images/weaponicons/Killicon_syringegun.png'),
	  array('index' => '18','name' => 'Rocket Launcher','weapon' => 'tf_projectile_rocket','slot' => 'Primary','class' => 'Soldier','image' => 'images/weaponicons/Killicon_rocketlauncher.png'),
	  array('index' => '19','name' => 'Grenade Launcher','weapon' => 'tf_projectile_pipe','slot' => 'Primary','class' => 'Demoman','image' => 'images/weaponicons/Killicon_grenade_launcher.png'),
	  array('index' => '20','name' => 'Stickybomb Launcher','weapon' => 'tf_projectile_pipe_remote','slot' => 'Secondary','class' => 'Demoman','image' => 'images/weaponicons/Killicon_stickybomb_launcher.png'),
	  array('index' => '21','name' => 'Flamethrower','weapon' => 'flamethrower','slot' => 'Primary','class' => 'Pyro','image' => 'images/weaponicons/Killicon_flamethrower.png'),
	  array('index' => '22','name' => 'Engineer\'s Pistol','weapon' => 'pistol','slot' => 'Secondary','class' => 'Engineer','image' => 'images/weaponicons/Killicon_pistol.png'),
	  array('index' => '23','name' => 'Scout\'s Pistol','weapon' => 'pistol_scout','slot' => 'Secondary','class' => 'Scout','image' => 'images/weaponicons/Killicon_pistol.png'),
	  array('index' => '24','name' => 'Revolver','weapon' => 'revolver','slot' => 'Secondary','class' => 'Spy','image' => 'images/weaponicons/Killicon_revolver.png'),
	  array('index' => '36','name' => 'Blutsauger','weapon' => 'blutsauger','slot' => 'Primary','class' => 'Medic','image' => 'images/weaponicons/Killicon_blutsauger.png'),
	  array('index' => '37','name' => 'Ubersaw','weapon' => 'ubersaw','slot' => 'Melee','class' => 'Medic','image' => 'images/weaponicons/Killicon_ubersaw.png'),
	  array('index' => '38','name' => 'Axtinguisher','weapon' => 'axtinguisher','slot' => 'Melee','class' => 'Pyro','image' => 'images/weaponicons/Killicon_axtinguisher.png'),
	  array('index' => '39','name' => 'Flare Gun','weapon' => 'flaregun','slot' => 'Secondary','class' => 'Pyro','image' => 'images/weaponicons/Killicon_flare_gun.png'),
	  array('index' => '40','name' => 'Backburner','weapon' => 'backburner','slot' => 'Primary','class' => 'Pyro','image' => 'images/weaponicons/Killicon_backburner.png'),
	  array('index' => '41','name' => 'Natascha','weapon' => 'natascha','slot' => 'Primary','class' => 'Heavy','image' => 'images/weaponicons/Killicon_natascha.png'),
	  array('index' => '43','name' => 'Killing Gloves Of Boxing','weapon' => 'gloves','slot' => 'Melee','class' => 'Heavy','image' => 'images/weaponicons/Killicon_kgb.png'),
	  array('index' => '44','name' => 'Sandman','weapon' => 'sandman','slot' => 'Melee','class' => 'Scout','image' => 'images/weaponicons/Killicon_sandman.png'),
	  array('index' => '45','name' => 'Force-A-Nature','weapon' => 'force_a_nature','slot' => 'Primary','class' => 'Scout','image' => 'images/weaponicons/Killicon_force_a_nature.png'),
	  array('index' => '56','name' => 'Huntsman','weapon' => 'tf_projectile_arrow','slot' => 'Primary','class' => 'Sniper','image' => 'images/weaponicons/Killicon_huntsman.png'),
	  array('index' => '61','name' => 'Ambassador','weapon' => 'ambassador','slot' => 'Secondary','class' => 'Spy','image' => 'images/weaponicons/Killicon_ambassador.png'),
	  array('index' => '127','name' => 'Direct Hit','weapon' => 'rocketlauncher_directhit','slot' => 'Primary','class' => 'Soldier','image' => 'images/weaponicons/Killicon_direct_hit.png'),
	  array('index' => '128','name' => 'Equalizer','weapon' => 'unique_pickaxe','slot' => 'Melee','class' => 'Soldier','image' => 'images/weaponicons/Killicon_equalizer.png'),
	  array('index' => '130','name' => 'Scottish Resistance','weapon' => 'sticky_resistance','slot' => 'Secondary','class' => 'Demoman','image' => 'images/weaponicons/Killicon_scottish_resistance.png'),
	  array('index' => '131','name' => 'Chargin\' Targe','weapon' => 'demoshield','slot' => 'Secondary','class' => 'Demoman','image' => 'images/weaponicons/Killicon_chargin_targe.png'),
	  array('index' => '132','name' => 'Eyelander','weapon' => 'sword','slot' => 'Melee','class' => 'Demoman','image' => 'images/weaponicons/Killicon_eyelander.png'),
	  array('index' => '140','name' => 'Wrangler','weapon' => 'wrangler_kill','slot' => 'Secondary','class' => 'Engineer','image' => 'images/weaponicons/Killicon_wrangler.png'),
	  array('index' => '141','name' => 'Frontier Justice','weapon' => 'frontier_justice','slot' => 'Primary','class' => 'Engineer','image' => 'images/weaponicons/Killicon_frontier_justice.png'),
	  array('index' => '142','name' => 'Gunslinger (Triple Punch)','weapon' => 'robot_arm_combo_kill','slot' => 'Melee','class' => 'Engineer','image' => 'images/weaponicons/Killicon_gunslinger_triple_punch.png'),
	  array('index' => '142','name' => 'Gunslinger','weapon' => 'robot_arm','slot' => 'Melee','class' => 'Engineer','image' => 'images/weaponicons/Killicon_gunslinger.png'),
	  array('index' => '153','name' => 'Homewrecker','weapon' => 'sledgehammer','slot' => 'Melee','class' => 'Pyro','image' => 'images/weaponicons/Killicon_homewrecker.png'),
	  array('index' => '154','name' => 'Pain Train','weapon' => 'paintrain','slot' => 'Melee','class' => 'Soldier, Demoman','image' => 'images/weaponicons/Killicon_pain_train.png'),
	  array('index' => '155','name' => 'Southern Hospitality','weapon' => 'southern_hospitality','slot' => 'Melee','class' => 'Engineer','image' => 'images/weaponicons/Killicon_southern_hospitality.png'),
	  array('index' => '161','name' => 'Big Kill','weapon' => 'samrevolver','slot' => 'Secondary','class' => 'Spy','image' => 'images/weaponicons/Killicon_samgun.png'),
	  array('index' => '169','name' => 'Golden Wrench','weapon' => 'wrench_golden','slot' => 'Melee','class' => 'Engineer','image' => 'images/weaponicons/Killicon_golden_wrench.png'),
	  array('index' => '171','name' => 'Tribalman\'s Shiv','weapon' => 'tribalkukri','slot' => 'Melee','class' => 'Sniper','image' => 'images/weaponicons/Killicon_tribalman\'s_shiv.png'),
	  array('index' => '172','name' => 'Scotsman\'s Skullcutter','weapon' => 'battleaxe','slot' => 'Melee','class' => 'Demoman','image' => 'images/weaponicons/Killicon_scotsman\'s_skullcutter.png'),
	  array('index' => '173','name' => 'Vita-Saw','weapon' => 'battleneedle','slot' => 'Melee','class' => 'Medic','image' => 'images/weaponicons/Killicon_vita-saw.png'),
	  array('index' => '214','name' => 'Powerjack','weapon' => 'powerjack','slot' => 'Melee','class' => 'Pyro','image' => 'images/weaponicons/Killicon_powerjack.png'),
	  array('index' => '215','name' => 'Degreaser','weapon' => 'degreaser','slot' => 'Primary','class' => 'Pyro','image' => 'images/weaponicons/Killicon_degreaser.png'),
	  array('index' => '220','name' => 'Shortstop','weapon' => 'short_stop','slot' => 'Primary','class' => 'Scout','image' => 'images/weaponicons/Killicon_shortstop.png'),
	  array('index' => '221','name' => 'Holy Mackerel','weapon' => 'holy_mackerel','slot' => 'Melee','class' => 'Scout','image' => 'images/weaponicons/Killicon_holy_mackerel.png'),
	  array('index' => '224','name' => 'L\'Etranger','weapon' => 'letranger','slot' => 'Secondary','class' => 'Spy','image' => 'images/weaponicons/Killicon_l\'etranger.png'),
	  array('index' => '225','name' => 'Your Eternal Reward','weapon' => 'eternal_reward','slot' => 'Melee','class' => 'Spy','image' => 'images/weaponicons/Killicon_your_eternal_reward.png'),
	  array('index' => '228','name' => 'Black Box','weapon' => 'blackbox','slot' => 'Primary','class' => 'Soldier','image' => 'images/weaponicons/Killicon_black_box.png'),
	  array('index' => '230','name' => 'Sydney Sleeper','weapon' => 'sydney_sleeper','slot' => 'Primary','class' => 'Sniper','image' => 'images/weaponicons/Killicon_sydney_sleeper.png'),
	  array('index' => '232','name' => 'Bushwacka','weapon' => 'bushwacka','slot' => 'Melee','class' => 'Sniper','image' => 'images/weaponicons/Killicon_bushwacka.png'),
	  array('index' => '239','name' => 'Gloves of Running Urgently','weapon' => 'gloves_running_urgently','slot' => 'Melee','class' => 'Heavy','image' => 'images/weaponicons/Killicon_gru.png'),
	  array('index' => '264','name' => 'Frying Pan','weapon' => 'fryingpan','slot' => 'Melee','class' => 'Scout, Sniper, Soldier, Demoman, Medic, Heavy, Pyro','image' => 'images/weaponicons/Killicon_frying_pan.png'),
	  array('index' => '266','name' => 'Horseless Headless Horsemann\'s Headtaker','weapon' => 'headtaker','slot' => 'Melee','class' => 'Demoman','image' => 'images/weaponicons/Killicon_horseless_headless_horsemann\'s_headtaker.png'),
	  array('index' => '294','name' => 'Lugermorph','weapon' => 'maxgun','slot' => 'Secondary','class' => 'Scout, Engineer','image' => 'images/weaponicons/Killicon_maxgun.png'),
	  array('index' => '298','name' => 'Iron Curtain','weapon' => 'iron_curtain','slot' => 'Primary','class' => 'Heavy','image' => 'images/weaponicons/Killicon_iron_curtain.png'),
	  array('index' => '304','name' => 'Amputator','weapon' => 'amputator','slot' => 'Melee','class' => 'Medic','image' => 'images/weaponicons/Killicon_amputator.png'),
	  array('index' => '305','name' => 'Crusader\'s Crossbow','weapon' => 'crusaders_crossbow','slot' => 'Primary','class' => 'Medic','image' => 'images/weaponicons/Killicon_crusader\'s_crossbow.png'),
	  array('index' => '307','name' => 'Ullapool Caber Explosion','weapon' => 'ullapool_caber_explosion','slot' => 'Melee','class' => 'Demoman','image' => 'images/weaponicons/Killicon_ullapool_caber_explode.png'),
	  array('index' => '307','name' => 'Ullapool Caber','weapon' => 'ullapool_caber','slot' => 'Melee','class' => 'Demoman','image' => 'images/weaponicons/Killicon_ullapool_caber.png'),
	  array('index' => '308','name' => 'Loch-n-Load','weapon' => 'loch_n_load','slot' => 'Primary','class' => 'Demoman','image' => 'images/weaponicons/Killicon_loch-n-load.png'),
	  array('index' => '310','name' => 'Warrior\'s Spirit','weapon' => 'warrior_spirit','slot' => 'Melee','class' => 'Heavy','image' => 'images/weaponicons/Killicon_warrior\'s_spirit.png'),
	  array('index' => '312','name' => 'Brass Beast','weapon' => 'brass_beast','slot' => 'Primary','class' => 'Heavy','image' => 'images/weaponicons/Killicon_brass_beast.png'),
	  array('index' => '317','name' => 'Candy Cane','weapon' => 'candy_cane','slot' => 'Melee','class' => 'Scout','image' => 'images/weaponicons/Killicon_candy_cane.png'),
	  array('index' => '325','name' => 'Boston Basher','weapon' => 'boston_basher','slot' => 'Melee','class' => 'Scout','image' => 'images/weaponicons/Killicon_boston_basher.png'),
	  array('index' => '326','name' => 'Back Scratcher','weapon' => 'back_scratcher','slot' => 'Melee','class' => 'Pyro','image' => 'images/weaponicons/Killicon_back_scratcher.png'),
	  array('index' => '327','name' => 'Claidheamh Mòr','weapon' => 'claidheamohmor','slot' => 'Melee','class' => 'Demoman','image' => 'images/weaponicons/Killicon_claidheamh_mor.png'),
	  array('index' => '329','name' => 'Jag','weapon' => 'wrench_jag','slot' => 'Melee','class' => 'Engineer','image' => 'images/weaponicons/Killicon_jag.png'),
	  array('index' => '331','name' => 'Fists of Steel','weapon' => 'steel_fists','slot' => 'Melee','class' => 'Heavy','image' => 'images/weaponicons/Killicon_fists_of_steel.png'),
	  array('index' => '348','name' => 'Sharpened Volcano Fragment','weapon' => 'lava_axe','slot' => 'Melee','class' => 'Pyro','image' => 'images/weaponicons/Killicon_sharpened_volcano_fragment.png'),
	  array('index' => '349','name' => 'Sun-on-a-Stick','weapon' => 'lava_bat','slot' => 'Melee','class' => 'Scout','image' => 'images/weaponicons/Killicon_sun-on-a-stick.png'),
	  array('index' => '351','name' => 'Detonator','weapon' => 'detonator','slot' => 'Secondary','class' => 'Pyro','image' => 'images/weaponicons/Killicon_detonator.png'),
	  array('index' => '355','name' => 'Fan O\'War','weapon' => 'warfan','slot' => 'Melee','class' => 'Scout','image' => 'images/weaponicons/Killicon_fan_owar.png'),
	  array('index' => '356','name' => 'Conniver\'s Kunai','weapon' => 'kunai','slot' => 'Melee','class' => 'Spy','image' => 'images/weaponicons/Killicon_connivers_kunai.png'),
	  array('index' => '357','name' => 'Half-Zatoichi','weapon' => 'demokatana','slot' => 'Melee','class' => 'Soldier, Demoman','image' => 'images/weaponicons/Killicon_half-zatoichi.png'),
	  array('index' => '401','name' => 'Shahanshah','weapon' => 'shahanshah','slot' => 'Melee','class' => 'Sniper','image' => 'images/weaponicons/Killicon_shahanshah.png'),
	  array('index' => '402','name' => 'Bazaar Bargain','weapon' => 'bazaar_bargain','slot' => 'Primary','class' => 'Sniper','image' => 'images/weaponicons/Killicon_bazaar_bargain.png'),
	  array('index' => '404','name' => 'Persian Persuader','weapon' => 'persian_persuader','slot' => 'Melee','class' => 'Demoman','image' => 'images/weaponicons/Killicon_persian_persuader.png'),
	  array('index' => '406','name' => 'Splendid Screen','weapon' => 'splendid_screen','slot' => 'Secondary','class' => 'Demoman','image' => 'images/weaponicons/Killicon_splendid_screen.png'),
	  array('index' => '412','name' => 'Overdose','weapon' => 'proto_syringe','slot' => 'Primary','class' => 'Medic','image' => 'images/weaponicons/Killicon_overdose.png'),
	  array('index' => '413','name' => 'Solemn Vow','weapon' => 'solemn_vow','slot' => 'Melee','class' => 'Medic','image' => 'images/weaponicons/Killicon_solemn_vow.png'),
	  array('index' => '414','name' => 'Liberty Launcher','weapon' => 'liberty_launcher','slot' => 'Primary','class' => 'Soldier','image' => 'images/weaponicons/Killicon_liberty_launcher.png'),
	  array('index' => '415','name' => 'Reserve Shooter','weapon' => 'reserve_shooter','slot' => 'Secondary','class' => 'Soldier, Pyro','image' => 'images/weaponicons/Killicon_reserve_shooter.png'),
	  array('index' => '416','name' => 'Market Gardener','weapon' => 'market_gardener','slot' => 'Melee','class' => 'Soldier','image' => 'images/weaponicons/Killicon_market_gardener.png'),
	  array('index' => '423','name' => 'Saxxy','weapon' => 'saxxy','slot' => 'Melee','class' => 'Soldier','image' => 'images/weaponicons/Killicon_saxxy.png'),
	  array('index' => '424','name' => 'Tomislav','weapon' => 'tomislav','slot' => 'Primary','class' => 'Heavy','image' => 'images/weaponicons/Killicon_tomislav.png'),
	  array('index' => '425','name' => 'Family Business','weapon' => 'family_business','slot' => 'Secondary','class' => 'Heavy','image' => 'images/weaponicons/Killicon_family_business.png'),
	  array('index' => '426','name' => 'Eviction Notice','weapon' => 'eviction_notice','slot' => 'Melee','class' => 'Heavy','image' => 'images/weaponicons/Killicon_eviction_notice.png'),
	  array('index' => '441','name' => 'Cow Mangler 5000','weapon' => 'cow_mangler','slot' => 'Primary','class' => 'Soldier','image' => 'images/weaponicons/Killicon_cow_mangler_5000.png'),
	  array('index' => '441','name' => 'Cow Mangler 5000','weapon' => 'cow_mangler','slot' => 'Primary','class' => 'Soldier','image' => 'images/weaponicons/Killicon_fire.png'),
	  array('index' => '442','name' => 'Righteous Bison','weapon' => 'righteous_bison','slot' => 'Secondary','class' => 'Soldier','image' => 'images/weaponicons/Killicon_righteous_bison.png'),
	  array('index' => '444','name' => 'Mantreads','weapon' => 'mantreads','slot' => 'Secondary','class' => 'Soldier','image' => 'images/weaponicons/Killicon_mantreads.png'),
	  array('index' => '447','name' => 'Disciplinary Action','weapon' => 'disciplinary_action','slot' => 'Melee','class' => 'Soldier','image' => 'images/weaponicons/Killicon_disciplinary_action.png'),
	  array('index' => '448','name' => 'Soda Popper','weapon' => 'soda_popper','slot' => 'Primary','class' => 'Scout','image' => 'images/weaponicons/Killicon_soda_popper.png'),
	  array('index' => '449','name' => 'Winger','weapon' => 'the_winger','slot' => 'Secondary','class' => 'Scout','image' => 'images/weaponicons/Killicon_winger.png'),
	  array('index' => '450','name' => 'Atomizer','weapon' => 'atomizer','slot' => 'Melee','class' => 'Scout','image' => 'images/weaponicons/Killicon_atomizer.png'),
	  array('index' => '452','name' => 'Three-Rune Blade','weapon' => 'scout_sword','slot' => 'Melee','class' => 'Scout','image' => 'images/weaponicons/Killicon_three-rune_blade.png'),
	  array('index' => '457','name' => 'Postal Pummeler','weapon' => 'mailbox','slot' => 'Melee','class' => 'Pyro','image' => 'images/weaponicons/Killicon_postal_pummeler.png'),
	  array('index' => '460','name' => 'Enforcer','weapon' => 'enforcer','slot' => 'Secondary','class' => 'Spy','image' => 'images/weaponicons/Killicon_enforcer.png'),
	  array('index' => '461','name' => 'Big Earner','weapon' => 'big_earner','slot' => 'Melee','class' => 'Spy','image' => 'images/weaponicons/Killicon_big_earner.png'),
	  array('index' => '466','name' => 'Maul','weapon' => 'the_maul','slot' => 'Melee','class' => 'Pyro','image' => 'images/weaponicons/Killicon_maul.png'),
	  array('index' => '474','name' => 'Conscientious Objector','weapon' => 'nonnonviolent_protest','slot' => 'Melee','class' => 'Scout, Sniper, Soldier, Demoman, Medic, Heavy, Pyro','image' => 'images/weaponicons/Killicon_conscientious_objector.png'),
	  array('index' => '482','name' => 'Nessie\'s Nine Iron','weapon' => 'nessieclub','slot' => 'Melee','class' => 'Demoman','image' => 'images/weaponicons/Killicon_nessie\'s_nine_iron.png'),
	  array('index' => '513','name' => 'Original','weapon' => 'quake_rl','slot' => 'Primary','class' => 'Soldier','image' => 'images/weaponicons/Killicon_original.png'),
	  array('index' => '525','name' => 'Diamondback','weapon' => 'diamondback','slot' => 'Secondary','class' => 'Spy','image' => 'images/weaponicons/Killicon_diamondback.png'),
	  array('index' => '526','name' => 'Machina Double Kill','weapon' => 'player_penetration','slot' => 'Primary','class' => 'Sniper','image' => 'images/weaponicons/Killicon_machina_penetrate.png'),
	  array('index' => '526','name' => 'Machina','weapon' => 'machina','slot' => 'Primary','class' => 'Sniper','image' => 'images/weaponicons/Killicon_machina.png'),
	  array('index' => '527','name' => 'Widowmaker','weapon' => 'widowmaker','slot' => 'Primary','class' => 'Engineer','image' => 'images/weaponicons/Killicon_widowmaker.png'),
	  array('index' => '528','name' => 'Short Circuit','weapon' => 'short_circuit','slot' => 'Secondary','class' => 'Engineer','image' => 'images/weaponicons/Killicon_short_circuit.png'),
	  array('index' => '572','name' => 'Unarmed Combat','weapon' => 'unarmed_combat','slot' => 'Melee','class' => 'Scout','image' => 'images/weaponicons/Killicon_unarmed_combat.png'),
	  array('index' => '574','name' => 'Wanga Prick','weapon' => 'voodoo_pin','slot' => 'Melee','class' => 'Spy','image' => 'images/weaponicons/Killicon_wanga_prick.png'),
	  array('index' => '587','name' => 'Apoco-Fists','weapon' => 'apocofists','slot' => 'Melee','class' => 'Heavy','image' => 'images/weaponicons/Killicon_apoco-fists.png'),
	  array('index' => '588','name' => 'Pomson 6000','weapon' => 'pomson','slot' => 'Primary','class' => 'Engineer','image' => 'images/weaponicons/Killicon_pomson_6000.png'),
	  array('index' => '589','name' => 'Eureka Effect','weapon' => 'eureka_effect','slot' => 'Melee','class' => 'Engineer','image' => 'images/weaponicons/Killicon_eureka_effect.png'),
	  array('index' => '593','name' => 'Third Degree','weapon' => 'thirddegree','slot' => 'Melee','class' => 'Pyro','image' => 'images/weaponicons/Killicon_third_degree.png'),
	  array('index' => '594','name' => 'Phlogistinator','weapon' => 'phlogistinator','slot' => 'Primary','class' => 'Pyro','image' => 'images/weaponicons/Killicon_phlogistinator.png'),
	  array('index' => '595','name' => 'Manmelter','weapon' => 'manmelter','slot' => 'Secondary','class' => 'Pyro','image' => 'images/weaponicons/Killicon_manmelter.png'),
	  array('index' => '609','name' => 'Scottish Handshake','weapon' => 'scotland_shard','slot' => 'Melee','class' => 'Demoman','image' => 'images/weaponicons/Killicon_scottish_handshake.png'),
	  array('index' => '638','name' => 'Sharp Dresser','weapon' => 'sharp_dresser','slot' => 'Melee','class' => 'Spy','image' => 'images/weaponicons/Killicon_sharp_dresser.png'),
	  array('index' => '648','name' => 'Wrap Assassin','weapon' => 'wrap_assassin','slot' => 'Melee','class' => 'Scout','image' => 'images/weaponicons/Killicon_wrap_assassin.png'),
	  array('index' => '649','name' => 'Spy-cicle','weapon' => 'spy_cicle','slot' => 'Melee','class' => 'Spy','image' => 'images/weaponicons/Killicon_spy-cicle.png'),
	  array('index' => '656','name' => 'Holiday Punch','weapon' => 'holiday_punch','slot' => 'Melee','class' => 'Heavy','image' => 'images/weaponicons/Killicon_holiday_punch.png'),
	  array('index' => '727','name' => 'Black Rose','weapon' => 'black_rose','slot' => 'Melee','class' => 'Spy','image' => 'images/weaponicons/Killicon_black_rose.png'),
	  array('index' => '730','name' => 'Beggar\'s Bazooka','weapon' => 'dumpster_device','slot' => 'Primary','class' => 'Soldier','image' => 'images/weaponicons/Killicon_beggar\'s_bazooka.png'),
	  array('index' => '739','name' => 'Lollichop','weapon' => 'lollichop','slot' => 'Melee','class' => 'Pyro','image' => 'images/weaponicons/Killicon_lollichop.png'),
	  array('index' => '740','name' => 'Scorch Shot','weapon' => 'scorchshot','slot' => 'Secondary','class' => 'Pyro','image' => 'images/weaponicons/Killicon_scorch_shot.png'),
	  array('index' => '741','name' => 'Rainblower','weapon' => 'rainblower','slot' => 'Primary','class' => 'Pyro','image' => 'images/weaponicons/Killicon_rainblower.png'),
	  array('index' => '751','name' => 'Cleaner\'s Carbine','weapon' => 'pro_smg','slot' => 'Secondary','class' => 'Sniper','image' => 'images/weaponicons/Killicon_cleaner\'s_carbine.png'),
	  array('index' => '752','name' => 'Hitman\'s Heatmaker','weapon' => 'pro_rifle','slot' => 'Primary','class' => 'Sniper','image' => 'images/weaponicons/Killicon_hitman\'s_heatmaker.png'),
	  array('index' => '772','name' => 'Baby Face\'s Blaster','weapon' => 'pep_brawlerblaster','slot' => 'Primary','class' => 'Scout','image' => 'images/weaponicons/Killicon_baby_face\'s_blaster.png'),
	  array('index' => '773','name' => 'Pretty Boy\'s Pocket Pistol','weapon' => 'pep_pistol','slot' => 'Secondary','class' => 'Scout, Engineer','image' => 'images/weaponicons/Killicon_pretty_boy\'s_pocket_pistol.png'),
	  array('index' => '775','name' => 'Escape Plan','weapon' => 'unique_pickaxe_escape','slot' => 'Melee','class' => 'Soldier','image' => 'images/weaponicons/Killicon_escape_plan.png'),
	  array('index' => '811','name' => 'Huo-Long Heater','weapon' => 'long_heatmaker','slot' => 'Primary','class' => 'Heavy','image' => 'images/weaponicons/Killicon_huo-long_heater.png'),
	  array('index' => '812','name' => 'Flying Guillotine','weapon' => 'guillotine','slot' => 'Secondary','class' => 'Scout','image' => 'images/weaponicons/Killicon_flying_guillotine.png'),
	  array('index' => '813','name' => 'Neon Annihilator','weapon' => 'annihilator','slot' => 'Melee','class' => 'Pyro','image' => 'images/weaponicons/Killicon_neon_annihilator.png'),
	  array('index' => '851','name' => 'AWPer Hand','weapon' => 'awper_hand','slot' => 'Primary','class' => 'Sniper','image' => 'images/weaponicons/Killicon_awperhand.png'),
	  array('index' => '880','name' => 'Freedom Staff','weapon' => 'freedom_staff','slot' => 'Melee','class' => 'Scout, Sniper, Soldier, Demoman, Medic, Heavy, Pyro','image' => 'images/weaponicons/Killicon_freedom_staff.png'),
	  array('index' => '939','name' => 'Bat Outta Hell','weapon' => 'skullbat','slot' => 'Melee','class' => 'Scout, Sniper, Soldier, Demoman, Medic, Heavy, Pyro','image' => 'images/weaponicons/Killicon_bat_outta_hell.png'),
	  array('index' => '954','name' => 'Memory Maker','weapon' => 'memory_maker','slot' => 'Melee','class' => 'Scout, Sniper, Soldier, Demoman, Medic, Heavy, Pyro','image' => 'images/weaponicons/Killicon_memory_maker.png'),
	  array('index' => '996','name' => 'Loose Cannon Impact','weapon' => 'loose_cannon_impact','slot' => 'Primary','class' => 'Demoman','image' => 'images/weaponicons/Killicon_loose_cannon_pushed.png'),
	  array('index' => '996','name' => 'Loose Cannon','weapon' => 'loose_cannon','slot' => 'Primary','class' => 'Demoman','image' => 'images/weaponicons/Killicon_loose_cannon.png'),
	  array('index' => '997','name' => 'Rescue Ranger','weapon' => 'the_rescue_ranger','slot' => 'Primary','class' => 'Engineer','image' => 'images/weaponicons/Killicon_rescue_ranger.png'),
	  array('index' => '1013','name' => 'Ham Shank','weapon' => 'ham_shank','slot' => 'Melee','class' => 'Scout, Sniper, Soldier, Demoman, Medic, Heavy, Pyro','image' => 'images/weaponicons/Killicon_ham_shank.png'),
	  array('index' => '1071','name' => 'Golden Frying Pan','weapon' => 'golden_fryingpan','slot' => 'Melee','class' => 'Scout, Sniper, Soldier, Demoman, Medic, Heavy, Pyro, Spy, Engineer','image' => 'images/weaponicons/Killicon_golden_frying_pan.png'),
	  array('index' => '1098','name' => 'Classic','weapon' => 'the_classic','slot' => 'Primary','class' => 'Sniper','image' => 'images/weaponicons/Killicon_classic.png'),
	  array('index' => '1099','name' => 'Tide Turner','weapon' => 'tide_turner','slot' => 'Secondary','class' => 'Demoman','image' => 'images/weaponicons/Killicon_tide_turner.png'),
	  array('index' => '1100','name' => 'Bread Bite','weapon' => 'bread_bite','slot' => 'Melee','class' => 'Heavy','image' => 'images/weaponicons/Killicon_bread_bite.png'),
	  array('index' => '1103','name' => 'Back Scatter','weapon' => 'back_scatter','slot' => 'Primary','class' => 'Scout','image' => 'images/weaponicons/Killicon_back_scatter.png'),
	  array('index' => '1104','name' => 'Air Strike','weapon' => 'airstrike','slot' => 'Primary','class' => 'Soldier','image' => 'images/weaponicons/Killicon_air_strike.png'),
	  array('index' => '1123','name' => 'Necro Smasher','weapon' => 'necro_smasher','slot' => 'Melee','class' => 'Scout, Sniper, Soldier, Demoman, Medic, Heavy, Pyro, Engineer','image' => 'images/weaponicons/Killicon_necro_smasher.png'),
	  array('index' => '1127','name' => 'The Crossing Guard','weapon' => 'crossing_guard','slot' => 'Melee','class' => 'Scout, Sniper, Soldier, Demoman, Medic, Heavy, Pyro','image' => 'images/weaponicons/Killicon_crossing_guard.png'),
	  array('index' => '1150','name' => 'The Quickiebomb Launcher','weapon' => 'quickiebomb_launcher','slot' => 'Secondary','class' => 'Demoman','image' => 'images/weaponicons/Killicon_quickiebomb_launcher.png'),
	  array('index' => '1151','name' => 'The Iron Bomber','weapon' => 'iron_bomber','slot' => 'Primary','class' => 'Demoman','image' => 'images/weaponicons/Killicon_iron_bomber.png'),
	  array('index' => '1153','name' => 'Panic Attack Shotgun','weapon' => 'panic_attack','slot' => 'Primary','class' => 'Engineer, Pyro, Solider, Heavy','image' => 'images/weaponicons/Killicon_panic_attack.png'),
	  array('index' => '30474','name' => 'The Nostromo Napalmer','weapon' => 'ai_flamethrower','slot' => 'Primary','class' => 'Pyro','image' => 'images/weaponicons/Killicon_nostromo_napalmer.png'),
	  array('index' => NULL,'name' => 'Armageddon','weapon' => 'armageddon','slot' => 'Taunt','class' => 'Pyro','image' => 'images/weaponicons/Killicon_armageddon.png'),
	  array('index' => NULL,'name' => 'Ball O\' Bats','weapon' => 'spellbook_bats','slot' => 'Other','class' => 'Scout, Sniper, Soldier, Demoman, Medic, Heavy, Pyro, Spy, Engineer','image' => 'images/weaponicons/Killicon_batball.png'),
	  array('index' => NULL,'name' => 'Ball','weapon' => 'ball','slot' => 'Melee','class' => 'Scout','image' => 'images/weaponicons/Killicon_sandman_ball.png'),
	  array('index' => NULL,'name' => 'Bleed','weapon' => 'bleed_kill','slot' => 'Melee','class' => 'Scout, Sniper, Engineer','image' => 'images/weaponicons/Killicon_bleed.png'),
	  array('index' => NULL,'name' => 'Decapitation','weapon' => 'taunt_demoman','slot' => 'Taunt','class' => 'Demoman','image' => 'images/weaponicons/Killicon_decapitation.png'),
	  array('index' => NULL,'name' => 'Deflected Arrow','weapon' => 'deflect_arrow','slot' => 'Primary','class' => 'Pyro','image' => 'images/weaponicons/Killicon_deflect_arrow.png'),
	  array('index' => NULL,'name' => 'Deflected Cannonballs','weapon' => 'loose_cannon_reflect','slot' => 'Primary','class' => 'Pyro','image' => 'images/weaponicons/Killicon_deflect_cannonballs.png'),
	  array('index' => NULL,'name' => 'Deflected Cow Mangler 5000','weapon' => 'tf_projectile_energy_ball','slot' => 'Primary','class' => 'Pyro','image' => 'images/weaponicons/Killicon_skull.png'),
	  array('index' => NULL,'name' => 'Deflected Flare','weapon' => 'deflect_flare','slot' => 'Primary','class' => 'Pyro','image' => 'images/weaponicons/Killicon_deflect_flare.png'),
	  array('index' => NULL,'name' => 'Deflected Grenade','weapon' => 'deflect_promode','slot' => 'Primary','class' => 'Pyro','image' => 'images/weaponicons/Killicon_deflect_grenade.png'),
	  array('index' => NULL,'name' => 'Deflected Repair Claws','weapon' => 'rescue_ranger_reflect','slot' => 'Primary','class' => 'Pyro','image' => 'images/weaponicons/Killicon_deflect_repair_claws.png'),
	  array('index' => NULL,'name' => 'Deflected Rocket','weapon' => 'deflect_rocket','slot' => 'Primary','class' => 'Pyro','image' => 'images/weaponicons/Killicon_deflect_rocket.png'),
	  array('index' => NULL,'name' => 'Deflected Sticky','weapon' => 'deflect_sticky','slot' => 'Primary','class' => 'Pyro','image' => 'images/weaponicons/Killicon_deflect_sticky.png'),
	  array('index' => NULL,'name' => 'Dischord','weapon' => 'taunt_guitar_kill','slot' => 'Taunt','class' => 'Engineer','image' => 'images/weaponicons/Killicon_dischord.png'),
	  array('index' => NULL,'name' => 'Environment','weapon' => 'world','slot' => 'Other','class' => 'Scout, Sniper, Soldier, Demoman, Medic, Heavy, Pyro, Spy, Engineer','image' => 'images/weaponicons/Killicon_skull.png'),
	  array('index' => NULL,'name' => 'Eyeball Rocket','weapon' => 'eyeball_rocket','slot' => 'Primary','class' => 'Pyro','image' => 'images/weaponicons/Killicon_monoculus.png'),
	  array('index' => NULL,'name' => 'Fencing','weapon' => 'taunt_spy','slot' => 'Taunt','class' => 'Spy','image' => 'images/weaponicons/Killicon_fencing.png'),
	  array('index' => NULL,'name' => 'Fireball','weapon' => 'spellbook_fireball','slot' => 'Other','class' => 'Scout, Sniper, Soldier, Demoman, Medic, Heavy, Pyro, Spy, Engineer','image' => 'images/weaponicons/Killicon_fireball.png'),
	  array('index' => NULL,'name' => 'Goomba Stomp','weapon' => 'goomba','slot' => 'Other','class' => 'Scout, Sniper, Soldier, Demoman, Medic, Heavy, Pyro, Spy, Engineer','image' => 'images/weaponicons/Killicon_home_run.png'),
	  array('index' => NULL,'name' => 'Hadouken','weapon' => 'taunt_pyro','slot' => 'Taunt','class' => 'Pyro','image' => 'images/weaponicons/Killicon_hadouken.png'),
	  array('index' => NULL,'name' => 'Home Run','weapon' => 'taunt_scout','slot' => 'Taunt','class' => 'Scout','image' => 'images/weaponicons/Killicon_home_run.png'),
	  array('index' => NULL,'name' => 'Huntsman Fire Arrow','weapon' => 'compound_bow','slot' => 'Primary','class' => 'Sniper','image' => 'images/weaponicons/Killicon_flaming_huntsman.png'),
	  array('index' => NULL,'name' => 'Instant Kills','weapon' => 'rtd_instant_kills','slot' => 'Other','class' => 'Scout, Sniper, Soldier, Demoman, Medic, Heavy, Pyro, Spy, Engineer','image' => 'images/weaponicons/Killicon_underworld.png'),
	  array('index' => NULL,'name' => 'Kamikaze','weapon' => 'taunt_soldier','slot' => 'Taunt','class' => 'Soldier','image' => 'images/weaponicons/Killicon_kamikaze.png'),
	  array('index' => NULL,'name' => 'MONOCULUS','weapon' => 'spellbook_boss','slot' => 'Other','class' => 'Scout, Sniper, Soldier, Demoman, Medic, Heavy, Pyro, Spy, Engineer','image' => 'images/weaponicons/Killicon_monoculus_spell.png'),
	  array('index' => NULL,'name' => 'Meteor Shower','weapon' => 'spellbook_meteor','slot' => 'Other','class' => 'Scout, Sniper, Soldier, Demoman, Medic, Heavy, Pyro, Spy, Engineer','image' => 'images/weaponicons/Killicon_meteor_shower.png'),
	  array('index' => NULL,'name' => 'Mini-Sentry','weapon' => 'obj_minisentry','slot' => 'PDA','class' => 'Scout','image' => 'images/weaponicons/Killicon_minisentry.png'),
	  array('index' => NULL,'name' => 'Minify','weapon' => 'spellbook_athletic','slot' => 'Other','class' => 'Scout, Sniper, Soldier, Demoman, Medic, Heavy, Pyro, Spy, Engineer','image' => 'images/weaponicons/Killicon_minify.png'),
	  array('index' => NULL,'name' => 'Organ Grinder','weapon' => 'robot_arm_blender_kill','slot' => 'Taunt','class' => 'Engineer','image' => 'images/weaponicons/Killicon_organ_grinder.png'),
	  array('index' => NULL,'name' => 'Pumpkin Bomb','weapon' => 'tf_pumpkin_bomb','slot' => 'Other','class' => 'Scout, Sniper, Soldier, Demoman, Medic, Heavy, Pyro, Spy, Engineer','image' => 'images/weaponicons/Killicon_pumpkin.png'),
	  array('index' => NULL,'name' => 'Pumpkin MIRV','weapon' => 'spellbook_mirv','slot' => 'Other','class' => 'Scout, Sniper, Soldier, Demoman, Medic, Heavy, Pyro, Spy, Engineer','image' => 'images/weaponicons/Killicon_pumpkin_mirv.png'),
	  array('index' => NULL,'name' => 'Sentry Lvl1','weapon' => 'obj_sentrygun','slot' => 'PDA','class' => 'Engineer','image' => 'images/weaponicons/Killicon_sentry1.png'),
	  array('index' => NULL,'name' => 'Sentry Lvl2','weapon' => 'obj_sentrygun2','slot' => 'PDA','class' => 'Engineer','image' => 'images/weaponicons/Killicon_sentry2.png'),
	  array('index' => NULL,'name' => 'Sentry Lvl3','weapon' => 'obj_sentrygun3','slot' => 'PDA','class' => 'Engineer','image' => 'images/weaponicons/Killicon_sentry3.png'),
	  array('index' => NULL,'name' => 'Showdown','weapon' => 'taunt_heavy','slot' => 'Taunt','class' => 'Heavy','image' => 'images/weaponicons/Killicon_showdown.png'),
	  array('index' => NULL,'name' => 'Skeletons','weapon' => 'spellbook_skeleton','slot' => 'Other','class' => 'Scout, Sniper, Soldier, Demoman, Medic, Heavy, Pyro, Spy, Engineer','image' => 'images/weaponicons/Killicon_skeletons.png'),
	  array('index' => NULL,'name' => 'Skewer','weapon' => 'taunt_sniper','slot' => 'Taunt','class' => 'Sniper','image' => 'images/weaponicons/Killicon_skewer.png'),
	  array('index' => NULL,'name' => 'Spinal Tap','weapon' => 'taunt_medic','slot' => 'Taunt','class' => 'Medic','image' => 'images/weaponicons/Killicon_spinal_tap.png'),
	  array('index' => NULL,'name' => 'Suicide','weapon' => 'player','slot' => 'Other','class' => 'Scout, Sniper, Soldier, Demoman, Medic, Heavy, Pyro, Spy, Engineer','image' => 'images/weaponicons/Killicon_skull.png'),
	  array('index' => NULL,'name' => 'Superjump','weapon' => 'spellbook_blastjump','slot' => 'Other','class' => 'Scout, Sniper, Soldier, Demoman, Medic, Heavy, Pyro, Spy, Engineer','image' => 'images/weaponicons/Killicon_superjump.png'),
	  array('index' => NULL,'name' => 'Telefrag','weapon' => 'telefrag','slot' => 'Other','class' => 'Scout, Sniper, Soldier, Demoman, Medic, Heavy, Pyro, Spy, Engineer','image' => 'images/weaponicons/Killicon_telefrag.png'),
	  array('index' => NULL,'name' => 'Teleport','weapon' => 'spellbook_teleport','slot' => 'Other','class' => 'Scout, Sniper, Soldier, Demoman, Medic, Heavy, Pyro, Spy, Engineer','image' => 'images/weaponicons/Killicon_teleport.png'),
	  array('index' => NULL,'name' => 'Tesla Bolt','weapon' => 'spellbook_lightning','slot' => 'Other','class' => 'Scout, Sniper, Soldier, Demoman, Medic, Heavy, Pyro, Spy, Engineer','image' => 'images/weaponicons/Killicon_tesla_bolt.png'),
	  array('index' => NULL,'name' => 'Timebomb','weapon' => 'rtd_timebomb','slot' => 'Other','class' => 'Scout, Sniper, Soldier, Demoman, Medic, Heavy, Pyro, Spy, Engineer','image' => 'images/weaponicons/Killicon_explosion.png'),
	  array('index' => NULL,'name' => 'Toxic','weapon' => 'rtd_toxic','slot' => 'Other','class' => 'Scout, Sniper, Soldier, Demoman, Medic, Heavy, Pyro, Spy, Engineer','image' => 'images/weaponicons/Killicon_fire.png'),
	  array('index' => NULL,'name' => 'Worm\'s Grenade','weapon' => 'taunt_soldier_lumbricus','slot' => 'Taunt','class' => 'Soldier','image' => 'images/weaponicons/Killicon_hhg.png')
	);

	$database->query('INSERT INTO `weapons` (`index`, `name`, `weapon`, `slot`, `class`, `image`) VALUES (:index, :name, :weapon, :slot, :class, :image)');

	foreach($weapons as $key => $value){
		$database->bind(':index', $value['index']);
		$database->bind(':name', $value['name']);
		$database->bind(':weapon', $value['weapon']);
		$database->bind(':slot', $value['slot']);
		$database->bind(':class', $value['class']);
		$database->bind(':image', $value['image']);
		$database->execute();
	}
}
?>