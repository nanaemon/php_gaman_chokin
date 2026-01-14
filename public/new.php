<?php
declare(strict_types=1);
require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/functions.php';

$in = normalize_input($_POST + [
  'date' => date('Y-m-d'),
  'category' => 'コンビニ',
]);
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
<body>
  <div class="container">

    <div class="topbar">
      <div class="brand">
        <div class="logo">🐷</div>
        <div class="name">ガマン貯金</div>
      </div>
      <div class="nav">
        <a href="index.php" class="btn link">一覧へ戻る</a>
      </div>
    </div>

    <div class="card">
      <div class="h1">我慢を記録</div>
      <div class="sub">買わなかった自分、ちゃんと偉い。</div>

      <form method="post" action="confirm.php" style="margin-top:14px;">
        <input type="hidden" name="csrf" value="<?= h(csrf_token()) ?>">

        <div class="field">
          <label>日付</label>
          <input type="date" name="date" value="<?= h($in['date']) ?>" required>
        </div>

        <div class="field">
          <label>我慢したもの</label>
          <input type="text" name="item" value="<?= h($in['item']) ?>" maxlength="100" required placeholder="例：コンビニお菓子">
        </div>

        <div class="field">
          <label>金額（円）</label>
          <input type="number" name="amount" value="<?= h($in['amount']) ?>" min="1" step="1" required placeholder="例：200">
        </div>

        <div class="field">
          <label>カテゴリ</label>
          <select name="category" required>
            <?php foreach (CATEGORIES as $c): ?>
              <option value="<?= h($c) ?>" <?= $c === $in['category'] ? 'selected' : '' ?>><?= h($c) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="field">
          <label>メモ（任意）</label>
          <textarea name="memo" rows="3" maxlength="200" placeholder="例：今日は寄り道しなかった"><?= h($in['memo']) ?></textarea>
        </div>

        <div class="actions">
          <button type="submit" class="btn primary">確認へ</button>
          <a href="index.php" class="btn link">キャンセル</a>
        </div>

        <p class="small" style="margin-top:10px;">
          🐽 「完璧」じゃなくてOK。1件入れられたら十分。
        </p>
      </form>
    </div>

  </div>
</body>

</html>
