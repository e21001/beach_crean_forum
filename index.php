<?php
session_start();
require('dbconnect.php');

if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
  // ログインしてる
  $_SESSION['time'] = time();

  $users = $db->prepare('SELECT * FROM users WHERE id=?');
  $users->execute(array($_SESSION['id']));
  $user = $users->fetch();
} else {
  // ログインしていない
  header('Location: login.php'); exit();
}

?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>beach_clean_volunteer</title>
    <meta name="description" content="みんなでビーチクリーンしよう">
    <!-- CSS -->
    <link rel="stylesheet" href="/css/beach_clean.css">
  </head>
  <body>
    <header>
      <div class="header-wrapper">
        <div class="header-logo"><a href="#">ビーチクリーン沖縄(仮)</a></div>
        <p>会員登録する</p>
      </div>
    </header>
    <form action="" method="post">
      <dl>
        <dt><?php echo htmlspecialchars($user['name'], ENT_QUOTES) ?>さん、メッセージをどうぞ</dt>
        <dd>
          <textarea name="message" rows="5" cols="100"></textarea>
        </dd>
        <dt>写真など</dt>
        <dd>
          <input type="file" name="image" size="35">
        </dd>
      </dl>
      <div>
        <input type="submit" value="投稿する">
      </div>
    </form>
  </body>
</html>
