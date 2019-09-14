<?php
  class USER
  {
    private $db;

    function __construct($DB_con)
    {
      $this->db = $DB_con;
    }

    //ユーザー登録する関数
    public function register($uname,$umail,$upoint,$ucomment,$upass)
    {
       try{
          //新しく入力したパスワードをハッシュ関数で暗号化する
           $new_password = password_hash($upass, PASSWORD_DEFAULT);

           //格納するデータベースの準備をする
           $stmt = $this->db->prepare("INSERT INTO usersData1(user_name,user_email,user_pointNum,user_comment,user_pass)
                                                       VALUES(:uname, :umail, :upoint, :ucomment, :upass)");

          //入力した情報をデータベースに格納する
           $stmt->bindparam(":uname", $uname);
           $stmt->bindparam(":umail", $umail);
           $stmt->bindparam(":upoint", $upoint);
           $stmt->bindparam(":ucomment", $ucomment);
           $stmt->bindparam(":upass", $new_password);
           $stmt->execute();

           return $stmt;

       }
       catch(PDOException $e){
           echo $e->getMessage();
       }
    }

    //ログインするときの関数
    public function login($uname,$umail,$upass)
    {
       try{
          //データベースから名前とメールアドレスのデータを取ってくる
          $stmt = $this->db->prepare("SELECT * FROM usersData1 WHERE user_name=:uname OR user_email=:umail LIMIT 1");
          $stmt->execute(array(':uname'=>$uname, ':umail'=>$umail));
          $userRow=$stmt->fetch(PDO::FETCH_ASSOC);

          //入力したパスワードが入力した名前かメールアドレスのデータのパスワードと一致するか確かめる
          if($stmt->rowCount() > 0){
             if(password_verify($upass, $userRow['user_pass'])){
                $_SESSION['user_session'] = $userRow['user_id'];
                return true;
             }
             else{
                return false;
             }
          }

       }
       catch(PDOException $e){
           echo $e->getMessage();
       }
    }

    //ログイン中か判断する関数
    public function is_loggedin()
    {
      if(isset($_SESSION['user_session'])){
         return true;
      }
    }

     //ログアウトしてログイン状態を解除する関数
     public function logout()
     {
        session_destroy(); //logput.phpのsession_startを終了させる
        unset($_SESSION['user_session']);
        return true;
      }

      //引数に入力したページへ移動させる関数
      public function redirect($url)
      {
         header("Location: $url");
       }
  }
?>
