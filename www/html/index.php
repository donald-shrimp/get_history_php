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
$link = PDO('mysql:host=localhost:13306;dbname=', 'root', 'secret');
if (!$link) {
    die('接続失敗です。'.mysql_error());
}

print('<p>接続に成功しました。</p>');

?>
