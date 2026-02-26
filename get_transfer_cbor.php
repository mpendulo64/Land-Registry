<?php
declare(strict_types=1);
require_once __DIR__ . '/db.php';
header('Content-Type: application/json');

$transferId = (int)($_GET['transfer_id'] ?? 0);
if ($transferId <= 0) {
  http_response_code(400);
  echo json_encode(["error" => "transfer_id required"]);
  exit;
}

$path = __DIR__ . "/tx/transfer_$transferId.cbor";
if (!file_exists($path)) {
  http_response_code(404);
  echo json_encode(["error" => "CBOR not found"]);
  exit;
}

$cbor = file_get_contents($path);
echo json_encode(["ok" => true, "transfer_id" => $transferId, "partial_tx_cbor" => $cbor]);