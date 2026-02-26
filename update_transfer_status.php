<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';

require_login();
header('Content-Type: application/json');

$pdo = db();

$transferId = (int)($_POST['transfer_id'] ?? 0);
$status     = trim($_POST['status'] ?? '');
$txHash     = trim($_POST['tx_hash'] ?? '');
$note       = trim($_POST['note'] ?? '');

if ($transferId <= 0 || $status === '') {
  http_response_code(400);
  echo json_encode(["ok"=>false, "error"=>"transfer_id and status required"]);
  exit;
}

$allowed = ['requested','approved','submitted','confirmed','rejected'];
if (!in_array($status, $allowed, true)) {
  http_response_code(400);
  echo json_encode(["ok"=>false, "error"=>"invalid status"]);
  exit;
}

// Get transfer row
$stmt = $pdo->prepare("SELECT * FROM land_transfers WHERE id=:id LIMIT 1");
$stmt->execute([":id" => $transferId]);
$t = $stmt->fetch();

if (!$t) {
  http_response_code(404);
  echo json_encode(["ok"=>false, "error"=>"transfer not found"]);
  exit;
}

$pdo->beginTransaction();
try {
  // Update transfer record
  $u = $pdo->prepare("
    UPDATE land_transfers
    SET status = :status,
        tx_hash = :tx_hash,
        authority_note = :note
    WHERE id = :id
  ");
  $u->execute([
    ":status" => $status,
    ":tx_hash" => ($txHash !== '' ? $txHash : $t['tx_hash']),
    ":note" => ($note !== '' ? $note : $t['authority_note']),
    ":id" => $transferId,
  ]);

  // ✅ CRITICAL: If submitted/confirmed, update lands.current_owner_pkh
  if ($status === 'submitted' || $status === 'confirmed') {
    $u2 = $pdo->prepare("
      UPDATE lands
      SET current_owner_pkh = :to_pkh
      WHERE parcel_id = :parcel_id
      LIMIT 1
    ");
    $u2->execute([
      ":to_pkh" => strtolower($t['to_pkh']),
      ":parcel_id" => $t['parcel_id'],
    ]);
  }

  $pdo->commit();
  echo json_encode(["ok"=>true]);
} catch (Throwable $e) {
  $pdo->rollBack();
  http_response_code(500);
  echo json_encode(["ok"=>false, "error"=>$e->getMessage()]);
}