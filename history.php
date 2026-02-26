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
  <title>Transaction History – LandRegistry DApp</title>
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
    }

    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--bg); color: var(--text);
      -webkit-font-smoothing: antialiased;
    }
    h1,h2,h3,h4,h5 { font-family: 'Plus Jakarta Sans', sans-serif; }

    .material-symbols-outlined {
      font-family: 'Material Symbols Outlined';
      font-weight: normal; font-style: normal; font-size: 20px;
      line-height: 1; display: inline-block; white-space: nowrap;
      direction: ltr; -webkit-font-feature-settings: 'liga';
      font-feature-settings: 'liga'; -webkit-font-smoothing: antialiased;
      vertical-align: middle;
    }

    a { text-decoration: none; color: inherit; }

    /* topbar */
    .topbar {
      position: fixed; top: 0; left: 0; right: 0;
      height: var(--header-h); background: var(--white);
      border-bottom: 1px solid var(--border);
      display: flex; align-items: center; justify-content: space-between;
      padding: 0 24px; z-index: 200;
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
    .topbar-right { display: flex; align-items: center; gap: 16px; }
    .user-info { display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--muted); }
    .connect-btn {
      display: inline-flex; align-items: center; gap: 6px;
      background: var(--blue); color: white;
      border: none; border-radius: 8px; padding: 8px 16px; font-size: 13px;
      font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700;
      cursor: pointer; transition: background .15s;
    }
    .connect-btn:hover { background: var(--blue-dark); }
    .connect-btn .material-symbols-outlined { font-size: 16px; }
    .notif-btn {
      width: 36px; height: 36px; border-radius: 8px;
      border: 1px solid var(--border); background: var(--white);
      display: flex; align-items: center; justify-content: center;
      cursor: pointer; color: var(--muted);
    }
    .logout-link { font-size: 13px; color: var(--muted); font-weight: 500; }
    .logout-link:hover { color: #dc2626; }

    /* sidebar */
    .sidebar {
      position: fixed; top: var(--header-h); left: 0; bottom: 0;
      width: var(--sidebar-w); background: var(--white);
      border-right: 1px solid var(--border); padding: 16px 0;
      overflow-y: auto; z-index: 100;
    }
    .nav-item {
      display: flex; align-items: center; gap: 10px;
      padding: 10px 20px; font-size: 14px; font-weight: 500; color: var(--muted);
      cursor: pointer; transition: background .15s, color .15s; position: relative;
    }
    .nav-item:hover { background: var(--blue-light); color: var(--blue); }
    .nav-item.active { background: var(--blue-light); color: var(--blue); font-weight: 600; }
    .nav-item.active::before {
      content: ''; position: absolute; left: 0; top: 4px; bottom: 4px;
      width: 3px; background: var(--blue); border-radius: 0 2px 2px 0;
    }
    .nav-item .material-symbols-outlined { font-size: 20px; }

    /* main */
    .main {
      margin-left: var(--sidebar-w);
      margin-top: var(--header-h);
      padding: 36px 40px 60px;
    }

    .page-title { font-size: 24px; font-weight: 800; margin-bottom: 6px; }
    .page-sub { font-size: 14px; color: var(--muted); margin-bottom: 32px; }

    /* search card */
    .search-card {
      background: var(--white);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 28px 32px;
      margin-bottom: 28px;
    }

    .search-card h3 {
      font-size: 15px; font-weight: 700; margin-bottom: 6px;
    }
    .search-card p {
      font-size: 13px; color: var(--muted); margin-bottom: 18px;
    }

    .addr-search-row {
      display: flex; gap: 10px; align-items: center; flex-wrap: wrap;
    }

    .addr-input-wrap {
      flex: 1; min-width: 280px;
      display: flex; align-items: center; gap: 10px;
      background: var(--bg);
      border: 1.5px solid var(--border);
      border-radius: 10px; padding: 0 16px;
      transition: border-color .15s;
    }
    .addr-input-wrap:focus-within { border-color: var(--blue); }
    .addr-input-wrap .material-symbols-outlined { color: var(--muted); font-size: 18px; }
    .addr-input-wrap input {
      flex: 1; border: none; outline: none; background: transparent;
      font-family: 'DM Sans', monospace; font-size: 14px;
      color: var(--text); padding: 12px 0;
    }
    .addr-input-wrap input::placeholder { color: var(--muted); font-family: 'DM Sans', sans-serif; }

    .search-btn {
      display: inline-flex; align-items: center; gap: 6px;
      background: var(--blue); color: white;
      border: none; border-radius: 10px;
      padding: 12px 22px; font-size: 14px;
      font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700;
      cursor: pointer; transition: background .15s, transform .15s;
      white-space: nowrap;
    }
    .search-btn:hover { background: var(--blue-dark); transform: translateY(-1px); }
    .search-btn .material-symbols-outlined { font-size: 17px; }

    /* summary badges */
    .summary-row {
      display: flex; gap: 14px; flex-wrap: wrap; margin-bottom: 20px;
      display: none;
    }
    .summary-badge {
      display: flex; align-items: center; gap: 8px;
      background: var(--white); border: 1px solid var(--border);
      border-radius: 10px; padding: 10px 16px;
    }
    .summary-badge .material-symbols-outlined { font-size: 18px; color: var(--blue); }
    .summary-badge span.val { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; font-size: 16px; }
    .summary-badge span.lbl { font-size: 12px; color: var(--muted); margin-left: 2px; }

    /* results card */
    .results-card {
      background: var(--white);
      border: 1px solid var(--border);
      border-radius: 14px;
      overflow: hidden;
      display: none;
    }

    .results-header {
      display: flex; align-items: center; justify-content: space-between;
      padding: 20px 24px 16px;
      border-bottom: 1px solid var(--border);
    }
    .results-header h3 { font-size: 15px; font-weight: 700; }
    .results-addr {
      font-family: monospace; font-size: 13px;
      color: var(--muted); background: var(--bg);
      border: 1px solid var(--border);
      border-radius: 6px; padding: 4px 10px;
    }

    table { width: 100%; border-collapse: collapse; }
    thead th {
      font-size: 11px; font-weight: 700; text-transform: uppercase;
      letter-spacing: .06em; color: var(--muted);
      padding: 12px 24px; text-align: left;
      border-bottom: 1px solid var(--border);
      background: #fafbfc;
    }
    tbody tr { transition: background .12s; }
    tbody tr:hover { background: var(--bg); }
    tbody tr:not(:last-child) td { border-bottom: 1px solid var(--border); }
    td { padding: 14px 24px; font-size: 14px; color: var(--text); }

    .prop-id { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 600; }
    .action-cell { color: var(--muted); }
    .date-cell { color: var(--muted); font-size: 13px; }
    .tx-hash { font-family: monospace; font-size: 13px; color: var(--blue); font-weight: 600; cursor: pointer; }
    .tx-hash:hover { text-decoration: underline; }

    .block-cell { font-family: monospace; font-size: 13px; color: var(--muted); }

    .ada-amount {
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-weight: 700; font-size: 13px;
      display: flex; align-items: center; gap: 3px;
    }
    .ada-symbol { color: var(--blue); font-weight: 800; }

    .status-pill {
      display: inline-flex; align-items: center; gap: 5px;
      font-size: 12px; font-weight: 600;
      padding: 4px 10px; border-radius: 20px;
    }
    .status-pill::before { content: ''; width: 6px; height: 6px; border-radius: 50%; }
    .status-completed { background: var(--green-bg);  color: var(--green); }
    .status-completed::before { background: var(--green); }
    .status-verified   { background: var(--blue-mid);  color: var(--blue); }
    .status-verified::before   { background: var(--blue); }
    .status-pending   { background: var(--amber-bg);  color: var(--amber); }
    .status-pending::before   { background: var(--amber); }

    /* empty/loading */
    .loading-spinner {
      display: flex; align-items: center; justify-content: center;
      padding: 60px; color: var(--muted); gap: 12px;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    .spinner {
      width: 24px; height: 24px; border: 3px solid var(--border);
      border-top-color: var(--blue); border-radius: 50%;
      animation: spin .7s linear infinite;
    }

    .no-results {
      text-align: center; padding: 60px 20px; color: var(--muted);
    }
    .no-results .material-symbols-outlined { font-size: 48px; color: var(--border); display: block; margin-bottom: 14px; }
    .no-results h3 { font-size: 16px; font-weight: 700; color: var(--text); margin-bottom: 6px; }

    /* idle state */
    .idle-state {
      text-align: center; padding: 80px 20px; color: var(--muted);
    }
    .idle-state .material-symbols-outlined { font-size: 52px; color: #cbd5e1; display: block; margin-bottom: 16px; }
    .idle-state h3 { font-size: 17px; font-weight: 700; color: var(--text); margin-bottom: 8px; }

    @media (max-width: 768px) {
      .sidebar { display: none; }
      .main { margin-left: 0; padding: 24px 20px 48px; }
    }
  </style>
</head>
<body>

<!-- TOPBAR -->
<header class="topbar">
  <a href="app.php" class="topbar-brand">
    <div class="brand-icon"><span class="material-symbols-outlined">account_balance</span></div>
    LandRegistry
  </a>
  <div class="topbar-right">
    <div class="user-info">
      <span class="material-symbols-outlined" style="font-size:16px">person</span>
      <span><?= $userEmail ?></span>
    </div>
    <div class="notif-btn">
      <span class="material-symbols-outlined" style="font-size:18px">notifications</span>
    </div>
    <button class="connect-btn" id="connect-wallet-btn">
      <span class="material-symbols-outlined">account_balance_wallet</span>
      Connect Wallet
    </button>
    <a href="logout.php" class="logout-link">Logout</a>
  </div>
</header>

<!-- SIDEBAR -->
<nav class="sidebar">
  <a href="app.php" class="nav-item">
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
  <a href="history.php" class="nav-item active">
    <span class="material-symbols-outlined">history</span>
    History
  </a>
</nav>

<!-- MAIN -->
<main class="main">
  <h1 class="page-title">Transaction History</h1>
  <p class="page-sub">Look up the complete on-chain activity for any wallet address.</p>

  <!-- Address search -->
  <div class="search-card">
    <h3>Search by Wallet Address</h3>
    <p>Enter an ADA wallet address to view all associated land registry transactions.</p>
    <div class="addr-search-row">
      <div class="addr-input-wrap">
        <span class="material-symbols-outlined">account_balance_wallet</span>
        <input
          type="text"
          id="wallet-addr-input"
          placeholder="addr1qx…  or  stake1u…"
          autocomplete="off"
          spellcheck="false"
        />
      </div>
      <button class="search-btn" id="history-search-btn" onclick="window.searchHistory()">
        <span class="material-symbols-outlined">search</span>
        Search
      </button>
    </div>
  </div>

  <!-- Summary row (shown after search) -->
  <div class="summary-row" id="summary-row">
    <div class="summary-badge">
      <span class="material-symbols-outlined">receipt_long</span>
      <span class="val" id="sum-total">0</span>
      <span class="lbl">Transactions</span>
    </div>
    <div class="summary-badge">
      <span class="material-symbols-outlined">home_work</span>
      <span class="val" id="sum-properties">0</span>
      <span class="lbl">Properties</span>
    </div>
    <div class="summary-badge">
      <span class="material-symbols-outlined">swap_horiz</span>
      <span class="val" id="sum-transfers">0</span>
      <span class="lbl">Transfers</span>
    </div>
  </div>

  <!-- Results -->
  <div class="results-card" id="results-card">
    <div class="results-header">
      <h3>Transaction Records</h3>
      <span class="results-addr" id="results-addr-display"></span>
    </div>

    <div id="results-loading" class="loading-spinner" style="display:none">
      <div class="spinner"></div>
      <span>Fetching transactions…</span>
    </div>

    <div id="results-table-wrap">
      <table>
        <thead>
          <tr>
            <th>Property ID</th>
            <th>Action</th>
            <th>Status</th>
            <th>Date</th>
            <th>Block</th>
            <th>Fee (₳)</th>
            <th>Transaction Hash</th>
          </tr>
        </thead>
        <tbody id="history-tbody"></tbody>
      </table>
    </div>

    <div class="no-results" id="no-results" style="display:none">
      <span class="material-symbols-outlined">search_off</span>
      <h3>No transactions found</h3>
      <p>No land registry transactions were found for this address.</p>
    </div>
  </div>

  <!-- Idle state -->
  <div class="idle-state" id="idle-state">
    <span class="material-symbols-outlined">manage_search</span>
    <h3>Enter a wallet address to begin</h3>
    <p>Paste any ADA wallet address above to view its complete land registry transaction history.</p>
  </div>

</main>

<!-- Keep your existing file (connect wallet etc) -->
<script src="land-re.js"></script>

<!-- ✅ Add robust history logic (works even if land-re.js doesn't define fetchHistory) -->
<script type="module">
  import { Lucid, Blockfrost } from "https://unpkg.com/lucid-cardano@0.10.11/web/mod.js";

  // ✅ Match your project (Preprod). Update key if different.
  const BLOCKFROST_URL = "https://cardano-preprod.blockfrost.io/api/v0";
  const BLOCKFROST_KEY = "preprodYjRkHfcazNkL0xxG9C2RdUbUoTrG7wip";
  const NETWORK = "Preprod";

  let lucid = null;
  async function getLucidReadOnly() {
    if (lucid) return lucid;
    lucid = await Lucid.new(new Blockfrost(BLOCKFROST_URL, BLOCKFROST_KEY), NETWORK);
    return lucid;
  }

  function shorten(s) {
    s = String(s || "");
    if (s.length <= 24) return s;
    return s.slice(0, 10) + "…" + s.slice(-8);
  }

  function addrToPkhHex(address, lucidInst) {
    const details = lucidInst.utils.getAddressDetails(address);

    // Your DB matches payment key hash (PKH). Stake addresses won't work here.
    if (!details.paymentCredential || details.paymentCredential.type !== "Key") {
      throw new Error("Paste a normal wallet address (addr...), not a stake address (stake...).");
    }
    return details.paymentCredential.hash; // 56 hex
  }

  function showLoading(addr) {
    document.getElementById('idle-state').style.display = 'none';
    document.getElementById('results-card').style.display = 'block';
    document.getElementById('results-loading').style.display = 'flex';
    document.getElementById('results-table-wrap').style.display = 'none';
    document.getElementById('no-results').style.display = 'none';
    document.getElementById('summary-row').style.display = 'none';
    document.getElementById('results-addr-display').textContent = shorten(addr);
  }

  function showErrorNoResults() {
    document.getElementById('results-loading').style.display = 'none';
    document.getElementById('no-results').style.display = 'block';
  }

  // ✅ Provide fetchHistory even if land-re.js doesn't.
  // Uses your backend (history_api.php) which queries DB by PKH.
  window.fetchHistory = async function(addr) {
    const lucidInst = await getLucidReadOnly();
    const pkh = addrToPkhHex(addr, lucidInst);

    const r = await fetch("./history_api.php?pkh=" + encodeURIComponent(pkh), { cache: "no-store" });
    const j = await r.json();
    if (!j.ok) throw new Error(j.error || "History fetch failed");

    const rows = (j.rows || []).map(x => ({
      id: x.id,
      action: x.action,
      status: x.status,
      date: x.date,
      block: x.block,
      fee: x.fee,
      tx: x.tx
    }));

    window.renderHistory(addr, rows);
  };

  // ── Search trigger ────────────────────────────────────────────────────
  window.searchHistory = function() {
    const addr = document.getElementById('wallet-addr-input').value.trim();
    if (!addr) return;

    showLoading(addr);

    window.fetchHistory(addr).catch((e) => {
      console.warn(e);
      showErrorNoResults();
    });
  };

  // Allow Enter key to trigger search
  document.getElementById('wallet-addr-input').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') window.searchHistory();
  });

  // ── Expose render function (unchanged but improved summary property count) ──
  window.renderHistory = function(addr, rows) {
    document.getElementById('results-loading').style.display = 'none';
    document.getElementById('results-addr-display').textContent =
      addr.length > 24 ? addr.slice(0,10) + '…' + addr.slice(-8) : addr;

    if (!rows || rows.length === 0) {
      document.getElementById('no-results').style.display = 'block';
      return;
    }

    const statusMap = {
      'completed': 'status-completed',
      'verified':  'status-verified',
      'pending':   'status-pending',
    };

    document.getElementById('results-table-wrap').style.display = '';
    document.getElementById('history-tbody').innerHTML = rows.map(r => {
      const cls = statusMap[(r.status||'').toLowerCase()] || 'status-pending';
      const fullTx = r.tx || '';
      const txShort = fullTx && fullTx !== '—' && fullTx !== '-' ? (fullTx.slice(0,8) + '…' + fullTx.slice(-6)) : '—';
      return `<tr>
        <td class="prop-id">${r.id || '—'}</td>
        <td class="action-cell">${r.action || '—'}</td>
        <td><span class="status-pill ${cls}">${r.status || '—'}</span></td>
        <td class="date-cell">${r.date || '—'}</td>
        <td class="block-cell">${r.block || '—'}</td>
        <td><span class="ada-amount"><span class="ada-symbol">₳</span>${r.fee || '—'}</span></td>
        <td class="tx-hash" title="${fullTx}">${txShort}</td>
      </tr>`;
    }).join('');

    // Summary
    const transfers = rows.filter(r => (r.action||'').toLowerCase().includes('transfer')).length;

    // Property count: normalize "#PARCEL" -> "PARCEL"
    const propIds = new Set(
      rows.map(r => String(r.id || '').replace(/^#/, '').trim()).filter(Boolean)
    );

    document.getElementById('sum-total').textContent       = rows.length;
    document.getElementById('sum-properties').textContent  = propIds.size;
    document.getElementById('sum-transfers').textContent   = transfers;
    document.getElementById('summary-row').style.display   = 'flex';
  };

  // ✅ Click tx to open Cardanoscan (preprod)
  document.addEventListener("click", (e) => {
    const el = e.target;
    if (!el || !el.classList || !el.classList.contains("tx-hash")) return;

    const full = el.getAttribute("title") || "";
    if (!full || full === "—" || full === "-") return;

    window.open("https://preprod.cardanoscan.io/transaction/" + full, "_blank", "noopener,noreferrer");
  });

  // Pre-fill from URL param ?addr=…
  const params = new URLSearchParams(window.location.search);
  if (params.get('addr')) {
    document.getElementById('wallet-addr-input').value = params.get('addr');
    window.searchHistory();
  }
</script>

</body>
</html>