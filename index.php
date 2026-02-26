<?php
declare(strict_types=1);
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';

$loggedIn = is_logged_in();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= htmlspecialchars(APP_NAME) ?> – Secure Land Ownership</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@300&display=swap" rel="stylesheet"/>
  <style>
    /* ─── RESET & TOKENS ─────────────────────────────────── */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --blue:        #135bec;
      --blue-dark:   #0d47a1;
      --blue-light:  #eff4ff;
      --blue-mid:    #dbeafe;
      --slate:       #4c669a;
      --slate-light: #f6f8fc;
      --text:        #0f172a;
      --text-muted:  #64748b;
      --white:       #ffffff;
      --border:      #e2e8f0;
      --shadow-sm:   0 1px 3px rgba(0,0,0,.07), 0 1px 2px rgba(0,0,0,.05);
      --shadow-md:   0 4px 16px rgba(19,91,236,.10);
      --shadow-lg:   0 12px 40px rgba(19,91,236,.15);
      --radius-sm:   8px;
      --radius-md:   14px;
      --radius-lg:   20px;
      --radius-full: 9999px;
    }

    html { scroll-behavior: smooth; }

    body {
      font-family: 'DM Sans', sans-serif;
      color: var(--text);
      background: var(--white);
      line-height: 1.6;
      -webkit-font-smoothing: antialiased;
    }

    h1, h2, h3, h4, h5 {
      font-family: 'Plus Jakarta Sans', sans-serif;
      line-height: 1.15;
    }

    a { text-decoration: none; color: inherit; }

    img { display: block; width: 100%; height: auto; }

    .container {
      max-width: 1160px;
      margin: 0 auto;
      padding: 0 32px;
    }

    /* ─── MATERIAL ICONS ─────────────────────────────────── */
    .material-symbols-outlined {
      font-family: 'Material Symbols Outlined';
      font-weight: normal;
      font-style: normal;
      font-size: 20px;
      line-height: 1;
      letter-spacing: normal;
      text-transform: none;
      display: inline-block;
      white-space: nowrap;
      word-wrap: normal;
      direction: ltr;
      -webkit-font-feature-settings: 'liga';
      font-feature-settings: 'liga';
      -webkit-font-smoothing: antialiased;
      vertical-align: middle;
    }

    /* ─── BUTTONS ────────────────────────────────────────── */
    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-weight: 700;
      font-size: 15px;
      border: none;
      cursor: pointer;
      transition: transform .18s ease, box-shadow .18s ease, background .18s ease, border-color .18s ease;
      white-space: nowrap;
    }
    .btn:hover { transform: translateY(-2px); }
    .btn:active { transform: translateY(0); }

    .btn-primary {
      background: var(--blue);
      color: var(--white);
      padding: 11px 24px;
      border-radius: var(--radius-sm);
      box-shadow: 0 4px 14px rgba(19,91,236,.30);
    }
    .btn-primary:hover { background: var(--blue-dark); box-shadow: 0 6px 20px rgba(19,91,236,.40); }

    .btn-secondary {
      background: var(--blue-light);
      color: var(--blue);
      padding: 11px 24px;
      border-radius: var(--radius-sm);
    }
    .btn-secondary:hover { background: var(--blue-mid); }

    .btn-outline {
      background: var(--white);
      color: var(--text);
      padding: 13px 28px;
      border-radius: var(--radius-md);
      border: 2px solid var(--border);
    }
    .btn-outline:hover { border-color: var(--blue); color: var(--blue); }

    .btn-hero-primary {
      background: var(--blue);
      color: var(--white);
      padding: 15px 32px;
      border-radius: var(--radius-md);
      font-size: 16px;
      box-shadow: 0 6px 20px rgba(19,91,236,.35);
    }
    .btn-hero-primary:hover { background: var(--blue-dark); box-shadow: 0 8px 28px rgba(19,91,236,.45); }

    .btn-cta-primary {
      background: var(--blue);
      color: var(--white);
      padding: 15px 36px;
      border-radius: var(--radius-md);
      font-size: 16px;
      box-shadow: 0 6px 20px rgba(19,91,236,.30);
    }
    .btn-cta-primary:hover { background: var(--blue-dark); }

    .btn-cta-outline {
      background: var(--white);
      color: var(--text);
      padding: 13px 36px;
      border-radius: var(--radius-md);
      border: 2px solid var(--border);
      font-size: 16px;
    }
    .btn-cta-outline:hover { border-color: var(--blue); color: var(--blue); }

    /* ─── HEADER ─────────────────────────────────────────── */
    header {
      position: sticky;
      top: 0;
      z-index: 100;
      background: rgba(255,255,255,.88);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border-bottom: 1px solid var(--border);
    }

    .header-inner {
      display: flex;
      align-items: center;
      justify-content: space-between;
      height: 68px;
      gap: 24px;
    }

    .brand {
      display: flex;
      align-items: center;
      gap: 10px;
      flex-shrink: 0;
    }

    .brand-icon {
      width: 36px;
      height: 36px;
      background: var(--blue);
      border-radius: var(--radius-sm);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
    }
    .brand-icon .material-symbols-outlined { font-size: 22px; }

    .brand-name {
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-weight: 800;
      font-size: 18px;
      color: var(--text);
    }
    .brand-name span { color: var(--blue); }

    nav {
      display: flex;
      align-items: center;
      gap: 32px;
    }

    nav a {
      font-size: 14px;
      font-weight: 500;
      color: var(--text-muted);
      transition: color .15s;
    }
    nav a:hover { color: var(--blue); }

    .header-actions { display: flex; gap: 10px; align-items: center; }

    /* ─── HERO ───────────────────────────────────────────── */
    .hero {
      background: var(--white);
      overflow: hidden;
      position: relative;
    }

    /* dot grid */
    .hero::before {
      content: '';
      position: absolute;
      inset: 0;
      background-image: radial-gradient(circle, rgba(19,91,236,.07) 1px, transparent 1px);
      background-size: 28px 28px;
      pointer-events: none;
    }

    .hero-inner {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 56px;
      align-items: center;
      padding: 80px 0 90px;
      position: relative;
    }

    .hero-left { display: flex; flex-direction: column; gap: 28px; }

    .hero-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: var(--blue-light);
      border: 1px solid rgba(19,91,236,.2);
      color: var(--blue);
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-weight: 700;
      font-size: 11px;
      letter-spacing: .08em;
      text-transform: uppercase;
      padding: 6px 14px;
      border-radius: var(--radius-full);
      width: fit-content;
    }
    .hero-badge .material-symbols-outlined { font-size: 15px; }

    .hero-title {
      font-size: clamp(42px, 5.5vw, 62px);
      font-weight: 900;
      color: var(--text);
      letter-spacing: -.02em;
    }
    .hero-title .accent { color: var(--blue); }

    .hero-sub {
      font-size: 17px;
      color: var(--text-muted);
      line-height: 1.7;
      max-width: 460px;
    }

    .hero-cta { display: flex; gap: 14px; flex-wrap: wrap; align-items: center; }

    .hero-social-proof {
      display: flex;
      align-items: center;
      gap: 14px;
      padding-top: 8px;
    }

    .hero-card.has-photo{
    background: #000;
    }

    .hero-card.has-photo::before{
    content:'';
    position:absolute;
    inset:0;
    background-image: url('./assets/land.jpg'); /* <-- change path */
    background-size: cover;
    background-position: center;
    filter: saturate(1.05) contrast(1.05);
    transform: scale(1.02);
    }

    .avatars {
      display: flex;
      align-items: center;
    }
    .avatars span {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      border: 2.5px solid white;
      display: block;
      margin-left: -10px;
    }
    .avatars span:first-child { margin-left: 0; }
    .avatars span:nth-child(1) { background: #c7d7f5; }
    .avatars span:nth-child(2) { background: #a5bef0; }
    .avatars span:nth-child(3) { background: #7da5e8; }

    .social-text { font-size: 13px; color: var(--text-muted); }
    .social-text strong { color: var(--text); }

    /* ─── HERO CARD ──────────────────────────────────────── */
    .hero-right { position: relative; }

    .hero-card {
      position: relative;
      border-radius: var(--radius-lg);
      overflow: hidden;
      box-shadow: var(--shadow-lg);
      border: 1px solid rgba(19,91,236,.1);
      aspect-ratio: 4/3;
      background: linear-gradient(140deg, #e8f0fe 0%, #d5e3fb 40%, #bdd1f8 100%);
    }

    /* Land parcels SVG illustration */
    .hero-card-art {
      position: absolute;
      inset: 20px 20px 80px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .parcels-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      grid-template-rows: repeat(3, 1fr);
      gap: 8px;
      width: 88%;
      height: 88%;
      opacity: .55;
    }

    .parcel {
      background: rgba(19,91,236,.35);
      border-radius: 6px;
    }
    .parcel.tall { grid-row: span 2; }
    .parcel.wide { grid-column: span 2; }
    .parcel.highlight {
      background: rgba(19,91,236,.6);
      box-shadow: 0 0 0 2px rgba(19,91,236,.4);
    }

    /* gradient overlay */
    .hero-card::after {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(to top, rgba(19,91,236,.28) 0%, transparent 60%);
      pointer-events: none;
    }

    .hero-card-pill {
      position: absolute;
      bottom: 20px;
      left: 20px;
      right: 20px;
      z-index: 2;
      background: rgba(255,255,255,.94);
      backdrop-filter: blur(10px);
      border-radius: var(--radius-md);
      padding: 14px 18px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: 0 4px 24px rgba(0,0,0,.08);
    }

    .pill-label {
      font-size: 10px;
      font-weight: 700;
      color: var(--blue);
      text-transform: uppercase;
      letter-spacing: .08em;
      margin-bottom: 4px;
    }
    .pill-value {
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: 14px;
      font-weight: 700;
      color: var(--text);
    }
    .pill-icon { color: #22c55e; font-size: 26px; }
    .pill-icon .material-symbols-outlined { font-size: 26px; }

    /* glow blobs */
    .blob {
      position: absolute;
      border-radius: 50%;
      pointer-events: none;
    }
    .blob-1 {
      width: 180px; height: 180px;
      top: -40px; right: -40px;
      background: radial-gradient(circle, rgba(19,91,236,.12), transparent 70%);
    }
    .blob-2 {
      width: 240px; height: 240px;
      bottom: -60px; left: -60px;
      background: radial-gradient(circle, rgba(19,91,236,.07), transparent 70%);
    }

    /* ─── SECTION HEADINGS ───────────────────────────────── */
    .section-header {
      text-align: center;
      max-width: 640px;
      margin: 0 auto 56px;
    }
    .section-header h2 {
      font-size: clamp(28px, 3.5vw, 40px);
      font-weight: 800;
      margin-bottom: 16px;
      color: var(--text);
    }
    .section-header p {
      font-size: 17px;
      color: var(--text-muted);
      line-height: 1.7;
    }

    /* ─── PROBLEM / SOLUTION ─────────────────────────────── */
    .problem-section {
      background: var(--slate-light);
      padding: 96px 0;
    }

    .cards-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 24px;
    }

    .card {
      background: var(--white);
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      padding: 40px;
      transition: box-shadow .2s, transform .2s;
    }
    .card:hover { box-shadow: var(--shadow-lg); transform: translateY(-3px); }

    .card.card-solution {
      background: var(--blue-light);
      border-color: rgba(19,91,236,.2);
      box-shadow: 0 0 0 1px rgba(19,91,236,.12);
    }

    .card-icon {
      width: 56px;
      height: 56px;
      border-radius: var(--radius-md);
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 24px;
      flex-shrink: 0;
    }
    .card-icon .material-symbols-outlined { font-size: 28px; }

    .icon-red { background: #fef2f2; color: #dc2626; }
    .icon-blue { background: var(--blue); color: white; }

    .card h3 {
      font-size: 20px;
      font-weight: 800;
      margin-bottom: 14px;
      color: var(--text);
    }
    .card p {
      font-size: 15px;
      color: var(--text-muted);
      line-height: 1.7;
      margin-bottom: 24px;
    }

    .check-list { list-style: none; display: flex; flex-direction: column; gap: 12px; }
    .check-list li {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 14px;
      color: var(--text-muted);
    }
    .check-list .material-symbols-outlined { font-size: 18px; }
    .icon-x { color: #ef4444; }
    .icon-check { color: var(--blue); }

    /* ─── FEATURES ───────────────────────────────────────── */
    .features-section {
      background: var(--white);
      padding: 96px 0;
    }

    .features-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 28px;
    }

    .feature-card {
      border-radius: var(--radius-lg);
      padding: 8px;
      transition: background .2s;
    }
    .feature-card:hover { background: var(--slate-light); }

    .feature-image {
      width: 100%;
      aspect-ratio: 1;
      border-radius: var(--radius-md);
      overflow: hidden;
      margin-bottom: 20px;
      position: relative;
    }

    /* Illustrated feature images using CSS */
    .feat-img-1 {
      background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    }
    .feat-img-2 {
      background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
    }
    .feat-img-3 {
      background: linear-gradient(135deg, #dbeafe 0%, #a5f3fc 100%);
    }

    .feat-img-inner {
      position: absolute;
      inset: 0;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    /* Network nodes illustration */
    .nodes-art {
      position: relative;
      width: 120px;
      height: 120px;
    }
    .node {
      position: absolute;
      background: rgba(19,91,236,.4);
      border-radius: 50%;
    }
    .node-center { width: 24px; height: 24px; top: 50%; left: 50%; transform: translate(-50%,-50%); background: var(--blue); }
    .node-a { width: 14px; height: 14px; top: 15%; left: 20%; }
    .node-b { width: 18px; height: 18px; top: 10%; right: 15%; }
    .node-c { width: 12px; height: 12px; bottom: 20%; left: 10%; }
    .node-d { width: 16px; height: 16px; bottom: 10%; right: 20%; }
    .node-e { width: 10px; height: 10px; top: 45%; right: 8%; }

    /* Transfer arrows */
    .transfer-art {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .transfer-box {
      width: 48px; height: 48px;
      background: rgba(19,91,236,.25);
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
    }
    .transfer-box .material-symbols-outlined { font-size: 22px; color: var(--blue); }
    .transfer-arrow {
      display: flex; flex-direction: column; gap: 4px;
    }
    .transfer-arrow span {
      width: 32px; height: 3px;
      background: var(--blue);
      border-radius: 2px;
      opacity: .6;
    }
    .transfer-arrow span:nth-child(2) { opacity: .3; width: 24px; }

    /* Fractional rings */
    .rings-art {
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      width: 100px;
      height: 100px;
    }
    .ring {
      position: absolute;
      border-radius: 50%;
      border: 3px solid rgba(19,91,236,.25);
    }
    .ring-1 { width: 96px; height: 96px; }
    .ring-2 { width: 68px; height: 68px; border-color: rgba(19,91,236,.45); }
    .ring-3 { width: 40px; height: 40px; background: var(--blue); border: none; display:flex; align-items:center; justify-content:center; }
    .ring-3 .material-symbols-outlined { font-size: 18px; color: white; }

    .feature-label {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 10px;
    }
    .feature-label .material-symbols-outlined { font-size: 18px; color: var(--blue); }
    .feature-label h4 {
      font-size: 17px;
      font-weight: 700;
      color: var(--text);
    }
    .feature-card > p {
      font-size: 14px;
      color: var(--text-muted);
      line-height: 1.7;
    }

    /* ─── CTA BANNER ─────────────────────────────────────── */
    .cta-section {
      background: var(--blue-light);
      border-top: 1px solid rgba(19,91,236,.12);
      border-bottom: 1px solid rgba(19,91,236,.12);
      padding: 96px 0;
      text-align: center;
    }
    .cta-section h2 {
      font-size: clamp(28px, 3.5vw, 40px);
      font-weight: 800;
      margin-bottom: 16px;
    }
    .cta-section p {
      font-size: 17px;
      color: var(--text-muted);
      margin-bottom: 36px;
    }
    .cta-actions { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; }

    /* ─── FOOTER ─────────────────────────────────────────── */
    footer {
      background: var(--white);
      border-top: 1px solid var(--border);
      padding: 64px 0 0;
    }

    .footer-grid {
      display: grid;
      grid-template-columns: 1.6fr 1fr 1fr 1.4fr;
      gap: 48px;
      padding-bottom: 48px;
    }

    .footer-brand .brand { margin-bottom: 16px; }
    .footer-brand p { font-size: 14px; color: var(--text-muted); line-height: 1.7; }

    .footer-col h4 {
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: 14px;
      font-weight: 700;
      color: var(--text);
      margin-bottom: 20px;
    }

    .footer-links { list-style: none; display: flex; flex-direction: column; gap: 12px; }
    .footer-links a { font-size: 14px; color: var(--text-muted); transition: color .15s; }
    .footer-links a:hover { color: var(--blue); }

    .newsletter-row {
      display: flex;
      gap: 8px;
    }
    .newsletter-row input {
      flex: 1;
      background: var(--slate-light);
      border: 1px solid var(--border);
      border-radius: var(--radius-sm);
      padding: 10px 14px;
      font-size: 14px;
      font-family: 'DM Sans', sans-serif;
      color: var(--text);
      outline: none;
      transition: border-color .15s;
    }
    .newsletter-row input:focus { border-color: var(--blue); }
    .newsletter-row button {
      background: var(--blue);
      color: white;
      border: none;
      border-radius: var(--radius-sm);
      padding: 10px 18px;
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-weight: 700;
      font-size: 14px;
      cursor: pointer;
      transition: background .15s;
    }
    .newsletter-row button:hover { background: var(--blue-dark); }

    .footer-bottom {
      border-top: 1px solid var(--border);
      padding: 20px 0;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
    }
    .footer-bottom p { font-size: 13px; color: var(--text-muted); }
    .footer-legal { display: flex; gap: 24px; }
    .footer-legal a { font-size: 13px; color: var(--text-muted); transition: color .15s; }
    .footer-legal a:hover { color: var(--blue); }

    /* ─── LOGGED-IN STATE ────────────────────────────────── */
    .welcome-pill {
      background: var(--blue-light);
      border: 1px solid rgba(19,91,236,.2);
      border-radius: var(--radius-full);
      padding: 6px 16px;
      font-size: 13px;
      font-weight: 600;
      color: var(--blue);
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }
    .welcome-pill .material-symbols-outlined { font-size: 16px; }

    /* ─── RESPONSIVE ─────────────────────────────────────── */
    @media (max-width: 900px) {
      .hero-inner { grid-template-columns: 1fr; padding: 56px 0 64px; }
      .hero-right { display: none; }
      .cards-grid { grid-template-columns: 1fr; }
      .features-grid { grid-template-columns: 1fr 1fr; }
      .footer-grid { grid-template-columns: 1fr 1fr; }
      nav { display: none; }
    }

    @media (max-width: 600px) {
      .container { padding: 0 20px; }
      .features-grid { grid-template-columns: 1fr; }
      .footer-grid { grid-template-columns: 1fr; }
      .hero-title { font-size: 38px; }
      .footer-bottom { flex-direction: column; align-items: flex-start; }
    }
  </style>
</head>
<body>

<!-- ── HEADER ───────────────────────────────────────────── -->
<header>
  <div class="container">
    <div class="header-inner">
      <a href="#" class="brand">
        <div class="brand-icon">
          <span class="material-symbols-outlined">account_balance</span>
        </div>
        <span class="brand-name">LandRegistry <span>DApp</span></span>
      </a>

      <nav>
        <a href="#problem">Problem</a>
        <a href="#solution">Solution</a>
        <a href="#how-it-works">How it Works</a>
        <a href="#faq">FAQ</a>
      </nav>

      <div class="header-actions">
        <?php if (!$loggedIn): ?>
          <a href="login.php" class="btn btn-primary">Launch App</a>
          <a href="login.php" class="btn btn-secondary">Login</a>
        <?php else: ?>
          <a href="app.php" class="btn btn-primary">Launch App</a>
          <a href="logout.php" class="btn btn-secondary">Logout</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</header>

<!-- ── HERO ─────────────────────────────────────────────── -->
<section class="hero">
  <div class="container">
    <div class="hero-inner">

      <!-- Left -->
      <div class="hero-left">
        <div class="hero-badge">
          <span class="material-symbols-outlined">verified_user</span>
          Blockchain Verified Titles
        </div>

        <h1 class="hero-title">
          Secure Land<br>
          Ownership<br>
          through<br>
          <span class="accent">Blockchain</span>
        </h1>

        <p class="hero-sub">
          Revolutionizing land registry with NFT-based titles to eliminate disputes and ensure immutable, transparent ownership for everyone.
        </p>

        <div class="hero-cta">
          <?php if (!$loggedIn): ?>
            <a href="register.php" class="btn btn-hero-primary">Register Land</a>
            <a href="login.php" class="btn btn-outline">Launch DApp</a>
          <?php else: ?>
            <div class="welcome-pill">
              <span class="material-symbols-outlined">person</span>
              <?= htmlspecialchars(current_user_email() ?? '') ?>
            </div>
            <a href="app.php" class="btn btn-hero-primary">Launch DApp</a>
            <a href="logout.php" class="btn btn-outline">Logout</a>
          <?php endif; ?>
        </div>

        <div class="hero-social-proof">
          <div class="avatars">
            <span></span><span></span><span></span>
          </div>
          <p class="social-text">Trusted by over <strong>10,000+</strong> landowners worldwide</p>
        </div>
      </div>

      <!-- Right: land parcel card -->
      <div class="hero-right">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="hero-card has-photo">
        <div class="hero-card-pill">
            <div>
            <p class="pill-label">Current Transaction</p>
            <p class="pill-value">Parcel #8829-X Verified</p>
            </div>
            <div class="pill-icon">
            <span class="material-symbols-outlined">check_circle</span>
            </div>
        </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ── PROBLEM & SOLUTION ────────────────────────────────── -->
<section class="problem-section" id="problem">
  <div class="container">
    <div class="section-header">
      <h2>The Problem &amp; Our Solution</h2>
      <p>Traditional land registries are prone to fraud, lost records, and endless legal disputes. Our DApp solves this by minting every property as a unique NFT on a decentralized ledger.</p>
    </div>

    <div class="cards-grid" id="solution">
      <!-- Problem -->
      <div class="card">
        <div class="card-icon icon-red">
          <span class="material-symbols-outlined">report_problem</span>
        </div>
        <h3>The Problem: Land Disputes</h3>
        <p>Opaque paper-based records, manual data entry errors, and centralized corruption lead to conflicting claims, forgery, and decades of expensive litigation.</p>
        <ul class="check-list">
          <li>
            <span class="material-symbols-outlined icon-x">close</span>
            Forgery &amp; Title Fraud
          </li>
          <li>
            <span class="material-symbols-outlined icon-x">close</span>
            Lost Physical Documents
          </li>
          <li>
            <span class="material-symbols-outlined icon-x">close</span>
            Slow, Corrupt Bureaucracy
          </li>
        </ul>
      </div>

      <!-- Solution -->
      <div class="card card-solution">
        <div class="card-icon icon-blue">
          <span class="material-symbols-outlined">verified</span>
        </div>
        <h3>The Solution: NFT Titles</h3>
        <p>Blockchain-backed digital deeds provide instant verification and an immutable history of ownership. Once registered, the record is permanent and unchangeable.</p>
        <ul class="check-list">
          <li>
            <span class="material-symbols-outlined icon-check">check_circle</span>
            Immutable Blockchain Records
          </li>
          <li>
            <span class="material-symbols-outlined icon-check">check_circle</span>
            Instant Proof of Ownership
          </li>
          <li>
            <span class="material-symbols-outlined icon-check">check_circle</span>
            Transparent &amp; Tamper-proof
          </li>
        </ul>
      </div>
    </div>
  </div>
</section>

<!-- ── FEATURES ──────────────────────────────────────────── -->
<section class="features-section" id="how-it-works">
  <div class="container">
    <div class="section-header">
      <h2>Why Choose Decentralized Registry?</h2>
    </div>

    <div class="features-grid">

      <!-- Immutable Records -->
      <div class="feature-card">
        <div class="feature-image feat-img-1">
          <div class="feat-img-inner">
            <div class="nodes-art">
              <div class="node node-center"></div>
              <div class="node node-a"></div>
              <div class="node node-b"></div>
              <div class="node node-c"></div>
              <div class="node node-d"></div>
              <div class="node node-e"></div>
              <!-- lines via SVG -->
              <svg style="position:absolute;inset:0;width:100%;height:100%;opacity:.4" viewBox="0 0 120 120">
                <line x1="60" y1="60" x2="24" y2="18" stroke="#135bec" stroke-width="1.5"/>
                <line x1="60" y1="60" x2="96" y2="12" stroke="#135bec" stroke-width="1.5"/>
                <line x1="60" y1="60" x2="12" y2="96" stroke="#135bec" stroke-width="1.5"/>
                <line x1="60" y1="60" x2="96" y2="108" stroke="#135bec" stroke-width="1.5"/>
                <line x1="60" y1="60" x2="110" y2="54" stroke="#135bec" stroke-width="1.5"/>
              </svg>
            </div>
          </div>
        </div>
        <div class="feature-label">
          <span class="material-symbols-outlined">lock</span>
          <h4>Immutable Records</h4>
        </div>
        <p>Once recorded on the blockchain, ownership data cannot be altered, deleted, or tampered with by any central authority.</p>
      </div>

      <!-- Instant Transfer -->
      <div class="feature-card">
        <div class="feature-image feat-img-2">
          <div class="feat-img-inner">
            <div class="transfer-art">
              <div class="transfer-box">
                <span class="material-symbols-outlined">home</span>
              </div>
              <div class="transfer-arrow">
                <span></span>
                <span></span>
              </div>
              <div class="transfer-box">
                <span class="material-symbols-outlined">person</span>
              </div>
            </div>
          </div>
        </div>
        <div class="feature-label">
          <span class="material-symbols-outlined">speed</span>
          <h4>Instant Transfer</h4>
        </div>
        <p>Transfer land titles instantly to new buyers through peer-to-peer transactions, bypassing weeks of bureaucratic paperwork.</p>
      </div>

      <!-- Fractional Ownership -->
      <div class="feature-card">
        <div class="feature-image feat-img-3">
          <div class="feat-img-inner">
            <div class="rings-art">
              <div class="ring ring-1"></div>
              <div class="ring ring-2"></div>
              <div class="ring ring-3">
                <span class="material-symbols-outlined">groups</span>
              </div>
            </div>
          </div>
        </div>
        <div class="feature-label">
          <span class="material-symbols-outlined">groups</span>
          <h4>Fractional Ownership</h4>
        </div>
        <p>Tokenization allows multiple stakeholders to own shares of a property easily through pre-defined smart contracts.</p>
      </div>

    </div>
  </div>
</section>

<!-- ── CTA BANNER ───────────────────────────────────────── -->
<section class="cta-section">
  <div class="container">
    <h2>Ready to secure your property?</h2>
    <p>Join thousands of landowners who have already digitized their property deeds on the blockchain.</p>
    <div class="cta-actions">
      <?php if (!$loggedIn): ?>
        <a href="register.php" class="btn btn-cta-primary">Create Free Account</a>
        <a href="login.php" class="btn btn-cta-outline">Login</a>
      <?php else: ?>
        <a href="app.php" class="btn btn-cta-primary">Go to App</a>
        <a href="logout.php" class="btn btn-cta-outline">Logout</a>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- ── FOOTER ────────────────────────────────────────────── -->
<footer>
  <div class="container">
    <div class="footer-grid">

      <div class="footer-brand">
        <a href="#" class="brand">
          <div class="brand-icon" style="width:28px;height:28px;">
            <span class="material-symbols-outlined" style="font-size:17px;">account_balance</span>
          </div>
          <span class="brand-name" style="font-size:16px;">LandRegistry</span>
        </a>
        <p>Empowering global land ownership with secure, transparent, and immutable blockchain technology.</p>
      </div>

      <div class="footer-col">
        <h4>Product</h4>
        <ul class="footer-links">
          <li><a href="#">How it works</a></li>
          <li><a href="#">Pricing</a></li>
          <li><a href="#">Mobile App</a></li>
          <li><a href="#">Security</a></li>
        </ul>
      </div>

      <div class="footer-col">
        <h4>Resources</h4>
        <ul class="footer-links">
          <li><a href="#">Documentation</a></li>
          <li><a href="#">Legal Framework</a></li>
          <li><a href="#">Community</a></li>
          <li><a href="#">API Reference</a></li>
        </ul>
      </div>

      <div class="footer-col">
        <h4>Stay Updated</h4>
        <p style="font-size:14px;color:var(--text-muted);margin-bottom:14px;line-height:1.6;">Subscribe to our newsletter for latest updates.</p>
        <div class="newsletter-row">
          <input type="email" placeholder="Email address"/>
          <button type="button">Join</button>
        </div>
      </div>

    </div>

    <div class="footer-bottom">
      <p>© <?= date('Y') ?> LandRegistry DApp. All rights reserved.</p>
      <div class="footer-legal">
        <a href="#">Privacy Policy</a>
        <a href="#">Terms of Service</a>
        <a href="#">Cookie Policy</a>
      </div>
    </div>
  </div>
</footer>

</body>
</html>