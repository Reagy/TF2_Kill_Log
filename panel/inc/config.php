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

function Quality($key)
{
  //Genuine
  if($key == 1)
    return "#4D7455";
  //Vintage
  elseif($key == 3)
    return "#476291";
  //Unusual
  elseif($key == 5)
    return "#8650AC";
  //Unique
  elseif($key == 6)
    return "#FFD700";
  //Strange
  elseif($key == 11)
    return "#CF6A32";
  //Haunted
  elseif($key == 13)
    return "#38F3AB";
  //Collectors
  elseif($key == 14)
    return "#AA0000";
}

function QualityText($key)
{
  //Genuine
  if($key == 1)
    return "Genuine";
  //Vintage
  elseif($key == 3)
    return "Vintage";
  //Unusual
  elseif($key == 5)
    return "Unusual";
  //Unique
  elseif($key == 6)
    return "Unique";
  //Strange
  elseif($key == 11)
    return "Strange";
  //Haunted
  elseif($key == 13)
    return "Haunted";
  //Collectors
  elseif($key == 14)
    return "";
}

function Method($key)
{
  if (is_numeric($key)) {
    if($key == 0)
      return "Found";
    elseif($key == 1)
      return "Crafted";
    elseif($key == 2)
      return "Traded";
    elseif($key == 3)
      return "Bought";
    elseif($key == 4)
      return "Unboxed";
    elseif($key == 5)
      return "Gifted";
    else
      return $key;
  }
  else {
    if($key == "Found")
      return 0;
    elseif($key == "Crafted")
      return 1;
    elseif($key == "Traded")
      return 2;
    elseif($key == "Bought")
      return 3;
    elseif($key == "Unboxed")
      return 4;
    elseif($key == "Gifted")
      return 5;
    else
      return $key;
  }
}
?>
