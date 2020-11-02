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


function redir($path){
  header("Location: ". $_SERVER['REQUEST_SCHEME']."://".  $_SERVER['SERVER_NAME']."/".$path);
  die();
}

function rFoot(){?>
  <div id=copyright> (c) 2020 jednotliví autoři | <a href="https://github.com/vatoz/listorypad">zdrojáky aplikace a možnost hlášení chyb </a> </div>
  <div id=admin><a href="/" >Titulka</a>
  <?php
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
  redir("user");
  die();
  }
  showLoginScreen();
  die();
}

function doAdd(){
  if(!isset($_REQUEST['duration']))die("Chyba..");
  if(!isset($_REQUEST['topic']))die("Chyba...");
  if(!isset($_REQUEST['name']))die("Chyba....");
  if(!loggedUser())die ("To nedává smysl");
  if(!isset($_FILES['audio'])) die("Takhle to nefunguje");
  if($_FILES['audio']['error']) die("Chyba nahrávání souboru ".$_FILES['audio']['error']);
  $posts=getPosts(loggedUser());
  $topics=getTopics();
  if(!isset($topics[intval($_REQUEST['topic'])]))die("Tohle téma ještě není otevřené");
  foreach($posts as $post){
    if($post['topic']==intval($_REQUEST['topic'])){
      die ("To už v db mám.");
    }
  }
  var_export($_FILES);
  if(!is_uploaded_file($_FILES['audio']['tmp_name'])) die("Jako vážně, tohle by se nemělo stát. ".$_FILES['audio']['tmp_name']);
  if($_FILES['audio']["size"]>8000000) die("Tenhle soubor už mi přijde moc velký");
  $extenze=strtolower( substr($_FILES["audio"]["name"], -3 ));
  switch ($extenze) {
      case "mp3":$mimetype="media/mpeg"; break;
      case "aac":$mimetype="media/aac"; break;
      case "m4a":$mimetype="media/mp4"; break;
      default:die("Tuhle příponu souboru ještě neznám: ".$extenze);
  }
  $filename="media/".f2(loggedUser())."/".f2(intval($_REQUEST['topic'])).".".$extenze;
  if(!is_dir("media/".f2(loggedUser()))){
      if(!mkdir("media/".f2(loggedUser()))) die("Uživatel nemá složku a nepovedlo se ji vytvořit");
  }
  if(!move_uploaded_file($_FILES['audio']['tmp_name'], $filename))die("Selhalo přesouvání dočasného souboru");
  if(!dbAddPost(
    loggedUser(),
    intval($_REQUEST['topic']),
    trim($_REQUEST['name']),
    $filename,
    $mimetype,
    trim($_REQUEST['duration']),
    $_FILES['audio']["size"]
    )
    ) die("Selhalo uložení do db");
    redir("user?action=ok");
}


function showPosts(){
  rHead();

  $posts=getPosts(loggedUser());
  $topics=getTopics();
  echo '<ol>';
    foreach ($topics as $Key=>$Value){
    $fnd=false;
    foreach($posts as $pid=>$pda){
      if($pda['topic']==$Key){
          echo "<li>".$pda["name"]. " (" .$Value.")</li>";
          $fnd=true;
      }
    }
    if(!$fnd) echo '<li><a href="user?action=add&topic='.$Key. '">'.$Value."</a></li>";
  }
  echo "</ol>";

  echo "<hr>Komplet <ol>";
  foreach(getTopics(false) as $topic){
      echo '<li>'.$topic.'</li>';
  }
  echo "</ol>";
  rFoot();
}
function showAddScreen(){
  if(!loggedUser())die ("To nedává smysl");
  if(!isset($_REQUEST['topic'])) die("Takhle to nefunguje");
  rHead();
  ?><br><br><br>
        <form action="/user" method="post" enctype="multipart/form-data">
              <div >
                  <label>Jméno příběhu</label>
                  <input type="text" name="name" >
              </div>
              <div >
                  <label>Délka (myslím že stačí zhruba)</label>
                  <input type="text" name="duration"  value="5:55">
              </div>
              <div>
                  <label>Soubor</label>
                  <input type="file" name="audio">
              </div>
              <div>
                  <input type="submit" value="Zaslat">
              </div>
                  <input type="hidden"  name=action value="do_add">
                  <input type="hidden"  name=topic value="<?php echo intval($_REQUEST['topic']); ?>">
          </form>
          <p> Snažte se velikost souboru držet kolem 2-3 MB, kapacita disků na hostingu není nekonečná.
            Na Androidu mi třeba dobrý možnosti (např. stereo většinou nemá smysl) nabízí <a href="https://play.google.com/store/apps/details?id=com.dimowner.audiorecorder">tahle appka</a> </p>
          <p> Pokud by nebyl obsah v češtině (slovenština taky ok), či by byl nějakým způsobem explicitní (hádám násilí, vulgarismy, sex ), tak se se mnou radši předem dohodněte, ať pak třeba na straně Apple nemáme problém. Vašek</p>
          <p><a href="https://open.spotify.com/show/7wCAQtwvArHuVPekQYqqRT">Spotify link</a>. Další služby nepouýívám, ale rss by mělo být možné tam přidat. Kdyžtak mi napište.</p>


  <?php
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
  case 'add':
    showAddScreen();
    break;
  case "do_add":
    doAdd();
    break;
  case "do_login":
    doLogin();
    break;
  case "display":
    showPosts();
    break;
  case "ok":
    rHead();echo '<h1>Povedlo se</h1><a href="/user">seznam</a>'; rFoot(); die();
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
