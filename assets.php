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
  <title>My Assets – LandRegistry DApp</title>
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
      background: var(--bg);
      color: var(--text);
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
      height: var(--header-h);
      background: var(--white);
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
      border: none; border-radius: 8px;
      padding: 8px 16px; font-size: 13px;
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
      width: var(--sidebar-w);
      background: var(--white);
      border-right: 1px solid var(--border);
      padding: 16px 0; overflow-y: auto; z-index: 100;
    }
    .nav-item {
      display: flex; align-items: center; gap: 10px;
      padding: 10px 20px;
      font-size: 14px; font-weight: 500; color: var(--muted);
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

    .page-header {
      display: flex; align-items: center; justify-content: space-between;
      margin-bottom: 28px; flex-wrap: wrap; gap: 16px;
    }
    .page-title { font-size: 24px; font-weight: 800; }
    .page-sub { font-size: 14px; color: var(--muted); margin-top: 4px; }

    /* filter bar */
    .filter-bar {
      display: flex; gap: 12px; align-items: center; flex-wrap: wrap;
      margin-bottom: 28px;
    }

    .search-wrap {
      display: flex; align-items: center; gap: 8px;
      background: var(--white);
      border: 1px solid var(--border);
      border-radius: 10px; padding: 0 14px;
      flex: 1; max-width: 360px;
    }
    .search-wrap .material-symbols-outlined { color: var(--muted); font-size: 18px; }
    .search-wrap input {
      border: none; outline: none; background: transparent;
      font-family: 'DM Sans', sans-serif; font-size: 14px;
      color: var(--text); padding: 10px 0; width: 100%;
    }
    .search-wrap input::placeholder { color: var(--muted); }

    .filter-select {
      background: var(--white); border: 1px solid var(--border);
      border-radius: 10px; padding: 10px 14px;
      font-family: 'DM Sans', sans-serif; font-size: 14px; color: var(--text);
      outline: none; cursor: pointer;
    }

    /* assets grid */
    .assets-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 20px;
    }

    .asset-card {
      background: var(--white);
      border: 1px solid var(--border);
      border-radius: 14px;
      overflow: hidden;
      transition: box-shadow .2s, transform .2s;
    }
    .asset-card:hover { box-shadow: 0 8px 28px rgba(19,91,236,.12); transform: translateY(-3px); }

    .asset-thumb {
      height: 140px;
      background: linear-gradient(135deg, #4a7c59 0%, #2d5a27 50%, #8fae8a 100%);
      position: relative;
      display: flex; align-items: center; justify-content: center;
    }

    /* Vary thumbnail colors */
    .asset-thumb.t1 { background: linear-gradient(135deg, #4a7c59, #8fae8a); }
    .asset-thumb.t2 { background: linear-gradient(135deg, #1e3a5f, #2d6a9f); }
    .asset-thumb.t3 { background: linear-gradient(135deg, #7c4a1e, #c4813a); }
    .asset-thumb.t4 { background: linear-gradient(135deg, #4a1e7c, #7c4ab8); }
    .asset-thumb.t5 { background: linear-gradient(135deg, #1e7c5a, #4ab88a); }
    .asset-thumb.t6 { background: linear-gradient(135deg, #7c1e4a, #b84a7c); }

    .asset-thumb .material-symbols-outlined { font-size: 40px; color: rgba(255,255,255,.4); }

    .asset-nft-tag {
      position: absolute; top: 10px; right: 10px;
      background: var(--blue); color: white;
      font-size: 10px; font-weight: 700;
      letter-spacing: .05em; padding: 3px 8px; border-radius: 4px;
    }

    .asset-status-dot {
      position: absolute; top: 10px; left: 10px;
      display: flex; align-items: center; gap: 5px;
      background: rgba(0,0,0,.45); backdrop-filter: blur(4px);
      border-radius: 20px; padding: 3px 10px;
    }
    .asset-status-dot span.dot {
      width: 6px; height: 6px; border-radius: 50%; background: #22c55e;
    }
    .asset-status-dot span.dot.pending { background: #f59e0b; }
    .asset-status-dot .label { font-size: 11px; color: white; font-weight: 600; }

    .asset-body { padding: 16px; }
    .asset-id {
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: 12px; font-weight: 700; color: var(--blue);
      letter-spacing: .04em; margin-bottom: 6px;
    }
    .asset-title { font-size: 15px; font-weight: 700; margin-bottom: 6px; }
    .asset-location {
      display: flex; align-items: center; gap: 4px;
      font-size: 12px; color: var(--muted); margin-bottom: 14px;
    }
    .asset-location .material-symbols-outlined { font-size: 14px; }

    .asset-meta {
      display: flex; justify-content: space-between; align-items: center;
      border-top: 1px solid var(--border); padding-top: 12px;
    }
    .asset-meta-item { font-size: 11px; color: var(--muted); }
    .asset-meta-item strong { display: block; font-size: 13px; font-weight: 700; color: var(--text); margin-top: 2px; }

    .asset-action-btn {
      display: inline-flex; align-items: center; gap: 4px;
      background: var(--blue-light); color: var(--blue);
      border: none; border-radius: 7px;
      padding: 6px 12px; font-size: 12px; font-weight: 700;
      font-family: 'Plus Jakarta Sans', sans-serif;
      cursor: pointer; transition: background .15s;
    }
    .asset-action-btn:hover { background: var(--blue-mid); }
    .asset-action-btn .material-symbols-outlined { font-size: 14px; }

    /* empty state */
    .empty-state {
      text-align: center; padding: 80px 20px; color: var(--muted);
      display: none;
    }
    .empty-state .material-symbols-outlined { font-size: 56px; color: var(--border); margin-bottom: 16px; display: block; }
    .empty-state h3 { font-size: 18px; font-weight: 700; color: var(--text); margin-bottom: 8px; }

    /* loading */
    .loading-spinner {
      display: flex; align-items: center; justify-content: center;
      padding: 80px; color: var(--muted); gap: 12px;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    .spinner {
      width: 28px; height: 28px; border: 3px solid var(--border);
      border-top-color: var(--blue); border-radius: 50%;
      animation: spin .7s linear infinite;
    }

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
  <a href="assets.php" class="nav-item active">
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

<!-- MAIN -->
<main class="main">
  <div class="page-header">
    <div>
      <h1 class="page-title">My Land Assets</h1>
      <p class="page-sub">Your NFT-backed property portfolio on the blockchain.</p>
    </div>
  </div>

  <!-- Filter bar -->
  <div class="filter-bar">
    <div class="search-wrap">
      <span class="material-symbols-outlined">search</span>
      <input type="text" id="asset-search" placeholder="Search by Title ID or location…"/>
    </div>
    <select class="filter-select" id="status-filter">
      <option value="">All Status</option>
      <option value="verified">Verified</option>
      <option value="pending">Pending</option>
    </select>
  </div>

  <!-- Assets grid — populated by JS via window.renderAssets() or falls back to static -->
  <div id="assets-loading" class="loading-spinner">
    <div class="spinner"></div>
    <span>Loading assets…</span>
  </div>

  <div class="assets-grid" id="assets-grid" style="display:none">
    <!-- Static fallback cards shown if JS doesn't populate -->
  </div>

  <div class="empty-state" id="assets-empty">
    <span class="material-symbols-outlined">home_work</span>
    <h3>No assets found</h3>
    <p>No registered land assets were found for your wallet address.</p>
  </div>
</main>

<script>
  // ── Expose render function for your JS to call ──────────────────────────
  // assets: [{id, title, location, size, registered, status, thumb}]
  window.renderAssets = function(assets) {
    const grid    = document.getElementById('assets-grid');
    const loading = document.getElementById('assets-loading');
    const empty   = document.getElementById('assets-empty');

    // ✅ ADD THESE:
  empty.style.display = 'none';
  grid.style.display = 'none';

  loading.style.display = 'none';

    if (!assets || assets.length === 0) {
      empty.style.display = 'block';
      return;
    }

    const thumbs = ['t1','t2','t3','t4','t5','t6'];
    grid.style.display = 'grid';
    grid.innerHTML = assets.map((a, i) => `
      <div class="asset-card" data-status="${(a.status||'').toLowerCase()}" data-title="${(a.title||'').toLowerCase()} ${(a.location||'').toLowerCase()} ${(a.id||'').toLowerCase()}">
        <div class="asset-thumb ${thumbs[i % thumbs.length]}">
          <span class="material-symbols-outlined">terrain</span>
          <span class="asset-nft-tag">NFT</span>
          <div class="asset-status-dot">
            <span class="dot ${a.status?.toLowerCase() === 'pending' ? 'pending' : ''}"></span>
            <span class="label">${a.status || 'Verified'}</span>
          </div>
        </div>
        <div class="asset-body">
          <p class="asset-id">${a.id || '—'}</p>
          <h4 class="asset-title">${a.title || 'Land Parcel'}</h4>
          <p class="asset-location">
            <span class="material-symbols-outlined">location_on</span>
            ${a.location || '—'}
          </p>
          <div class="asset-meta">
            <div class="asset-meta-item">Size<strong>${a.size || '—'}</strong></div>
            <div class="asset-meta-item">Registered<strong>${a.registered || '—'}</strong></div>
            <button class="asset-action-btn" onclick="window.viewAsset && window.viewAsset('${a.id}')">
              <span class="material-symbols-outlined">open_in_new</span> View
            </button>
          </div>
        </div>
      </div>
    `).join('');

    // Client-side filter
    const applyFilter = () => {
      const q      = document.getElementById('asset-search').value.toLowerCase();
      const status = document.getElementById('status-filter').value.toLowerCase();
      document.querySelectorAll('.asset-card').forEach(card => {
        const matchQ = !q || card.dataset.title.includes(q);
        const matchS = !status || card.dataset.status === status;
        card.style.display = (matchQ && matchS) ? '' : 'none';
      });
    };

    document.getElementById('asset-search').addEventListener('input', applyFilter);
    document.getElementById('status-filter').addEventListener('change', applyFilter);
  };

  // If JS doesn't call renderAssets within 3s, show empty state
  setTimeout(() => {
  const loading = document.getElementById('assets-loading');
  const grid = document.getElementById('assets-grid');
  if (loading.style.display !== 'none' && grid.style.display === 'none') {
    loading.style.display = 'none';
    document.getElementById('assets-empty').style.display = 'block';
  }
}, 3000);
</script>

<script type="module">
  import { Lucid, Blockfrost } from "https://unpkg.com/lucid-cardano@0.10.11/web/mod.js";

  const BLOCKFROST_URL = "https://cardano-preprod.blockfrost.io/api/v0";
  const BLOCKFROST_KEY = "preprodYjRkHfcazNkL0xxG9C2RdUbUoTrG7wip";
  const NETWORK = "Preprod";

  const connectBtn = document.getElementById("connect-wallet-btn");
  let lucid;

  async function connectWallet() {
    // pick any wallet you support; for now assume Nami exists
    if (!window.cardano?.lace) throw new Error("lace wallet not found");

    lucid = await Lucid.new(new Blockfrost(BLOCKFROST_URL, BLOCKFROST_KEY), NETWORK);
    const api = await window.cardano.lace.enable();
    lucid.selectWallet(api);

    const addr = await lucid.wallet.address();
    const details = lucid.utils.getAddressDetails(addr);

    if (!details.paymentCredential || details.paymentCredential.type !== "Key") {
      throw new Error("Wallet must be a key payment address");
    }

    return {
      address: addr,
      pkh: details.paymentCredential.hash
    };
  }

  async function loadAssets(ownerPkh) {
    const res = await fetch("./get_my_assets.php?owner_pkh=" + encodeURIComponent(ownerPkh));
    const j = await res.json();
    if (!j.ok) throw new Error(j.error || "Failed loading assets");

    // Map backend response to your renderAssets structure
    const assets = (j.items || []).map(x => ({
      id: x.id,
      title: x.title,
      location: x.location,
      size: x.size,
      registered: x.registered,
      status: x.status,
      unit: x.unit,
      documents: x.documents
    }));

    window.renderAssets(assets);
  }

  // optional: simple view handler
  window.viewAsset = (parcelId) => {
  window.location.href = "./asset_view.php?parcel_id=" + encodeURIComponent(parcelId);
};

  connectBtn.addEventListener("click", async () => {
    try {
      connectBtn.disabled = true;
      connectBtn.textContent = "Connecting...";
      const { pkh } = await connectWallet();
      connectBtn.textContent = "Wallet Connected";
      await loadAssets(pkh);
    } catch (e) {
      console.error(e);
      connectBtn.disabled = false;
      connectBtn.textContent = "Connect Wallet";
      alert(e?.message || e);
    }
  });

  // Optional auto-load if wallet already connected? (keep simple for now)
</script>
</body>
</html>