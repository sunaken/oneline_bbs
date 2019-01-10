<?php
  // ここにDBに登録する処理を記述する
  //登録処理
//1,DB 接続
$dsn='mysql:dbname=oneline_bbs1;host=localhost';
//Data SOurce Name
//DB 情報　どこに接続するか

$user='root';//誰が
$password='';//パスワードは何か

$dbn=new PDO($dsn,$user,$password);
//接続処理
//dbh
//Database Handle 
//データベースを扱うことができるやつ
$dbn->query('SET NAMES utf8');
//文字コード設定
//2.SQL実装

if(!empty($_POST)){//POST送信かどうか
	$nickname=$_POST['nickname'];
    $comment=$_POST['comment'];
    //$_POSTは連想配列
    $sql='INSERT INTO `posts`(`nickname`,`comment`,`created`)VALUES (?,?,NOW())';
    //?を使う理由
    //SQLインジェクション対策
    //NOW()はSQLの関数　現在日時を算出
    $data=[$nickname,$comment];
    $stmt=$dbn->prepare($sql);
    $stmt->execute($data);
    //ここで初めてSQLが実行される
}

//一覧表示
//投稿情報をすべて取得
//SELECT*FROM テーブル名;
$sql='SELECT*FROM`posts`';
$stmt=$dbn->prepare($sql);
$stmt->execute();
//sql文に?がないので$data渡す必要なし

$posts=[];  //取得したデータを格納するための配列
while(true){
	$record=$stmt->fetch(PDO::FETCH_ASSOC);
	//1行ずつ処理
	if($record==false){
		break;
	}
	$posts[]=$record;
	//配列にレコードを追加
}

echo'<pre>';
var_dump($posts);
echo'</pre>';

?>

<?php  foreach($posts as $post):?>
      <p><?php echo $post['nickname'];?></p>

    <!--日付-->
    
      <p><?php echo $post['created'];?></p>
  
    
    <!--コメント-->
    
      <p><?php echo $post['comment'];?></p>
  <hr>
    <?php endforeach;?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>セブ掲示版</title>
</head>
<body>
	<!--formタグにはmethodとaction必須-->
	<!--method:送信方法　どうアクセスするか
		action:送信先　アクセスする場所
	    actionが空白の場合、自分自身に戻る-->

    <form method="post" action="">
    	<!--formタグないのinputタグやtextareaタグのname属性が$_POSTのキーになる
    	-->
      <p><input type="text" name="nickname" placeholder="nickname"></p>
      <p><textarea type="text" name="comment" placeholder="comment"></textarea></p>
      <p><button type="submit" >つぶやく</button></p>
    </form>
    <!-- ここにニックネーム、つぶやいた内容、日付を表示する -->
    <!--一覧表示-->
    <!--投稿情報をすべて表示する=一件ずつ繰り返し表示処理をする$postsは配列なので、foreachが使える
         foreach($配列名 as  $任意の変数名)
         foreach(複数形as単数形)-->
　　 
</body>
</html>