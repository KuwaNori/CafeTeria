<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>jG5 Cafe</title>
    <link rel="stylesheet" href="../css/hub.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/animation.css">
    <link rel="stylesheet" href="../css/cafe.css">
    <link rel="stylesheet" href="../css/todaysList.css">
  </head>
  <body>
    <?php
    session_start();
    header('Expires: -1');
    header('Cache-Control:');
    header('Pragma:');
    // rquire other files
    require_once("../general/connectDB.php");
    require_once("./cafeFunctions.php");
    // connect to Data Base
    $dbconn= connectDB();
    // set date
    date_default_timezone_set('Asia/Tokyo');
    $today = date("Y-m-d");
    // create daily password
    $daypass = createPW($dbconn,$today);
    ?>
    <header>
      <p class="logo">jG5</p>
      <p class="daypass"><?php echo $daypass; ?></p>
      <div class="links">
        <a href="#" class="cafe" id="first" onclick="ChangeMain('Today')"><img class="logoimg" src="../logoimg/list.png"><span class="title">今日の一覧</span></a>
        <a href="#" class="cafe" id="second" onclick="ChangeMain('List')"><img class="logoimg" src="../logoimg/food.png"><span class="title">食材の一覧</span></a>
      </div>
    </header>

    <?php
    // get img file and add it to food list database
    $uploadStatus = uploadFood($dbconn);
    echo $uploadStatus;
    // 食材リストからの削除
    $df = deleteList($dbconn);
    echo $df;
    ?>
    <!-- Today's List Section -->
    <main id="Today">
      <?php
      // Get today's list
      $tdres = getTdlist($dbconn,$today);
      // If recieved delete request
      if(isset($_POST['delid'])){
        cancel($dbconn);
      }
      ?>
      <!-- Whether food has {posted} or {not} -->
      <!-- not -->
      <?php if (pg_num_rows($tdres)==0):?>
        <p>本日の食材はまだポストされてません。</p>
      <!-- posted -->
      <?php else:?>
      <!-- create variable for delete number -->
      <?php $delnum=0;?>
        <p>本日の食材</p>
        <div class='wrap'>
          <div class='container'>
            <!-- showing today's list -->
            <?php while ($line = pg_fetch_row($tdres)):?>
              <?php $img = getImg($dbconn,$line);?>
              <div class='food-box' onclick='showdel(<?php echo $delnum;?>)'>
                <img class='food__pic' src='<?php echo $img; ?>'>
                <div class='food__num'><p><?php echo $line[3];?></p></div>
                <div class='food__name'><p><?php echo $line[2];?><p></div>
                <div class='food__detail'><p><?php echo $line[6];?><p></div>
                <form action='./cafe.php' class='delc' method='post'>
                  <input type='hidden' name='delid' value='<?php echo $line[0];?>'>
                  <input type='submit' class='delf' value='削除' onclick='return com()'>
                </form>
              </div>
              <?php$delnum++;?>
            <?php endwhile; ?>
          </div>
        </div>
      <?php endif; ?>
    </main>
    <!-- The code below remains for update someday -->
    <main id="Add">
    </main>

    <!-- Food List Section -->
    <main id="List">
      <?php
      if (isset($_REQUEST["token"]) && isset($_SESSION["token"]) && $_REQUEST["token"] == $_SESSION["token"]){
      // Add today's food list
        if (isset($_POST['number']) && isset($_POST['namae'])){
            $fid = $_POST['fid'];
            $number =$_POST['number'];
            $namae = $_POST['namae'];
            $memo =$_POST['memo'];
            $add ="insert into foodstream(time,fname,number,fid,memo,restudents) values('{$today}','{$namae}',{$number},{$fid},'{$memo}','-');";
            pg_query($dbconn,$add) or die('Query failed11: ' . pg_last_error());
            header('Location: https://gms.gdl.jp/~kuwanori/JulyGroup5/cafe.php');
        }
    }
      $_SESSION["token"] = $token = mt_rand();
      ?>
      <!-- Form for uploading image file -->
      <div class="upload">
        <label><input type="button" onclick="showup()" style="display: none;"><p class="tuika">一覧に食材を追加</p></label>
        <form action="./cafe.php" id="uploading" enctype="multipart/form-data" method="post">
          <input type="hidden" name="token" value="<?php echo $token; ?>">
            <input type="text" name="name" placeholder="食材名(15文字以内)" maxlength="15" required="required"><br>
            <div class="two">
              <label for="choose"><div class="chosen">画像を選択</div></label>
              <input type="file" id="choose" accept="image/*" name="file_upload">
              <img id="preview"><br>
              <input type="submit" name="" value="送信">
            </div>
        </form>
      </div>
      <?php
      // Showing food list
      $listing="select * from foodlist;";
      $lis = pg_query($dbconn,$listing) or die('Query failed12: ' . pg_last_error());
      if (pg_num_rows($lis)==0){
        echo "食材はまだ登録されていません。";
      } else {
        $n=0;
        echo "<div class='wrap'>";
        echo "<div class='container'>";
        while ($line = pg_fetch_row($lis)){
          echo "<label class='food-box''>";
          echo "<img class='food__pic' src='{$line[2]}'><div class='food__name'><p>{$line[1]}<p></div>";
          echo "<input class='checkbox' type='button' style='display:none;' onclick='pop({$n})'></label>";
          echo "<div class='popup'><form action='./cafe.php' method='post'>";
          echo "<input name='token' type='hidden' value='{$token}'>";
          echo "<div><img height='70px' src='{$line[2]}'></div>";
          echo "<div>{$line[1]}</div>";
          echo "<div class='number'><input type='text' name='number' placeholder='個数'></div>";
          echo "<textarea name='memo' rows='2' placeholder='コメント(20字以内)' maxlength='20'></textarea><br>";
          echo "<input type='hidden' name='namae' value='{$line[1]}'>";
          echo "<input type='hidden' name='fid' value='{$line[0]}'>";
          echo "<input class='btn2c' type='submit' value='送信'></form>";
          echo "<form action='./cafe.php' method='post'>";
          echo "<input name='token' type='hidden' value='{$token}'>";
          echo "<input type='hidden' name='dfid' value='{$line[0]}'>";
          echo "<input type='hidden' name='dfname' value='{$line[1]}'>";
          echo "<input type='submit' class='delf' value='この食材を削除' onclick='return com()'></form></div>";
          echo "<input type='button' id='pclose' value='閉じる' onclick='pclose()' style='display:none;'>";
          $n++;
         }
        echo "</div>";
        echo "</div>";
        echo "<label for='pclose'><span id='bg' class='bga'>";
        echo "</span></label>";
       }
       ?>
    </main>
    <!-- The code below remains for update someday -->
    <main id="Select">
    </main>
    <!-- javascript section -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="../js/functions.js" type="text/javascript"></script>
  </body>
</html>
