<?php
declare(strict_types=1);
require_once __DIR__ . '/db.php';
header('Content-Type: application/json');

$pdo = db();
$parcelId = trim($_GET['parcel_id'] ?? '');
if ($parcelId === '') {
  http_response_code(400);
  echo json_encode(["error" => "parcel_id required"]);
  exit;
}

$land = $pdo->prepare("SELECT * FROM lands WHERE parcel_id=:p LIMIT 1");
$land->execute([":p" => $parcelId]);

$transfers = $pdo->prepare("SELECT * FROM land_transfers WHERE parcel_id=:p ORDER BY id DESC");
$transfers->execute([":p" => $parcelId]);

$docs = $pdo->prepare("SELECT * FROM land_documents WHERE parcel_id=:p ORDER BY id DESC");
$docs->execute([":p" => $parcelId]);

echo json_encode([
  "ok" => true,
  "land" => $land->fetch() ?: null,
  "transfers" => $transfers->fetchAll(),
  "documents" => $docs->fetchAll(),
]);