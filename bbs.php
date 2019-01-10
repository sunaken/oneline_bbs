<?php


$dsn='mysql:dbname=oneline_bbs1;host=localhost';


$user='root';
$password='';
$dbn=new PDO($dsn,$user,$password);

$dbn->query('SET NAMES utf8');




if(!empty($_POST)){//POST送信かどうか
  $nickname=$_POST['nickname'];
  $comment=$_POST['comment'];
  //$_POSTは連想配列
  $sql='INSERT INTO `posts`(`nickname`,`comment`,`created`)VALUES (?,?,NOW())';
  //?を使う理由
  //SQLインジェクション対策
  //NOW()はSQLの関数 現在日時を算出
  $data=[$nickname,$comment];
  $stmt=$dbn->prepare($sql);
  $stmt->execute($data);
  //ここで初めてSQLが実行される


}


//ORDER BY カラム名(ASC OR DESK)
//指定したカラムでソートがされる
//ASC=昇順 DESC=降順
//ASCは省略可能(デフォルト値)

$sql='SELECT*FROM`posts` ORDER BY `ID` DESC';
$stmt=$dbn->prepare($sql);
$stmt->execute();

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



?>




<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>セブ掲示版</title>

  <!-- CSS -->
  <link rel="stylesheet" href="assets/css/bootstrap.css">
  <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="assets/css/form.css">
  <link rel="stylesheet" href="assets/css/timeline.css">
  <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>
  <!-- ナビゲーションバー -->
  <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header page-scroll">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="#page-top"><span class="strong-title"><i class="fa fa-linux"></i> Oneline bbs</span></a>
          </div>
          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav navbar-right">
              </ul>
          </div>
          <!-- /.navbar-collapse -->
      </div>
      <!-- /.container-fluid -->
  </nav>

  <!-- Bootstrapのcontainer -->
  <div class="container">
    <!-- Bootstrapのrow -->
    <div class="row">

      <!-- 画面左側 -->
      <div class="col-md-4 content-margin-top">
        <!-- form部分 -->
        <form action="bbs.php" method="post">
          <!-- nickname -->
          <div class="form-group">
            <div class="input-group">
              <input type="text" name="nickname" class="form-control" id="validate-text" placeholder="nickname" required>
              <span class="input-group-addon danger"><span class="glyphicon glyphicon-remove"></span></span>
            </div>
          </div>
          <!-- comment -->
          <div class="form-group">
            <div class="input-group" data-validate="length" data-length="4">
              <textarea type="text" class="form-control" name="comment" id="validate-length" placeholder="comment" required></textarea>
              <span class="input-group-addon danger"><span class="glyphicon glyphicon-remove"></span></span>
            </div>
          </div>
          <!-- つぶやくボタン -->
          <button type="submit" class="btn btn-primary col-xs-12" disabled>つぶやく</button>
        </form>

         
      </div>

      <!-- 画面右側 -->
      <div class="col-md-8 content-margin-top">
        <div class="timeline-centered">
          <?php  foreach($posts as $post):?>
          <article class="timeline-entry">
              <div class="timeline-entry-inner">
                <div class="timeline-icon bg-success">
                  <i class="entypo-feather"></i>
                  <i class="fa fa-cogs"></i>
                </div>
                <div class="timeline-label">
                  <h2><a href="#"><?php echo $post['nickname'];?></a><span><?php echo $post['created'];?></span></h2>
                  <p><?php echo $post['comment'];?></p>
                </div>
              </div>
            </article>
            <?php endforeach; ?>

            <article class="timeline-entry begin">
              <div class="timeline-entry-inner">
                  <div class="timeline-icon" style="-webkit-transform: rotate(-90deg); -moz-transform: rotate(-90deg);">
                      <i class="entypo-flight"></i> +
                  </div>
              </div>
          </article>

        </div>
      </div>

    </div>
  </div>

  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <script src="assets/js/bootstrap.js"></script>
  <script src="assets/js/form.js"></script>



 
　　
    
</body>
</html>


