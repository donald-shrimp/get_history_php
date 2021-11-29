<?php
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
/* POSTされたJSON文字列を取り出し */
$json = file_get_contents("php://input");

/* JSON文字列をobjectに変換
  ⇒ 第2引数をtrueにしないとハマるので注意 */
$contents = json_decode($json, true);


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
  
  //もしタイトルが空,/youtubeなら取得していれる
  if(strlen($contents['title']) == 0||$contents['title'] == 'YouTube'||preg_match('/www.youtube.com/',$contents['url'] )){
    $prepare->bindValue(':title',GetTitlefromURL($contents['url']), PDO::PARAM_STR);
  }else{
    $prepare->bindValue(':title',$contents['title'], PDO::PARAM_STR);
  }

  $prepare->bindValue(':url',$contents['url'], PDO::PARAM_STR);
  $prepare->bindValue(':date',$today, PDO::PARAM_STR);

  if(!($prepare->execute())){
    print("\nデータ登録に失敗\n");
    print_r("\n\nERROR:\n");
    print_r($prepare->errorInfo());
    print("\n");
  }


  
  
}catch(PDOException $e){

  $error = $e->getMessage();
  print($error);
}
?>
