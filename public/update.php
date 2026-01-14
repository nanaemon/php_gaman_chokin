<?php
declare(strict_types=1);
require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/db.php';
require_once __DIR__ . '/../app/functions.php';

csrf_verify($_POST['csrf'] ?? null);

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) { http_response_code(400); echo 'IDが不正'; exit; }

$in = normalize_input($_POST);
$errors = validate_record($in, CATEGORIES);
if ($errors) {
  http_response_code(400);
  echo '入力エラー: ' . implode(' / ', $errors);
  exit;
}

$stmt = db()->prepare('
  UPDATE records
  SET date = :date, item = :item, amount = :amount, category = :category, memo = :memo
  WHERE id = :id
');
$stmt->execute([
  ':date' => $in['date'],
  ':item' => $in['item'],
  ':amount' => (int)$in['amount'],
  ':category' => $in['category'],
  ':memo' => $in['memo'],
  ':id' => $id,
]);

flash_set('更新したよ！');
header('Location: index.php');
exit;
