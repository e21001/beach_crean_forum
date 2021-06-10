<?php
require('dbconnect.php');

session_start();
if ($_COOKIE['email'] != '') {
  $_POST['email'] = $_COOKIE['email'];
  $_POST['password'] = $_COOKIE['password'];
  $_POST['save'] = 'on';
}

if (!empty($_POST)) {
  // ログインの処理
  if ($_POST['email'] != '' && $_POST['password'] != '') {
    $login = $db->prepare('SELECT * FROM users WHERE email=? AND password=?');
    $login->execute(array(
      $_POST['email'],
      sha1($_POST['password'])
    ));
    $user = $login->fetch();

    if ($user) {
      // ログイン成功
      $_SESSION['id'] = $user['id'];
      $_SESSION['time'] = time();

      // ログイン情報を記録する
      if ($_POST['save'] == 'on') {
        setcookie('email', $_POST['email'], time()+60*60*24*14);
        setcookie('password', $_POST['password'], time()+60*60*24*14);
      }

      header('Location: index.php');
    } else {
      $error['login'] = 'failed';
    }
  } else {
    $error['login'] = 'blank';
  }
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
      <div id="">
        <p>メールアドレスとパスワードを記入してログインしてください。</p>
        <p>入会手続きがまだの方はこちらからどうぞ。</p>
        <p>&raquo;<a href="join/">入会手続きをする</a></p>
      </div>
      <form action="" method="post">
        <dl>
          <dt>メールアドレス</dt>
          <dd>
            <input type="text" name="email" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['email'], ENT_QUOTES) ?>">
            <?php if ($error['login'] == 'blank'): ?>
              <p>* メールアドレスとパスワードをご記入ください</p>
            <?php endif ?>
            <?php if ($error['login'] == 'failed'): ?>
              <p>* ログインに失敗しました。正しくご記入ください。</p>
            <?php endif ?>
          </dd>
          <dt>パスワード</dt>
          <dd>
            <input type="password" name="password" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['password'], ENT_QUOTES) ?>">
          </dd>
          <dt>ログイン情報の記録</dt>
          <dd>
            <input id="save" type="checkbox" name="save" value="on"><label for="save">次回からは自動的にログインする</label>
          </dd>
        </dl>
        <div><input type="submit" value="ログインする"></div>
      </form>
    </header>
  </body>
</html>
