<?php
declare(strict_types=1);

function db(): PDO {
  $host = "127.0.0.1";
  $name = "land-registry";
  $user = "root";
  $pass = "";

  $pdo = new PDO("mysql:host=$host;dbname=$name;charset=utf8mb4", $user, $pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  ]);
  return $pdo;
}

function ensure_dir(string $path): void {
  if (!is_dir($path)) mkdir($path, 0755, true);
}