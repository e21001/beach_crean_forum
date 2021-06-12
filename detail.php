<?php
session_start();
require('dbconnect.php');

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
