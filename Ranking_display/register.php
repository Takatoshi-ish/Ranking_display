<?php
  require_once 'dbconfig.php';

  //ログイン状態か確認し、ログイン状態ならmypageへ移動
  if($user->is_loggedin()!=""){
    $user->redirect('mypage.php');
  }

  //ボタンを押したとき
  if(isset($_POST['btn-signup'])){
    $uname = trim($_POST['txt_uname']);  //名前
    $umail = trim($_POST['txt_umail']); //メールアドレス
    $upoint = trim($_POST['txt_upoint']); //ポイント
    $ucomment = trim($_POST['txt_ucomment']); //コメント
    $upass = trim($_POST['txt_upass']); //パスワード

    //入力されていないときにエラー表示をする
    if($uname=="") {
      $error[] = "ユーザー名を入力してください!";
    }
    else if($umail=="") {
      $error[] = "メールアドレスを入力してください!";
    }
    else if(!filter_var($umail, FILTER_VALIDATE_EMAIL)) {
      $error[] = 'メールアドレスのフィルタリングが失敗しました!';
    }
    else if($upass=="") {
      $error[] = "パスワードを入力してください!";
    }
    else if(strlen($upass) < 6){ //パスワードは6文字以上である必要があるようにバリデーションを付ける
      $error[] = "パスワードを6文字以上に設定してください!";
    }
    else{
      try{
        //データベースへの格納準備
        $stmt = $DB_con->prepare("SELECT user_name,user_email,user_pointNum,user_comment FROM usersData1 WHERE user_name=:uname OR user_email=:umail OR user_pointNum=:upoint OR user_comment=:ucomment");
        $stmt->execute(array(':uname'=>$uname, ':umail'=>$umail, ':upoint'=>$upoint, ':ucomment'=>$ucomment));
        $row=$stmt->fetch(PDO::FETCH_ASSOC);

        if($row['user_name']==$uname) {
          $error[] = "既にあるユーザー名です。ユーザー名を変更してください。";
        }
        else if($row['user_email']==$umail) {
          $error[] = "既にあるメールアドレスです。メールアドレスを変更してください。";
        }
        else{
          //データベースへ入力したユーザー情報を登録する
          if($user->register($uname,$umail,$upoint,$ucomment,$upass)){
            $user->redirect('register.php?joined');
          }
        }
      }
      catch(PDOException $e){
        echo $e->getMessage();
      }
    }
  }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Sign up Page</title>
  <link rel="stylesheet" href="style.css" type="text/css"  />
</head>
<body>
  <div class="container">
     <div class="form-container">
        <form method="post">
            <h2>Sign up.</h2><hr />
            <?php
            if(isset($error))
            {
               foreach($error as $error)
               {
                  ?>
                  <div class="alert alert-danger">
                      <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $error; ?>
                  </div>
                  <?php
               }
            }
            else if(isset($_GET['joined']))
            {
                 ?>
                 <div class="alert alert-info">
                      <i class="glyphicon glyphicon-log-in"></i> &nbsp; Successfully registered <a href='index.php'>login</a> here
                 </div>
                 <?php
            }
            ?>
            <div class="form-group">
            <input type="text" class="form-control" name="txt_uname" placeholder="Enter Username" value="<?php if(isset($error)){echo $uname;}?>" />
            </div>
            <div class="form-group">
            <input type="text" class="form-control" name="txt_umail" placeholder="Enter E-Mail ID" value="<?php if(isset($error)){echo $umail;}?>" />
            </div>
            <div class="form-group">
              <input type="hidden" class="form-control" name="txt_upoint" value="0" />
            </div>
            <div class="form-group">
              <input type="hidden" class="form-control" name="txt_ucomment" value="コメント" />
            </div>
            <div class="form-group">
             <input type="text" class="form-control" name="txt_upass" placeholder="Enter Password" />
            </div>
            <div class="clearfix"></div><hr/>
            <div class="form-group">
              <button type="submit" class="btn btn-block btn-primary" name="btn-signup">
                 <i class="glyphicon glyphicon-open-file"></i>&nbsp;SIGN UP
              </button>
            </div>
            <br />
            <label>ログインへ <a href="index.php">Sign In</a></label>
        </form>
       </div>
     </div>
</body>
</html>
