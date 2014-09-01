<?php

/*@license MIT - http://datatables.net/license_mit/
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || !strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
{
    header("Location: ../index.php?error=".urlencode("Direct access not allowed."));
    die();
}

include 'config.php';

$sql_details = array(
    'user' => DB_USER,
    'pass' => DB_PASS,
    'db'   => DB_NAME,
    'host' => DB_HOST
);

$custkill = array('0' => 'None', '1' => 'Headshot', '2' => 'Backstab', '3' => 'Burning', '4' => 'Fix', '5' => 'Minigun', '6' => 'Suicide', '7' => 'Hadouken', '8' => 'Flare', '9' => 'Taunt', '10' => 'Taunt', '11' => 'Team Penetration', '12' => 'Plaer Penetration', '13' => 'Taunt', '14' => 'Penetration Headshot', '15' => 'Taunt', '16' => 'Telefrag', '17' => 'Burning Arrow', '18' => 'Flying Burn', '19' => 'Pumpkin Bomb', '20' => 'Decapitation', '21' => 'Taunt', '22' => 'Baseball', '23' => 'Charge Impact', '24' => 'Taunt', '25' => 'Air Sticky', '26' => 'Defensive Sticky', '27' => 'Pickaxe', '28' => 'Direct Hit', '29' => 'Taunt', '30' => 'Wrangled', '31' => 'Sticky', '32' => 'Revenge Crit', '33' => 'Taunt', '34' => 'Bleed', '35' => 'Golden', '36' => 'Carry Building', '37' => 'Combo', '38' => 'Taunt', '39' => 'Fish Kill', '40' => 'Hurt', '41' => 'Boss Decapitation', '42' => 'Explosion', '43' => 'Aegis', '44' => 'Flare Explode', '45' => 'Stomp', '46' => 'Plasma', '47' => 'Deflected Plasma', '48' => 'Plasma Gib', '49' => 'Practice Sticky', '50' => 'Eyeball', '51' => 'Hitman Headshot', '52' => 'Taunt', '53' => 'Flare', '54' => 'Cleaver', '55' => 'Crit Cleaver', '56' => 'Red Tape', '57' => 'Player Bomb', '58' => 'Merasmus Grenade', '59' => 'Merasmus Zap', '60' => 'Merasmus Decapitation', '61' => 'Cannonball Push');

if (isset($_GET['type']) && $_GET['type'] == 'getwep') {

    $table = 'smalllog';
    $primaryKey = 'attacker';
     
    $columns = array(
        array(
            'db'        => 'smalllog.attacker',
            'dt'        => 'attacker',
            'field'     => 'attacker'
        ),
        array(
            'db'        => 'smalllog.kills',
            'dt'        => 'kills',
            'field'     => 'kills'
        ),
        array(
            'db'        => 'smalllog.deaths',
            'dt'        => 'deaths',
            'field'     => 'deaths'
        ),
        array(
            'db'        => 'smalllog.crits',
            'dt'        => 'crits',
            'field'     => 'crits'
        ),
        array(
            'db'        => 'smalllog.ks',
            'dt'        => 'ks',
            'field'     => 'ks'
        ),
        array(
            'db'        => 'smalllog.customkill',
            'dt'        => 'ck',
            'field'     => 'customkill'
        ),
        array(
            'db'        => 'playerlog.name',
            'dt'        => 'name',
            'field'     => 'name',
            'formatter' => function( $d, $row ) {
                return htmlentities($d);
            }
        )
    );

    $extraCondition = "`weapon` = '".$_GET['id']."' AND smalllog.`kills` > '0'";
    $joinQuery = "FROM `smalllog` INNER JOIN `playerlog` ON (smalllog.`attacker` = playerlog.`auth`)";
    require('ssp.class.php');

    echo json_encode(
        SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraCondition)
    );
}

if (isset($_GET['type']) && $_GET['type'] == 'getaction') {

    $table = 'smalllog';
    $primaryKey = 'kills';
     
    $columns = array(
        array(
            'db'        => 'SUM(smalllog.kills)',
            'dt'        => 'kills',
            'field'     => 'kills',
            'as'        => 'kills'
        ),
        array(
            'db'        => 'SUM(smalllog.deaths)',
            'dt'        => 'deaths',
            'field'     => 'deaths',
            'as'        => 'deaths'
        ),
        array(
            'db'        => 'smalllog.attacker',
            'dt'        => 'attacker',
            'field'     => 'attacker'
        ),
        array(
            'db'        => 'playerlog.name',
            'dt'        => 'name',
            'field'     => 'name',
            'formatter' => function( $d, $row ) {
                return htmlentities($d);
            }
        )
    );

    $extraCondition = "`customkill` = '".$_GET['id']."'";
    $joinQuery = "FROM `smalllog` INNER JOIN `playerlog` ON (smalllog.`attacker` = playerlog.`auth`)";
    $groupBy = "GROUP BY smalllog.`attacker`, smalllog.`customkill`";

    require('ssp.class.php');

    echo json_encode(
        SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraCondition, $groupBy)
    );
}

if (isset($_GET['type']) && $_GET['type'] == 'getplayers') {

    $table = 'playerlog';
    $primaryKey = 'kills';
     
    $columns = array(
        array(
            'db'        => 'name',
            'dt'        => 'name',
            'formatter' => function( $d, $row ) {
                return htmlentities($d);
            }
        ),
        array(
            'db'        => 'auth',
            'dt'        => 'auth'
        ),
        array(
            'db'        => 'kills',
            'dt'        => 'kills'
        ),
        array(
            'db'        => 'deaths',
            'dt'        => 'deaths'
        ),
        array(
            'db'        => 'assists',
            'dt'        => 'assists'
        ),
        array(
            'db'        => 'ROUND(kills/deaths,2)',
            'dt'        => 'kpd',
            'formatter' => function( $d, $row ) {
                return ($d == NULL ? "?" : $d);
            }
        ),
        array(
            'db'        => 'ROUND(kills/(playtime/60),2)',
            'dt'        => 'kpm',
            'formatter' => function( $d, $row ) {
                return ($d == NULL ? "?" : $d);
            }
        ),
        array(
            'db'        => 'playtime',
            'dt'        => 'playtime',
            'formatter' => function( $d, $row ) {
                return PlaytimeCon($d);
            }
        ),
        array(
            'db'        => 'disconnect_time',
            'dt'        => 'disconnect_time',
            'formatter' => function( $d, $row ) {
                return ($d == 0 ? "Connected" : date( 'F j, Y, g:i a', $d));
            }
        )
    );

    $joinQuery = '';

    require('ssp.class.php');

    echo json_encode(
        SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery)
    );
}

if (isset($_GET['type']) && $_GET['type'] == 'getplayer') {

    $table = 'killlog';
    $primaryKey = 'killtime';
     
    $columns = array(
        array(
            'db'        => 'killlog.attacker',
            'dt'        => 'attacker',
            'field'     => 'attacker'
        ),
        array(
            'db'        => 'killlog.aclass',
            'dt'        => 'aclass',
            'field'     => 'aclass',
            'formatter' => function( $d, $row ) {
                return '<img src="images/classicons/'.$d.'.png">';
            }
        ),
         array(
            'db'        => 'killlog.victim',
            'dt'        => 'victim',
            'field'     => 'victim'
        ),
        array(
            'db'        => 'killlog.vclass',
            'dt'        => 'vclass',
            'field'     => 'vclass',
            'formatter' => function( $d, $row ) {
                return '<img src="images/classicons/'.$d.'.png">';
            }
        ),
        array(
            'db'        => 'killlog.weapon',
            'dt'        => 'weapon',
            'field'     => 'weapon'
        ),
        array(
            'db'        => 'killlog.crit',
            'dt'        => 'crit',
            'field'     => 'crit'
        ),
        array(
            'db'        => 'killlog.wep_ks',
            'dt'        => 'wep_ks',
            'field'     => 'wep_ks'
        ),
        array(
            'db'        => 'a.name',
            'dt'        => 'a_name',
            'field'     => 'a_name',
            'as'        => 'a_name',
            'formatter' => function( $d, $row ) {
                return htmlentities($d);
            }
        ),
        array(
            'db'        => 'v.name',
            'dt'        => 'v_name',
            'field'     => 'v_name',
            'as'        => 'v_name',
            'formatter' => function( $d, $row ) {
                return htmlentities($d);
            }
        ),
        array(
            'db'        => 'killlog.killtime',
            'dt'        => 'time',
            'field'     => 'time',
            'as'        => 'time'
        )
    );

    $extraCondition = "(`attacker` = '".$_GET['id']."')";
    $joinQuery = "FROM `killlog` INNER JOIN (`playerlog` as a, `playerlog` as v) ON (killlog.`attacker` = a.`auth` AND killlog.`victim` = v.`auth`)";

    require('ssp.class.php');

    echo json_encode(
        SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraCondition)
    );
}

if (isset($_GET['type']) && $_GET['type'] == 'getstreak') {

    $table = 'smalllog';
    $primaryKey = 'kills';
     
    $columns = array(
        array(
            'db'        => 'smalllog.ks',
            'dt'        => 'ks',
            'field'     => 'ks'
        ),
        array(
            'db'        => 'smalllog.attacker',
            'dt'        => 'attacker',
            'field'     => 'attacker'
        ),
        array(
            'db'        => 'playerlog.name',
            'dt'        => 'name',
            'field'     => 'name',
            'formatter' => function( $d, $row ) {
                return htmlentities($d);
            }
        )
    );

    $extraCondition = "`weapon` = '".$_GET['id']."' AND `ks` > 0";
    $joinQuery = "FROM `smalllog` INNER JOIN `playerlog` ON (smalllog.`attacker` = playerlog.`auth`)";
    $groupBy = "GROUP BY smalllog.`attacker`, smalllog.`weapon`";

    require('ssp.class.php');

    echo json_encode(
        SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraCondition, $groupBy)
    );
}

if (isset($_GET['type']) && $_GET['type'] == 'getitems') {

    $table = 'itemlog';
    $primaryKey = 'auth';
     
    $columns = array(
        array(
            'db'        => 'itemlog.auth',
            'dt'        => 'auth',
            'field'     => 'auth'
        ),
        array(
            'db'        => 'itemlog.index',
            'dt'        => 'index',
            'field'     => 'index'
        ),
        array(
            'db'        => 'itemlog.quality',
            'dt'        => 'quality',
            'field'     => 'quality',
            'formatter' => function( $d, $row ) {
                return QualityText($d,'text');
            }
        ),
        array(
            'db'        => 'itemlog.method',
            'dt'        => 'method',
            'field'     => 'method',
            'formatter' => function( $d, $row ) {
                return Method($d);
            }
        ),
        array(
            'db'        => 'itemlog.time',
            'dt'        => 'time',
            'field'     => 'time',
            'formatter' => function( $d, $row ) {
                return date("m/d/y, g:i a", $d);
            }
        ),
        array(
            'db'        => 'playerlog.name',
            'dt'        => 'name',
            'field'     => 'name',
            'formatter' => function( $d, $row ) {
                return htmlentities($d);
            }
        )
    );

    $extraCondition = "`index` = ".$_GET['id'];
    $joinQuery = "FROM `itemlog` INNER JOIN `playerlog` ON (itemlog.`auth` = playerlog.`auth`)";

    require('ssp.class.php');

    echo json_encode(
        SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraCondition)
    );
}