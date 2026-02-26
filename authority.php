<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';
require_login();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>LandRegistry DApp - Authority</title>

  <style>
    :root{
      --bg:#f6f8fc;
      --card:#ffffff;
      --line:#e6edf7;
      --text:#0f172a;
      --muted:#64748b;

      --blue:#2563eb;
      --blue2:#3b82f6;
      --blueSoft:#eff6ff;

      --shadow: 0 10px 30px rgba(15, 23, 42, .08);
      --radius: 16px;
    }

    *{ box-sizing:border-box; }
    html,body{ height:100%; }
    body{
      margin:0;
      font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial, "Noto Sans", "Helvetica Neue", sans-serif;
      background: var(--bg);
      color: var(--text);
    }

    /* Top Nav */
    .topbar{
      position: sticky;
      top: 0;
      z-index: 50;
      background: #fff;
      border-bottom: 1px solid var(--line);
    }
    .topbar-inner{
      max-width: 1180px;
      margin: 0 auto;
      padding: 12px 18px;
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap: 12px;
    }

    .brand{
      display:flex;
      align-items:center;
      gap:10px;
      font-weight: 800;
      letter-spacing: .2px;
    }
    .brand .logo{
      width: 28px; height: 28px;
      border-radius: 8px;
      background: linear-gradient(135deg, var(--blue), var(--blue2));
      display:grid;
      place-items:center;
      color:#fff;
      box-shadow: 0 10px 18px rgba(37, 99, 235, .22);
    }

    .nav{
      display:flex;
      align-items:center;
      gap: 18px;
      margin-left: 22px;
      flex: 1;
    }
    .nav a{
      text-decoration:none;
      color:#334155;
      font-size: 13px;
      font-weight: 600;
      padding: 8px 10px;
      border-radius: 10px;
    }
    .nav a:hover{
      background: #f1f5ff;
      color: var(--blue);
    }
    .nav a.active{
      background: var(--blueSoft);
      color: var(--blue);
    }

    .actions{
      display:flex;
      align-items:center;
      gap: 10px;
    }

    .btn{
      border:0;
      border-radius: 12px;
      padding: 10px 14px;
      font-weight: 800;
      font-size: 13px;
      cursor:pointer;
      display:inline-flex;
      align-items:center;
      gap: 10px;
      transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
      user-select:none;
    }
    .btn-primary{
      color: #fff;
      background: linear-gradient(135deg, var(--blue), var(--blue2));
      box-shadow: 0 12px 22px rgba(37, 99, 235, .20);
    }
    .btn-primary:hover{ transform: translateY(-1px); box-shadow: 0 16px 28px rgba(37, 99, 235, .24); }

    .btn svg{ width: 16px; height: 16px; }

    /* Layout */
    .wrap{
      max-width: 1180px;
      margin: 18px auto 80px;
      padding: 0 18px;
    }

    .crumb{
      font-size: 12px;
      color: var(--muted);
      margin: 8px 0 12px;
    }
    .crumb a{ color: var(--muted); text-decoration:none; }
    .crumb a:hover{ color: var(--blue); }

    .page-title{
      display:flex;
      align-items:flex-start;
      gap: 14px;
      margin: 14px 0 18px;
    }
    .page-title .icon{
      width: 44px; height: 44px;
      border-radius: 14px;
      background: #eaf2ff;
      display:grid;
      place-items:center;
      color: var(--blue);
      border: 1px solid #dbe7ff;
    }
    .page-title h1{
      margin:0;
      font-size: 22px;
      line-height: 1.15;
      letter-spacing: -0.2px;
    }
    .page-title p{
      margin:4px 0 0;
      color: var(--muted);
      font-size: 13px;
      max-width: 620px;
    }

    .grid{
      display:grid;
      grid-template-columns: 1.6fr .9fr;
      gap: 18px;
      align-items:start;
    }

    .card{
      background: var(--card);
      border: 1px solid var(--line);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
    }
    .card-hd{
      display:flex;
      align-items:center;
      gap: 10px;
      padding: 16px 16px 12px;
      border-bottom: 1px solid var(--line);
      font-weight: 900;
      font-size: 14px;
    }
    .card-hd .mini{
      width: 28px; height: 28px;
      border-radius: 10px;
      background: #eef4ff;
      color: var(--blue);
      display:grid;
      place-items:center;
      border: 1px solid #dbe7ff;
    }
    .card-bd{
      padding: 16px;
    }

    .form-grid{
      display:grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
    }
    .field label{
      display:block;
      font-size: 12px;
      font-weight: 800;
      color: #0f172a;
      margin: 0 0 6px;
    }
    .input{
      width: 100%;
      border: 1px solid #dbe3f3;
      border-radius: 12px;
      padding: 12px 12px;
      font-size: 13px;
      background: #fff;
      outline: none;
      transition: box-shadow .15s ease, border-color .15s ease;
    }
    .input:focus{
      border-color: #b9d1ff;
      box-shadow: 0 0 0 4px rgba(37, 99, 235, .12);
    }
    .hint{
      font-size: 12px;
      color: var(--muted);
      margin-top: 8px;
    }

    /* Upload box (click + drag) */
    .drop{
      border: 2px dashed #d4e2ff;
      background: #f7fbff;
      border-radius: 16px;
      padding: 18px;
      display:flex;
      flex-direction:column;
      align-items:center;
      justify-content:center;
      gap: 8px;
      text-align:center;
      min-height: 170px;
      cursor:pointer;
      transition: transform .15s ease, border-color .15s ease, box-shadow .15s ease;
    }
    .drop:hover{
      border-color: #b9d1ff;
      transform: translateY(-1px);
      box-shadow: 0 14px 24px rgba(37, 99, 235, .10);
    }
    .drop .cloud{
      width: 56px; height: 56px;
      border-radius: 999px;
      background: #eaf2ff;
      color: var(--blue);
      display:grid;
      place-items:center;
      border: 1px solid #dbe7ff;
    }
    .drop .title{
      font-weight: 900;
      font-size: 13px;
    }
    .drop .sub{
      color: var(--muted);
      font-size: 12px;
      max-width: 380px;
    }

    .files{
      margin-top: 12px;
      display:flex;
      flex-direction:column;
      gap: 10px;
    }
    .file-pill{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap: 10px;
      border: 1px solid var(--line);
      border-radius: 14px;
      padding: 10px 12px;
      background: #fff;
    }
    .file-pill .left{
      display:flex; align-items:center; gap:10px;
      min-width: 0;
    }
    .file-pill .name{
      font-weight: 800;
      font-size: 13px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      max-width: 320px;
    }
    .file-pill .meta{
      font-size: 12px;
      color: var(--muted);
    }
    .icon-doc{
      width: 30px; height: 30px;
      border-radius: 10px;
      background: #fff7ed;
      border: 1px solid #ffe4c7;
      color: #f97316;
      display:grid; place-items:center;
      flex: 0 0 auto;
    }
    .trash{
      border: 1px solid var(--line);
      background:#fff;
      border-radius: 12px;
      padding: 8px 10px;
      cursor:pointer;
      color:#475569;
      font-weight: 900;
    }
    .trash:hover{ background:#f8fafc; }

    /* Bottom register button */
    .cta-wrap{
      display:flex;
      justify-content:center;
      margin-top: 16px;
    }
    .cta{
      min-width: 220px;
      justify-content:center;
      padding: 14px 18px;
      border-radius: 14px;
      font-size: 14px;
      font-weight: 900;
    }

    /* Right column */
    .small{
      font-size: 12px;
      color: var(--muted);
      margin-top: 2px;
      font-weight: 700;
    }

    .notice{
      margin-top: 14px;
      border-radius: 16px;
      border: 1px solid #cfe1ff;
      background: #f2f7ff;
      padding: 14px;
    }
    .notice .nt{
      display:flex;
      align-items:center;
      gap: 10px;
      font-weight: 900;
      color: #1e3a8a;
      margin-bottom: 6px;
    }
    .notice p{
      margin:0;
      font-size: 12px;
      color: #1f355d;
      line-height: 1.5;
    }

    .transfer-item{
      border: 1px solid var(--line);
      border-radius: 16px;
      padding: 12px;
      background:#fff;
      margin-top: 10px;
    }
    .transfer-item b{ font-size: 13px; }
    .transfer-item .meta{
      font-size: 12px;
      color: var(--muted);
      margin-top: 6px;
      line-height: 1.45;
      word-break: break-word;
    }
    .transfer-item .rowbtn{
      margin-top: 10px;
      display:flex;
      gap: 10px;
      align-items:center;
      justify-content:flex-end;
    }
    .mini-btn{
      padding: 10px 12px;
      border-radius: 12px;
      font-weight: 900;
      font-size: 12px;
    }

    .logbox{
      margin-top: 12px;
      background:#0b1220;
      color:#dbeafe;
      border: 1px solid rgba(255,255,255,.10);
      border-radius: 14px;
      padding: 12px;
      font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
      font-size: 12px;
      overflow:auto;
      min-height: 44px;
      white-space: pre-wrap;
    }

    @media (max-width: 980px){
      .grid{ grid-template-columns: 1fr; }
      .nav{ display:none; }
      .form-grid{ grid-template-columns: 1fr; }
      .file-pill .name{ max-width: 200px; }
    }
  </style>
</head>

<body>
  <!-- TOP BAR -->
  <header class="topbar">
    <div class="topbar-inner">
      <div style="display:flex;align-items:center;gap:12px;min-width:0;">
        <div class="brand">
          <div class="logo" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M12 2l8.5 4.9v10.2L12 22 3.5 17.1V6.9L12 2Z" stroke="white" stroke-width="1.8"/>
              <path d="M3.5 6.9 12 12l8.5-5.1" stroke="white" stroke-width="1.8"/>
              <path d="M12 12v10" stroke="white" stroke-width="1.8"/>
            </svg>
          </div>
          <span>LandRegistry DApp</span>
        </div>

        <nav class="nav">
          <a href="app.php">Dashboard</a>
          <a class="active" href="authority.php">Registrations</a>
          <a href="assets.php">NFT Assets</a>
        </nav>
      </div>

      <div class="actions">
        <button id="connect" class="btn btn-primary" type="button">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M7 7v6m10-6v6" stroke="white" stroke-width="2" stroke-linecap="round"/>
            <path d="M6 13h12v2a5 5 0 0 1-5 5h-2a5 5 0 0 1-5-5v-2Z" stroke="white" stroke-width="2" stroke-linejoin="round"/>
            <path d="M9 7V4m6 3V4" stroke="white" stroke-width="2" stroke-linecap="round"/>
          </svg>
          Connect Wallet
        </button>
      </div>
    </div>
  </header>

  <main class="wrap">
    <div class="crumb">
      <a href="app.php">Dashboard</a> &nbsp;/&nbsp; <span>Register New Land</span>
    </div>

    <div class="page-title">
      <div class="icon" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none">
          <path d="M12 2 20 6v6c0 5-3.2 9.4-8 10-4.8-.6-8-5-8-10V6l8-4Z" stroke="#2563eb" stroke-width="2" />
          <path d="M12 9v6M9 12h6" stroke="#2563eb" stroke-width="2" stroke-linecap="round"/>
        </svg>
      </div>
      <div>
        <h1>Mint New Land NFT</h1>
        <p>Securely record official property titles on the immutable ledger.</p>
      </div>
    </div>

    <section class="grid">
      <!-- LEFT COLUMN -->
      <div>
        <div class="card">
          <div class="card-hd">
            <div class="mini" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M7 3h7l3 3v15H7V3Z" stroke="#2563eb" stroke-width="2"/>
                <path d="M14 3v4h4" stroke="#2563eb" stroke-width="2"/>
              </svg>
            </div>
            Property Information
          </div>
          <div class="card-bd">
            <div class="form-grid">
              <div class="field">
                <label>Parcel ID</label>
                <input id="parcel_id" class="input" placeholder="NG-LAND-LAGOS-IKEJA-001" />
              </div>
              <div class="field">
                <label>Initial Owner Address</label>
                <input id="owner_Addr" class="input" placeholder="56-hex chars" />
              </div>
            </div>

            <div class="form-grid" style="margin-top:10px;">
              <div class="field">
                <label>Location (text)</label>
                <input id="location_text" class="input" placeholder="Ikeja, Lagos" />
              </div>
              <div class="field">
                <label>Size (sqm)</label>
                <input id="size_sqm" class="input" placeholder="350" />
              </div>
            </div>

            <div class="hint">Ensure this is the verified wallet/PKH of the primary title holder.</div>
          </div>
        </div>

        <div class="card" style="margin-top:14px;">
          <div class="card-hd">
            <div class="mini" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M12 16V7" stroke="#2563eb" stroke-width="2" stroke-linecap="round"/>
                <path d="M8.5 10.5 12 7l3.5 3.5" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M5 17a4 4 0 0 1 1-7.8A5 5 0 0 1 20 11a3.5 3.5 0 0 1-.5 7H7" stroke="#2563eb" stroke-width="2" stroke-linecap="round"/>
              </svg>
            </div>
            Metadata & Documents
          </div>
          <div class="card-bd">
            <!-- IMPORTANT: real clickable file input must be present and not blocked -->
            <input id="docs" type="file" multiple
                   accept=".pdf,image/*"
                   style="position:absolute; left:-9999px; width:1px; height:1px; opacity:0;" />

            <div id="drop" class="drop" role="button" tabindex="0" aria-label="Upload documents">
              <div class="cloud" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none">
                  <path d="M12 16V7" stroke="#2563eb" stroke-width="2" stroke-linecap="round"/>
                  <path d="M8.5 10.5 12 7l3.5 3.5" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M5 17a4 4 0 0 1 1-7.8A5 5 0 0 1 20 11a3.5 3.5 0 0 1-.5 7H7" stroke="#2563eb" stroke-width="2" stroke-linecap="round"/>
                </svg>
              </div>
              <div class="title">Click to upload or drag and drop</div>
              <div class="sub">Survey reports, deed PDF, and parcel boundary map (max. 10MB each)</div>
            </div>

            <div id="files" class="files"></div>

            <div class="cta-wrap">
              <button id="register" class="btn btn-primary cta" type="button">
                <svg viewBox="0 0 24 24" fill="none">
                  <path d="M12 2 20 6v6c0 5-3.2 9.4-8 10-4.8-.6-8-5-8-10V6l8-4Z" stroke="white" stroke-width="2"/>
                  <path d="M8.5 12.3 11 14.8l4.8-5.2" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Register Land
              </button>
            </div>

            <div id="reg_log" class="logbox"></div>
          </div>
        </div>
      </div>

      <!-- RIGHT COLUMN -->
      <aside>
        <div class="card">
          <div class="card-hd">
            <div class="mini" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M8 6h13M8 12h13M8 18h13" stroke="#2563eb" stroke-width="2" stroke-linecap="round"/>
                <path d="M3.5 6h.5M3.5 12h.5M3.5 18h.5" stroke="#2563eb" stroke-width="2" stroke-linecap="round"/>
              </svg>
            </div>
            Pending Transfers
          </div>
          <div class="card-bd">
            <div style="display:flex;align-items:center;justify-content:space-between;gap:10px;">
              <div>
                <div style="font-weight:900;">Authority Actions</div>
                <div class="small">Cosign & submit verified transfer requests.</div>
              </div>

              <button id="refresh" class="btn btn-primary mini-btn" type="button" title="Refresh transfer list">
                <svg viewBox="0 0 24 24" fill="none">
                  <path d="M20 12a8 8 0 1 1-2.3-5.7" stroke="white" stroke-width="2" stroke-linecap="round"/>
                  <path d="M20 4v6h-6" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Refresh List
              </button>
            </div>

            <div id="transfers" style="margin-top:12px;"></div>

            <div id="tx_log" class="logbox"></div>

            <div class="notice">
              <div class="nt">
                <svg viewBox="0 0 24 24" fill="none" width="18" height="18">
                  <path d="M12 22a10 10 0 1 0-10-10 10 10 0 0 0 10 10Z" stroke="#1e3a8a" stroke-width="2"/>
                  <path d="M12 10v7" stroke="#1e3a8a" stroke-width="2" stroke-linecap="round"/>
                  <path d="M12 7h.01" stroke="#1e3a8a" stroke-width="3" stroke-linecap="round"/>
                </svg>
                Admin Notice
              </div>
              <p>
                Registration and cosigning are permanent on-chain actions. Ensure parcel data
                and legal documentation are verified against official records before proceeding.
              </p>
            </div>
          </div>
        </div>
      </aside>
    </section>
  </main>

<script type="module">
  import {
    connectWallet,
    getLucid,
    computePolicyId,
    registerLandOnchain,
    authorityCosignAndSubmit,
    AUTHORITY_ADDRESS,
  } from "./land-registry.js";

  const $ = (id) => document.getElementById(id);

  const regLog = $("reg_log");
  const txLog = $("tx_log");
  const filesBox = $("files");
  const fileInput = $("docs");
  const drop = $("drop");

  let cachedPolicyId = null;
  let selectedFiles = [];

  function setLog(el, msg){ el.textContent = msg || ""; }

  function humanSize(bytes){
    if (!Number.isFinite(bytes)) return "";
    const units = ["B","KB","MB","GB"];
    let b = bytes, i = 0;
    while (b >= 1024 && i < units.length-1){ b /= 1024; i++; }
    const v = i === 0 ? String(Math.round(b)) : String(Math.round(b * 10) / 10);
    return `${v} ${units[i]}`;
  }

  function renderFiles(){
    filesBox.innerHTML = "";
    if (!selectedFiles.length) return;

    selectedFiles.forEach((f, idx) => {
      const row = document.createElement("div");
      row.className = "file-pill";
      row.innerHTML = `
        <div class="left">
          <div class="icon-doc" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" width="16" height="16">
              <path d="M7 3h7l3 3v15H7V3Z" stroke="#f97316" stroke-width="2"/>
              <path d="M14 3v4h4" stroke="#f97316" stroke-width="2"/>
            </svg>
          </div>
          <div style="min-width:0;">
            <div class="name" title="${f.name}">${f.name}</div>
            <div class="meta">${humanSize(f.size)}</div>
          </div>
        </div>
        <button class="trash" type="button" title="Remove">✕</button>
      `;
      row.querySelector(".trash").onclick = () => {
        selectedFiles.splice(idx, 1);
        renderFiles();
      };
      filesBox.appendChild(row);
    });
  }

  function addFiles(fileList){
    const incoming = Array.from(fileList || []);
    const ok = [];
    for (const f of incoming){
      if (f.size > 10 * 1024 * 1024) continue; // 10MB cap
      ok.push(f);
    }
    selectedFiles = selectedFiles.concat(ok);
    renderFiles();
  }

  // ✅ Make click-to-upload work reliably:
  // Use BOTH pointer + click, and reset input value to allow re-selecting same file.
  function openPicker(){
    fileInput.value = "";
    // some browsers require the click to be directly from a user gesture (this is)
    fileInput.click();
  }

  drop.addEventListener("pointerdown", (e) => {
    // prevent drag selecting text but keep it a real user gesture
    e.preventDefault();
  });

  drop.addEventListener("click", (e) => {
    e.preventDefault();
    openPicker();
  });

  drop.addEventListener("keydown", (e) => {
    if (e.key === "Enter" || e.key === " ") {
      e.preventDefault();
      openPicker();
    }
  });

  fileInput.addEventListener("change", (e) => {
    addFiles(e.target.files);
  });

  // Drag & drop
  ["dragenter","dragover"].forEach(evt => {
    drop.addEventListener(evt, (e) => {
      e.preventDefault();
      e.stopPropagation();
      drop.style.borderColor = "#b9d1ff";
    });
  });
  ["dragleave","drop"].forEach(evt => {
    drop.addEventListener(evt, (e) => {
      e.preventDefault();
      e.stopPropagation();
      drop.style.borderColor = "#d4e2ff";
    });
  });
  drop.addEventListener("drop", (e) => {
    addFiles(e.dataTransfer.files);
  });

  // CONNECT WALLET (no dropdown, no status button)
  $("connect").onclick = async () => {
    try {
      // If your connectWallet() previously required a wallet name,
      // update it in land-registry.js to auto-detect / use default wallet,
      // OR keep a default here:
      const info = await connectWallet("lace"); // default
      cachedPolicyId = await computePolicyId();

      // Minimal feedback in reg_log instead of a status panel/button
      setLog(regLog,
        "Wallet connected ✅\n" +
        "Address: " + info.address + "\n" +
        "My PKH: " + info.myPkh + "\n" +
        "Authority Address: " + AUTHORITY_ADDRESS + "\n" +
        "PolicyId: " + cachedPolicyId
      );
    } catch (e) {
      setLog(regLog, "Connect error: " + (e?.message || e));
    }
  };

  // REGISTER LAND
  $("register").onclick = async () => {
    const lucidInstance = getLucid();
    const parcelId = $("parcel_id").value.trim();
    const ownerAddr = $("owner_Addr").value.trim();
    const ownerPkh = lucidInstance.utils.getAddressDetails(ownerAddr).paymentCredential.hash;
    const locationText = $("location_text").value.trim();
    const sizeSqm = $("size_sqm").value.trim();

    if (!parcelId || !ownerPkh || !locationText) {
      return setLog(regLog, "Fill parcel_id, owner_pkh, location_text");
    }

    try {
      setLog(regLog, "Registering on-chain (mint + lock)...");
      const r = await registerLandOnchain({ parcelId, initialOwnerPkhHex: ownerPkh });

      setLog(regLog,
        "On-chain registered ✅\n" +
        "TxHash: " + r.txHash + "\n" +
        "Unit: " + r.unit + "\n" +
        "TokenNameHex: " + r.tokenNameHex + "\n" +
        "Script Address: " + r.landAddr
      );

      const fd = new FormData();
      fd.append("parcel_id", parcelId);
      fd.append("location_text", locationText);
      fd.append("size_sqm", sizeSqm);
      fd.append("metadata_hash", "");
      fd.append("policy_id", r.policyId);
      fd.append("token_name", r.tokenNameHex);
      fd.append("unit", r.unit);
      fd.append("authority_address", AUTHORITY_ADDRESS);
      fd.append("initial_owner_pkh", ownerPkh);
      fd.append("current_owner_pkh", ownerPkh);
      fd.append("tx_hash", r.txHash);

      for (const f of selectedFiles) fd.append("docs[]", f);

      const resp = await fetch("./register_land.php", { method: "POST", body: fd });
      const j = await resp.json();
      if (!j.ok) throw new Error(j.error || "Backend save failed");

      setLog(regLog, regLog.textContent + "\n\nSaved to DB ✅");
    } catch (e) {
      setLog(regLog, "Error: " + (e?.message || e));
    }
  };

  // PENDING TRANSFERS
  async function refreshTransfers() {
    const box = $("transfers");
    box.innerHTML = "";
    setLog(txLog, "");

    try{
      const resp = await fetch("./get_pending_transfers.php");
      const j = await resp.json();
      if (!j.ok) {
        box.textContent = "Error loading transfers";
        return;
      }

      if (!j.items?.length){
        box.innerHTML = `<div class="transfer-item">
          <b>No pending transfers</b>
          <div class="meta">When users initiate transfers, they will appear here for authority cosign.</div>
        </div>`;
        return;
      }

      for (const t of j.items) {
        const div = document.createElement("div");
        div.className = "transfer-item";
        div.innerHTML = `
          <b>#${t.id}</b> &nbsp; Parcel: <span style="font-weight:900;color:#1e3a8a">${t.parcel_id}</span>
          <div class="meta">
            <div><span style="font-weight:800;color:#0f172a">From:</span> ${t.from_pkh}</div>
            <div><span style="font-weight:800;color:#0f172a">To:</span> ${t.to_pkh}</div>
            <div><span style="font-weight:800;color:#0f172a">Status:</span> ${t.status}</div>
          </div>
          <div class="rowbtn">
            <button class="btn btn-primary mini-btn" type="button" data-id="${t.id}">
              Cosign & Submit
            </button>
          </div>
        `;

        div.querySelector("button").onclick = async () => {
          try {
            setLog(txLog, "Fetching partial tx for transfer #" + t.id + " ...");
            const r1 = await fetch("./get_transfer_cbor.php?transfer_id=" + encodeURIComponent(t.id));
            const j1 = await r1.json();
            if (!j1.ok) throw new Error(j1.error || "No cbor");

            setLog(txLog, "Cosigning + submitting...");
            const sub = await authorityCosignAndSubmit({ partialTxCbor: j1.partial_tx_cbor });

            const fd = new FormData();
            fd.append("transfer_id", t.id);
            fd.append("status", "submitted");
            fd.append("tx_hash", sub.txHash);
            fd.append("note", "Authority signed & submitted");

            const r2 = await fetch("./update_transfer_status.php", { method: "POST", body: fd });
            const j2 = await r2.json();
            if (!j2.ok) throw new Error(j2.error || "Failed update status");

            setLog(txLog, "Submitted ✅ TxHash: " + sub.txHash);
            await refreshTransfers();
          } catch (e) {
            setLog(txLog, "Error: " + (e?.message || e));
          }
        };

        box.appendChild(div);
      }
    }catch(e){
      box.textContent = "Error loading transfers";
      setLog(txLog, "Error: " + (e?.message || e));
    }
  }

  $("refresh").onclick = refreshTransfers;
  refreshTransfers();
</script>
</body>
</html>