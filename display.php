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
  <center><table><tr><td>&nbsp;</td><?php
    $users=getActiveUsers();
    foreach($users as $user){
      echo '<td><img src="'.$user['img'].'"><br>'.htmlspecialchars($user["name"] ).'</td>';
    }
   ?></tr>
<?php
  $posts=getPosts();
  foreach(getTopics() as $tid=> $topic){
    echo "\n<tr>\n<td><h2>".$topic."</h2></td>";
    foreach ($users as $uid=>$user){
        echo "\n<td>";
          foreach($posts as $pid=>$post){
            if($tid!==$post['topic']){
              continue;
            }
            if($post['author']==$uid){
              echo '<a/ name="'.$post['url'].'">';
              echo htmlentities($post['name'])."<br>";
              echo '<audio controls preload="none"><source src="'.$post['url'].'" type="audio/mpeg"></audio>';
              unset ($posts[$pid]);
              continue;
            }
          }
        echo "</td>";
    }
    echo "</tr>\n";
  }
?></table>
</center>




<div id=copyright> (c) 2020 jednotliví autoři</div>
</body>
<html>
