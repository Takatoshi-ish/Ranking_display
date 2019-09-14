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
$sql = 'SELECT * FROM usersData1';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();

/* データベースによる出力 */
$team = array('A','B','C','D','E');
$teamPoint = array(0,0,0,0,0);
foreach ($results as $row){
  $teamName = substr($row['user_name'],0,1);
  if($team[0] == $teamName){
    $teamPoint[0] += (int)$row['user_pointNum'];
    continue;
  }
  else if($team[1] == $teamName){
    $teamPoint[1] += (int)$row['user_pointNum'];
    continue;
  }
  else if($team[2] == $teamName){
    $teamPoint[2] += (int)$row['user_pointNum'];
    continue;
  }
  else if($team[3] == $teamName){
    $teamPoint[3] += (int)$row['user_pointNum'];
    continue;
  }
  else if($team[4] == $teamName){
    $teamPoint[4] += (int)$row['user_pointNum'];
    continue;
  }
}
if(count($teamPoint) >= 2){
  for($i = 0; $i < count($teamPoint)-1; $i++){
    for($j = $i+1; $j < count($teamPoint); $j++){
      if($teamPoint[$i] < $teamPoint[$j]){
        $tempPoint = $teamPoint[$i];
        $teamPoint[$i] = $teamPoint[$j];
        $teamPoint[$j] = $tempPoint;
        $tempName = $team[$i];
        $team[$i] = $team[$j];
        $team[$j] = $tempName;
      }
    }
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="style.css" type="text/css"  />
<link rel="stylesheet" href="rank.css" type="text/css"  />
<title>チームランキング</title>
</head>

<body>
  <div class="header">
    <div class="left">
      <label><a href="mypage.php">MyPage</a></label>
      <label><a href="allRank.php">AllRanking</a></label>
    </div>
    <div class="right">
      <label><a href="logout.php?logout=true"><i class="glyphicon glyphicon-log-out"></i> logout</a></label>
    </div>
  </div>
  <div class="ranking">
  全体順位(チーム)<br>
  <table>
    <tr>
      <th>順位</th>
      <th>チーム名</th>
      <th>チーム：ポイント</th>
    </tr>
    <?php
    /* データベースによる出力 */
    $count = count($teamPoint);
    $rankNum = 0;
    for($i = 0; $i < $count; $i++){
      $rankNum = $i + 1;
    ?>
    <tr>
      <td><?php echo $rankNum . "位"; ?></td>
      <td><?php echo $team[$i]; ?></td>
      <td><?php echo $teamPoint[$i] . " points"; ?></td>
    </tr>
    <?php
    }
    ?>
  </table>
  </div>
</body>
</html>
