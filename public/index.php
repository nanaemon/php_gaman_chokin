<?php
declare(strict_types=1);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../app/db.php';
require_once __DIR__ . '/../app/functions.php';

$pdo = db();

// フラッシュ
$flash = flash_get();

// 今月合計
$sumStmt = $pdo->query("
  SELECT COALESCE(SUM(amount), 0) AS monthly_total
  FROM records
  WHERE date BETWEEN DATE_FORMAT(CURDATE(), '%Y-%m-01') AND LAST_DAY(CURDATE())
");
$monthlyTotal = (int)$sumStmt->fetch()['monthly_total'];

// 一覧
$listStmt = $pdo->query("
  SELECT id, date, item, amount, category, memo
  FROM records
  ORDER BY date DESC, id DESC
");
$rows = $listStmt->fetchAll();
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
  <div class="container">

    <!-- ヘッダー -->
    <div class="topbar">
      <div class="brand">
        <div class="logo">🐷</div>
        <div class="name">ガマン貯金</div>
      </div>
      <div class="nav">
        <a href="new.php" class="btn primary">＋ 我慢を記録</a>
      </div>
    </div>

    <!-- フラッシュメッセージ -->
    <?php if (!empty($flash)): ?>
      <div class="flash"><?= h($flash) ?></div>
    <?php endif; ?>

    <!-- 今月の合計 -->
    <div class="card">
      <div class="h1">今月の節約</div>
      <div class="sub">塵も積もれば山となる</div>

      <div class="kpi">
        <div>
          <span class="value"><?= h((string)$monthlyTotal) ?></span>
          <span class="unit">円</span>
        </div>
        <span class="badge">🐽 コツコツ貯金中</span>
      </div>
    </div>

    <!-- 一覧 -->
    <div class="card" style="margin-top:18px;">
      <div class="h1">記録一覧</div>
      <div class="sub">ちょっとの我慢は無駄じゃない。</div>

      <table class="table">
        <thead>
          <tr>
            <th>日付</th>
            <th>我慢したもの</th>
            <th>金額</th>
            <th>カテゴリ</th>
            <th>メモ</th>
            <th>操作</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($rows)): ?>
            <tr>
              <td colspan="6" class="small">
                まだ記録がないよ。最初の我慢を入れてみよう 🐷
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($rows as $r): ?>
              <tr>
                <td><?= h($r['date']) ?></td>
                <td><?= h($r['item']) ?></td>
                <td><?= h((string)$r['amount']) ?> 円</td>
                <td><?= h($r['category']) ?></td>
                <td><?= nl2br(h((string)($r['memo'] ?? ''))) ?></td>
                <td>
                  <div class="actions">
                    <a href="edit.php?id=<?= h((string)$r['id']) ?>" class="btn">編集</a>

                    <form method="post" action="delete.php" onsubmit="return confirm('この記録を削除する？');">
                      <input type="hidden" name="csrf" value="<?= h(csrf_token()) ?>">
                      <input type="hidden" name="id" value="<?= h((string)$r['id']) ?>">
                      <button type="submit" class="btn danger">削除</button>
                    </form>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- フッター的メッセージ -->
    <p class="small" >
      🐖 小さな積み重ねは未来へのプレゼント
    </p>

  </div>
</body>
</html>
