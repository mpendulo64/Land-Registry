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
  <title>Verify Property – LandRegistry</title>

  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;700;800&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@300&display=swap" rel="stylesheet"/>

  <style>
    :root{
      --blue:#135bec;
      --blue-dark:#0d47a1;
      --blue-light:#eff4ff;
      --border:#e2e8f0;
      --bg:#f8fafc;
      --white:#ffffff;
      --text:#0f172a;
      --muted:#64748b;
      --green:#16a34a;
      --green-bg:#dcfce7;
      --red:#dc2626;
      --red-bg:#fee2e2;
      --amber:#d97706;
      --amber-bg:#fef3c7;
    }

    *{box-sizing:border-box;margin:0;padding:0}
    body{
      font-family:'DM Sans',sans-serif;
      background:var(--bg);
      color:var(--text);
      -webkit-font-smoothing:antialiased;
    }
    h1,h2,h3{font-family:'Plus Jakarta Sans',sans-serif}
    a{color:var(--blue);text-decoration:none}
    a:hover{color:var(--blue-dark)}

    .material-symbols-outlined{
      font-family:'Material Symbols Outlined';
      font-weight:normal;font-style:normal;font-size:20px;
      line-height:1;display:inline-block;white-space:nowrap;
      -webkit-font-feature-settings:'liga';
      font-feature-settings:'liga';
      -webkit-font-smoothing:antialiased;
      vertical-align:middle;
    }

    .wrap{max-width:980px;margin:34px auto;padding:0 16px}
    .header{
      display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;
      margin-bottom:18px;
    }
    .title h1{font-size:24px;font-weight:800}
    .title p{margin-top:6px;color:var(--muted);font-size:14px}

    .card{
      background:var(--white);
      border:1px solid var(--border);
      border-radius:16px;
      box-shadow:0 8px 28px rgba(19,91,236,.08);
      padding:18px;
      margin:14px 0;
    }

    .row{display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end}
    .field{flex:1;min-width:240px}
    label{display:block;font-size:12px;color:var(--muted);font-weight:600;margin-bottom:6px}
    input{
      width:100%;
      border:1px solid var(--border);
      border-radius:12px;
      padding:12px 12px;
      font-size:14px;
      outline:none;
      background:#fff;
    }
    input:focus{border-color:rgba(19,91,236,.55);box-shadow:0 0 0 4px rgba(19,91,236,.10)}
    .btn{
      display:inline-flex;align-items:center;gap:8px;
      background:var(--blue);color:#fff;
      border:none;border-radius:12px;
      padding:12px 14px;
      font-weight:700;font-size:14px;
      cursor:pointer;
      transition:background .15s;
      height:44px;
    }
    .btn:hover{background:var(--blue-dark)}
    .btn.secondary{
      background:var(--blue-light);
      color:var(--blue);
      border:1px solid var(--border);
    }
    .btn.secondary:hover{background:#dbeafe}

    .hint{
      display:flex;align-items:center;gap:8px;
      margin-top:10px;
      color:var(--muted);
      font-size:13px;
    }

    .status{
      display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;
      padding:14px;
      border-radius:14px;
      border:1px solid var(--border);
      margin-top:12px;
    }
    .status.good{background:var(--green-bg);border-color:#bbf7d0}
    .status.bad{background:var(--red-bg);border-color:#fecaca}
    .status.warn{background:var(--amber-bg);border-color:#fde68a}

    .status-left{display:flex;align-items:center;gap:10px}
    .status-icon{
      width:36px;height:36px;border-radius:12px;
      display:flex;align-items:center;justify-content:center;
      background:rgba(0,0,0,.06);
    }
    .status.good .status-icon{background:rgba(22,163,74,.12);color:var(--green)}
    .status.bad .status-icon{background:rgba(220,38,38,.12);color:var(--red)}
    .status.warn .status-icon{background:rgba(217,119,6,.12);color:var(--amber)}

    .status-title{font-weight:800;font-family:'Plus Jakarta Sans',sans-serif}
    .status-sub{color:var(--muted);font-size:13px;margin-top:2px}

    .grid{
      display:grid;
      grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
      gap:14px;
      margin-top:14px;
    }
    .mini{
      background:var(--blue-light);
      border:1px solid #dbeafe;
      border-radius:14px;
      padding:14px;
    }
    .mini .k{font-size:12px;color:var(--muted);font-weight:700}
    .mini .v{margin-top:6px;font-weight:800;font-size:13px;word-break:break-word}
    .copy{
      margin-top:10px;
      display:inline-flex;align-items:center;gap:6px;
      border:none;border-radius:10px;
      padding:8px 10px;
      background:#fff;
      border:1px solid var(--border);
      cursor:pointer;
      font-weight:700;
      font-size:12px;
      color:var(--text);
    }
    .copy:hover{border-color:rgba(19,91,236,.45);background:rgba(19,91,236,.06)}

    .docs{margin-top:12px}
    .doc{
      display:flex;align-items:center;justify-content:space-between;gap:10px;
      padding:12px 12px;
      border:1px solid var(--border);
      border-radius:12px;
      margin-top:10px;
      background:#fff;
    }
    .doc:hover{background:var(--blue-light);border-color:#dbeafe}
    .doc-left{display:flex;align-items:center;gap:10px}
    .pill{
      font-size:11px;font-weight:800;letter-spacing:.04em;
      background:var(--blue);color:#fff;
      padding:4px 10px;border-radius:999px;
    }
    .doc small{display:block;color:var(--muted);margin-top:2px}
    details{margin-top:14px}
    summary{cursor:pointer;font-weight:800;color:var(--blue)}
    pre{
      margin-top:10px;
      background:#0b1220;
      color:#c7ffd1;
      padding:12px;
      border-radius:12px;
      overflow:auto;
      font-size:12px;
      border:1px solid rgba(226,232,240,.2);
    }

    .footer-note{
      color:var(--muted);
      font-size:12px;
      margin-top:10px;
      line-height:1.4;
    }
  </style>
</head>

<body>
<div class="wrap">

  <div class="header">
    <div class="title">
      <h1>Verify Property Ownership</h1>
      <p>Enter a Parcel ID to check if it exists on-chain and confirm the recorded owner & documents.</p>
    </div>
    <a href="app.php" class="btn secondary">
      <span class="material-symbols-outlined">dashboard</span>
      Dashboard
    </a>
  </div>

  <div class="card">
    <div class="row">
      <div class="field">
        <label>Parcel ID</label>
        <input id="parcel_id" placeholder="NG-LAND-LAGOS-IKEJA-001" />
      </div>
      <button class="btn" id="verify">
        <span class="material-symbols-outlined">verified</span>
        Verify
      </button>
    </div>

    <div class="hint">
      <span class="material-symbols-outlined" style="font-size:18px;color:var(--muted)">info</span>
      Tip: Verification checks <b>on-chain</b> ownership (NFT at script) and <b>backend</b> documents/history.
    </div>

    <!-- Status -->
    <div id="statusBox" class="status warn" style="display:none">
      <div class="status-left">
        <div class="status-icon"><span class="material-symbols-outlined">hourglass_top</span></div>
        <div>
          <div class="status-title" id="statusTitle">Waiting</div>
          <div class="status-sub" id="statusSub">Enter a Parcel ID and click Verify.</div>
        </div>
      </div>
      <div id="statusRight"></div>
    </div>

    <!-- Results -->
    <div id="results" style="display:none">
      <div class="grid">
        <div class="mini">
          <div class="k">On-chain Owner (PKH)</div>
          <div class="v" id="ownerPkh">—</div>
          <button class="copy" id="copyOwner">
            <span class="material-symbols-outlined" style="font-size:16px">content_copy</span>
            Copy
          </button>
        </div>

        <div class="mini">
          <div class="k">Policy ID</div>
          <div class="v" id="policyId">—</div>
          <button class="copy" id="copyPolicy">
            <span class="material-symbols-outlined" style="font-size:16px">content_copy</span>
            Copy
          </button>
        </div>

        <div class="mini">
          <div class="k">Asset Unit</div>
          <div class="v" id="unit">—</div>
          <button class="copy" id="copyUnit">
            <span class="material-symbols-outlined" style="font-size:16px">content_copy</span>
            Copy
          </button>
        </div>

        <div class="mini">
          <div class="k">Location (Backend)</div>
          <div class="v" id="location">—</div>
        </div>
      </div>

      <div class="docs">
        <h3 style="font-family:'Plus Jakarta Sans';margin-top:16px;">Documents</h3>
        <div id="docsList"></div>
        <div id="docsEmpty" class="footer-note" style="display:none;">
          No documents were found for this parcel.
        </div>
      </div>

      <details>
        <summary>Advanced details (for developers)</summary>
        <pre id="advanced"></pre>
      </details>

      <div class="footer-note">
        ✅ <b>Verified</b> means: the parcel exists on-chain and the registry has corresponding backend records.
        Always confirm the signer authority during transfers.
      </div>
    </div>
  </div>

</div>

<script type="module">
  import { connectWallet, getOnchainOwnerForParcel } from "./land-registry.js";

  const safeStringify = (obj) =>
  JSON.stringify(obj, (_k, v) => (typeof v === "bigint" ? v.toString() : v), 2);

  const $ = (id) => document.getElementById(id);

  const statusBox   = $("statusBox");
  const statusTitle = $("statusTitle");
  const statusSub   = $("statusSub");
  const statusRight = $("statusRight");
  const results     = $("results");

  const setStatus = (type, title, sub, rightHtml = "") => {
    statusBox.style.display = "flex";
    statusBox.classList.remove("good","bad","warn");
    statusBox.classList.add(type);
    statusTitle.textContent = title;
    statusSub.textContent = sub;
    statusRight.innerHTML = rightHtml;
    const icon = statusBox.querySelector(".status-icon span");
    icon.textContent =
      type === "good" ? "check_circle" :
      type === "bad"  ? "cancel" :
      "hourglass_top";
  };

  const setCopy = (btn, textProvider) => {
    btn.onclick = async () => {
      const txt = textProvider();
      if (!txt || txt === "—") return;
      await navigator.clipboard.writeText(txt);
      btn.innerHTML = `<span class="material-symbols-outlined" style="font-size:16px">done</span> Copied`;
      setTimeout(() => {
        btn.innerHTML = `<span class="material-symbols-outlined" style="font-size:16px">content_copy</span> Copy`;
      }, 900);
    };
  };

  // Try connect for read-mode (still needs wallet enabled in browser)
  try {
    await connectWallet("lace");
  } catch {
    setStatus("warn", "Wallet not connected", "Click Verify after connecting a wallet in your browser (Nami/Lace).");
  }

  setCopy($("copyOwner"), () => $("ownerPkh").textContent);
  setCopy($("copyPolicy"), () => $("policyId").textContent);
  setCopy($("copyUnit"),  () => $("unit").textContent);

  $("verify").onclick = async () => {
    results.style.display = "none";
    $("docsList").innerHTML = "";
    $("docsEmpty").style.display = "none";

    const parcelId = $("parcel_id").value.trim();
    if (!parcelId) {
      setStatus("warn", "Parcel ID required", "Please enter a Parcel ID (e.g. NG-LAND-LAGOS-IKEJA-001).");
      return;
    }

    try {
      setStatus("warn", "Checking…", "Searching on-chain and registry records for this parcel…",
        `<span style="color:var(--muted);font-size:12px;font-weight:700">Please wait</span>`
      );

      const onchain = await getOnchainOwnerForParcel({ parcelId });
      if (!onchain) {
        setStatus("bad", "Not found on-chain", "This Parcel ID was not found on the blockchain. It may not be registered.");
        return;
      }

      const backend = await fetch("./parcel.php?parcel_id=" + encodeURIComponent(parcelId)).then(r => r.json());

      // Expect backend to include land + docs; if your parcel.php differs, this still shows raw in advanced.
      const land = backend?.land || backend?.item || backend || {};
      const docs = backend?.documents || backend?.docs || [];

      // Fill UI
      $("ownerPkh").textContent = onchain.ownerPkh || "—";
      $("policyId").textContent = land.policy_id || "—";
      $("unit").textContent = land.unit || "—";
      $("location").textContent = land.location_text || "—";

      // Documents list
      if (Array.isArray(docs) && docs.length) {
        const html = docs.map(d => {
          const type = (d.doc_type || "document").toString();
          const path = (d.file_path || "").toString();
          const hash = (d.file_hash || "").toString();
          return `
            <div class="doc">
              <div class="doc-left">
                <span class="pill">${type.toUpperCase()}</span>
                <div>
                  <div style="font-weight:800">${type}</div>
                  <small>Hash: ${hash ? (hash.slice(0,18)+"…") : "—"}</small>
                </div>
              </div>
              ${path ? `<a class="btn secondary" style="height:auto;padding:8px 12px;border-radius:10px" target="_blank" href="${path}">
                <span class="material-symbols-outlined" style="font-size:18px">open_in_new</span> Open
              </a>` : ""}
            </div>
          `;
        }).join("");
        $("docsList").innerHTML = html;
      } else {
        $("docsEmpty").style.display = "block";
      }

      // Advanced raw view
      $("advanced").textContent =
        "ON-CHAIN:\n" + safeStringify(onchain) +
        "\n\nBACKEND:\n" + safeStringify(backend);

      results.style.display = "block";
      setStatus("good", "Verified", "This parcel exists on-chain and registry data was found.");

    } catch (e) {
      console.error(e);
      setStatus("bad", "Verification failed", (e?.message || e || "Unknown error").toString());
    }
  };
</script>
</body>
</html>