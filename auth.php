<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

function is_logged_in(): bool {
  return isset($_SESSION['user_id']) && is_int($_SESSION['user_id']);
}

function require_login(): void {
  if (!is_logged_in()) {
    header("Location: login.php");
    exit;
  }
}

function current_user_email(): ?string {
  return $_SESSION['user_email'] ?? null;
}