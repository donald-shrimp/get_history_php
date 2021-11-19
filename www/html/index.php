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

var_dump($contents);

//DB接続テスト
try{
    $link = new PDO(
      'mysql:host=db;dbname=history;charset=utf8mb4',
      'root',
      'secret'
    );
  print('<p>接続に成功しました。</p>');

  // データ挿入
  $today = date("Y-m-d");

  $sql = 'INSERT INTO history VALUES (:uid, :title, :url, :date)';
  $prepare = $link->prepare($sql);
  $prepare->bindValue(':uid',$contents['uid'], PDO::PARAM_STR);
  $prepare->bindValue(':title',$contents['title'], PDO::PARAM_STR);
  $prepare->bindValue(':url',$contents['url'], PDO::PARAM_STR);
  $prepare->bindValue(':date',$today, PDO::PARAM_STR);

  if(!($prepare->execute())){
    print("\nデータ登録に失敗\n");
    print_r("\n\nERROR:\n");
    print_r($prepare->errorInfo());
    print("\n");
  }


  // データ取得(鬱陶しいのでコメントアウト)
  // $prepare = $link->prepare('SELECT * FROM history');
  // $prepare->execute();
  // $result = $prepare->fetchAll(PDO::FETCH_ASSOC);
  // print_r($result);
  
}catch(PDOException $e){

  $error = $e->getMessage();
  print($error);
}
?>
