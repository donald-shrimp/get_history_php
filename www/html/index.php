<?php
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
/* POSTされたJSON文字列を取り出し */
$json = file_get_contents("php://input");

 /* JSON文字列をobjectに変換
  ⇒ 第2引数をtrueにしないとハマるので注意 */
$contents = json_decode($json, true);

$uid = "User:".$contents['uid']."<br>";
$title = "Title:".$contents['title']."<br>";
$url = "URL:".$contents['url']."<br>";
$time = "time:".$contents['time']."<br>";

// print $uid.$title.$url.$time;
var_dump($contents);
echo $json;

//DB接続テスト 
try{
    $link = new PDO(
    'mysql:host=db;dbname=history;charset=utf8mb4',
    'root',
    'secret'
    // 例外を投げるオプション。PHP8以降は最初からオンなのでこの設定はいらないらしい
    // [
    //   PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    // ]  
  );
  print('<p>接続に成功しました。</p>');

  

  $prepare = $link->prepare('INSERT INTO history VALUES (:uid, :title, :url, :time)');
  $prepare->bindValue(':uid',$contents['uid']);
  $prepare->bindValue(':title',$contents['title']);
  $prepare->bindValue(':url',$contents['url']);
  $prepare->bindValue(':time',$contents['time']);
  $prepare->execute();

  $prepare = $link->prepare('SELECT * FROM history');
  $prepare->execute();
  $result = $prepare->fetchAll(PDO::FETCH_ASSOC);
  print_r($result);
}catch(PDOException $e){

  $error = $e->getMessage();
  print($error);
}




?>
