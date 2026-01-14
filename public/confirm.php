<?php
declare(strict_types=1);
require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/functions.php';

csrf_verify($_POST['csrf'] ?? null);

$in = normalize_input($_POST);
$errors = validate_record($in, CATEGORIES);
?>
<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/svg+xml" href="../favicon.svg?v=2">
  <link rel="stylesheet" href="../css/reset.css">
  <link rel="stylesheet" href="../css/style.css">
  <title>ガマン貯金</title>
</head>
<body>
<h1>登録内容確認</h1>
<p><a href="index.php">一覧へ</a></p>

<?php if ($errors): ?>
  <h2>入力エラー</h2>
  <ul>
    <?php foreach ($errors as $e): ?><li><?=h($e)?></li><?php endforeach; ?>
  </ul>
  <form method="post" action="new.php">
    <?php foreach ($in as $k => $v): ?>
      <input type="hidden" name="<?=h($k)?>" value="<?=h($v)?>">
    <?php endforeach; ?>
    <button type="submit">入力に戻る</button>
  </form>
<?php else: ?>
  <dl>
    <dt>日付</dt><dd><?=h($in['date'])?></dd>
    <dt>品目</dt><dd><?=h($in['item'])?></dd>
    <dt>金額</dt><dd><?=h($in['amount'])?> 円</dd>
    <dt>カテゴリ</dt><dd><?=h($in['category'])?></dd>
    <dt>メモ</dt><dd><?=nl2br(h($in['memo']))?></dd>
  </dl>

  <form method="post" action="new.php" style="display:inline-block;margin-right:8px">
    <?php foreach ($in as $k => $v): ?>
      <input type="hidden" name="<?=h($k)?>" value="<?=h($v)?>">
    <?php endforeach; ?>
    <button type="submit">戻る</button>
  </form>

  <form method="post" action="store.php" style="display:inline-block">
    <input type="hidden" name="csrf" value="<?=h(csrf_token())?>">
    <?php foreach ($in as $k => $v): ?>
      <input type="hidden" name="<?=h($k)?>" value="<?=h($v)?>">
    <?php endforeach; ?>
    <button type="submit">この内容で保存</button>
  </form>
<?php endif; ?>
</body>
</html>
