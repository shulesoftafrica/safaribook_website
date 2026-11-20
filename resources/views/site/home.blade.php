@extends('site.layout')

@section('title', 'Safaribook — Your Virtual Accountant')

@section('css')
.hero{background:linear-gradient(135deg,var(--navy) 0%,var(--navy2) 100%);color:#fff;padding:70px 0 80px;position:relative;overflow:hidden}
.hero::after{content:"";position:absolute;right:-120px;top:-120px;width:420px;height:420px;border-radius:50%;background:radial-gradient(circle,rgba(201,162,78,.28),transparent 70%)}
.hero .wrap{display:grid;grid-template-columns:1.1fr .9fr;gap:48px;align-items:center;position:relative;z-index:1}
.hero h1{color:#fff}
.hero h1 em{font-style:normal;color:var(--gold)}
.hero p.lead{font-size:1.15rem;color:#d7e0ee;max-width:540px}
.hero .cta{display:flex;gap:12px;flex-wrap:wrap;margin-top:26px}
.hero .note{margin-top:16px;font-size:.88rem;color:#a9b9d1}
@media(max-width:880px){.hero .wrap{grid-template-columns:1fr}.hero{padding:48px 0 56px}}

/* phone mock */
.phone{background:#0a1a36;border-radius:34px;padding:12px;max-width:340px;margin:0 auto;box-shadow:0 30px 60px rgba(0,0,0,.35)}
.screen{background:#ECE5DD;border-radius:24px;overflow:hidden}
.chat-head{background:var(--navy);color:#fff;padding:14px 16px;display:flex;align-items:center;gap:10px;font-weight:600;font-size:.95rem}
.avatar{width:34px;height:34px;border-radius:50%;background:var(--gold);color:#1b1405;display:grid;place-items:center;font-weight:700}
.chat-head small{display:block;font-weight:400;opacity:.75;font-size:.75rem}
.chat{padding:14px 12px 18px;display:flex;flex-direction:column;gap:9px;min-height:330px}
.msg{max-width:84%;padding:9px 12px;border-radius:12px;font-size:.84rem;line-height:1.45;box-shadow:0 1px 1px rgba(0,0,0,.08)}
.msg.me{align-self:flex-end;background:#DCF8C6;border-top-right-radius:3px;color:#163020}
.msg.bot{align-self:flex-start;background:#fff;border-top-left-radius:3px;color:#1c2430}
.msg b{color:var(--navy)}
.msg .tag{display:inline-block;margin-top:5px;background:var(--gold-soft);color:var(--gold-ink);border:1px solid #ecdcae;border-radius:6px;padding:1px 7px;font-size:.72rem;font-weight:600}

/* sections */
section{padding:70px 0}
.sec-head{text-align:center;max-width:680px;margin:0 auto 42px}
.sec-head p{color:var(--muted);font-size:1.05rem}
.eyebrow{display:inline-block;color:var(--gold-ink);background:var(--gold-soft);border:1px solid #ecdcae;border-radius:999px;padding:3px 14px;font-size:.78rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;margin-bottom:14px}
.grid{display:grid;gap:20px}
.g3{grid-template-columns:repeat(3,1fr)} .g2{grid-template-columns:repeat(2,1fr)} .g4{grid-template-columns:repeat(4,1fr)}
@media(max-width:900px){.g3,.g4{grid-template-columns:repeat(2,1fr)}}
@media(max-width:600px){.g3,.g2,.g4{grid-template-columns:1fr}}
.card{background:var(--card);border:1px solid var(--line);border-radius:var(--radius);padding:24px;box-shadow:var(--shadow)}
.card .ico{width:44px;height:44px;border-radius:12px;background:var(--gold-soft);border:1px solid #ecdcae;display:grid;place-items:center;font-size:1.3rem;margin-bottom:14px}
.card p{color:var(--muted);margin:0;font-size:.95rem}
.card ul{margin:10px 0 0;padding-left:18px;color:var(--muted);font-size:.93rem}
.card li{margin:3px 0}

.steps{counter-reset:s}
.step{position:relative;padding-top:8px}
.step .n{width:42px;height:42px;border-radius:50%;background:var(--navy);color:var(--gold);display:grid;place-items:center;font-weight:700;margin-bottom:14px}
.step p{color:var(--muted);font-size:.95rem}

.band{background:#fff;border-top:1px solid var(--line);border-bottom:1px solid var(--line)}
.two{display:grid;grid-template-columns:1fr 1fr;gap:36px;align-items:start}
@media(max-width:800px){.two{grid-template-columns:1fr}}
.ticks{list-style:none;padding:0;margin:0}
.ticks li{padding:7px 0 7px 30px;position:relative;color:var(--ink)}
.ticks li::before{content:"✓";position:absolute;left:0;top:7px;width:20px;height:20px;border-radius:50%;background:var(--navy);color:var(--gold);font-size:.75rem;display:grid;place-items:center;font-weight:700}
.ticks.human li::before{content:"•";background:#e8ecf3;color:var(--navy2)}

.cta-band{background:linear-gradient(135deg,var(--navy),var(--navy2));color:#fff;border-radius:24px;padding:48px 28px;text-align:center}
.cta-band h2{color:#fff}
.cta-band p{color:#d7e0ee;max-width:560px;margin:0 auto 22px}

details{background:#fff;border:1px solid var(--line);border-radius:12px;padding:16px 20px;margin-bottom:12px}
summary{cursor:pointer;font-weight:600;color:var(--navy)}
details p{margin:10px 0 0;color:var(--muted)}
@endsection

@section('content')
<section class="hero" style="padding-bottom:80px">
  <div class="wrap">
    <div>
      <h1>Your books, kept for you by a <em>Virtual Accountant</em></h1>
      <p class="lead">Send a receipt, a sale or a quick message. Safaribook records it, sorts it, chases late payers, runs payroll and gives you clear reports, so you can run the business instead of the paperwork.</p>
      <div class="cta">
        <a class="btn btn-gold" href="/join">Register your business</a>
        <a class="btn btn-ghost" href="#how">See how it works</a>
      </div>
      <p class="note">Built for small and growing businesses. No accounting background needed.</p>
    </div>

    <div class="phone" aria-label="Example conversation with the Virtual Accountant">
      <div class="screen">
        <div class="chat-head"><div class="avatar">M</div><div>Mhasibu<small>Your Virtual Accountant</small></div></div>
        <div class="chat">
          <div class="msg me">Bought stationery for the office, 45,000 cash 📎</div>
          <div class="msg bot">Got it. I read the receipt: <b>TSh 45,000</b>, Stationery. Was this for the office or the shop?<br><span class="tag">Receipt scanned</span></div>
          <div class="msg me">Office</div>
          <div class="msg bot">Recorded under <b>Office expenses</b>. ✅<br>Mama Neema still owes you <b>TSh 320,000</b> (14 days). Shall I send her a friendly reminder?</div>
          <div class="msg me">Yes please</div>
          <div class="msg bot">Done. I'll send your daily summary tonight.</div>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="what">
  <div class="wrap">
    <div class="sec-head">
      <span class="eyebrow">What it does</span>
      <h2>The accounting work nobody wants to do, done</h2>
      <p>Your Virtual Accountant does the work of a clerk, a bookkeeper, a reporter and a first-level adviser, and checks with you when it is unsure.</p>
    </div>
    <div class="grid g3">
      <div class="card"><div class="ico">🧾</div><h3>Records everything</h3><p>Sales, expenses and asset purchases are captured from receipts, messages, uploads and your sales system, then categorised automatically.</p></div>
      <div class="card"><div class="ico">📤</div><h3>Invoices &amp; reminders</h3><p>Tell it what was sold and it prepares the invoice. It tracks who owes you and follows up politely until you are paid.</p></div>
      <div class="card"><div class="ico">💬</div><h3>Asks when unsure</h3><p>"Was this stationery for the office or the shop?" A short question keeps your books right, without you opening a spreadsheet.</p></div>
      <div class="card"><div class="ico">👥</div><h3>Payroll &amp; payslips</h3><p>Monthly payroll with statutory deductions, advances and allowances. It confirms any extras with you before salaries go out.</p></div>
      <div class="card"><div class="ico">📊</div><h3>Reports you can read</h3><p>Daily cash summary, profit &amp; loss, balance sheet, cash flow, budget vs actual and VAT reports, ready when you need them.</p></div>
      <div class="card"><div class="ico">🧭</div><h3>Plain-language advice</h3><p>"You spent 20% more on utilities this month." Profitability insights, credit-policy tips and loan-affordability guidance.</p></div>
    </div>
  </div>
</section>

<section id="how" class="band">
  <div class="wrap">
    <div class="sec-head">
      <span class="eyebrow">How it works</span>
      <h2>Three simple steps</h2>
      <p>Use the tools you already use every day. There is nothing complicated to learn.</p>
    </div>
    <div class="grid g3 steps">
      <div class="step"><div class="n">1</div><h3>Send it</h3><p>Message on WhatsApp, snap a receipt in the Mhasibu app, upload a file or statement, or connect your Lineshop sales.</p></div>
      <div class="step"><div class="n">2</div><h3>It's sorted</h3><p>The Virtual Accountant reads it, classifies it, flags duplicates and missing receipts, and posts the entry to your books.</p></div>
      <div class="step"><div class="n">3</div><h3>You stay informed</h3><p>Get daily summaries, due-date alerts and monthly statements. Ask it questions in chat, any time.</p></div>
    </div>
  </div>
</section>

<section>
  <div class="wrap">
    <div class="sec-head">
      <span class="eyebrow">Everything covered</span>
      <h2>From the first sale to the monthly report</h2>
    </div>
    <div class="grid g2">
      <div class="card"><h3>Income</h3>
        <ul>
          <li>Record sales from Lineshop or from your sales agents</li>
          <li>Create and send customer invoices from a simple instruction</li>
          <li>Track receivables and follow up on late payments</li>
          <li>Record bank, mobile-money and WhatsApp payment receipts</li>
        </ul></div>
      <div class="card"><h3>Expenses &amp; assets</h3>
        <ul>
          <li>Categorise daily expenses from receipts and invoices</li>
          <li>Petty cash tracking with float reconciliation</li>
          <li>Fixed-asset purchases and depreciation schedules</li>
          <li>Staff cash requests held within the budget you set</li>
        </ul></div>
      <div class="card"><h3>Payroll &amp; staff</h3>
        <ul>
          <li>Monthly payroll with PAYE, NSSF and SDL calculations</li>
          <li>Allowances, deductions, bonuses, advances and loans</li>
          <li>Payslips and payroll summaries</li>
        </ul></div>
      <div class="card"><h3>Reports, tax &amp; advice</h3>
        <ul>
          <li>Profit &amp; loss, balance sheet and cash-flow statements</li>
          <li>Budget vs actual and VAT reports</li>
          <li>Draft tax calculations for your accountant to review</li>
          <li>Guidance on credit terms, borrowing and cost control</li>
        </ul></div>
    </div>
  </div>
</section>

<section class="band">
  <div class="wrap two">
    <div>
      <span class="eyebrow">Capture it anywhere</span>
      <h2>Works where your business already happens</h2>
      <ul class="ticks">
        <li><b>WhatsApp</b>: text, voice-note style instructions, photos of receipts</li>
        <li><b>Mhasibu app</b>: scan receipts, review what was recorded, chat with your accountant</li>
        <li><b>Lineshop</b>: sales flow straight into your books</li>
        <li><b>File upload</b>: bank and mobile-money statements, attachments, proofs of payment</li>
      </ul>
    </div>
    <div>
      <span class="eyebrow">Honest about limits</span>
      <h2>Automation, with people still in charge</h2>
      <p style="color:var(--muted)">The Virtual Accountant takes over the routine so your accountant can focus on judgment. These stay human:</p>
      <ul class="ticks human">
        <li>Strategy, expansion and investment decisions</li>
        <li>Loan and supplier negotiations</li>
        <li>Regulatory filings, audits and disputes</li>
        <li>Deciding whether an anomaly is fraud or just an error</li>
      </ul>
    </div>
  </div>
</section>

<section>
  <div class="wrap">
    <div class="sec-head">
      <span class="eyebrow">Who it's for</span>
      <h2>Made for businesses that are busy growing</h2>
    </div>
    <div class="grid g4">
      <div class="card"><h3>Shops &amp; retail</h3><p>Sales, stock-linked costs and daily cash in one view.</p></div>
      <div class="card"><h3>Service businesses</h3><p>Invoice clients and collect on time without awkward chasing.</p></div>
      <div class="card"><h3>Restaurants &amp; pharmacies</h3><p>Many small daily expenses, sorted automatically.</p></div>
      <div class="card"><h3>Growing companies</h3><p>Payroll, VAT and monthly statements without hiring a full team.</p></div>
    </div>
  </div>
</section>

<section id="faq" class="band">
  <div class="wrap" style="max-width:780px">
    <div class="sec-head"><span class="eyebrow">Questions</span><h2>Things people ask</h2></div>
    <details><summary>Do I need to know accounting?</summary><p>No. You send the information the way you normally would, and the Virtual Accountant asks plain questions when it needs to be sure. Setup takes about three minutes.</p></details>
    <details><summary>What happens after I register?</summary><p>We verify your phone with a one-time code, then take you to your own business page at <b>yourname.safaribook.africa</b> to finish setup: business type, currency and your chart of accounts.</p></details>
    <details><summary>Can my own accountant still use it?</summary><p>Yes. Your accountant can review everything that was recorded, handle filings and decisions that need a human, and work from the same books.</p></details>
    <details><summary>Is my data safe?</summary><p>Your business data stays in your own books and is not shared with other businesses. We only use the contact details you give us to verify your registration and support you.</p></details>
    <details><summary>Which countries and currencies?</summary><p>You can choose TZS, KES, UGX, USD, EUR or GBP as your business currency. Some tax features, such as PAYE, NSSF and SDL, follow Tanzanian rules.</p></details>
    <details><summary>How do I get help?</summary><p>WhatsApp or call +255 689 353 642, or email support@shulesoft.africa.</p></details>
  </div>
</section>

<section style="padding-top:20px">
  <div class="wrap">
    <div class="cta-band">
      <h2>Give your business a Virtual Accountant</h2>
      <p>Register in a couple of minutes. We'll verify your number and take you straight to your business setup.</p>
      <a class="btn btn-gold" href="/join">Register your business</a>
    </div>
  </div>
</section>
@endsection
