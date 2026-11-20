<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'Safaribook — Your Virtual Accountant')</title>
<meta name="description" content="@yield('description', 'Safaribook gives your business a Virtual Accountant: snap a receipt or send a WhatsApp message, and your books, invoices, payroll and reports are taken care of.')">
<meta name="theme-color" content="#0B2347">
<link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Crect width='64' height='64' rx='14' fill='%230B2347'/%3E%3Cpath d='M18 42 32 16l14 26' fill='none' stroke='%23C9A24E' stroke-width='6' stroke-linecap='round' stroke-linejoin='round'/%3E%3Cpath d='M23 42h18' stroke='%23C9A24E' stroke-width='6' stroke-linecap='round'/%3E%3C/svg%3E">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@600;700&family=Source+Serif+4:wght@600;700&display=swap" rel="stylesheet">
<style>
:root{
  --navy:#0B2347; --navy2:#143A70; --gold:#C9A24E; --gold-ink:#8A6A24; --gold-soft:#FBF6EA;
  --bg:#F4F6FA; --card:#fff; --ink:#122033; --muted:#566377; --line:#E1E6EE; --ok:#1E8E5A; --err:#C0392B;
  --radius:16px; --shadow:0 10px 30px rgba(11,35,71,.08);
}
*{box-sizing:border-box}
html{scroll-behavior:smooth}
body{margin:0;font-family:Inter,"Segoe UI",system-ui,sans-serif;color:var(--ink);background:var(--bg);line-height:1.6;-webkit-font-smoothing:antialiased}
img,svg{max-width:100%}
a{color:var(--navy2)}
.wrap{max-width:1120px;margin:0 auto;padding:0 20px}
h1,h2,h3{font-family:"Playfair Display",Georgia,serif;color:var(--navy);line-height:1.2;margin:0 0 .5em}
h1{font-size:clamp(2rem,5vw,3.2rem)} h2{font-size:clamp(1.6rem,3.4vw,2.2rem)} h3{font-size:1.15rem;font-family:Inter,sans-serif;font-weight:700}
p{margin:0 0 1em}

/* nav */
.nav{position:sticky;top:0;z-index:50;background:rgba(255,255,255,.92);backdrop-filter:blur(8px);border-bottom:1px solid var(--line)}
.nav .wrap{display:flex;align-items:center;justify-content:space-between;height:64px;gap:16px}
.brand{display:flex;align-items:center;gap:10px;text-decoration:none;color:var(--navy);font-weight:700;font-size:1.15rem}
.brand svg{width:34px;height:34px}
.nav-links{display:flex;align-items:center;gap:22px}
.nav-links a{color:var(--ink);text-decoration:none;font-size:.93rem;font-weight:500}
.nav-links a:hover{color:var(--navy2)}
@media(max-width:760px){.nav-links a.hide-sm{display:none}}

/* buttons */
.btn{display:inline-block;border:0;border-radius:999px;padding:13px 26px;font:600 1rem Inter,sans-serif;cursor:pointer;text-decoration:none;transition:.15s}
.btn-gold{background:var(--gold);color:#1b1405}
.btn-gold:hover{background:#d6b061;transform:translateY(-1px);box-shadow:0 6px 18px rgba(201,162,78,.4)}
.btn-navy{background:var(--navy);color:#fff}
.btn-navy:hover{background:var(--navy2)}
.btn-ghost{background:transparent;color:#fff;border:1.5px solid rgba(255,255,255,.5)}
.btn-ghost:hover{background:rgba(255,255,255,.1)}
.btn-sm{padding:9px 20px;font-size:.9rem}
.btn:disabled{opacity:.6;cursor:not-allowed;transform:none;box-shadow:none}

/* footer */
.foot{background:var(--navy);color:#cfd8e6;padding:44px 0 28px;margin-top:70px;font-size:.92rem}
.foot a{color:#e8d9b0;text-decoration:none}
.foot a:hover{text-decoration:underline}
.foot-grid{display:grid;grid-template-columns:2fr 1fr 1fr;gap:30px}
.foot h4{color:#fff;margin:0 0 10px;font-size:.95rem}
.foot small{display:block;margin-top:28px;padding-top:18px;border-top:1px solid rgba(255,255,255,.12);color:#93a3bb}
@media(max-width:700px){.foot-grid{grid-template-columns:1fr}}
@yield('css')
</style>
@yield('head')
</head>
<body>
<header class="nav">
  <div class="wrap">
    <a class="brand" href="/" aria-label="Safaribook home">
      <svg viewBox="0 0 64 64" aria-hidden="true"><rect width="64" height="64" rx="14" fill="#0B2347"/><path d="M18 42 32 16l14 26" fill="none" stroke="#C9A24E" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/><path d="M23 42h18" stroke="#C9A24E" stroke-width="6" stroke-linecap="round"/></svg>
      Safaribook
    </a>
    <nav class="nav-links">
      <a class="hide-sm" href="/#what">What it does</a>
      <a class="hide-sm" href="/#how">How it works</a>
      <a class="hide-sm" href="/#faq">Questions</a>
      <a class="btn btn-gold btn-sm" href="/join">Register your business</a>
    </nav>
  </div>
</header>

@yield('content')

<footer class="foot">
  <div class="wrap">
    <div class="foot-grid">
      <div>
        <h4>Safaribook</h4>
        <p>A Virtual Accountant for growing businesses. Part of the ShuleSoft family of products.</p>
      </div>
      <div>
        <h4>Get started</h4>
        <p><a href="/join">Register your business</a><br><a href="/#how">How it works</a><br><a href="/#faq">Questions</a></p>
      </div>
      <div>
        <h4>Talk to us</h4>
        <p><a href="https://wa.me/255689353642">WhatsApp +255 689 353 642</a><br><a href="mailto:support@shulesoft.africa">support@shulesoft.africa</a></p>
      </div>
    </div>
    <small>&copy; {{ date('Y') }} ShuleSoft Limited. <a href="https://shulesoft.africa/terms">Terms</a> &middot; <a href="https://shulesoft.africa/privacy">Privacy</a></small>
  </div>
</footer>
@yield('scripts')
</body>
</html>
