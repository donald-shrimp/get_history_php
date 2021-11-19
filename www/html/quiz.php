<?php
if (isset($_POST["uid"])) {
  $comment = $_POST["uid"];
  echo "<p color='red'>$comment</p>";

  //５日前の日付を取得
  $date = date('Y-m-d', strtotime('-5 day'));

  //正解を10個DBから配列に入れる.正答数も配列に記録したい
  try {
    $link = new PDO(
      'mysql:host=db;dbname=history;charset=utf8mb4',
      'root',
      'secret'
    );
    
    // データ挿入
    $sql = "SELECT * FROM `history` WHERE date >= ':date' AND uid=':uid' ORDER BY RAND() LIMIT 10";
    $prepare = $link->prepare($sql);
    $prepare->bindValue(':date', $date, PDO::PARAM_STR);
    $prepare->bindValue(':uid', $_POST['uid'], PDO::PARAM_STR);
    echo $date ,$_POST["uid"];
    

    // データ取得(鬱陶しいのでコメントアウト)
    // $prepare = $link->prepare('SELECT * FROM history');
    $prepare->execute();
    $result = $prepare->fetchAll(PDO::FETCH_ASSOC);
    var_dump($result);

  } catch (PDOException $e) {

    $error = $e->getMessage();
    print($error);
  }



  //問題作りをする




  //不正解をDBからランダムに三件取得（自分のIDのものは除外）(同じURLも除外)




  //四つをランダムに並べる



  //正解が押されたら配列の正答判定をおん



  //10問繰り返す



  //正答率を出す 

} else {
  echo "何かに失敗した";
}
