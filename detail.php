<?php
session_start();
require('dbconnect.php');

// 返信の場合
if (isset($_REQUEST['res'])) {
  $response = $db->prepare('SELECT u.name, p* FROM users u, posts p WHERE u.id=p.user_id AND p.id=? ORDER BY p.created DESC');
  $response->excute(array($_REQUEST['res']));

  $table = $response->fetch();
  $message = $table['name']. ' '.$table['message'];
}
// 投稿を記録する
if (!empty($_POST)) {
  if ($_POST['message']) {
    $reply = $db->prepare('INSERT INTO posts SET message=?, user_id=?, reply_post_id=?, created=NOW()');
    $reply->execute(array(
      $_POST['message'],
      $user['id'],
      $_POST['reply_post_id']
    ));

    header('Location: detail.php'); exit();
  }
}

$posts = $db->query('SELECT u.name, p.* FROM users u, posts p WHERE u.id=p.user_id ORDER BY p.created DESC');
$post = $posts->fetch();
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
    </header><main class="main-wrapper wrapper">
      <article>
        <div class="posting">
          <img src="<?php echo './posted_picture/'. htmlspecialchars($post['picture'], ENT_QUOTES) ?>" alt="<?php echo htmlspecialchars($post['picture']) ?>">
          <div class="posting-str">
            <p><span class="poster">投稿者</span>：<?php echo htmlspecialchars($post['name']. ' ') ?><span class="poster">投稿日</span>：<?php echo htmlspecialchars($post['created'], ENT_QUOTES) ?></p>
            <p><?php echo htmlspecialchars($post['message'], ENT_QUOTES) ?></p>
          </div>
        </div>
        <p class="back"><a href="index.php">戻る</a></p>
        <div class="reply">
          <form class="reply-form" action="" method="post">
            <textarea  name="message" rows="5" cols="40" placeholder="なんでもどうぞ"></textarea>
            <div>
              <input type="submit" value="投稿する">
            </div>
          </form>
          <div class="reply-display">
            この投稿への返信>>>
            <div>
              <p>返信者：　返信日</p>
              <p>ここに返信</p>
            </div>
          </div>
        </div>
      </article>
      <aside>
        <p>ぱ</p>
        <p>お</p>
        <p>ん</p>
      </aside>
    </main>
  </body>
</html>
