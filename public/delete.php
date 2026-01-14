<?php
declare(strict_types=1);
require_once __DIR__ . '/../app/db.php';
require_once __DIR__ . '/../app/functions.php';

csrf_verify($_POST['csrf'] ?? null);

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) { http_response_code(400); echo 'IDが不正'; exit; }

$stmt = db()->prepare('DELETE FROM records WHERE id = :id');
$stmt->execute([':id' => $id]);

flash_set('削除したよ！');
header('Location: index.php');
exit;
