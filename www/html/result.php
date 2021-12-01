<?php
$count = 0;
for($i=0;$i<10;$i+=1){
    if($_POST['quiz_'+(string)$i]==$_POST['ans_'+(string)$i]){
        echo "第",$i+1,"問　正解";
        $count+=1;
    }else{
        echo "第",$i+1,"問　不正解";
    }
}
    echo "正答率",$count*10,"%";
?>