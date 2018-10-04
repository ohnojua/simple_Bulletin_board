<?php
header("Content-Type:text/html; charset=UTF-8");
?>


<?php
mb_language("uni");
   mb_internal_encoding("utf-8");
   mb_http_input("auto");
   mb_http_output("utf-8");
   $time=date("Y/m/d H:i:s");
   $num=0;

//sqlに接続
$dsn='データベース名';
$user='ユーザー名';
$password='パスワード';     
$pdo=new PDO($dsn,$user,$password);

$sql="CREATE TABLE mission4a"
   ."("."num int(5),"
  ."name char(32),"
  ."comment TEXT,"
  ."time char(50),"
  ."pw char(32)"
.");";

$stmt=$pdo->query($sql);


//今の一番最後のの投稿番号取得
$pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true);
$sql="SELECT * FROM mission4a ORDER BY num";
$stmt=$pdo->query($sql);
foreach($stmt as $row){
   $num=$row['num'];
}


$name=htmlspecialchars($_POST["name"]);
$comment=htmlspecialchars($_POST["comment"]);
$remove=htmlspecialchars($_POST["remove"]);
$editnum=htmlspecialchars($_POST["editnum"]);
$hidnum=htmlspecialchars($_POST["hidnum"]);
$password=htmlspecialchars($_POST["password"]);
$password1=htmlspecialchars($_POST["password1"]);
$password2=htmlspecialchars($_POST["password2"]);

$editname=NULL;
$editcomment=NULL;

if(($name=='' or $comment=='') and $remove=='' and $editnum!=''){
    //編集機能
    $sql="SELECT * FROM mission4a";
    $results=$pdo->query($sql);
    foreach($results as $row){
      if($editnum==$row['num']){
          if($password2==$row['pw']){
               $editname=$row['name'];
               $editcomment=$row['comment'];
               echo "{$editnum}番を編集します"."<br>";
               break;
          }elseif($password2!=$row['pw']){
               echo "パスワードが間違っています。"."<br>";
          }
      }
    }



}elseif($name!='' and $comment!='' and $hidnum!='' and $password!=''){
    //編集機能②
     $nm=$name;
     $cm=$comment;
     $tm=$time;
     $pass=$password;
     $sql="update mission4a set name='$nm', comment='$cm', time='$tm', pw='$pass' where num=$hidnum";
     $result=$pdo->query($sql);
     echo "{$hidnum}番を編集しました。"."<br>";


}elseif($name!='' and $comment!='' and $hidnum=='' and $password!=''){
     //新規書き込み
     echo "入力ありがとうございます"."<br>";
     
     $num+=1;
     
     #SQL書き込み
     $sql=$pdo->prepare("INSERT INTO mission4a (num,name,comment,time,pw) VALUES (:num,:name,:comment,:time,:pw)");
     $sql->bindParam(':num',$numq,PDO::PARAM_INT);
     $sql->bindParam(':name',$nameq,PDO::PARAM_STR);
     $sql->bindParam(':comment',$commentq,PDO::PARAM_STR);
     $sql->bindParam(':time',$timeq,PDO::PARAM_STR);
     $sql->bindParam(':pw',$pwq,PDO::PARAM_STR);
     $numq=$num;
     $nameq=$name;
     $commentq=$comment;
     $timeq=$time;
     $pwq=$password;
     $sql->execute();

     $sql="SELECT * FROM mission4a ORDER BY num";
     $result=$pdo->query($sql);


}elseif(($name=='' or $comment=='') and $remove!='' and $editnum==''){
    //削除機能
    $sql="SELECT * FROM mission4a";
    $results=$pdo->query($sql);
    foreach($results as $row){
      if($remove==$row['num']){
         if($password1==$row['pw']){
             $sql="delete from mission4a where num=$remove";
             $result=$pdo->query($sql);
             echo "{$remove}番を削除しました。"."<br>";
         }elseif($remove!=$row['num']){
             echo "パスワードが間違っています。"."<br>";
         }
      }
    }

}


?>
<html>
<meta http-equiv="content-type" charset="utf-8">
<head><title>mission4</title></head>
<body>
<?php
$name=htmlspecialchars($_POST["name"]);
$comment=htmlspecialchars($_POST["comment"]);
$remove=htmlspecialchars($_POST["remove"]);
$editnum=htmlspecialchars($_POST["editnum"]);
$hidnum=htmlspecialchars($_POST["hidnum"]);
$password=htmlspecialchars($_POST["password"]);
$password1=htmlspecialchars($_POST["password1"]);
$password2=htmlspecialchars($_POST["password2"]);


   if(isset($_POST["btn"])){
     if(($name=='' or $comment=='' or $password=='') and ($remove=='' and $password1=='' ) and ($editnum=='' and $password2=='' )){
              echo "入力に不備があります。"."<br>";
              echo "書き込みなら、名前、コメント、パスワードを、"."<br>";
              echo "削除なら、削除したい投稿番号、そのパスワードを、"."<br>";
              echo "編集なら、編集したい投稿番号、そのパスワードをご入力ください。"."<br>"."<br>";
     }elseif(($name=='' and $comment=='' and $password=='') and ($remove=='' or $password1=='' ) and ($editnum=='' and $password2=='' )){
               echo "入力に不備があります。"."<br>";
               echo "書き込みなら、名前、コメント、パスワードを、"."<br>";
               echo "削除なら、削除したい投稿番号、そのパスワードを、"."<br>";
               echo "編集なら、編集したい投稿番号、そのパスワードをご入力ください。"."<br>"."<br>";
     }elseif(($name=='' and $comment=='' and $password=='') and ($remove=='' and $password1=='' ) and ($editnum=='' or $password2=='' )){
                echo "入力に不備があります。"."<br>";
                echo "書き込みなら、名前、コメント、パスワードを、"."<br>";
                echo "削除なら、削除したい投稿番号、そのパスワードを、"."<br>";
                echo "編集なら、編集したい投稿番号、そのパスワードをご入力ください。"."<br>"."<br>";
     }
  }
?>

<form action="mission4.php" method="post" target="_self">
こんにちは！何か入力してください。<br/>
パスワードの扱いには注意してください。<br/>
<br/>
   名前：<br/>
<input type="text" name="name" value="<?=$editname; ?>" placeholder="山田太郎"/><br/>
コメント：<br/>
<input type="text" name="comment" value="<?=$editcomment; ?>" placeholder="こんにちは！"/><br/>
パスワード：<br/>
<input type="text" name="password" placeholder="×××××"/>
<input type="submit" name="btn" value="送信"/><br/>
<input type="hidden" name="hidnum" value="<?=$editnum; ?>"/>
   <br/>
   ＜削除＞<br/>
   <input type="text" name="remove" placeholder="削除したい番号"/><br/>
   <input type="text" name="password1" placeholder="そのパスワード"/>
   <input type="submit" name="btn" value="削除"/><br/>
   <br/>
   ＜編集＞<br/>
   <input type="text" name="editnum" placeholder="編集したい番号"/><br/>
   <input type="text" name="password2" placeholder="そのパスワード"/>
   <input type="submit" name="btn" value="編集番号"/><br/>
</form>
<p><?php
     $sql='SELECT * FROM mission4a ORDER BY num';
     $results=$pdo->query($sql);
     foreach($results as $row){
        echo "投稿番号：{$row['num']} 名前：{$row['name']} コメント：{$row['comment']} 投稿日：{$row['time']}"."<br>";
     }
?></p>
</body>
</html>