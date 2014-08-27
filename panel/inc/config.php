<?php

//Set encoding
ini_set('default_charset', 'utf-8');

//Database Info
define("DB_HOST",  'localhost');
define("DB_USER",  'USERNAME');
define("DB_PASS",  'PASSWORD');
define("DB_NAME",  'DATABASE');

$Home = "/";
$Title = "Title";

const STEAM_APIKEY  = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';

function SteamTo64($key) 
{ 
  if (preg_match('/^\[U:[0-9]:([0-9]+)\]$/', $key, $matches)) {
    $key = '7656'.(1197960265728 + $matches[1]);
    return $key;
  }
  else {
    $key = '7656'.(((substr($key, 10)) * 2) + 1197960265728 + (substr($key, 8, 1)));
    return $key;
  }
}

function ToSteam64($key) 
{
  $key = ((substr($key, 4) - 1197960265728) / 2);
  if(strpos( $key, "." )) {$int = 1;}
  else{$int = 0;}
  $key = 'STEAM_0:'.$int.':'.floor($key);
  return $key; 
}

function GetPlayerInformation($key)
{
  $url = "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=".STEAM_APIKEY."&steamids=".$key."&format=json";

  $data = file_get_contents($url);
  $information = json_decode($data, true);

  return $information['response']['players'][0];
}

function StatCon($key,$lock)
{
  if ($lock == 0) {
    return "$key";
  }
  elseif ($key == 0) {
    return "0";
  }
  else {
    return round("$key"/"$lock", 2);
  }
}

function PlaytimeCon($key)
{
  return gmdate("H:i:s", $key);
}

?>