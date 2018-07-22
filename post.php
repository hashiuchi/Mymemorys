<?php

date_default_timezone_set('Asia/Manila');

$title ='';
$detail='';
$errors=array();


if (!empty($_POST)) {
    $title = $_POST['title'];
    $detail = $_POST['detail'];
    $date = $_POST['date'];
    if ($title == '') {
        $errors['title'] = 'red';
    }

    if ($detail == ''){
        $errors['detail'] = 'blue';
    }
    if ($date == '') {
        $errors['date'] = 'pink';
    }
// 文字数制限つけた
    $count = strlen($title);
    $figue = strlen($detail);
    if ($count > 25) {
        $errors['titles'] = 'yellow';
    }
    if ($figue >141) {
        $errors['details'] = 'black';
    }


    $file_name = '';
    if (!isset($_GET['action'])) {
//$_FILEはGET送信？？なぜPOSTで送っているのにGET？？
        $file_name = $_FILES['input_img_name']['name'];
    }

    //fpgかpngpngのたしかめ
    if(!empty($file_name)){
        $file_type = substr($file_name,-3);
        $file_type = strtolower($file_type);
        if($file_type != 'jpg'&& $file_type !='png' && $file_type !='gif'){
            $errors['img_name'] = 'type';
        }
    }
    else{
        $errors['img_name']='blank';
    }

            //バリエーションがうまくいった時
    if (empty($errors)) {
        $date_str = date('YmdHis');
        $submit_file_name = $date_str;
        move_uploaded_file($_FILES['input_img_name']['tmp_name'] , 'post_img/'.$submit_file_name);

// tmp_nameとname(写真送信のとき使うやつ)の違い

        require('template.php');


        $titles = $_POST['title'];
        $dates = $_POST['date'];
        $details = $_POST['detail'];
        $img_name = $submit_file_name;

        $sql = 'INSERT INTO`feeds`SET`title`=?,`date`=?,`detail`=?,`img_name`=?';
        $data = array($titles,$dates,$details,$img_name);
        $stmt = $dbh->prepare($sql);
        $stmt ->execute($data);

        $dbh=null;

        header('Location: index.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>My Memories</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="assets/css/main.css" rel="stylesheet">
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="assets/js/chart.js"></script>


    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
  <![endif]-->
</head>

<body>

    <!-- Fixed navbar -->
    <div class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href=""><i class="fa fa-camera" style="color: #fff;"></i></a>
    </div>
    <div class="navbar-collapse collapse">
      <ul class="nav navbar-nav navbar-right">
        <li class="active"><a href="index.html">Main page</a></li>
    </ul>
</div><!--/.nav-collapse -->
</div>
</div>


<div class="container">
  <div class="col-xs-8 col-xs-offset-2 thumbnail">
    <h2 class="text-center content_header">写真投稿</h2>
    <form method="POST" action="" enctype="multipart/form-data">
      <div class="form-group">
        <label for="task">タイトル</label>
        <input name="title" class="form-control">
        <?php if (isset($errors['title']) && $errors['title'] = 'red') { ?>
            <p class="text-danger">タイトルを入力してください</p>
        <?php } ?>
        <?php if (isset($errors['titles']) && $errors['titles'] = 'yellow') { ?>
            <p class="text-danger">タイトルの字数が多すぎます</p>
        <?php } ?>

    </div>
    <div class="form-group">
        <label for="date">日程</label>
        <input type="date" name="date" class="form-control">
        <?php if (isset($errors['date']) && $errors['date'] = 'pink') { ?>
            <p class="text-danger">日付を選択して下さい</p>
        <?php } ?>
    </div>
    <div class="form-group">
        <label for="detail">詳細</label>
        <textarea name="detail" class="form-control" rows="3"></textarea>
        <?php if (isset($errors['detail']) && $errors['detail'] = 'blue') { ?>
            <p class="text-danger">内容を記述して下さい</p>
        <?php } ?>
        <?php if (isset($errors['details']) && $errors['details'] = 'black') { ?>
            <p class="text-danger">内容がたっぷりすぎます</p>
            <?php } ?><br>
        </div>
        <div class="form-group">
            <label for="img_name">写真</label>
            <input type="file" name="input_img_name" id="img_name">
            <?php if(isset($errors['img_name']) && $errors['img_name'] == 'blank'){ ?>
                <p class="text-danger">画像を選択してください</p>
            <?php } ?>
            <?php if(isset($errors['img_name']) && $errors['img_name'] == 'type'){ ?>
                <p class="text-danger">拡張子がjpg pngにしてください</p>
            <?php } ?>
        </div><br>
        <input type="submit" class="btn btn-primary" value="投稿">
    </form>
</div>
</div>

<div id="f">
  <div class="container">
    <div class="row">
      <p>I <i class="fa fa-heart"></i> Cubu.</p>
  </div>
</div>
</div>

    <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="assets/js/bootstrap.js"></script>
    </body>
    </html>
