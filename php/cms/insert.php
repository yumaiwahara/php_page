<?php
//----------------------------------------------------
//１．入力チェック(受信確認処理追加)
//----------------------------------------------------
//商品名 受信チェック:item
if(!isset($_POST["item"]) || $_POST["item"]==""){
  exit("ParameError! item!");
}

//金額 受信チェック:value
if(!isset($_POST["value"]) || $_POST["value"]==""){
  exit("ParameError! value!");
}


//商品紹介文 受信チェック:description
if(!isset($_POST["description"]) || $_POST["description"]==""){
  exit("ParameError! description!");
}


//ファイル受信チェック※$_FILES["******"]["name"]の場合
if(!isset($_FILES["fname"]["name"]) || $_FILES["fname"]["name"]==""){
  exit("ParameError! FILES!");
}




//----------------------------------------------------
//２. POSTデータ取得
//----------------------------------------------------
$fname  = $_FILES["fname"]["name"];   //File名
$item   = $_POST["item"];   //商品名
$value  = $_POST["value"];   //価格(数字：intvalを使う)
$description = $_POST["description"];   //商品紹介文


//1-2. FileUpload処理
$upload = "../img/"; //画像アップロードフォルダへのパス
//アップロードした画像を../img/へ移動させる記述↓
if(move_uploaded_file($_FILES['fname']['tmp_name'], $upload.$fname)){
  //FileUpload:OK
} else {
  //FileUpload:NG
  echo "Upload failed";
  echo $_FILES['fname']['error'];
}

//----------------------------------------------------
//３. DB接続します(エラー処理追加)
//----------------------------------------------------
try {
  $pdo = new PDO('mysql:dbname=gs_db;charset=utf8;host=localhost','root','');
} catch (PDOException $e) {
  exit('DbConnectError:'.$e->getMessage());
}

//----------------------------------------------------
//４．データ登録SQL作成
//----------------------------------------------------
$stmt = $pdo->prepare("INSERT INTO gs_ec_table(id, item, value, fname,
description, indate )VALUES(NULL, :item, :value, :fname, :description, sysdate())");
$stmt->bindValue(':item', $item, PDO::PARAM_STR);
$stmt->bindValue(':value', $value, PDO::PARAM_INT); //数値
$stmt->bindValue(':fname', $fname, PDO::PARAM_STR);
$stmt->bindValue(':description', $description, PDO::PARAM_STR);
$status = $stmt->execute();

//----------------------------------------------------
//５．データ登録処理後
//----------------------------------------------------
if($status==false){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error = $stmt->errorInfo();
  exit("QueryError:".$error[2]);
}else{
  //５．item.phpへリダイレクト
  header("Location: item.php");
  exit;
}
?>
