<?php
declare(strict_types=1);
require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/db.php';
require_once __DIR__ . '/../app/functions.php';

csrf_verify($_POST['csrf'] ?? null);

$in = normalize_input($_POST);
$errors = validate_record($in, CATEGORIES);
if ($errors) {
  http_response_code(400);
  echo '不正な入力です';
  exit;
}

$stmt = db()->prepare('
  INSERT INTO records (date, item, amount, category, memo)
  VALUES (:date, :item, :amount, :category, :memo)
');
$stmt->execute([
  ':date' => $in['date'],
  ':item' => $in['item'],
  ':amount' => (int)$in['amount'],
  ':category' => $in['category'],
  ':memo' => $in['memo'],
]);

flash_set('保存したよ！');
header('Location: index.php');
exit;
