<?php
declare(strict_types=1);

function h(string $s): string {
  return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

function csrf_token(): string {
  if (session_status() !== PHP_SESSION_ACTIVE) session_start();
  if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
  }
  return $_SESSION['csrf'];
}

function csrf_verify(?string $token): void {
  if (session_status() !== PHP_SESSION_ACTIVE) session_start();
  if (!$token || empty($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $token)) {
    http_response_code(403);
    echo 'CSRFトークンが不正です';
    exit;
  }
}

function flash_set(string $msg): void {
  if (session_status() !== PHP_SESSION_ACTIVE) session_start();
  $_SESSION['flash'] = $msg;
}

function flash_get(): ?string {
  if (session_status() !== PHP_SESSION_ACTIVE) session_start();
  $m = $_SESSION['flash'] ?? null;
  unset($_SESSION['flash']);
  return $m;
}

function normalize_input(array $src): array {
  return [
    'date' => (string)($src['date'] ?? ''),
    'item' => trim((string)($src['item'] ?? '')),
    'amount' => (string)($src['amount'] ?? ''),
    'category' => (string)($src['category'] ?? ''),
    'memo' => trim((string)($src['memo'] ?? '')),
  ];
}

function validate_record(array $in, array $categories): array {
  $errors = [];

  if ($in['date'] === '') $errors[] = '日付を入力してね';
  if ($in['date'] !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $in['date'])) $errors[] = '日付形式が不正だよ';

  if ($in['item'] === '') $errors[] = '品目を入力してね';
  if (mb_strlen($in['item']) > 100) $errors[] = '品目は100文字以内でね';

  if ($in['amount'] === '' || !ctype_digit($in['amount']) || (int)$in['amount'] <= 0) {
    $errors[] = '金額は1以上の整数で入力してね';
  }

  if (!in_array($in['category'], $categories, true)) $errors[] = 'カテゴリが不正だよ';
  if (mb_strlen($in['memo']) > 200) $errors[] = 'メモは200文字以内でね';

  return $errors;
}
