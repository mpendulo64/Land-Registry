<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';

require_login();

header('Content-Type: application/json');

$pdo = db();

$ownerPkh = strtolower(trim($_GET['owner_pkh'] ?? ''));
if ($ownerPkh === '' || !preg_match('/^[0-9a-f]{56}$/', $ownerPkh)) {
  http_response_code(400);
  echo json_encode(["ok" => false, "error" => "owner_pkh (56 hex) required"]);
  exit;
}

/**
 * We want assets where:
 * - current_owner_pkh == current wallet pkh
 * For an MVP, that's enough.
 * Later you can update ownership on transfer approvals.
 */
$stmt = $pdo->prepare("
  SELECT
    l.parcel_id,
    l.location_text,
    l.size_sqm,
    l.created_at,
    l.policy_id,
    l.token_name,
    l.unit
  FROM lands l
  WHERE LOWER(COALESCE(l.current_owner_pkh, l.initial_owner_pkh)) = :pkh
  ORDER BY l.created_at DESC
");
$stmt->execute([":pkh" => $ownerPkh]);
$lands = $stmt->fetchAll();

if (!$lands) {
  echo json_encode(["ok" => true, "items" => []]);
  exit;
}

$parcelIds = array_map(fn($r) => $r["parcel_id"], $lands);
$in = implode(",", array_fill(0, count($parcelIds), "?"));

$docsStmt = $pdo->prepare("
  SELECT parcel_id, doc_type, file_path, file_hash, uploaded_at
  FROM land_documents
  WHERE parcel_id IN ($in)
  ORDER BY uploaded_at DESC
");
$docsStmt->execute($parcelIds);
$docs = $docsStmt->fetchAll();

$docsByParcel = [];
foreach ($docs as $d) {
  $pid = $d["parcel_id"];
  if (!isset($docsByParcel[$pid])) $docsByParcel[$pid] = [];
  $docsByParcel[$pid][] = $d;
}

$out = [];
foreach ($lands as $l) {
  $pid = $l["parcel_id"];
  $out[] = [
    "id" => $pid,
    "title" => "Land Parcel",
    "location" => $l["location_text"],
    "size" => ($l["size_sqm"] === null ? "—" : ($l["size_sqm"] . " sqm")),
    "registered" => substr((string)$l["created_at"], 0, 10),
    "status" => "Verified", // optional: later verify on-chain
    "unit" => $l["unit"],
    "policy_id" => $l["policy_id"],
    "token_name" => $l["token_name"],
    "documents" => $docsByParcel[$pid] ?? [],
  ];
}

echo json_encode(["ok" => true, "items" => $out]);