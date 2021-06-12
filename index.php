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
    <link rel="stylesheet" href="beach_clean.css">
  </head>
  <body>
    <header>
      <div class="header-wrapper wrapper">
        <div class="header-logo"><a href="#">適当なロゴ</a></div>
        <div class="header-title">~ビーチクリーン募集掲示板(仮)~</div>
        <p><a href="/join/index.php">会員登録する</a></p>
      </div>
    </header>
    <main class="main-wrapper wrapper">
      <article>
        <form action="" method="post" enctype="multipart/form-data">
          <dl>
            <dt><?php echo htmlspecialchars($user['name'], ENT_QUOTES) ?>さん、何か投稿してみよう！</dt>
            <dd>
              <textarea name="message" rows="5" cols="100" placeholder="ビーチクリーンの募集、参加、報告または汚れてる海の情報などなんでもどうぞ"></textarea>
            </dd>
          </dl>
          <div>
            <p>写真など：<input type="file" name="image" size="35"></p>
            <?php if ($error['image'] == 'type'): ?>
              <p class="error">* 写真などは「.jpg」または「.git」の画像を指定してください</p>
            <?php endif ?>
            <input type="submit" value="投稿する">
          </div>
        </form>
        <?php foreach ($posts as $post): ?>
          <div class="posting">
            <img src="<?php echo './posted_picture/'. htmlspecialchars($post['picture'], ENT_QUOTES) ?>" alt="<?php echo htmlspecialchars($post['picture']) ?>">
            <div class="posting-str">
              <p><span class="poster">投稿者</span>：<?php echo htmlspecialchars($post['name']. ' ') ?><span class="poster">投稿日</span>：<?php echo htmlspecialchars($post['created'], ENT_QUOTES) ?></p>
              <?php if (mb_strlen(htmlspecialchars($post['message'], ENT_QUOTES)) > 50): ?>
              <p><a href="detail.php"><?php echo mb_substr(htmlspecialchars($post['message'], ENT_QUOTES), 0, 50) ?>...</a></p>
              <?php else: ?>
              <p><a href="detail.php"><?php echo htmlspecialchars($post['message'], ENT_QUOTES) ?></a></p>
              <?php endif ?>
              <p><a href="detail.php">返信する</a></p>
            </div>
          </div>
        <?php endforeach ?>
      </article>
      <aside>
        <p>ぱ</p>
        <p>お</p>
        <p>ん</p>
      </aside>
    </main>
  </body>
</html>
