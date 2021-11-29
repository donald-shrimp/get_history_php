<?php
if (isset($_POST["uid"])) {
  $comment = $_POST["uid"];
  echo "<p color='red'>$comment</p>";

  //urlからタイトル取得
  function getTitlefromURL($url){
    //ソースの取得
    $source = @file_get_contents($url);
    //タイトルを抽出
    if (preg_match('/<title>(.*?)<\/title>/i', mb_convert_encoding($source, 'UTF-8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS'), $result)) {
    $title = $result[1];
    } else {
    //TITLEタグが存在しない場合
    $title = 'タイトルなし';
    }
    return $title;
  }


  //５日前の日付を取得
  $date = date('Y-m-d', strtotime('-5 day'));

  //正解を10個DBから配列に入れる.正答数も配列に記録したい
  try {
    $link = new PDO(
      'mysql:host=db;dbname=history;charset=utf8mb4',
      'root',
      'secret'
    );
    
    // 正解取得
    $sql = "SELECT * FROM 'history' WHERE date >= :date AND uid=:uid ORDER BY RAND() LIMIT 10";
    $prepare = $link->prepare($sql);
    $prepare->bindValue(':date', $date, PDO::PARAM_STR);
    $prepare->bindValue(':uid', $_POST['uid'], PDO::PARAM_STR);
    $prepare->execute();
    $answer = $prepare->fetchAll(PDO::FETCH_ASSOC);
    echo $answer[0]['title'] , "<br><br>";
    
    //ほかの選択肢を取得
    $sql = "SELECT * FROM 'history' WHERE NOT uid=:uid ORDER BY RAND() LIMIT 40";
    $prepare = $link->prepare($sql);
    $prepare->bindValue(':uid', $_POST['uid'], PDO::PARAM_STR);
    $prepare->execute();
    $other = $prepare->fetchAll(PDO::FETCH_ASSOC);
    var_dump($other);

    //問題用配列を作成
    for($i=0; $i<10 ; $i+=1 ){
      $rand_num = random_int($i*4,$i*4+3);
      $other[$rand_num]['title'] = $answer[$i]['title'];
      $other[$rand_num]['uid'] = $answer[$i]['uid'];
      $other[$rand_num]['url'] = $answer[$i]['url'];
      $other[$rand_num]['date'] = $answer[$i]['date'];
    }
    


 //不正解をDBからランダムに三件取得（自分のIDのものは除外）(同じURLも除外)




  //四つをランダムに並べる



  //正解が押されたら配列の正答判定をおん



  //10問繰り返す



  //正答率を出す 




  } catch (PDOException $e) {

    $error = $e->getMessage();
    print($error);
  }


 
} else {
  echo "何かに失敗した";
}
