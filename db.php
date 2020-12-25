<?php
/*
*Vrací všechny uživatele co se
*/
function getUsers($Event=0){
  global $pdo;
  $result = $pdo->query("select users.* from users " .($Event?"left join (select author,count(*) cn  from posts where event=".$Event." group by author  ) aa on users.id=aa.author
where cn>0
order by cn desc":""));
  $ret=array();
  foreach($result as $row){
    $ret[$row['id']]= $row;
  }
  return $ret;
}

/*
/*Vrátí uživatele
*/
function getUser($User,$Password){
  //$k= getActiveUsers();  foreach($k as $u){    return $u;  }
  $sql = 'SELECT * FROM users WHERE name=:u';
  //$sql = 'SELECT * FROM users WHERE id=:p';
  global $pdo;
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':u', $User,\PDO::PARAM_STR);
  //$stmt->bindValue();
  //$result=$stmt->execute([':p'=>$Password]);
  $result=$stmt->execute();
  //error_log($Password . "  qqq".var_export($result,true));
  if($result==false) return false;

  while($row=  $stmt->fetch(\PDO::FETCH_ASSOC)){
   if($row['password']==''){
     $sql="update users set password=:p where id=:i";
     $stmt = $pdo->prepare($sql);
     $stmt->bindValue(':p', password_hash($Password,PASSWORD_DEFAULT),\PDO::PARAM_STR);
     $stmt->bindValue(':i', $row['id'],\PDO::PARAM_INT);
     if ($stmt->execute()) return $row;
     echo "Chyba při ukládání. Pardon.";
     die();

   }

    if(password_verify($Password,$row['password']))  return $row;
  }
  error_log("no data");

}


function dbAddPost($User,$Topic,$Name,$Url,$Mimetype,$Duration, $Size,$Event){
  $sql="insert into posts(author,topic,name,url,mimetype,moment,duration, filesize,event) values(:a,:t,:n,:u,:m,:dt, :du,:f,:eve) ";
  global $pdo;
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':n',$Name,\PDO::PARAM_STR);
  $stmt->bindValue(':u',$Url,\PDO::PARAM_STR);
  $stmt->bindValue(':m',$Mimetype,\PDO::PARAM_STR);
  $stmt->bindValue(':du',$Duration,\PDO::PARAM_STR);

  $stmt->bindValue(':dt',date("Y-m-d H:m:s"),\PDO::PARAM_STR);

  $stmt->bindValue(':a', $User,\PDO::PARAM_INT);
  $stmt->bindValue(':t', $Topic,\PDO::PARAM_INT);
  $stmt->bindValue(':f', $Size,\PDO::PARAM_INT);
  $stmt->bindValue(':eve', $Event,\PDO::PARAM_INT);
  return $stmt->execute();

}


/*
/*Vrátí témata.
*@param Active Ve výchozím stavu jen ty už aktivní
*/
function getTopics($Active=true,$Event=0){
  global $pdo;
  $result = $pdo->query("SELECT id,name FROM topics WHERE (1=1) " .($Active? " AND since_when <  '".date("Y-m-d H:m:s")."'":"")." ".($Event?" AND event=".$Event:""). " ORDER by id DESC ");
  $ret=array();
  foreach($result as $row){
    $ret[$row['id']]= $row['name'];
  }
  return $ret;
}

function getEvents(){
  global $pdo;
  $result = $pdo->query("SELECT * FROM events ORDER BY id DESC");
  $ret=array();
  foreach($result as $row){
    $ret[$row['id']]= $row;
  }
  return $ret;
}


function f2($t){
  $k=''.$t;
  if(strlen($k)){
    $k='0'.$k;
  }
  return $k;
}

/*
* Vrátí aktuality. Naplánuji na ně editor, moc jich asi nebude.
*/
function  getEditorials(){
  global $pdo;
  $result = $pdo->query('SELECT * FROM editorials');
  $ret=array();
  foreach($result as $row){
    $ret[$row['id']]= $row;
  }
  return $ret;

}
/*
*NAčte z db všechny příspěvky, nebo příspěvky jednoho uživatele
*/
function getPosts($User=0){
  global $pdo;
  $ret= $pdo->query('SELECT * FROM posts '.($User>0 ?" WHERE author= ".$User:"").' ORDER BY topic ASC');
  return $ret->fetchAll();
}
