<?php
declare(strict_types=1);
require_once __DIR__ . '/db.php';
header('Content-Type: application/json');

$pdo = db();

$stmt = $pdo->query("
  SELECT id, parcel_id, from_pkh, to_pkh, status, created_at
  FROM land_transfers
  WHERE status IN ('requested','approved')
  ORDER BY id DESC
  LIMIT 50
");
echo json_encode(["ok" => true, "items" => $stmt->fetchAll()]);