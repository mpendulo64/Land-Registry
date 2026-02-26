<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';

require_login();
header('Content-Type: application/json');

$pdo = db();

$pkh = strtolower(trim($_GET['pkh'] ?? ''));

// Basic validation: allow 56 (typical pkh) up to 64 hex chars
if ($pkh === '' || !preg_match('/^[0-9a-f]{56,64}$/', $pkh)) {
  http_response_code(400);
  echo json_encode(["ok" => false, "error" => "Invalid or missing pkh"]);
  exit;
}

// Build a unified history feed from DB (registrations + transfers)
$rows = [];

// 1) Registrations (lands)
$stmt = $pdo->prepare("
  SELECT parcel_id, created_at, tx_hash
  FROM lands
  WHERE initial_owner_pkh = :pkh OR current_owner_pkh = :pkh
  ORDER BY created_at DESC
  LIMIT 200
");
$stmt->execute([":pkh" => $pkh]);
$lands = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

foreach ($lands as $r) {
  $rows[] = [
    "id"     => "#" . ($r["parcel_id"] ?? "-"),
    "action" => "New Registration",
    "status" => "Verified",
    "date"   => date("M j, Y", strtotime((string)$r["created_at"])),
    "block"  => "-",
    "fee"    => "-",
    "tx"     => ($r["tx_hash"] ?? "-") ?: "-",
    "ts"     => (string)$r["created_at"],
  ];
}

// 2) Transfers (land_transfers)
$stmt = $pdo->prepare("
  SELECT parcel_id, status, tx_hash, created_at, from_pkh, to_pkh
  FROM land_transfers
  WHERE from_pkh = :pkh OR to_pkh = :pkh
  ORDER BY created_at DESC
  LIMIT 200
");
$stmt->execute([":pkh" => $pkh]);
$transfers = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

foreach ($transfers as $r) {
  $st = strtolower((string)($r["status"] ?? "requested"));
  $statusPretty = "Pending";
  if (in_array($st, ["submitted","confirmed"], true)) $statusPretty = "Completed";
  else if ($st === "approved") $statusPretty = "Verified";
  else if ($st === "rejected") $statusPretty = "Pending";

  $rows[] = [
    "id"     => "#" . ($r["parcel_id"] ?? "-"),
    "action" => "Ownership Transfer",
    "status" => $statusPretty,
    "date"   => date("M j, Y", strtotime((string)$r["created_at"])),
    "block"  => "-",
    "fee"    => "-",
    "tx"     => ($r["tx_hash"] ?? "-") ?: "-",
    "ts"     => (string)$r["created_at"],
  ];
}

// Sort merged rows newest first
usort($rows, function($a, $b){
  return strcmp((string)$b["ts"], (string)$a["ts"]);
});

// Remove helper field
$rows = array_map(function($r){
  unset($r["ts"]);
  return $r;
}, $rows);

echo json_encode(["ok" => true, "rows" => $rows]);