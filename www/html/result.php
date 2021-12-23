<?php
$count = 0;
for($i=0;$i<10;$i+=1){
    $num = (string)$i;
    if($_POST['quiz_'.$num]==$_POST['ans_'.(string)$i]){
        echo "第",$i+1,"問 正解<br>";
        $count+=1;
    }else{
        echo "第",$i+1,"問 不正解<br>";
    }
}
    echo "正答率",$count*10,"%<br><br><br><br><br>";


    echo "<iframe src='https://docs.google.com/forms/d/e/1FAIpQLSck-W7ZyFcMFNuem2CiuU_36vLaXzZgTa-ZZGV64DiYRhgwQA/viewform?embedded=true' width='640' height='960' frameborder='0' marginheight='0' marginwidth='0'>読み込んでいます…</iframe>";

    echo "<a src='./user.html'>ホームに戻る</a>";
?>