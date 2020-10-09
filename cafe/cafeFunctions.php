<?php
// create daily password
function createPW($dbconn,$today){
  $getpas = "select * from dailypass where date='{$today}';";
  $respas = pg_query($dbconn,$getpas) or die('Query failed1: ' . pg_last_error());
  if (pg_num_rows($respas) == 0 ){
    $p = "";
    for ($i = 0; $i < 4; $i++){
      $p.=mt_rand(0,9);
    }
    $cryptedpass=password_hash($p, PASSWORD_BCRYPT);
    $inspass ="insert into dailypass(date,pass) values('{$today}','{$cryptedpass}');";
    pg_query($dbconn,$inspass) or die('Query failed2: ' . pg_last_error());
    $insp = "insert into dp(date,pass) values('{$today}','{$p}');";
    pg_query($dbconn,$insp) or die('Query failed3: ' . pg_last_error());
    return $p;
  } else {
    $getp="select * from dp where date='{$today}';";
    $g = pg_query($dbconn,$getp) or die('Query failed4: ' . pg_last_error());
    $getid = pg_fetch_row($g);
    $id =$getid[1];
    return $id;
  }
}

// get img file and add it to food list database
function uploadFood($dbconn){
  if (isset($_FILES['file_upload'])){
    if ($_FILES['file_upload']['error']==0){
      if (isset($_REQUEST["token"]) && isset($_SESSION["token"]) && $_REQUEST["token"] == $_SESSION["token"]){
        $file =$_FILES['file_upload'];
        $tn = $file['tmp_name'];
        $imgname = "../img/".time().getmypid().$file['name'];
        if (move_uploaded_file($tn,$imgname)){
          $fname=$_POST['name'];
          $check = "select * from foodlist where fname='{$fname}'";
          $che= pg_query($dbconn,$check) or die('Query failed5: ' . pg_last_error());
          if (pg_num_rows($che)==0){
            $addfood = "insert into foodlist(fname,fimg) values('{$fname}','{$imgname}');";
            pg_query($dbconn,$addfood) or die('Query failed6: ' . pg_last_error());
            return "<script>alert('「{$fname}」を登録しました');</script>";
          } else {
            return "<script>alert('「{$fname}」はすでに登録されています');</script>";
          }
        } else {
          return "<script>alert('画像アップロードに問題があり登録できませんでした');</script>";
        }
      }
    }
  }
}

// delete from food list
function deleteList($dbconn){
  if (isset($_POST['dfid'])){
    if (isset($_REQUEST["token"]) && isset($_SESSION["token"]) && $_REQUEST["token"] == $_SESSION["token"]){
      $dfid=$_POST['dfid'];
      $dfname =$_POST['dfname'];
      $del ="delete from foodlist where id='{$dfid}'";
      pg_query($dbconn,$del) or die('Query failed7: ' . pg_last_error());
      return "<script>alert('{$dfname}をフードリストから削除しました。');</script>";
    }
  }
  return "";
}
//return today's list
function getTdlist($dbconn,$today){
  $tdlist ="select * from foodstream where time='{$today}' order by id asc;";
  $tdres= pg_query($dbconn,$tdlist) or die('Query failed8: ' . pg_last_error());
  return $tdres;
}
// Cancel a post of today's foods
function cancel($dbconn){
    $delid=$_POST['delid'];
    $del="delete from foodstream where id={$delid};";
    pg_query($dbconn,$del) or die('Query failed9: ' . pg_last_error());
    header('Location: https://gms.gdl.jp/~kuwanori/JulyGroup5/cafe.php');
}
// get image data
function getImg($dbconn,$line){
  $gimg="select fimg from foodlist where id={$line[5]};";
  $kkimg = pg_query($dbconn,$gimg) or die('Query failed10: ' . pg_last_error());
  $img = pg_fetch_row($kkimg);
  return $img[0];
}
function addToday($dbconn,$today){
  $fid = $_POST['fid'];
  $number =$_POST['number'];
  $namae = $_POST['namae'];
  $memo =$_POST['memo'];
  $add ="insert into foodstream(time,fname,number,fid,memo,restudents) values('{$today}','{$namae}',{$number},{$fid},'{$memo}','-');";
  pg_query($dbconn,$add) or die('Query failed11: ' . pg_last_error());
  header('Location: https://gms.gdl.jp/~kuwanori/JulyGroup5/cafe.php');
}
function getFdlist($dbconn){
  $listing="select * from foodlist;";
  $lis = pg_query($dbconn,$listing) or die('Query failed12: ' . pg_last_error());
  return $lis;
}
 ?>
