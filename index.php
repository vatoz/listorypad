<?php
//*****************
//*Main router    *
//*****************
include "setup.php";
include "db.php";
if(!isset($_GET["url"])){
  $_GET["url"]="";
}

switch ($_GET["url"]){
  case "rss":
    require "rss.php";
    die();
    break;
  case "user":
    require "admin.php";
    die();
    break;

  default:
    require "display.php";
    die();
}
