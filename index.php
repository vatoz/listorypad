<?php
//*****************
//*Main router    *
//*****************
include "db.php";
if(!isset($_GET["url"])){
  $_GET["url"]="";
}


switch ($_GET["url"]){
  case "rss":
    include "rss.php";
    break;
  default:
    include "display.php";
}
