<?php
declare(strict_types=1);
require_once __DIR__ . '/db.php';
header('Content-Type: application/json');

$pdo = db();
ensure_dir(__DIR__ . "/tx");

$parcelId = trim($_POST['parcel_id'] ?? '');
$fromPkh  = strtolower(trim($_POST['from_pkh'] ?? ''));
$toPkh    = strtolower(trim($_POST['to_pkh'] ?? ''));
$partialCbor = trim($_POST['partial_tx_cbor'] ?? '');

if ($parcelId==='' || $fromPkh==='' || $toPkh==='' || $partialCbor==='') {
  http_response_code(400);
  echo json_encode(["ok"=>false, "error" => "Missing required fields"]);
  exit;
}

if (!preg_match('/^[0-9a-f]{56}$/', $fromPkh) || !preg_match('/^[0-9a-f]{56}$/', $toPkh)) {
  http_response_code(400);
  echo json_encode(["ok"=>false, "error" => "from_pkh/to_pkh must be 56-hex"]);
  exit;
}

// Insert transfer row
$stmt = $pdo->prepare("
  INSERT INTO land_transfers (parcel_id, from_pkh, to_pkh, status)
  VALUES (:parcel_id, :from_pkh, :to_pkh, 'requested')
");
$stmt->execute([
  ":parcel_id" => $parcelId,
  ":from_pkh"  => $fromPkh,
  ":to_pkh"    => $toPkh,
]);

$id = (int)$pdo->lastInsertId();

// Store CBOR file
file_put_contents(__DIR__ . "/tx/transfer_$id.cbor", $partialCbor);

echo json_encode(["ok" => true, "transfer_id" => $id]);