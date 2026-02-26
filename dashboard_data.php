<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';

require_login();
header('Content-Type: application/json');

$pdo = db();

$statsSql = "
  SELECT
    (SELECT COUNT(*) FROM lands) AS total_assets,
    (SELECT COUNT(*) FROM land_transfers) AS total_transfers,
    (SELECT COUNT(*) FROM lands) AS total_registered,

    (SELECT COUNT(*) FROM lands
      WHERE created_at >= DATE_FORMAT(NOW(), '%Y-%m-01')
    ) AS assets_this_month,

    (SELECT COUNT(*) FROM land_transfers
      WHERE created_at >= DATE_FORMAT(NOW(), '%Y-%m-01')
    ) AS transfers_this_month,

    (SELECT COUNT(*) FROM lands
      WHERE created_at >= DATE_FORMAT(NOW(), '%Y-%m-01')
    ) AS registered_this_month
";
$stats = $pdo->query($statsSql)->fetch(PDO::FETCH_ASSOC) ?: [];

function fmtInt($v): int { return (int)($v ?? 0); }

$statsOut = [
  "totalAssets"        => fmtInt($stats["total_assets"] ?? 0),
  "totalTransfers"     => fmtInt($stats["total_transfers"] ?? 0),
  "totalRegistered"    => fmtInt($stats["total_registered"] ?? 0),
  "assetsThisMonth"    => fmtInt($stats["assets_this_month"] ?? 0),
  "transfersThisMonth" => fmtInt($stats["transfers_this_month"] ?? 0),
  "registeredThisMonth"=> fmtInt($stats["registered_this_month"] ?? 0),
];

/**
 * Activity feed (latest 10):
 * - Registrations from lands (✅ now includes tx_hash)
 * - Transfers from land_transfers
 *
 * We return: id, action, status, date, tx
 */
$activitySql = "
  (SELECT
      CONCAT('#', parcel_id) AS id,
      'New Registration' AS action,
      'Verified' AS status,
      DATE_FORMAT(created_at, '%b %e, %Y') AS date,
      COALESCE(NULLIF(tx_hash,''), '-') AS tx,
      created_at AS sort_ts
   FROM lands
  )
  UNION ALL
  (SELECT
      CONCAT('#', parcel_id) AS id,
      'Ownership Transfer' AS action,
      CASE
        WHEN status = 'confirmed' THEN 'Completed'
        WHEN status = 'submitted' THEN 'Completed'
        WHEN status = 'approved'  THEN 'Verified'
        WHEN status = 'requested' THEN 'Pending'
        WHEN status = 'rejected'  THEN 'Pending'
        ELSE 'Pending'
      END AS status,
      DATE_FORMAT(created_at, '%b %e, %Y') AS date,
      COALESCE(NULLIF(tx_hash,''), '-') AS tx,
      created_at AS sort_ts
   FROM land_transfers
  )
  ORDER BY sort_ts DESC
  LIMIT 10
";

$items = $pdo->query($activitySql)->fetchAll(PDO::FETCH_ASSOC) ?: [];

function shortenTx(string $tx): string {
  $tx = trim($tx);
  if ($tx === '' || $tx === '-') return '-';
  if (strlen($tx) <= 14) return $tx;
  return substr($tx, 0, 8) . '...' . substr($tx, -6);
}

$activityOut = array_map(function($r){
  $status = (string)($r["status"] ?? "Pending");
  $tx = shortenTx((string)($r["tx"] ?? "-"));
  return [
    "id"     => (string)($r["id"] ?? "-"),
    "action" => (string)($r["action"] ?? "-"),
    "status" => $status,
    "date"   => (string)($r["date"] ?? "-"),
    "tx"     => $tx,
  ];
}, $items);

echo json_encode([
  "ok" => true,
  "stats" => $statsOut,
  "activity" => $activityOut
]);