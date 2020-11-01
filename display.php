<?php
header("Content-type: text/html");
?>
<!DOCTYPE html>
<html lang="cs-CZ">
<head>
<title>Listorypad</title>
<meta charset="utf-8">
<!--<link rel="icon" href="/favicon.ico" type="image/x-icon">-->
<link rel="stylesheet" href="style/style.css">
<!--<link href='https://fonts.googleapis.com/css?family=Source Code Pro' rel='stylesheet'>-->

</head>
<body>

  <center><h1>Listorypad</h1></center>
  <center><p>Performeři v rámci výzvy každý den vypráví nový příběh na zadané téma. Poslechnout si je můžeš tady na stránkách, nebo v rámci <a href="/rss">podcastu</a>. </p></center>
  <center>
  <?php
  foreach(getEditorials() as $editorial){
    echo '<p>'.$editorial["transcript"]. " <small><i>(".$editorial["moment"].")</i></small> </p>";
  }

  ?></ul></center>
<!--<ol><li> pasti</li><li> ateista</li><li> symbolika</li><li>nebojím</li><li> střela</li><li> informace</li><li> váhy</li><li> spoluvina</li><li> světlo</li><li> oni</li><li> pravda</li><li> všichni</li><li> cokoliv</li><li> nepřátelé</li><li> polopravda</li><li> pýcha</li><li> tygr</li><li> přeživší</li><li> nepotřebuji</li><li> kontrola</li><li> vtip</li><li> dnešek</li><li> čtyři</li><li> nové</li><li> zneužití</li><li> mezery</li><li> vůle</li><li> vítězství</li><li> billboardy</li><li> cesta</li></ol> -->

  <center><table><tr><td>&nbsp;</td><?php
    $users=getActiveUsers();
    foreach($users as $user){
      echo '<td><img src="'.$user['img'].'"><br>'.htmlspecialchars($user["name"] ).'</td>';
    }
   ?></tr>
<?php
  $posts=getPosts();
  foreach(getTopics() as $tid=> $topic){
    echo "\n<tr>\n<td><h2 id=t_".$tid.">".$tid.". ".$topic."</h2></td>";
    foreach ($users as $uid=>$user){
        echo "\n<td>";
          foreach($posts as $pid=>$post){
            if($post['author']==$uid && $tid==$post['topic']){
              echo '<a name="'.$post['url'].'"/>';
              echo htmlentities($post['name'])."<br>";
              echo '<audio controls preload="none"><source src="'.$post['url'].'" type="'.$post['mimetype'].'"></audio>';
              continue 1;

          }
        }

        echo "</td>";
    }
    echo "</tr>\n";
  }
?></table>
</center>




<div id=copyright> (c) 2020 jednotliví autoři | <a href="https://github.com/vatoz/listorypad">zdrojáky aplikace</a> </div>
</body>
<html>
