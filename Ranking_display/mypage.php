<?php
  include_once 'dbconfig.php';
  if(!$user->is_loggedin()){
    $user->redirect('index.php');
  }
  $user_id = $_SESSION['user_session'];
  $stmt = $DB_con->prepare("SELECT * FROM usersData1 WHERE user_id=:user_id");
  $stmt->execute(array(":user_id"=>$user_id));
  $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
  $check = false;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="stylesheet" href="style.css" type="text/css"  />
  <title>Mypage - <?php echo $userRow['user_name']; ?></title>
</head>

<body>

  <div class="header">
    <div class="left">
      <label><a href="allRank.php">AllRanking</a></label>
      <label><a href="teamRank.php">TeamRanking</a></label>
    </div>
    <div class="right">
      <label><a href="logout.php?logout=true"><i class="glyphicon glyphicon-log-out"></i> logout</a></label>
    </div>
  </div>

  <div class="content">
    ようこそ : <?php echo $userRow['user_name'] . "さん"; ?>
  </div>
  <div class="profile">
  <p>
    <div id="profile-title">
      Profile<br/>
    </div>
    Name:<?php echo $userRow['user_name'] . "<br>"; ?>
    Email:<?php echo $userRow['user_email'] . "<br>"; ?>
    Points:<?php echo $userRow['user_pointNum'] . "points<br>"; ?>
    Comment:<?php echo $userRow['user_comment'] . "<br>"; ?>
  </p>
  <form action="mypage.php" method="post" enctype="multipart/form-data">
    完成したファイルをアップロードしてください：<br/>
    <input type="file" name="upfile" size="30" /><br/>
    <input type="submit" name="upload" value="アップロード" /><br/><br/>
  </form>
  <form method="post" action="mypage.php" enctype="multipart/form-data">
      編集</br>
      <input type="text" name="name" value=<?php echo $userRow['user_name']; ?>></br></br>
      <input type="text" name="email" value=<?php echo $userRow['user_email']; ?>></br></br>
      <input type="text" name="pointNum" value=<?php echo $userRow['user_pointNum']; ?>></br></br>
      <input type="text" name="comment" value=<?php echo $userRow['user_comment']; ?>></br></br>
      <input type="text" name="passCheck1" placeholder="password">
      <input type="submit" name="update" value="編集・更新"></br></br>
      <p></p>
      削除</br>
      <input type="hidden" name="delete_num" placeholder="削除対象番号">
      <input type="text" name="passCheck2" placeholder="password">
      <input type="submit" name="delete" value="削除"></br></br>
  </form>
  <?php
    //データベースに入れる文字列を変数に代入する
    //各データベースの処理の事前操作
    $dsn = 'mysql:dbname=tb210027db;host=localhost';
    $user = 'tb-210027';
    $password = '54ZnHKKD3S';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    $sql = 'SELECT * FROM usersData1';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    /* データベース更新処理 */
    /*編集対象番号の行はあたらしく名前とコメント欄に
    入力された文字列をデータベースに格納し、それ以外は、変更せずに編集前と同じ行の文字列にする*/
    if(isset($_POST['update'])){
      if(strlen($_POST['passCheck1'])==0)
        echo "passwordが間違えているか、入力されていません。<br>"; //passwordが入力されていないとき
      else{
        /* データベースでの削除処理 */
        //更新したいユーザー登録番号
        foreach ($results as $row){
          if($row['user_id'] == $user_id){  //更新したいユーザー登録番号だった時、その行のデータを更新する,
            if(password_verify($_POST['passCheck1'], $row['user_pass'])){
              $sql = 'update usersData1 set user_name=:user_name,user_email=:user_email,user_pointNum=:user_pointNum,user_comment=:user_comment,user_pass=:user_pass where user_id=:user_id';
              $stmt = $pdo->prepare($sql);
              $new_password = password_hash($_POST['passCheck1'], PASSWORD_DEFAULT);
              $stmt->bindParam(':user_name', $_POST['name'], PDO::PARAM_STR);
              $stmt->bindParam(':user_email', $_POST['email'], PDO::PARAM_STR);
              $stmt->bindParam(':user_pointNum', $_POST['pointNum'], PDO::PARAM_INT);
              $stmt->bindParam(':user_comment', $_POST['comment'], PDO::PARAM_STR);
              $stmt->bindParam(':user_pass', $new_password);
              $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
              $stmt->execute();
              $page = 'mypage.php';
              header('Location: '.$page, true, 303);
              exit;
            }
            else{
              echo "passwordが間違えているか、入力されていません。<br>";
            }
          }
        }
      }
    }
    elseif(isset($_POST['delete'])){  //削除ボタンを押したとき
      if(strlen($_POST['passCheck2'])==0)
        echo "passwordが入力されていません。<br>"; //passwordが入力されていないとき
      else{
        /* データベースでの削除処理 */
        //削除したい投稿番号
        foreach ($results as $row){
          if($row['user_id'] == $user_id){  //削除したい投稿番号だった時、その行のデータを削除する
            if(password_verify($_POST['passCheck2'], $row['user_pass'])){
              $sql = 'delete from usersData1 where user_id=:user_id';
              $stmt = $pdo->prepare($sql);
              $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
              $stmt->execute();
              $page = 'index.php';
              header('Location: '.$page, true, 303);
              exit;
            }
            else{
              echo "passwordが間違えているか、入力されていません。<br>";
            }
          }
        }
      }
    }
    elseif(isset($_POST['upload'])){
      if(is_uploaded_file($_FILES["upfile"]["tmp_name"])){
        if(move_uploaded_file($_FILES["upfile"]["tmp_name"], 'files/' . $_FILES["upfile"]["name"])){
          chmod("files/" . $_FILES["upfile"]["name"], 0644);
          echo $_FILES["upfile"]["name"] . "をアップロードしました。";
        }else{
          echo "ファイルをアップロードできません。";
        }
      }else{
        echo "ファイルが選択されていません。";
      }
    }
  ?>
  </div>
</body>
</html>
