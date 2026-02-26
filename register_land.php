<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';
header('Content-Type: application/json');

$pdo = db();

$parcelId = trim($_POST['parcel_id'] ?? '');
$location = trim($_POST['location_text'] ?? '');
$sizeSqm  = trim($_POST['size_sqm'] ?? '');
$metadataHash = trim($_POST['metadata_hash'] ?? '');
$policyId = trim($_POST['policy_id'] ?? '');
$tokenName = trim($_POST['token_name'] ?? '');
$unit = trim($_POST['unit'] ?? '');
$authorityAddress = trim($_POST['authority_address'] ?? '');
$initialOwnerPkh = strtolower(trim($_POST['initial_owner_pkh'] ?? ''));

// ✅ NEW: registration tx hash (from authority.php -> fd.append("tx_hash", r.txHash))
$txHash = trim($_POST['tx_hash'] ?? '');

// ✅ current owner: for initial register, same as initial owner
$currentOwnerPkh = strtolower(trim($_POST['current_owner_pkh'] ?? ''));
if ($currentOwnerPkh === '') $currentOwnerPkh = $initialOwnerPkh;

// Required fields
if ($parcelId==='' || $location==='' || $policyId==='' || $tokenName==='' || $unit==='' || $authorityAddress==='' || $initialOwnerPkh==='') {
  http_response_code(400);
  echo json_encode(["ok" => false, "error" => "Missing required fields"]);
  exit;
}

// ✅ Validate PKHs (56 hex chars)
if (!preg_match('/^[0-9a-f]{56}$/', $initialOwnerPkh)) {
  http_response_code(400);
  echo json_encode(["ok" => false, "error" => "initial_owner_pkh must be 56 hex chars"]);
  exit;
}
if (!preg_match('/^[0-9a-f]{56}$/', $currentOwnerPkh)) {
  http_response_code(400);
  echo json_encode(["ok" => false, "error" => "current_owner_pkh must be 56 hex chars"]);
  exit;
}

// ✅ tx hash validation (cardano tx hash is 64 hex)
$txHashNorm = null;
if ($txHash !== '') {
  $txHash = strtolower($txHash);
  if (!preg_match('/^[0-9a-f]{64}$/', $txHash)) {
    http_response_code(400);
    echo json_encode(["ok" => false, "error" => "tx_hash must be 64 hex chars"]);
    exit;
  }
  $txHashNorm = $txHash;
}

// Size
$sizeVal = null;
if ($sizeSqm !== '') $sizeVal = (float)$sizeSqm;

// ✅ Insert/Update includes tx_hash
$stmt = $pdo->prepare("
  INSERT INTO lands
    (parcel_id, location_text, size_sqm, metadata_hash, policy_id, token_name, unit, authority_address, initial_owner_pkh, current_owner_pkh, tx_hash)
  VALUES
    (:parcel_id, :location_text, :size_sqm, :metadata_hash, :policy_id, :token_name, :unit, :authority_address, :initial_owner_pkh, :current_owner_pkh, :tx_hash)
  ON DUPLICATE KEY UPDATE
    location_text=VALUES(location_text),
    size_sqm=VALUES(size_sqm),
    metadata_hash=VALUES(metadata_hash),
    policy_id=VALUES(policy_id),
    token_name=VALUES(token_name),
    unit=VALUES(unit),
    authority_address=VALUES(authority_address),
    initial_owner_pkh=VALUES(initial_owner_pkh),
    current_owner_pkh=VALUES(current_owner_pkh),
    -- ✅ keep latest tx hash if provided, otherwise keep existing
    tx_hash = COALESCE(VALUES(tx_hash), tx_hash)
");

$stmt->execute([
  ":parcel_id" => $parcelId,
  ":location_text" => $location,
  ":size_sqm" => $sizeVal,
  ":metadata_hash" => ($metadataHash==='' ? null : $metadataHash),
  ":policy_id" => $policyId,
  ":token_name" => $tokenName,
  ":unit" => $unit,
  ":authority_address" => $authorityAddress,
  ":initial_owner_pkh" => $initialOwnerPkh,
  ":current_owner_pkh" => $currentOwnerPkh,
  ":tx_hash" => $txHashNorm,
]);

// handle document upload(s)
ensure_dir(__DIR__ . "/uploads");

$docsSaved = [];

if (!empty($_FILES['docs']) && is_array($_FILES['docs']['name'])) {
  for ($i=0; $i<count($_FILES['docs']['name']); $i++) {
    if ($_FILES['docs']['error'][$i] !== UPLOAD_ERR_OK) continue;

    $tmp = $_FILES['docs']['tmp_name'][$i];
    $orig = basename($_FILES['docs']['name'][$i]);
    $safeName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $orig);

    $bytes = file_get_contents($tmp);
    $hash = hash('sha256', $bytes);

    $dest = __DIR__ . "/uploads/" . $parcelId . "_" . time() . "_" . $safeName;
    move_uploaded_file($tmp, $dest);

    $docType = trim($_POST['doc_type'] ?? 'deed');

    $stmt2 = $pdo->prepare("
      INSERT INTO land_documents (parcel_id, doc_type, file_path, file_hash)
      VALUES (:parcel_id, :doc_type, :file_path, :file_hash)
    ");
    $stmt2->execute([
      ":parcel_id" => $parcelId,
      ":doc_type" => $docType,
      ":file_path" => "uploads/" . basename($dest),
      ":file_hash" => $hash,
    ]);

    $docsSaved[] = ["file" => "uploads/" . basename($dest), "hash" => $hash, "type" => $docType];
  }
}

echo json_encode([
  "ok" => true,
  "parcel_id" => $parcelId,
  "initial_owner_pkh" => $initialOwnerPkh,
  "current_owner_pkh" => $currentOwnerPkh,
  "tx_hash" => $txHashNorm,
  "docs" => $docsSaved
]);