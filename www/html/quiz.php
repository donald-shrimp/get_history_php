<head>
  <meta charset="utf-8" />
  <title>たのしい個人情報クイズ　</title>
  <link rel="stylesheet" href="quiz.css" type="text/css">
  <!-- CDN -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
</head>

<body>
  <div id="wrapper">
    <div class="quiz">
      <header>たのしい！個人情報クイズ</header>
    </div><!-- form一歩手前 -->
    <form action="result.php" method="post">

      <?php
      if (isset($_POST["uid"])) {
        $comment = $_POST["uid"];
        // echo "<p color='red'>$comment</p>";

        //urlからタイトル取得
        function getTitlefromURL($url) {
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
          $sql = "SELECT * FROM history WHERE date >= :date AND uid=:uid ORDER BY RAND() LIMIT 10";
          $prepare = $link->prepare($sql);
          $prepare->bindValue(':date', $date, PDO::PARAM_STR);
          $prepare->bindValue(':uid', $_POST['uid'], PDO::PARAM_STR);
          $prepare->execute();
          $answer = $prepare->fetchAll(PDO::FETCH_ASSOC);
          // echo $answer[0]['title'], "<br><br>";

          //ほかの選択肢を取得
          $sql = "SELECT * FROM history WHERE NOT uid=:uid ORDER BY RAND() LIMIT 40";
          $prepare = $link->prepare($sql);
          $prepare->bindValue(':uid', $_POST['uid'], PDO::PARAM_STR);
          $prepare->execute();
          $other = $prepare->fetchAll(PDO::FETCH_ASSOC);
          // var_dump($other);

          //問題用配列を作成
          for ($i = 0; $i < 10; $i += 1) {
            $rand_num = random_int($i * 4, $i * 4 + 3);
            $other[$rand_num]['title'] = $answer[$i]['title'];
            $other[$rand_num]['uid'] = $answer[$i]['uid'];
            $other[$rand_num]['url'] = $answer[$i]['url'];
            $other[$rand_num]['date'] = $answer[$i]['date'];
            $other[$rand_num]['id'] = $answer[$i]['id'];
          }

          //問題を10問作成

          for ($i = 0; $i < 10; $i += 1) {
            echo "<div class='quiz'><p>第", $i + 1, "問　次のうち、5日以内にアクセスしたサイトは？</p>";
            for ($j = 0; $j < 4; $j += 1) {
              echo "<label><input type='radio' name='quiz_",$i, "' class='choices' value='",$other[$i * 4 + $j]['id'],"'>", $other[$i * 4 + $j]['title'], "　<a href='", $other[$i * 4 + $j]['url'], "'><i class='fas fa-link'></i></a></label><br>";
            }
            echo "<input type='hidden' name='ans_",$i,"' value='",$answer[$i]['id'],"'></div>";
          }

        } catch (PDOException $e) {

          $error = $e->getMessage();
          print($error);
        }
      } else {
        echo "何かに失敗した";
      }
      ?>

      <input type="submit" value="回答する" class="button">
    </form>
  </div><!-- form直後 -->
</body>

</html>