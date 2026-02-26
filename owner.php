<?php
declare(strict_types=1);
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';
require_login();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Transfer – LandRegistry</title>

  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;700;800&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@300&display=swap" rel="stylesheet"/>

  <style>
    :root{
      --blue:#135bec;
      --blue-dark:#0d47a1;
      --blue-light:#eff4ff;
      --blue-mid:#dbeafe;
      --text:#0f172a;
      --muted:#64748b;
      --border:#e2e8f0;
      --bg:#f8fafc;
      --white:#ffffff;

      --green:#16a34a;
      --green-bg:#dcfce7;
      --amber:#d97706;
      --amber-bg:#fef3c7;
      --red:#dc2626;
      --red-bg:#fee2e2;

      --shadow: 0 10px 30px rgba(15, 23, 42, .08);
      --radius: 16px;
    }

    *{box-sizing:border-box;margin:0;padding:0}
    body{
      font-family:'DM Sans',sans-serif;
      background:var(--bg);
      color:var(--text);
      -webkit-font-smoothing:antialiased;
    }
    h1,h2,h3{font-family:'Plus Jakarta Sans',sans-serif}
    a{text-decoration:none;color:inherit}

    .material-symbols-outlined{
      font-family:'Material Symbols Outlined';
      font-weight:normal;font-style:normal;font-size:20px;
      line-height:1;display:inline-block;white-space:nowrap;
      -webkit-font-feature-settings:'liga';
      font-feature-settings:'liga';
      -webkit-font-smoothing:antialiased;
      vertical-align:middle;
    }

    .wrap{max-width:1180px;margin:26px auto;padding:0 16px}

    .page-head{
      display:flex;align-items:flex-end;justify-content:space-between;gap:14px;flex-wrap:wrap;
      margin-bottom:12px;
    }
    .page-title{font-size:24px;font-weight:800}
    .page-sub{margin-top:6px;color:var(--muted);font-size:14px}

    .header-actions{display:flex;gap:10px;align-items:center;flex-wrap:wrap}

    .btn{
      display:inline-flex;align-items:center;gap:8px;
      background:var(--blue);color:#fff;
      border:none;border-radius:12px;
      padding:12px 14px;
      font-weight:800;font-size:14px;
      cursor:pointer;
      transition:background .15s, transform .15s, box-shadow .15s;
      height:44px;
      box-shadow: 0 12px 22px rgba(19,91,236,.14);
    }
    .btn:hover{background:var(--blue-dark); transform: translateY(-1px); box-shadow: 0 16px 26px rgba(19,91,236,.18);}
    .btn.secondary{
      background:var(--blue-light);
      color:var(--blue);
      border:1px solid var(--border);
      box-shadow: none;
    }
    .btn.secondary:hover{background:var(--blue-mid); transform:none; box-shadow:none;}
    .btn:disabled{opacity:.65;cursor:not-allowed}

    .status{
      border:1px solid var(--border);
      background:var(--blue-light);
      border-radius:14px;
      padding:12px 14px;
      font-size:13px;
      color:var(--text);
      white-space:pre-wrap;
      word-break:break-word;
      margin-bottom:12px;
      display:none;
    }

    .card{
      background:var(--white);
      border:1px solid var(--border);
      border-radius:var(--radius);
      box-shadow: var(--shadow);
      padding:16px;
      margin:14px 0;
    }
    .card h3{font-size:15px;font-weight:900;margin-bottom:10px}

    label{display:block;font-size:12px;color:var(--muted);font-weight:800;margin:10px 0 6px}
    input, select{
      width:100%;
      border:1px solid var(--border);
      border-radius:12px;
      padding:12px 12px;
      font-size:14px;
      outline:none;
      background:#fff;
    }
    input:focus, select:focus{border-color:rgba(19,91,236,.55);box-shadow:0 0 0 4px rgba(19,91,236,.10)}
    .help{color:var(--muted);font-size:12px;margin-top:8px;line-height:1.35}

    .row{display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end}

    .log{
      background:#0b1220;
      color:#c7ffd1;
      border-radius:14px;
      padding:12px 14px;
      font-size:12px;
      overflow:auto;
      border:1px solid rgba(226,232,240,.18);
      min-height:80px;
      white-space:pre-wrap;
      word-break:break-word;
    }

    .steps{
      display:grid;
      grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
      gap:12px;
      margin-top:12px;
    }
    .step{
      border:1px solid var(--border);
      border-radius:14px;
      background:#fff;
      padding:12px 14px;
    }
    .step .n{font-weight:900;color:var(--blue);font-family:'Plus Jakarta Sans',sans-serif}
    .step .t{margin-top:6px;font-weight:900}
    .step .d{margin-top:4px;color:var(--muted);font-size:12px;line-height:1.35}

    /* NEW: Two-column layout */
    .grid{
      display:grid;
      grid-template-columns: 1.5fr 1fr;
      gap: 16px;
      align-items:start;
      margin-top: 10px;
    }

    /* NEW: Document preview box */
    .preview-shell{
      border: 1px solid var(--border);
      border-radius: 16px;
      overflow: hidden;
      background: #fff;
    }
    .preview-top{
      display:flex;
      justify-content:space-between;
      align-items:center;
      gap: 10px;
      padding: 12px 12px;
      border-bottom: 1px solid var(--border);
      background: linear-gradient(180deg, #ffffff, #f7fbff);
    }
    .preview-title{
      display:flex; align-items:center; gap:8px;
      font-weight: 900;
      font-size: 13px;
      color: #0f172a;
    }
    .preview-actions{
      display:flex; gap:8px; align-items:center; flex-wrap:wrap;
    }
    .mini-btn{
      display:inline-flex; align-items:center; gap:6px;
      border: 1px solid var(--border);
      background: var(--blue-light);
      color: var(--blue);
      font-weight: 900;
      font-size: 12px;
      padding: 8px 10px;
      border-radius: 12px;
      cursor: pointer;
    }
    .mini-btn:hover{ background: var(--blue-mid); }

    .preview-body{
      height: 520px;
      background: #f8fafc;
      display:flex;
      align-items:center;
      justify-content:center;
      padding: 10px;
    }
    .preview-empty{
      text-align:center;
      color: var(--muted);
      font-size: 12px;
      line-height: 1.5;
      padding: 20px;
      max-width: 420px;
    }
    .preview-frame{
      width: 100%;
      height: 100%;
      border: 0;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 10px 24px rgba(15,23,42,.06);
    }
    .preview-img{
      max-width: 100%;
      max-height: 100%;
      border-radius: 12px;
      box-shadow: 0 10px 24px rgba(15,23,42,.06);
      background:#fff;
    }
    .meta-line{
      padding: 10px 12px;
      border-top: 1px solid var(--border);
      font-size: 12px;
      color: var(--muted);
      display:flex;
      gap:10px;
      flex-wrap:wrap;
      align-items:center;
      justify-content:space-between;
      background:#fff;
    }
    .meta-line code{
      font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono","Courier New", monospace;
      font-size: 11px;
      color:#0f172a;
      background: #f1f5ff;
      border: 1px solid #dbeafe;
      padding: 2px 6px;
      border-radius: 8px;
    }

    /* NEW: Signature Status */
    .sig{
      display:flex;
      flex-direction:column;
      gap: 10px;
      margin-top: 10px;
    }
    .sig-item{
      display:flex;
      gap: 10px;
      align-items:flex-start;
      padding: 12px;
      border: 1px solid var(--border);
      border-radius: 16px;
      background: #fff;
    }
    .sig-icon{
      width: 30px; height: 30px;
      border-radius: 999px;
      display:grid;
      place-items:center;
      border: 1px solid var(--border);
      background: #fff;
      flex: 0 0 auto;
    }
    .sig-icon.ok{ background: var(--green-bg); border-color:#bbf7d0; color: var(--green); }
    .sig-icon.pending{ background: var(--amber-bg); border-color:#fde68a; color: var(--amber); }
    .sig-icon.todo{ background: #f1f5ff; border-color:#dbeafe; color: var(--blue); }

    .sig-main{ min-width:0; }
    .sig-title{ font-weight: 900; font-size: 13px; }
    .sig-sub{ font-size: 12px; color: var(--muted); margin-top: 4px; }
    .sig-hash{
      margin-top: 8px;
      font-size: 11px;
      color: #334155;
      background: #f8fafc;
      border: 1px dashed var(--border);
      padding: 6px 8px;
      border-radius: 12px;
      word-break: break-word;
    }

    @media (max-width: 980px){
      .grid{ grid-template-columns: 1fr; }
      .preview-body{ height: 420px; }
    }
  </style>
</head>

<body>
  <div class="wrap">
    <!-- Header with actions -->
    <div class="page-head">
      <div>
        <div class="page-title">Transfer Land Ownership</div>
        <div class="page-sub">
          Owner signs a transfer request, then the Authority co-signs to finalize the transfer on-chain.
        </div>
      </div>

      <div class="header-actions">
        <button class="btn secondary" id="connect" type="button">
          <span class="material-symbols-outlined">account_balance_wallet</span>
          Connect Wallet
        </button>

        <a class="btn secondary" href="app.php">
          <span class="material-symbols-outlined">dashboard</span>
          Dashboard
        </a>
      </div>
    </div>

    <!-- Subtle wallet status -->
    <div id="walletStatus" class="status"></div>

    <!-- NEW: Two-column layout -->
    <section class="grid">
      <!-- LEFT: existing transfer card (kept) -->
      <div>
        <div class="card">
          <h3>Create Transfer Request</h3>

          <label>Parcel ID</label>
          <input id="parcel_id" placeholder="NG-LAND-LAGOS-IKEJA-001" />

          <label>Policy ID (optional)</label>
          <input id="policy_id" placeholder="Leave empty to auto-fetch from registry" />

          <label>New Owner Wallet Address (bech32)</label>
          <input id="new_owner_addr" placeholder="addr_test1..." />
          <div class="help">
            Paste the receiver's wallet address (bech32). We’ll automatically derive their PKH.
          </div>

          <div class="row" style="margin-top:14px">
            <button class="btn" id="request" type="button">
              <span class="material-symbols-outlined">draw</span>
              Create + Sign Transfer
            </button>
            <button class="btn secondary" id="clear" type="button">
              <span class="material-symbols-outlined">refresh</span>
              Clear
            </button>
          </div>

          <div style="margin-top:14px">
            <div class="help" style="margin-bottom:8px;font-weight:900;color:var(--text)">Transfer Steps</div>
            <div class="steps">
              <div class="step">
                <div class="n">Step 1</div>
                <div class="t">Owner signs request</div>
                <div class="d">You (current owner) sign a partial transaction for the transfer.</div>
              </div>
              <div class="step">
                <div class="n">Step 2</div>
                <div class="t">Backend stores request</div>
                <div class="d">Request is saved for the Authority to review & co-sign.</div>
              </div>
              <div class="step">
                <div class="n">Step 3</div>
                <div class="t">Authority co-signs</div>
                <div class="d">Authority signs and submits the final transaction on-chain.</div>
              </div>
            </div>
          </div>

          <div style="margin-top:14px">
            <div class="help" style="margin-bottom:8px;font-weight:900;color:var(--text)">Output</div>
            <pre class="log" id="log">Ready.</pre>
          </div>
        </div>
      </div>

      <!-- RIGHT: Document preview + signature status -->
      <aside>
        <div class="card">
          <h3>On-Chain Legal Documents Preview</h3>

          <label style="margin-top:0">Document</label>
          <select id="doc_select">
            <option value="">Enter Parcel ID to load documents…</option>
          </select>
          <div class="help">These are the existing documents saved for this parcel (from your registry database).</div>

          <div class="preview-shell" style="margin-top:12px">
            <div class="preview-top">
              <div class="preview-title">
                <span class="material-symbols-outlined" style="font-size:18px;color:var(--blue)">description</span>
                <span id="doc_title">No document loaded</span>
              </div>
              <div class="preview-actions">
                <button class="mini-btn" id="open_doc" type="button" disabled>
                  <span class="material-symbols-outlined" style="font-size:16px">open_in_new</span>
                  Open
                </button>
              </div>
            </div>

            <div class="preview-body" id="preview_body">
              <div class="preview-empty">
                <b>Preview will appear here</b><br/>
                Enter a Parcel ID to load documents and preview them on the right.
              </div>
            </div>

            <div class="meta-line">
              <div>Type: <code id="doc_type">—</code></div>
              <div>Hash: <code id="doc_hash">—</code></div>
            </div>
          </div>

          <!-- Signature Status -->
          <div style="margin-top:14px; font-weight:900; font-size:13px;">
            Signature Status
          </div>
          <div class="sig" id="sigBox">
            <div class="sig-item">
              <div class="sig-icon todo"><span class="material-symbols-outlined" style="font-size:18px">person</span></div>
              <div class="sig-main">
                <div class="sig-title">Current Owner (Not signed)</div>
                <div class="sig-sub">Sign the transfer request from the left panel.</div>
                <div class="sig-hash" id="sig_owner">—</div>
              </div>
            </div>

            <div class="sig-item">
              <div class="sig-icon pending"><span class="material-symbols-outlined" style="font-size:18px">admin_panel_settings</span></div>
              <div class="sig-main">
                <div class="sig-title">Land Authority (Pending)</div>
                <div class="sig-sub">Authority will review and co-sign to submit on-chain.</div>
                <div class="sig-hash" id="sig_auth">—</div>
              </div>
            </div>

            <div class="sig-item">
              <div class="sig-icon todo"><span class="material-symbols-outlined" style="font-size:18px">person_check</span></div>
              <div class="sig-main">
                <div class="sig-title">New Owner (Ready)</div>
                <div class="sig-sub">New owner will see the asset after Authority submits and DB ownership updates.</div>
                <div class="sig-hash" id="sig_new">—</div>
              </div>
            </div>
          </div>

        </div>
      </aside>
    </section>

  </div>

<script type="module">
  import { connectWallet, ownerRequestTransferOnchain, getLucid } from "./land-registry.js";

  const $ = (id) => document.getElementById(id);
  const log = (msg) => $("log").textContent = msg;

  let mePkh = null;
  let meAddr = null;

  let loadedDocs = [];   // [{doc_type,file_path,file_hash,uploaded_at}]
  let activeDoc = null;  // selected doc object

  function setWalletStatus(msg) {
    const el = $("walletStatus");
    el.style.display = "block";
    el.textContent = msg;
  }

  function tryAddressToPkh(addr) {
    const lucid = getLucid();
    const details = lucid.utils.getAddressDetails(addr);
    if (!details.paymentCredential || details.paymentCredential.type !== "Key") {
      throw new Error("New owner address must be a normal wallet address (payment key), not a script address.");
    }
    return details.paymentCredential.hash;
  }

  function safeExt(path){
    const p = (path || "").toLowerCase();
    const i = p.lastIndexOf(".");
    if (i === -1) return "";
    return p.slice(i+1);
  }

  function renderPreview(doc){
    activeDoc = doc || null;

    $("doc_title").textContent = doc ? (doc.doc_type || "Document") : "No document loaded";
    $("doc_type").textContent  = doc ? (doc.doc_type || "—") : "—";
    $("doc_hash").textContent  = doc && doc.file_hash ? (String(doc.file_hash).slice(0,16) + "…") : "—";

    const openBtn = $("open_doc");
    openBtn.disabled = !doc || !doc.file_path;
    openBtn.onclick = () => {
      if (!doc || !doc.file_path) return;
      window.open(doc.file_path, "_blank");
    };

    const body = $("preview_body");
    body.innerHTML = "";

    if (!doc || !doc.file_path){
      body.innerHTML = `
        <div class="preview-empty">
          <b>No document loaded</b><br/>
          Enter a Parcel ID to load documents. Then select one to preview.
        </div>
      `;
      return;
    }

    const ext = safeExt(doc.file_path);

    // PDF -> iframe
    if (ext === "pdf"){
      const iframe = document.createElement("iframe");
      iframe.className = "preview-frame";
      iframe.src = doc.file_path;
      iframe.title = "Document Preview";
      body.appendChild(iframe);
      return;
    }

    // Images -> img
    if (["png","jpg","jpeg","webp","gif"].includes(ext)){
      const img = document.createElement("img");
      img.className = "preview-img";
      img.src = doc.file_path;
      img.alt = "Document Preview";
      body.appendChild(img);
      return;
    }

    // Other -> fallback
    body.innerHTML = `
      <div class="preview-empty">
        <b>Preview not supported for this file type</b><br/>
        Click <b>Open</b> to view it in a new tab.
      </div>
    `;
  }

  function populateDocSelect(){
    const sel = $("doc_select");
    sel.innerHTML = "";
    if (!loadedDocs.length){
      const opt = document.createElement("option");
      opt.value = "";
      opt.textContent = "No documents found for this parcel.";
      sel.appendChild(opt);
      renderPreview(null);
      return;
    }

    loadedDocs.forEach((d, idx) => {
      const opt = document.createElement("option");
      opt.value = String(idx);
      const label = `${d.doc_type || "document"} • ${String(d.uploaded_at || "").slice(0,10) || "date"}`;
      opt.textContent = label;
      sel.appendChild(opt);
    });

    sel.onchange = () => {
      const i = Number(sel.value);
      const doc = Number.isFinite(i) ? loadedDocs[i] : null;
      renderPreview(doc);
    };

    sel.value = "0";
    renderPreview(loadedDocs[0]);
  }

  async function fetchDocsForParcel(parcelId){
    loadedDocs = [];
    populateDocSelect();

    if (!parcelId) return;

    // Try 1) parcel.php (if it returns documents)
    try{
      const r = await fetch("./parcel.php?parcel_id=" + encodeURIComponent(parcelId));
      const j = await r.json();

      // Accept either j.documents or j.land.documents depending on your implementation
      const docs =
        (j && Array.isArray(j.documents) ? j.documents :
        (j && j.land && Array.isArray(j.land.documents) ? j.land.documents : null));

      if (docs){
        loadedDocs = docs;
        populateDocSelect();
        return;
      }
    }catch(e){ /* ignore and fallback */ }

    // Try 2) fallback endpoint
    try{
      const r2 = await fetch("./get_parcel_docs.php?parcel_id=" + encodeURIComponent(parcelId));
      const j2 = await r2.json();
      if (j2 && j2.ok && Array.isArray(j2.items)){
        loadedDocs = j2.items;
        populateDocSelect();
        return;
      }
    }catch(e){ /* ignore */ }

    // Nothing worked
    loadedDocs = [];
    populateDocSelect();
  }

  // Signature status helpers
  function setSig(ownerLine, authLine, newLine){
    $("sig_owner").textContent = ownerLine || "—";
    $("sig_auth").textContent  = authLine || "—";
    $("sig_new").textContent   = newLine || "—";
  }

  // Debounce parcelId typing
  let tmr = null;
  $("parcel_id").addEventListener("input", () => {
    const pid = $("parcel_id").value.trim();
    clearTimeout(tmr);
    tmr = setTimeout(() => fetchDocsForParcel(pid), 450);
  });

  $("connect").onclick = async () => {
    try {
      $("connect").disabled = true;
      $("connect").innerHTML =
        '<span class="material-symbols-outlined">hourglass_top</span> Connecting...';

      const info = await connectWallet();

      meAddr = info.address;
      mePkh  = info.myPkh || info.pkh || info.myPkhHex || null;

      if (!mePkh) {
        const lucid = getLucid();
        const details = lucid.utils.getAddressDetails(meAddr);
        mePkh = details.paymentCredential.hash;
      }

      setWalletStatus("Connected ✅  |  Address: " + meAddr + "  |  PKH: " + mePkh);

      $("connect").innerHTML =
        '<span class="material-symbols-outlined">check_circle</span> Connected';

      // Update signature panel baseline
      setSig(
        "Owner PKH: " + mePkh,
        "Pending authority signature",
        "Ready"
      );
    } catch (e) {
      setWalletStatus("Error: " + (e?.message || e));
      $("connect").disabled = false;
      $("connect").innerHTML =
        '<span class="material-symbols-outlined">account_balance_wallet</span> Connect Wallet';
    }
  };

  $("clear").onclick = () => {
    $("parcel_id").value = "";
    $("policy_id").value = "";
    $("new_owner_addr").value = "";
    log("Ready.");
    loadedDocs = [];
    populateDocSelect();
    setSig("—","—","—");
  };

  $("request").onclick = async () => {
    const parcelId = $("parcel_id").value.trim();
    let policyId = $("policy_id").value.trim();
    const newOwnerAddr = $("new_owner_addr").value.trim();

    if (!parcelId || !newOwnerAddr) return log("Fill parcel_id and new_owner_addr (bech32).");
    if (!mePkh || !meAddr) return log("Connect wallet first.");

    try {
      const newOwnerPkh = tryAddressToPkh(newOwnerAddr);

      if (!policyId) {
        const r = await fetch("./parcel.php?parcel_id=" + encodeURIComponent(parcelId));
        const j = await r.json();
        if (!j.ok || !j.land) throw new Error("Parcel not found in registry DB. Ask authority to register it.");
        policyId = j.land.policy_id;
      }

      log(
        "Building transfer tx (owner signs only)...\n" +
        "Parcel: " + parcelId + "\n" +
        "From (your PKH): " + mePkh + "\n" +
        "To (new owner PKH): " + newOwnerPkh + "\n" +
        "PolicyId: " + policyId
      );

      const tx = await ownerRequestTransferOnchain({
        parcelId,
        newOwnerPkhHex: newOwnerPkh,
        policyId
      });

      // Update signature UI
      setSig(
        "Owner signed ✅\nPKH: " + mePkh,
        "Awaiting authority signature…",
        "New owner PKH: " + newOwnerPkh
      );

      log("Signed partial tx ✅\nSending to backend for authority cosign...");

      const fd = new FormData();
      fd.append("parcel_id", parcelId);
      fd.append("from_pkh", mePkh);
      fd.append("to_pkh", newOwnerPkh);
      fd.append("to_addr", newOwnerAddr);
      fd.append("partial_tx_cbor", tx.partialTxCbor);

      const resp = await fetch("./request_transfer.php", { method: "POST", body: fd });
      const j2 = await resp.json();
      if (!j2.ok) throw new Error(j2.error || "Backend request failed");

      log(
        "Transfer request created ✅\n" +
        "Transfer ID: " + j2.transfer_id + "\n\n" +
        "Next: tell Authority to review & co-sign the transfer."
      );
    } catch (e) {
      log("Error: " + (e?.message || e));
    }
  };

  // Initial preview state
  populateDocSelect();
  renderPreview(null);
</script>
</body>
</html>