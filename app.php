<?php
declare(strict_types=1);
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';

require_login();
$userEmail = htmlspecialchars(current_user_email() ?? '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>LandRegistry DApp – Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@300&display=swap" rel="stylesheet"/>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --blue:       #135bec;
      --blue-dark:  #0d47a1;
      --blue-light: #eff4ff;
      --blue-mid:   #dbeafe;
      --text:       #0f172a;
      --muted:      #64748b;
      --border:     #e2e8f0;
      --bg:         #f8fafc;
      --white:      #ffffff;
      --sidebar-w:  220px;
      --header-h:   64px;
      --green:      #16a34a;
      --green-bg:   #dcfce7;
      --amber:      #d97706;
      --amber-bg:   #fef3c7;
      --teal:       #0d9488;
      --teal-bg:    #ccfbf1;
    }

    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--bg);
      color: var(--text);
      -webkit-font-smoothing: antialiased;
    }

    h1,h2,h3,h4,h5 { font-family: 'Plus Jakarta Sans', sans-serif; }

    .material-symbols-outlined {
      font-family: 'Material Symbols Outlined';
      font-weight: normal; font-style: normal; font-size: 20px;
      line-height: 1; letter-spacing: normal; text-transform: none;
      display: inline-block; white-space: nowrap; direction: ltr;
      -webkit-font-feature-settings: 'liga'; font-feature-settings: 'liga';
      -webkit-font-smoothing: antialiased; vertical-align: middle;
    }

    a { text-decoration: none; color: inherit; }

    /* ── TOP HEADER ── */
    .topbar {
      position: fixed; top: 0; left: 0; right: 0;
      height: var(--header-h);
      background: var(--white);
      border-bottom: 1px solid var(--border);
      display: flex; align-items: center; justify-content: space-between;
      padding: 0 24px;
      z-index: 200;
    }

    .topbar-brand {
      display: flex; align-items: center; gap: 10px;
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-weight: 800; font-size: 17px; color: var(--text);
    }
    .brand-icon {
      width: 34px; height: 34px; background: var(--blue);
      border-radius: 8px; display: flex; align-items: center;
      justify-content: center; color: white;
    }
    .brand-icon .material-symbols-outlined { font-size: 20px; }

    .topbar-right {
      display: flex; align-items: center; gap: 16px;
    }

    .user-info {
      display: flex; align-items: center; gap: 8px;
      font-size: 13px; color: var(--muted);
    }
    .user-info strong { color: var(--text); font-weight: 600; }

    .connect-btn {
      display: inline-flex; align-items: center; gap: 6px;
      background: var(--blue); color: white;
      border: none; border-radius: 8px;
      padding: 8px 16px; font-size: 13px;
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-weight: 700; cursor: pointer;
      transition: background .15s;
    }
    .connect-btn:hover { background: var(--blue-dark); }
    .connect-btn .material-symbols-outlined { font-size: 16px; }

    .connect-btn.connected {
      background: #f0fdf4;
      color: var(--green);
      border: 1px solid #bbf7d0;
    }
    .connect-btn.connected:hover { background: #dcfce7; }

    .notif-btn {
      width: 36px; height: 36px; border-radius: 8px;
      border: 1px solid var(--border); background: var(--white);
      display: flex; align-items: center; justify-content: center;
      cursor: pointer; color: var(--muted);
      transition: border-color .15s, color .15s;
    }
    .notif-btn:hover { border-color: var(--blue); color: var(--blue); }

    .logout-link {
      font-size: 13px; color: var(--muted); font-weight: 500;
      transition: color .15s;
    }
    .logout-link:hover { color: #dc2626; }

    /* ── SIDEBAR ── */
    .sidebar {
      position: fixed; top: var(--header-h); left: 0; bottom: 0;
      width: var(--sidebar-w);
      background: var(--white);
      border-right: 1px solid var(--border);
      padding: 16px 0;
      overflow-y: auto;
      z-index: 100;
    }

    .nav-section-label {
      font-size: 10px; font-weight: 700;
      letter-spacing: .08em; text-transform: uppercase;
      color: var(--muted); padding: 12px 20px 6px;
    }

    .nav-item {
      display: flex; align-items: center; gap: 10px;
      padding: 10px 20px;
      font-size: 14px; font-weight: 500; color: var(--muted);
      border-radius: 0; cursor: pointer;
      transition: background .15s, color .15s;
      position: relative;
    }
    .nav-item:hover { background: var(--blue-light); color: var(--blue); }
    .nav-item.active {
      background: var(--blue-light); color: var(--blue); font-weight: 600;
    }
    .nav-item.active::before {
      content: ''; position: absolute; left: 0; top: 4px; bottom: 4px;
      width: 3px; background: var(--blue); border-radius: 0 2px 2px 0;
    }
    .nav-item .material-symbols-outlined { font-size: 20px; }

    /* ── MAIN CONTENT ── */
    .main {
      margin-left: var(--sidebar-w);
      margin-top: var(--header-h);
      padding: 36px 40px 60px;
      min-height: calc(100vh - var(--header-h));
    }

    .page-title { font-size: 26px; font-weight: 800; color: var(--text); margin-bottom: 6px; }
    .page-sub { font-size: 14px; color: var(--muted); margin-bottom: 32px; }

    /* ── STAT CARDS ── */
    .stats-row {
      display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;
      margin-bottom: 40px;
    }

    .stat-card {
      background: var(--white);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 24px;
      position: relative;
      overflow: hidden;
    }

    .stat-card-top {
      display: flex; align-items: flex-start; justify-content: space-between;
      margin-bottom: 16px;
    }

    .stat-icon {
      width: 44px; height: 44px; border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
    }
    .stat-icon .material-symbols-outlined { font-size: 22px; }
    .stat-icon.blue  { background: var(--blue-light); color: var(--blue); }
    .stat-icon.teal  { background: var(--teal-bg);   color: var(--teal); }
    .stat-icon.amber { background: var(--amber-bg);  color: var(--amber); }

    .stat-badge {
      font-size: 11px; font-weight: 700;
      padding: 3px 10px; border-radius: 20px;
      white-space: nowrap;
    }
    .badge-green  { background: var(--green-bg);  color: var(--green); }
    .badge-muted  { background: #f1f5f9; color: var(--muted); }
    .badge-amber  { background: var(--amber-bg);  color: var(--amber); }

    .stat-label { font-size: 12px; color: var(--muted); margin-bottom: 6px; }
    .stat-value { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 800; color: var(--text); }

    /* ── SECTION HEADING ── */
    .section-heading {
      font-size: 18px; font-weight: 700; color: var(--text); margin-bottom: 20px;
    }

    /* ── MODULE CARDS ── */
    .modules-grid {
      display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px;
      margin-bottom: 40px;
    }

    .module-card {
      background: var(--white);
      border: 1px solid var(--border);
      border-radius: 14px;
      overflow: hidden;
      transition: box-shadow .2s, transform .2s;
      cursor: pointer;
    }
    .module-card:hover { box-shadow: 0 8px 28px rgba(19,91,236,.12); transform: translateY(-3px); }

    .module-img {
      width: 100%; height: 160px;
      object-fit: cover;
      display: block;
      position: relative;
    }

    .module-img-wrap {
      position: relative; height: 160px; overflow: hidden;
    }

    /* CSS images for modules */
    .module-img-assets {
      background: linear-gradient(to bottom, #4a7c59 0%, #2d5a27 40%, #8fae8a 70%, #c9d4be 100%);
    }
    .module-img-verify {
      background: linear-gradient(135deg, #0a1628 0%, #0d2847 50%, #133366 100%);
      display: flex; align-items: center; justify-content: center;
    }
    .module-img-verify::after {
      content: 'VERIFY\APROPERTY';
      white-space: pre;
      text-align: center;
      color: rgba(255,255,255,.15);
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: 20px; font-weight: 800;
      letter-spacing: .15em; line-height: 1.8;
    }
    .module-img-transfer {
      background: #0a0a0a;
      display: flex; align-items: center; justify-content: center;
    }
    .module-img-history {
      background: linear-gradient(135deg, #b8892a 0%, #d4a843 50%, #8b6914 100%);
      display: flex; align-items: center; justify-content: center;
    }

    /* Transfer badge box */
    .transfer-badge-box {
      border: 2px solid #22c55e;
      border-radius: 8px;
      padding: 8px 16px;
      color: #22c55e;
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: 12px; font-weight: 800;
      letter-spacing: .1em;
    }

    /* History lines */
    .history-lines {
      display: flex; flex-direction: column; gap: 6px; padding: 0 20px; width: 80%;
    }
    .h-line {
      height: 8px; border-radius: 4px;
      background: rgba(255,255,255,.2);
    }
    .h-line.short { width: 60%; }
    .h-line.medium { width: 80%; }
    .h-line.long { width: 100%; }

    .module-tag {
      position: absolute; bottom: 12px; left: 12px;
      font-size: 11px; font-weight: 700;
      padding: 4px 10px; border-radius: 4px;
      letter-spacing: .04em;
    }
    .tag-blue  { background: var(--blue);  color: white; }
    .tag-teal  { background: #0d9488;     color: white; }
    .tag-amber { background: #d97706;     color: white; }
    .tag-dark  { background: #1e293b;     color: white; }

    .module-body { padding: 16px; }
    .module-body h4 {
      font-size: 15px; font-weight: 700; margin-bottom: 8px; color: var(--text);
    }
    .module-body p {
      font-size: 13px; color: var(--muted); line-height: 1.6;
    }

    /* ── ACTIVITY TABLE ── */
    .activity-card {
      background: var(--white);
      border: 1px solid var(--border);
      border-radius: 14px;
      overflow: hidden;
    }

    .activity-header {
      display: flex; align-items: center; justify-content: space-between;
      padding: 20px 24px 16px;
      border-bottom: 1px solid var(--border);
    }
    .activity-header h3 { font-size: 16px; font-weight: 700; }
    .view-all {
      font-size: 13px; font-weight: 600; color: var(--blue);
      transition: color .15s;
    }
    .view-all:hover { color: var(--blue-dark); }

    table { width: 100%; border-collapse: collapse; }

    thead th {
      font-size: 11px; font-weight: 700;
      text-transform: uppercase; letter-spacing: .06em;
      color: var(--muted);
      padding: 12px 24px;
      text-align: left;
      border-bottom: 1px solid var(--border);
      background: #fafbfc;
    }

    tbody tr { transition: background .12s; }
    tbody tr:hover { background: var(--bg); }
    tbody tr:not(:last-child) td { border-bottom: 1px solid var(--border); }

    td {
      padding: 14px 24px;
      font-size: 14px; color: var(--text);
    }

    .prop-id { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 600; }
    .action-cell { color: var(--muted); }

    .status-pill {
      display: inline-flex; align-items: center; gap: 5px;
      font-size: 12px; font-weight: 600;
      padding: 4px 10px; border-radius: 20px;
    }
    .status-pill::before {
      content: ''; width: 6px; height: 6px; border-radius: 50%;
    }
    .status-completed { background: var(--green-bg); color: var(--green); }
    .status-completed::before { background: var(--green); }
    .status-verified   { background: var(--blue-mid); color: var(--blue); }
    .status-verified::before   { background: var(--blue); }
    .status-pending   { background: var(--amber-bg); color: var(--amber); }
    .status-pending::before   { background: var(--amber); }

    .date-cell { color: var(--muted); font-size: 13px; }

    .tx-hash {
      font-family: monospace; font-size: 13px;
      color: var(--blue); font-weight: 600;
    }

    /* responsive */
    @media (max-width: 1024px) {
      .modules-grid { grid-template-columns: repeat(2, 1fr); }
      .stats-row    { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 768px) {
      .sidebar { display: none; }
      .main    { margin-left: 0; padding: 24px 20px 48px; }
      .stats-row { grid-template-columns: 1fr; }
      .modules-grid { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>

<!-- ── TOP HEADER ─────────────────────────────────────── -->
<header class="topbar">
  <a href="app.php" class="topbar-brand">
    <div class="brand-icon"><span class="material-symbols-outlined">account_balance</span></div>
    LandRegistry
  </a>

  <div class="topbar-right">
    <div class="user-info">
      <span class="material-symbols-outlined" style="font-size:16px;color:var(--muted)">person</span>
      <span><?= $userEmail ?></span>
    </div>
    <div class="notif-btn">
      <span class="material-symbols-outlined" style="font-size:18px">notifications</span>
    </div>
    <!-- Wallet connect button — your JS hooks into id="connect-wallet-btn" -->
    <button class="connect-btn" id="connect-wallet-btn">
      <span class="material-symbols-outlined">account_balance_wallet</span>
      Connect Wallet
    </button>
    <a href="logout.php" class="logout-link">Logout</a>
  </div>
</header>

<!-- ── SIDEBAR ─────────────────────────────────────────── -->
<nav class="sidebar">
  <a href="app.php" class="nav-item active">
    <span class="material-symbols-outlined">dashboard</span>
    Dashboard
  </a>
  <a href="assets.php" class="nav-item">
    <span class="material-symbols-outlined">home_work</span>
    My Assets
  </a>
  <a href="verify.php" class="nav-item">
    <span class="material-symbols-outlined">verified_user</span>
    Verify Property
  </a>
  <a href="owner.php" class="nav-item">
    <span class="material-symbols-outlined">swap_horiz</span>
    Transfer
  </a>
  <a href="authority.php" class="nav-item">
    <span class="material-symbols-outlined">admin_panel_settings</span>
    Register
  </a>
  <a href="history.php" class="nav-item">
    <span class="material-symbols-outlined">history</span>
    History
  </a>
</nav>

<!-- ── MAIN CONTENT ────────────────────────────────────── -->
<main class="main">

  <h1 class="page-title">DApp Features Overview</h1>
  <p class="page-sub">Manage and verify land ownership with NFT-backed security on the blockchain.</p>

  <!-- Stats Row -->
  <div class="stats-row" id="stats-row">
    <div class="stat-card">
      <div class="stat-card-top">
        <div class="stat-icon blue">
          <span class="material-symbols-outlined">home_work</span>
        </div>
        <span class="stat-badge badge-green" id="assets-badge">+2 this month</span>
      </div>
      <p class="stat-label">Total Assets</p>
      <p class="stat-value" id="stat-total-assets">—</p>
    </div>

    <div class="stat-card">
      <div class="stat-card-top">
        <div class="stat-icon teal">
          <span class="material-symbols-outlined">swap_horiz</span>
        </div>
        <span class="stat-badge badge-muted" id="transfers-badge">All time</span>
      </div>
      <p class="stat-label">Total Transfers</p>
      <p class="stat-value" id="stat-total-transfers">—</p>
    </div>

    <div class="stat-card">
      <div class="stat-card-top">
        <div class="stat-icon amber">
          <span class="material-symbols-outlined">app_registration</span>
        </div>
        <span class="stat-badge badge-amber" id="registered-badge">On-chain</span>
      </div>
      <p class="stat-label">Total Registered</p>
      <p class="stat-value" id="stat-total-registered">—</p>
    </div>
  </div>

  <!-- Core Management Modules -->
  <h2 class="section-heading">Core Management Modules</h2>
  <div class="modules-grid">

    <!-- My Land Assets -->
    <a href="assets.php" class="module-card">
      <div class="module-img-wrap">
        <div class="module-img-assets" style="height:160px;"></div>
        <span class="module-tag tag-blue">NFT Verified</span>
      </div>
      <div class="module-body">
        <h4>My Land Assets</h4>
        <p>View and manage your personal portfolio of NFT-backed property deeds.</p>
      </div>
    </a>

    <!-- Verify Property -->
    <a href="verify.php" class="module-card">
      <div class="module-img-wrap">
        <div class="module-img-verify" style="height:160px;"></div>
        <span class="module-tag tag-teal">Anti-Fraud</span>
      </div>
      <div class="module-body">
        <h4>Verify Property</h4>
        <p>Check the authenticity of any land title against the blockchain registry.</p>
      </div>
    </a>

    <!-- Transfer Ownership -->
    <a href="owner.php" class="module-card">
      <div class="module-img-wrap">
        <div class="module-img-transfer" style="height:160px;">
          <div class="transfer-badge-box">TRANSFER<br>OWNERSHIP</div>
        </div>
        <span class="module-tag tag-amber">Secure Peer-to-Peer</span>
      </div>
      <div class="module-body">
        <h4>Transfer Ownership</h4>
        <p>Securely initiate ownership transfers to new buyers via smart contracts.</p>
      </div>
    </a>

    <!-- Transaction History -->
    <a href="history.php" class="module-card">
      <div class="module-img-wrap">
        <div class="module-img-history" style="height:160px;">
          <div class="history-lines">
            <div class="h-line long"></div>
            <div class="h-line short"></div>
            <div class="h-line medium"></div>
            <div class="h-line long"></div>
            <div class="h-line short"></div>
            <div class="h-line medium"></div>
          </div>
        </div>
        <span class="module-tag tag-dark">Full Audit Trail</span>
      </div>
      <div class="module-body">
        <h4>Transaction History</h4>
        <p>Access the complete, immutable history of all property movements and deeds.</p>
      </div>
    </a>

  </div>

  <!-- Recent Network Activity -->
  <div class="activity-card">
    <div class="activity-header">
      <h3>Recent Network Activity</h3>
      <a href="history.php" class="view-all">View All</a>
    </div>
    <table>
      <thead>
        <tr>
          <th>Property ID</th>
          <th>Action</th>
          <th>Status</th>
          <th>Date</th>
          <th>Transaction</th>
        </tr>
      </thead>
      <tbody id="activity-tbody">
        <!-- Populated by JS via window.renderRecentActivity() or falls back to static below -->
        <tr>
          <td class="prop-id">#LR-9821-XP</td>
          <td class="action-cell">Ownership Transfer</td>
          <td><span class="status-pill status-completed">Completed</span></td>
          <td class="date-cell">Oct 24, 2023</td>
          <td class="tx-hash">0x4a2...9e1</td>
        </tr>
        <tr>
          <td class="prop-id">#LR-4412-MK</td>
          <td class="action-cell">Deed Verification</td>
          <td><span class="status-pill status-verified">Verified</span></td>
          <td class="date-cell">Oct 22, 2023</td>
          <td class="tx-hash">0x8b1...2f4</td>
        </tr>
        <tr>
          <td class="prop-id">#LR-0051-ZZ</td>
          <td class="action-cell">New Registration</td>
          <td><span class="status-pill status-pending">Pending</span></td>
          <td class="date-cell">Oct 21, 2023</td>
          <td class="tx-hash">0x1c9...3a8</td>
        </tr>
      </tbody>
    </table>
  </div>

</main>

<!-- Your existing JS file — hooks into connect-wallet-btn, stat IDs, and activity-tbody -->
<script src="app.js"></script>
<script>
  // Helpers already used by your UI
  window.setDashboardStats = function({ totalAssets, totalTransfers, totalRegistered }) {
    if (totalAssets    !== undefined) document.getElementById('stat-total-assets').textContent     = totalAssets;
    if (totalTransfers !== undefined) document.getElementById('stat-total-transfers').textContent  = totalTransfers;
    if (totalRegistered!== undefined) document.getElementById('stat-total-registered').textContent = totalRegistered;
  };

  window.renderRecentActivity = function(rows) {
    const tbody = document.getElementById('activity-tbody');

    if (!rows || !rows.length) {
      tbody.innerHTML = `<tr>
        <td class="prop-id">—</td>
        <td class="action-cell">No activity</td>
        <td><span class="status-pill status-pending">Pending</span></td>
        <td class="date-cell">—</td>
        <td class="tx-hash">—</td>
      </tr>`;
      return;
    }

    const statusMap = {
      'completed': 'status-completed',
      'verified':  'status-verified',
      'pending':   'status-pending',
    };

    tbody.innerHTML = rows.map(r => {
      const cls = statusMap[(r.status || '').toLowerCase()] || 'status-pending';
      const safe = (s) => String(s ?? '').replace(/[<>&"]/g, (c) => ({'<':'&lt;','>':'&gt;','&':'&amp;','"':'&quot;'}[c]));
      return `<tr>
        <td class="prop-id">${safe(r.id)}</td>
        <td class="action-cell">${safe(r.action)}</td>
        <td><span class="status-pill ${cls}">${safe(r.status)}</span></td>
        <td class="date-cell">${safe(r.date)}</td>
        <td class="tx-hash">${safe(r.tx)}</td>
      </tr>`;
    }).join('');
  };

  function setBadges({ assetsThisMonth, transfersThisMonth, registeredThisMonth }) {
    const assetsBadge = document.getElementById('assets-badge');
    const transfersBadge = document.getElementById('transfers-badge');
    const registeredBadge = document.getElementById('registered-badge');

    if (assetsBadge && assetsThisMonth !== undefined) {
      assetsBadge.textContent = `+${assetsThisMonth} this month`;
      assetsBadge.className = "stat-badge badge-green";
    }
    if (transfersBadge && transfersThisMonth !== undefined) {
      transfersBadge.textContent = `+${transfersThisMonth} this month`;
      transfersBadge.className = "stat-badge badge-muted";
    }
    if (registeredBadge && registeredThisMonth !== undefined) {
      registeredBadge.textContent = `+${registeredThisMonth} this month`;
      registeredBadge.className = "stat-badge badge-amber";
    }
  }

  async function loadDashboardLive() {
    try {
      const r = await fetch('./dashboard_data.php', { cache: 'no-store' });
      const j = await r.json();
      if (!j.ok) throw new Error(j.error || 'Failed to load dashboard data');

      // Stats
      window.setDashboardStats({
        totalAssets: j.stats.totalAssets,
        totalTransfers: j.stats.totalTransfers,
        totalRegistered: j.stats.totalRegistered
      });
      setBadges(j.stats);

      // Activity
      window.renderRecentActivity(j.activity);

    } catch (e) {
      // If backend temporarily fails, don’t break UI; show placeholders
      console.warn("Dashboard live fetch error:", e);
    }
  }

  // Load now + refresh every 10s
  loadDashboardLive();
  setInterval(loadDashboardLive, 10000);

  // Keep your nav highlight logic
  document.querySelectorAll('.nav-item').forEach(el => {
    if (el.getAttribute('href') === window.location.pathname.split('/').pop()) {
      document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
      el.classList.add('active');
    }
  });
</script>
</body>
</html>