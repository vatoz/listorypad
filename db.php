<?php
/*
*Varací všechny uživatele
*@todo vrátit jen aktivní, a vymyslet pořadí
*/
function getActiveUsers(){
  global $pdo;
  $result = $pdo->query('SELECT * FROM users');
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
     $stmt->bindValue(':i', $row['id'],\PDO::PARAM_STR);
     if ($$stmt->execute()) return $row;
     echo "Chyba při ukládání. Pardon.";
     die();

   }

    if(password_verify($Password,$row['password']))  return $row;
  }
  error_log("no data");

}



/*
/*Vrátí témata.
*@param Active Ve výchozím stavu jen ty už aktivní
*/
function getTopics($Active=true){
  global $pdo;
  
  $result = $pdo->query("SELECT id,name FROM topics " .($Active?"where since_when <  '".date("Y-m-d H:m:s")."'":""));
  $ret=array();
  foreach($result as $row){
    $ret[$row['id']]= $row['name'];
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
