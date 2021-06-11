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
$fileName = $_FILES[img]['name'];
if (!empty($fileName)) {
  $ext = substr($fileName, -3);
  if ($ext != 'jpg' && $ext != 'gif') {
    $error['image'] = 'type';
  }
}
if (empty($error)) {
  // 画像をアップロードする
  $file = $_FILES['image'];
  $image = date('YmdHis') . $file['name'];
  move_uploaded_file($file['tmp_name'], './posted_picture/' . $image);
}

// 投稿を記録する
if (!empty($_POST)) {
  if ($_POST['message'] || $_POST['image']) {
    $m_and_pic = $db->prepare('INSERT INTO posts SET message=?, picture=?, user_id=?, created=NOW()');
    $m_and_pic->execute(array(
      $_POST['message'],
      $image,
      $user['id']
    ));

    header('Location: index.php'); exit();
  }
}
// 投稿を取得する
$posts = $db->query('SELECT u.name, p.* FROM users u, posts p WHERE u.id=p.user_id ORDER BY p.created DESC');
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
    <form action="" method="post" enctype="multipart/form-data">
      <dl>
        <dt><?php echo htmlspecialchars($user['name'], ENT_QUOTES) ?>さん、メッセージをどうぞ</dt>
        <dd>
          <textarea name="message" rows="5" cols="100"></textarea>
        </dd>
        <dt>写真など</dt>
        <dd>
          <input type="file" name="image" size="35">
          <?php if ($error['image'] == 'type'): ?>
            <p class="error">* 写真などは「.jpg」または「.git」の画像を指定してください</p>
          <?php endif ?>
        </dd>
      </dl>
      <div>
        <input type="submit" value="投稿する">
      </div>
    </form>
    <?php foreach ($posts as $post): ?>
      <div class="posted-image">
        <img src="<?php echo './posted_picture/'. htmlspecialchars($post['picture'], ENT_QUOTES) ?>" width="100" height="100" alt="<?php echo htmlspecialchars($post['picture']) ?>">
        <p>投稿者：<?php echo htmlspecialchars($post['name']) ?></p>
        <p><?php echo htmlspecialchars($post['message'], ENT_QUOTES) ?></p>
        <p class="day"><?php echo htmlspecialchars($post['created'], ENT_QUOTES) ?></p>
      </div>
    <?php endforeach ?>
  </body>
</html>
