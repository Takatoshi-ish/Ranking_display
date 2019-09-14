<?php

  session_start();

  //データベース情報
  $dsn = 'mysql:dbname=（データベース名）;host=localhost';
  $user = '（ユーザー名）';
  $password = '（パスワード）';

  try{
    $DB_con = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    $DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }
  catch(PDOException $e){
     echo $e->getMessage();
   }


   include_once 'class.user.php';
   $user = new USER($DB_con);
?>
