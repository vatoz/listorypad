<?php
if(!isset($_REQUEST['action'])){
  $_REQUEST['action']="login";
}

session_start();

function loggedUser(){
  if(isset($_SESSION["id"]) ){
    return $_SESSION["id"];
  }
  return false;
}



function rHead(){?>
  <!DOCTYPE html>
  <html lang="cs-CZ">
  <head>
  <title>Listorypad - admin</title>
  <meta charset="utf-8">
  <link rel="stylesheet" href="style/style.css">
  </head>
  <body>
  <?php
  if(loggedUser()){
    echo '<a href="?url=user&action=logout">Odhlásit -'.$_SESSION['name']."</a>";
  }
 ?>

  <div id=admin>
  <?php
}




function rFoot(){
  echo "</div></body></html>";
}

function doLogin(){
  if(isset($_REQUEST["username"])&&isset($_REQUEST["password"])){
  $k=getUser($_REQUEST["username"], $_REQUEST["password"] );
  if(!is_array($k)){
    showLoginScreen();
    die();
  }
  $_SESSION["id"]=$k["id"];
  $_SESSION["name"]=$k["name"];
  ShowPosts();
  die();
  }
  showLoginScreen();
  die();
}

function showPosts(){
  rHead();
  echo "<ol>";
  $posts=getPosts(loggedUser());
  foreach (getTopics() as $Key=>$Value){
    foreach($posts as $pid=>$pda){
      if($pda['topic']==$Key){
          echo "<li>".$pda["name"]. " (" .$Value.")</li>";
          continue 2;
      }
      echo "<li><a href='user?action=add&id=".$Key. "'>".$Value."</a></li>";
    }
  }
  echo "</ol>";

  echo "Komplet <ol>";
  foreach(getTopics(false) as $topic){
      echo '<li>'.$topic.'</li>';
  }
  echo "</ol>";
  rFoot();
}


function showLoginScreen(){
  rHead();
  ?>
  <form action="/user" method="post">
              <div >
                  <label>Jméno</label>
                  <input type="text" name="username" >
              </div>
              <div >
                  <label>Heslo</label>
                  <input type="password" name="password" >
              </div>
              <div>
                  <input type="submit" value="Zaslat">
              </div>
                  <input type="hidden"  name=action value="do_login">
          </form>
          Klasika, používáme cookies a podobný technologie...


  <?php
  rFoot();
}


switch ($_REQUEST['action']){
  case "do_login":
    doLogin();
    break;
  case "display":
    showPosts();
    break;
  case "logout":
    session_destroy();
    unset($_SESSION['id']);
  default:
    if(!loggedUser()){
      showLoginScreen();
    }else{
      showPosts();
    }
}
