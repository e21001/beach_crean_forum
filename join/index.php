<?php
require('../dbconnect.php');

session_start();

if (!empty($_POST)) {
  // エラー項目の確認
  if ($_POST['name'] == '') {
    $error['name'] = 'blank';
  }
  if ($_POST['email'] == '') {
    $error['email'] = 'blank';
  }
  if (strlen($_POST['password']) < 4) {
    $error['password'] = 'length';
  }
  if ($_POST['password'] == '') {
    $error['password'] = 'blank';
  }
  // 重複アカウントのチェック
  if (empty($error)) {
    $user = $db->prepare('SELECT COUNT(*) AS cnt FROM users WHERE email=?');
    $user->execute(array($_POST['email']));
    $record = $user->fetch();
    if ($record['cnt'] > 0) {
      $error['email'] = 'duplicate';
    }
  }

  if(empty($error)) {
    $_SESSION['join'] = $_POST;
    header('Location: check.php');
    exit();
  }
}



// 書き直し
if ($_REQUEST['action'] == 'rewrite') {
  $_POST = $_SESSION['join'];
  $error['rewrite'] = true;
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
      <p>次のフォームに必要事項をご記入ください。</p>
      <form action="" method="post">
        <dl>
          <dt>ニックネーム<span>必須</span></dt>
          <dd>
            <input type="text" name="name" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['name'], ENT_QUOTES) ?>">
            <?php if ($error['name'] == 'blank'): ?>
              <p class="error">* ニックネームを入力してください</p>
            <?php endif ?>
          </dd>
          <dt>メールアドレス<span>必須</span></dt>
          <dd>
            <input type="text" name="email" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['email'], ENT_QUOTES) ?>">
            <?php if ($error['email'] == 'blank'): ?>
              <p class="error">* メールアドレスを入力してください</p>
            <?php endif ?>
            <?php if ($error['email'] == 'duplicate'): ?>
              <p class="error">* このメールアドレスはすでに登録されています</p>
            <?php endif ?>
          </dd>
          <dt>パスワード<span>必須</span></dt>
          <dd>
            <input type="password" name="password" size="10" maxlength="20" value="<?php echo htmlspecialchars($_POST['password'], ENT_QUOTES) ?>">
            <?php if ($error['password'] == 'blank'): ?>
              <p class="error">* パスワードを入力してください</p>
            <?php endif ?>
            <?php if ($error['password'] == 'length'): ?>
              <p class="error">* パスワードは4文字以上で入力してください</p>
            <?php endif ?>
          </dd>
        </dl>
        <div><input type="submit" value="入力内容を確認する"></div>
      </form>
  </body>
</html>
