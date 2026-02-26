<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';

require_login();

$parcel = trim($_GET['parcel_id'] ?? '');
if ($parcel === '') { header("Location: assets.php"); exit; }

$pdo = db();
$stmt = $pdo->prepare("SELECT * FROM lands WHERE parcel_id=:p LIMIT 1");
$stmt->execute([":p" => $parcel]);
$land = $stmt->fetch();

$docsStmt = $pdo->prepare("SELECT * FROM land_documents WHERE parcel_id=:p ORDER BY uploaded_at DESC");
$docsStmt->execute([":p" => $parcel]);
$docs = $docsStmt->fetchAll();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Land Asset – <?= htmlspecialchars($parcel) ?></title>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;700;800&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">

<style>
:root {
  --blue:#135bec;
  --blue-dark:#0d47a1;
  --blue-light:#eff4ff;
  --border:#e2e8f0;
  --bg:#f8fafc;
  --text:#0f172a;
  --muted:#64748b;
  --white:#ffffff;
  --green:#16a34a;
}

*{box-sizing:border-box;margin:0;padding:0}

body{
  font-family:'DM Sans',sans-serif;
  background:var(--bg);
  color:var(--text);
  padding:40px 16px;
}

.container{
  max-width:980px;
  margin:auto;
}

.topbar{
  display:flex;
  justify-content:space-between;
  align-items:center;
  margin-bottom:28px;
}

.back-link{
  color:var(--blue);
  font-weight:600;
  text-decoration:none;
  font-size:14px;
}

.badge{
  background:var(--blue);
  color:#fff;
  padding:4px 10px;
  border-radius:6px;
  font-size:11px;
  font-weight:700;
  letter-spacing:.05em;
}

.card{
  background:var(--white);
  border:1px solid var(--border);
  border-radius:16px;
  padding:24px;
  margin-bottom:24px;
  box-shadow:0 6px 20px rgba(19,91,236,.08);
}

.asset-header{
  display:flex;
  justify-content:space-between;
  align-items:center;
  margin-bottom:18px;
}

.asset-title{
  font-family:'Plus Jakarta Sans',sans-serif;
  font-weight:800;
  font-size:20px;
}

.asset-meta{
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
  gap:18px;
  margin-top:16px;
}

.meta-item{
  background:var(--blue-light);
  padding:14px;
  border-radius:12px;
}

.meta-label{
  font-size:12px;
  color:var(--muted);
  margin-bottom:4px;
}

.meta-value{
  font-weight:700;
  font-size:14px;
  word-break:break-word;
}

.documents-list{
  margin-top:18px;
}

.doc-item{
  display:flex;
  justify-content:space-between;
  align-items:center;
  padding:12px 14px;
  border:1px solid var(--border);
  border-radius:10px;
  margin-bottom:10px;
  transition:.2s;
}

.doc-item:hover{
  border-color:var(--blue);
  background:var(--blue-light);
}

.doc-type{
  font-weight:600;
  font-size:14px;
}

.doc-hash{
  font-size:11px;
  color:var(--muted);
}

.open-btn{
  background:var(--blue);
  color:#fff;
  border:none;
  border-radius:8px;
  padding:6px 12px;
  font-size:12px;
  font-weight:600;
  cursor:pointer;
  text-decoration:none;
}

.open-btn:hover{
  background:var(--blue-dark);
}

.empty{
  color:var(--muted);
  font-size:14px;
  margin-top:12px;
}

.status-verified{
  color:var(--green);
  font-weight:700;
  font-size:13px;
}
</style>
</head>

<body>
<div class="container">

  <div class="topbar">
    <a href="assets.php" class="back-link">← Back to My Assets</a>
    <span class="badge">NFT</span>
  </div>

  <div class="card">
    <div class="asset-header">
      <div class="asset-title">
        <?= htmlspecialchars($parcel) ?>
      </div>
      <div class="status-verified">✔ Verified</div>
    </div>

    <?php if ($land): ?>
      <div class="asset-meta">
        <div class="meta-item">
          <div class="meta-label">Location</div>
          <div class="meta-value"><?= htmlspecialchars($land["location_text"] ?? "—") ?></div>
        </div>

        <div class="meta-item">
          <div class="meta-label">Size</div>
          <div class="meta-value">
            <?= htmlspecialchars((string)($land["size_sqm"] ?? "—")) ?> sqm
          </div>
        </div>

        <div class="meta-item">
          <div class="meta-label">Policy ID</div>
          <div class="meta-value"><?= htmlspecialchars($land["policy_id"] ?? "") ?></div>
        </div>

        <div class="meta-item">
          <div class="meta-label">Asset Unit</div>
          <div class="meta-value"><?= htmlspecialchars($land["unit"] ?? "") ?></div>
        </div>
      </div>
    <?php else: ?>
      <div class="empty">Land not found in database.</div>
    <?php endif; ?>
  </div>

  <div class="card">
    <h3 style="font-family:'Plus Jakarta Sans';margin-bottom:12px;">Property Documents</h3>

    <?php if (!$docs): ?>
      <div class="empty">No documents uploaded for this land asset.</div>
    <?php else: ?>
      <div class="documents-list">
        <?php foreach ($docs as $d): ?>
          <div class="doc-item">
            <div>
              <div class="doc-type"><?= htmlspecialchars($d["doc_type"]) ?></div>
              <div class="doc-hash">
                Hash: <?= htmlspecialchars(substr((string)$d["file_hash"],0,18)) ?>…
              </div>
            </div>
            <a class="open-btn" href="<?= htmlspecialchars($d["file_path"]) ?>" target="_blank">
              Open
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

</div>
</body>
</html>