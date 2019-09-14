<?php
/*include_once 'dbconfig.php';
if(!$user->is_loggedin())
{
 $user->redirect('index.php');
}*/

$dsn = 'mysql:dbname=（データベース名）;host=localhost';
$user = '（ユーザー名）';
$password = '（パスワード）';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
$sql = 'SELECT * FROM usersData1 ORDER BY user_pointNum DESC ';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="style.css" type="text/css"  />
<link rel="stylesheet" href="rank.css" type="text/css"  />
<title>個人ランキング</title>
</head>

<body>
  <div class="header">
    <div class="left">
      <label><a href="mypage.php">MyPage</a></label>
      <label><a href="teamRank.php">TeamRanking</a></label>
    </div>
    <div class="right">
      <label><a href="logout.php?logout=true"><i class="glyphicon glyphicon-log-out"></i> logout</a></label>
    </div>
  </div>
  <div class="ranking">
  全体順位(個人)<br>
  <table>
    <tr>
      <th>順位</th>
      <th>登録番号</th>
      <th>氏名</th>
      <th>コメント</th>
      <th>チーム名</th>
      <th>個人：ポイント</th>
    </tr>
    <?php
    /* データベースによる出力 */
    $rankNum = 0;
    foreach ($results as $row){
      $rankNum += 1;
      $teamName = substr($row['user_name'],0,1);
    ?>
    <tr>
      <td><?php echo $rankNum . "位"; ?></td>
      <td><?php echo $row['user_id']; ?></td>
      <td><?php echo $row['user_name']; ?></td>
      <td><?php echo $row['user_comment']; ?></td>
      <td><?php echo $teamName; ?></td>
      <td><?php echo $row['user_pointNum'] . " points"; ?></td>
    </tr>
    <?php
    }
    ?>
  </table>
  </div>
</body>
</html>
