<?php
session_start();

if (!isset($_SESSION['join'])) {
  header('Location: index.php');
  exit();
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
    <div class="registration"><h1>会員登録</h1></div>
    <p>記入した内容を確認して、「登録する」ボタンをクリックしてください。</p>
    <form action="" method="post">
      <dl>
        <dt>ニックネーム</dt>
        <dd>
          <?php echo htmlspecialchars($_SESSION['join']['name'], ENT_QUOTES) ?>
        </dd>
        <dt>メールアドレス</dt>
        <dd>
          <?php echo htmlspecialchars($_SESSION['join']['email'], ENT_QUOTES) ?>
        </dd>
        <dt>パスワード</dt>
        <dd>
          【表示されません】
        </dd>
      </dl>
      <div><a href="index.php?action=rewrite">&laquo;&nbsp;書き直す</a> | <input type="submit" value="登録する"></div>
    </form>
  </body>
</html>
