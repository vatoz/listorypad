<?php

function getActiveUsers(){
  global $pdo;
  $result = $pdo->query('SELECT * FROM users');
  $ret=array();
  foreach($result as $row){
    $ret[$row['id']]= $row;
  }
  return $ret;
}

function getTopics(){
  global $pdo;
  $result = $pdo->query("SELECT id,name FROM topics where since_when < datetime('now') ");
  $ret=array();
  foreach($result as $row){
    $ret[$row['id']]= $row['name'];
  }
  return $ret;
}


function  getEditorials(){
  global $pdo;
  $result = $pdo->query('SELECT * FROM editorials');
  $ret=array();
  foreach($result as $row){
    $ret[$row['id']]= $row;
  }
  return $ret;

}

function getPosts(){
  global $pdo;
  return $pdo->query('SELECT * FROM posts ORDER BY topic ASC');
}
