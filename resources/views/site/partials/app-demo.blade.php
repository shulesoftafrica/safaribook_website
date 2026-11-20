{{-- Interactive replica of the Mhasibu (Virtual Accountant) app: Today, Chat, Scan, Advice. Sample data only. --}}
<style>
.appdemo{--n:#0B2347;--n2:#143A70;--g:#C9A24E;--gi:#8A6A24;--gs:#FBF6EA;--bg:#F4F6FA;--t:#0F1B2D;--m:#66717F;--ln:#E6EAF0;--ok:#1C6B3F;--oks:#E7F3EC;--wn:#B7791F;--wns:#FDF1DC;
  display:flex;flex-direction:column;align-items:center;gap:14px;font-family:Inter,"Segoe UI",sans-serif}
.appdemo *{box-sizing:border-box}
.ad-phone{width:300px;height:610px;background:#0a1a36;border-radius:42px;padding:10px;box-shadow:0 30px 60px rgba(0,0,0,.4);position:relative}
.ad-screen{width:100%;height:100%;background:var(--bg);border-radius:33px;overflow:hidden;position:relative;display:flex;flex-direction:column;color:var(--t)}
.ad-status{height:26px;flex:none;display:flex;justify-content:space-between;align-items:center;padding:0 20px;font-size:11px;font-weight:600;color:var(--t);background:var(--bg)}
.ad-notch{position:absolute;top:8px;left:50%;transform:translateX(-50%);width:76px;height:20px;background:#0a1a36;border-radius:12px;z-index:5}
.ad-body{flex:1;position:relative;overflow:hidden}
.ad-pane{position:absolute;inset:0;padding:6px 14px 10px;opacity:0;visibility:hidden;transform:translateY(8px);transition:opacity .35s,transform .35s,visibility .35s;overflow:hidden}
.ad-pane.on{opacity:1;visibility:visible;transform:none}
.ad-head{display:flex;align-items:center;gap:9px;margin:2px 0 10px}
.ad-mono{width:30px;height:30px;border-radius:9px;background:var(--n);color:var(--g);display:grid;place-items:center;font:700 15px "Source Serif 4",Georgia,serif}
.ad-head b{font:600 15px "Source Serif 4",Georgia,serif;color:var(--n);display:block;line-height:1.1}
.ad-head small{font-size:10px;color:var(--m)}
.ad-card{background:#fff;border:1px solid var(--ln);border-radius:14px;padding:11px 12px;box-shadow:0 1px 3px rgba(11,35,71,.08);margin-bottom:9px}
.ad-navy{background:linear-gradient(135deg,var(--n),var(--n2));border:0;color:#fff;padding:14px}
.ad-kick{font-size:9.5px;font-weight:600;letter-spacing:1px;color:var(--g)}
.ad-big{font:600 30px/1.1 "Source Serif 4",Georgia,serif;margin:5px 0 2px}
.ad-big small{font:400 12px Inter,sans-serif;color:#D5DEEB;margin-right:5px}
.ad-sub{font-size:10px;color:#D5DEEB}
.ad-hr{height:1px;background:rgba(255,255,255,.18);margin:10px 0}
.ad-flow{display:flex;gap:10px}.ad-flow>div{flex:1}
.ad-flow span{display:block;font-size:9.5px;color:#D5DEEB}.ad-flow strong{font:600 16px "Source Serif 4",Georgia,serif}.ad-flow em{font-style:normal;font-size:9.5px;color:#D5DEEB}
.ad-h{font:600 13.5px "Source Serif 4",Georgia,serif;color:var(--n);margin:0 0 6px}
.ad-row{display:flex;align-items:center;gap:8px;padding:6px 0;border-top:1px solid var(--ln);font-size:11px}
.ad-row:first-of-type{border-top:0}
.ad-row div{flex:1;line-height:1.3}.ad-row div small{display:block;color:var(--m);font-size:9.5px}
.ad-pill{font-size:10px;font-weight:600;color:var(--n2);background:#E9EFF8;border-radius:999px;padding:3px 10px;white-space:nowrap}
.ad-btns{display:flex;gap:8px}
.ad-b{flex:1;text-align:center;font-size:11px;font-weight:600;border-radius:10px;padding:9px 6px}
.ad-b.p{background:var(--n);color:#fff}.ad-b.o{background:#fff;border:1px solid var(--ln);color:var(--n2)}
.ad-p{font-size:11px;line-height:1.45;color:var(--t);margin:0}
/* chat */
.ad-chat{display:flex;flex-direction:column;gap:8px;padding-top:4px}
.ad-msg{max-width:88%;font-size:11.5px;line-height:1.45;padding:8px 11px;border-radius:14px;opacity:0;transform:translateY(8px);transition:.35s}
.ad-msg.in{opacity:1;transform:none}
.ad-msg.u{align-self:flex-end;background:var(--n);color:#fff;border-bottom-right-radius:4px}
.ad-msg.a{align-self:flex-start;background:#fff;border:1px solid var(--ln);border-bottom-left-radius:4px}
.ad-msg b{color:var(--n)}
.ad-chips{display:flex;flex-wrap:wrap;gap:6px;margin-top:6px}
.ad-chips span{background:#fff;border:1px solid var(--ln);border-radius:999px;font-size:10px;padding:5px 10px;color:var(--n2)}
.ad-input{position:absolute;left:12px;right:12px;bottom:8px;display:flex;gap:7px;align-items:center}
.ad-input i{flex:1;background:#fff;border:1px solid var(--ln);border-radius:999px;padding:8px 12px;font-style:normal;font-size:10.5px;color:#8590A1}
.ad-input u{width:30px;height:30px;border-radius:50%;background:var(--g);display:grid;place-items:center;text-decoration:none;color:#1b1405;font-size:13px}
/* scan */
.ad-cam{background:#16202E;border-radius:16px;height:300px;position:relative;overflow:hidden;display:grid;place-items:center}
.ad-rcpt{width:150px;background:#fff;border-radius:4px;padding:12px 10px;font:9px/1.5 "Courier New",monospace;color:#333;transform:rotate(-2deg);box-shadow:0 8px 20px rgba(0,0,0,.4)}
.ad-rcpt hr{border:0;border-top:1px dashed #bbb;margin:5px 0}
.ad-rcpt p{display:flex;justify-content:space-between;margin:0}
.ad-corner{position:absolute;width:26px;height:26px;border:3px solid var(--g)}
.ad-corner.a{top:18px;left:18px;border-right:0;border-bottom:0;border-radius:8px 0 0 0}.ad-corner.b{top:18px;right:18px;border-left:0;border-bottom:0;border-radius:0 8px 0 0}
.ad-corner.c{bottom:18px;left:18px;border-right:0;border-top:0;border-radius:0 0 0 8px}.ad-corner.d{bottom:18px;right:18px;border-left:0;border-top:0;border-radius:0 0 8px 0}
.ad-line{position:absolute;left:18px;right:18px;height:2px;background:var(--g);box-shadow:0 0 12px var(--g);top:20px}
.ad-scanning .ad-line{animation:adscan 1.8s ease-in-out infinite}
@keyframes adscan{0%,100%{top:20px}50%{top:268px}}
.ad-sub1,.ad-sub2{position:absolute;inset:0;padding:6px 14px 10px;transition:opacity .35s}
.ad-sub2{opacity:0;visibility:hidden}.ad-review .ad-sub1{opacity:0;visibility:hidden}.ad-review .ad-sub2{opacity:1;visibility:visible}
.ad-snap{margin:12px auto 0;width:52px;height:52px;border-radius:50%;border:4px solid var(--n);display:grid;place-items:center}.ad-snap i{width:34px;height:34px;border-radius:50%;background:var(--n);display:block}
.ad-lines{font-size:11px}.ad-lines p{display:flex;justify-content:space-between;margin:3px 0}
.ad-tot{display:flex;justify-content:space-between;font-weight:700;font-size:13px;border-top:1px solid var(--ln);padding-top:7px;margin-top:5px}
/* advice */
.ad-badge{display:inline-block;font-size:10px;font-weight:600;background:var(--oks);color:var(--ok);border-radius:999px;padding:3px 10px}
.ad-bars{display:flex;align-items:flex-end;gap:7px;height:78px;margin-top:8px}
.ad-bars div{flex:1;display:flex;gap:2px;align-items:flex-end;height:100%}
.ad-bars i{flex:1;border-radius:3px 3px 0 0;background:var(--n2);display:block}
.ad-pane.on .ad-bars i{animation:adgrow .9s ease-out both}
@keyframes adgrow{from{height:4%}}.ad-bars i.o{background:var(--g)}
.ad-leg{display:flex;gap:12px;font-size:9.5px;color:var(--m);margin-top:5px}.ad-leg b{display:inline-block;width:8px;height:8px;border-radius:2px;background:var(--n2);margin-right:4px}.ad-leg b.o{background:var(--g)}
.ad-kv{display:flex;justify-content:space-between;font-size:11px;padding:3px 0}.ad-kv.t{font-size:9.5px;letter-spacing:.8px;color:var(--gi);font-weight:700;padding-top:7px}
/* tab bar */
.ad-tabs{flex:none;display:flex;background:#fff;border-top:1px solid var(--ln);padding:6px 4px 14px}
.ad-tab{flex:1;border:0;background:none;display:flex;flex-direction:column;align-items:center;gap:2px;font:600 9.5px Inter,sans-serif;color:#8590A1;cursor:pointer;padding:3px 0}
.ad-tab svg{width:20px;height:20px;stroke:currentColor;fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}
.ad-tab.on{color:var(--n)}
.ad-tab.on::after{content:"";width:16px;height:3px;border-radius:2px;background:var(--g);margin-top:1px}
.ad-ctrl{display:flex;gap:8px;align-items:center;font-size:12px;color:#c9d4e6}
.ad-ctrl button{border:1px solid rgba(255,255,255,.3);background:transparent;color:#e8eef8;border-radius:999px;padding:4px 12px;font:600 11.5px Inter,sans-serif;cursor:pointer}
.ad-ctrl button.on{background:var(--g);border-color:var(--g);color:#1b1405}
.ad-note{font-size:11px;color:#a9b9d1}
@media(max-width:380px){.ad-phone{transform:scale(.92);transform-origin:top center}}
</style>

<div class="appdemo" id="appdemo" aria-label="Interactive preview of the Mhasibu Virtual Accountant app">
  <div class="ad-phone">
    <div class="ad-notch"></div>
    <div class="ad-screen">
      <div class="ad-status"><span>9:41</span><span>●●● ▮</span></div>
      <div class="ad-body">

        {{-- TODAY --}}
        <section class="ad-pane" data-pane="0">
          <div class="ad-head"><div class="ad-mono">M</div><div><b>Neema Traders</b><small data-en="Mhasibu · Virtual Accountant" data-sw="Mhasibu · Msaidizi wa Mhasibu">Mhasibu · Virtual Accountant</small></div></div>
          <div class="ad-card ad-navy">
            <div class="ad-kick" data-en="TODAY'S RESULT" data-sw="MATOKEO YA LEO">TODAY'S RESULT</div>
            <div class="ad-big"><small>TSH</small>182,500</div>
            <div class="ad-sub" data-en="Money in minus money out, for today only" data-sw="Mapato ukitoa matumizi, ya leo tu">Money in minus money out, for today only</div>
            <div class="ad-hr"></div>
            <div class="ad-flow">
              <div><span data-en="Money in today" data-sw="Iliyoingia leo">Money in today</span><strong style="color:#8FE0B5">+265,000</strong><br><em data-en="3 sales" data-sw="mauzo 3">3 sales</em></div>
              <div><span data-en="Money out today" data-sw="Iliyotoka leo">Money out today</span><strong>−82,500</strong><br><em data-en="2 vouchers" data-sw="vocha 2">2 vouchers</em></div>
            </div>
          </div>
          <div class="ad-card">
            <div class="ad-h" data-en="Needs your attention" data-sw="Inakusubiri">Needs your attention</div>
            <div class="ad-row"><div><span data-en="Biggest spend today" data-sw="Matumizi makubwa leo">Biggest spend today</span><small>Transport · 45,000</small></div><span class="ad-pill" data-en="Review" data-sw="Angalia">Review</span></div>
            <div class="ad-row"><div><span data-en="Withholding tax due to TRA" data-sw="Kodi ya zuio inadaiwa TRA">Withholding tax due to TRA</span><small data-en="Pay by the 7th" data-sw="Lipa kabla ya tarehe 7">Pay by the 7th</small></div><span class="ad-pill" data-en="Ask" data-sw="Uliza">Ask</span></div>
          </div>
          <div class="ad-card"><div class="ad-h" data-en="Today's summary" data-sw="Muhtasari wa leo">Today's summary</div>
            <p class="ad-p" data-en="Received 265,000 (3 sales); spent 82,500 (2 vouchers). Biggest spend: Transport 45,000." data-sw="Mapato 265,000 (mauzo 3); matumizi 82,500 (vocha 2). Matumizi makubwa: Transport 45,000.">Received 265,000 (3 sales); spent 82,500 (2 vouchers). Biggest spend: Transport 45,000.</p></div>
          <div class="ad-btns"><div class="ad-b p" data-en="Ask or record" data-sw="Uliza au rekodi">Ask or record</div><div class="ad-b o" data-en="Scan receipt" data-sw="Skani risiti">Scan receipt</div></div>
        </section>

        {{-- CHAT --}}
        <section class="ad-pane" data-pane="1">
          <div class="ad-head"><div class="ad-mono">M</div><div><b data-en="Ask Mhasibu" data-sw="Uliza Mhasibu">Ask Mhasibu</b><small data-en="Sample conversation" data-sw="Mazungumzo ya mfano">Sample conversation</small></div></div>
          <div class="ad-chat">
            <div class="ad-msg u" data-en="Who owes me money?" data-sw="Nani ananidai?">Who owes me money?</div>
            <div class="ad-msg a" data-en="<b>Two customers</b> owe you <b>TSh 570,000</b>: Mama Neema 320,000 (14 days) and Juma Hardware 250,000 (6 days)." data-sw="<b>Wateja wawili</b> wanakudai <b>TSh 570,000</b>: Mama Neema 320,000 (siku 14) na Juma Hardware 250,000 (siku 6).">
              <b>Two customers</b> owe you <b>TSh 570,000</b>: Mama Neema 320,000 (14 days) and Juma Hardware 250,000 (6 days).</div>
            <div class="ad-msg u" data-en="I paid 45,000 for delivery transport" data-sw="Nimelipa 45,000 kwa usafiri wa mzigo">I paid 45,000 for delivery transport</div>
            <div class="ad-msg a" data-en="Recorded <b>45,000</b> as <b>Transport</b>. Say “undo my last entry” if that was wrong." data-sw="Nimerekodi <b>45,000</b> kama <b>Transport</b>. Sema “futa rekodi ya mwisho” kama ni makosa.">Recorded <b>45,000</b> as <b>Transport</b>. Say “undo my last entry” if that was wrong.</div>
          </div>
          <div class="ad-input"><i data-en="Ask or record…" data-sw="Uliza au rekodi…">Ask or record…</i><u>➤</u></div>
        </section>

        {{-- SCAN --}}
        <section class="ad-pane" data-pane="2" id="adScan">
          <div class="ad-sub1 ad-scanning">
            <div class="ad-head"><div class="ad-mono">M</div><div><b data-en="Scan receipt" data-sw="Skani risiti">Scan receipt</b></div></div>
            <div class="ad-cam">
              <div class="ad-rcpt"><p><b>QUICK MART</b><span>14/11</span></p><hr><p><span>Diesel 20L</span><span>45,000</span></p><p><span>Parking</span><span>5,000</span></p><hr><p><b>TOTAL</b><b>50,000</b></p></div>
              <i class="ad-corner a"></i><i class="ad-corner b"></i><i class="ad-corner c"></i><i class="ad-corner d"></i><i class="ad-line"></i>
            </div>
            <div class="ad-snap"><i></i></div>
          </div>
          <div class="ad-sub2">
            <div class="ad-head"><div class="ad-mono">M</div><div><b data-en="Please check this" data-sw="Tafadhali hakiki hili">Please check this</b></div></div>
            <div class="ad-card"><div class="ad-kick" style="color:var(--gi)" data-en="WHAT I READ" data-sw="NILICHOSOMA">WHAT I READ</div>
              <div class="ad-h" style="margin:4px 0 6px">Quick Mart</div>
              <div class="ad-lines"><p><span>Diesel 20L</span><span>45,000</span></p><p><span>Parking</span><span>5,000</span></p></div>
              <div class="ad-tot"><span data-en="Total" data-sw="Jumla">Total</span><span>50,000</span></div></div>
            <div class="ad-card"><div class="ad-kick" style="color:var(--gi)" data-en="WHAT I THINK IT IS" data-sw="NAFIKIRI NI NINI">WHAT I THINK IT IS</div>
              <p class="ad-p" style="margin-top:4px" data-en="A business expense: Transport." data-sw="Matumizi ya biashara: Transport.">A business expense: Transport.</p></div>
            <div class="ad-card"><div class="ad-kick" style="color:var(--gi)" data-en="HOW YOU PAID" data-sw="ULIVYOLIPA">HOW YOU PAID</div>
              <p class="ad-p" style="margin-top:4px" data-en="Mobile money" data-sw="Pesa ya simu">Mobile money</p></div>
            <div class="ad-btns"><div class="ad-b p" data-en="Yes, save it" data-sw="Ndiyo, hifadhi">Yes, save it</div><div class="ad-b o" data-en="Not now" data-sw="Si sasa">Not now</div></div>
          </div>
        </section>

        {{-- ADVICE --}}
        <section class="ad-pane" data-pane="3">
          <div class="ad-head"><div class="ad-mono">M</div><div><b data-en="Your money health" data-sw="Afya ya fedha zako">Your money health</b></div></div>
          <div class="ad-card"><span class="ad-badge" data-en="Healthy" data-sw="Nzuri">Healthy</span>
            <div class="ad-h" style="margin-top:8px" data-en="Last 6 months" data-sw="Miezi 6 iliyopita">Last 6 months</div>
            <div class="ad-bars" id="adBars">
              <div><i style="height:48%"></i><i class="o" style="height:36%"></i></div><div><i style="height:55%"></i><i class="o" style="height:40%"></i></div><div><i style="height:52%"></i><i class="o" style="height:45%"></i></div>
              <div><i style="height:64%"></i><i class="o" style="height:44%"></i></div><div><i style="height:70%"></i><i class="o" style="height:50%"></i></div><div><i style="height:78%"></i><i class="o" style="height:52%"></i></div>
            </div>
            <div class="ad-leg"><span><b></b><span data-en="In" data-sw="Mapato">In</span></span><span><b class="o"></b><span data-en="Out" data-sw="Matumizi">Out</span></span></div>
          </div>
          <div class="ad-card"><div class="ad-h" data-en="Growth plan" data-sw="Mpango wa kukua">Growth plan</div>
            <p class="ad-p" style="color:var(--m)" data-en="Based on your last 6 months of transactions" data-sw="Kutokana na miamala yako ya miezi 6">Based on your last 6 months of transactions</p></div>
          <div class="ad-card"><div class="ad-h" data-en="Reports" data-sw="Ripoti">Reports</div>
            <div class="ad-kv t" data-en="THIS MONTH" data-sw="MWEZI HUU">THIS MONTH</div>
            <div class="ad-kv"><span data-en="Income" data-sw="Mapato">Income</span><b>4,860,000</b></div>
            <div class="ad-kv"><span data-en="Spending" data-sw="Matumizi">Spending</span><b>3,120,000</b></div>
            <div class="ad-kv"><span data-en="Result" data-sw="Matokeo">Result</span><b style="color:var(--ok)">1,740,000</b></div>
          </div>
        </section>
      </div>

      <nav class="ad-tabs" role="tablist">
        <button class="ad-tab" data-go="0" role="tab"><svg viewBox="0 0 24 24"><path d="M3 11l9-8 9 8v10h-6v-6H9v6H3z"/></svg><span data-en="Today" data-sw="Leo">Today</span></button>
        <button class="ad-tab" data-go="1" role="tab"><svg viewBox="0 0 24 24"><path d="M4 5h16v11H9l-5 4z"/></svg><span data-en="Chat" data-sw="Ongea">Chat</span></button>
        <button class="ad-tab" data-go="2" role="tab"><svg viewBox="0 0 24 24"><path d="M4 8V5h3M17 5h3v3M20 16v3h-3M7 19H4v-3M4 12h16"/></svg><span data-en="Scan" data-sw="Skani">Scan</span></button>
        <button class="ad-tab" data-go="3" role="tab"><svg viewBox="0 0 24 24"><path d="M3 17l6-6 4 4 8-9M15 6h6v6"/></svg><span data-en="Advice" data-sw="Ushauri">Advice</span></button>
      </nav>
    </div>
  </div>
  <div class="ad-ctrl"><button data-lang="en" class="on">English</button><button data-lang="sw">Kiswahili</button></div>
  <div class="ad-note" data-en="Interactive preview with sample data" data-sw="Onyesho shirikishi lenye data ya mfano">Interactive preview with sample data</div>
</div>

<script>
(function () {
  var root = document.getElementById('appdemo');
  var panes = root.querySelectorAll('.ad-pane'), tabs = root.querySelectorAll('.ad-tab');
  var scan = document.getElementById('adScan');
  var reduce = window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches;
  var cur = -1, timers = [], auto = !reduce, loop;
  var hold = [6000, 9500, 9500, 6500];

  function later(fn, ms) { timers.push(setTimeout(fn, ms)); }
  function clear() { timers.forEach(clearTimeout); timers = []; }

  function enter(i) {
    if (i === 1) {
      var m = panes[1].querySelectorAll('.ad-msg');
      m.forEach(function (x) { x.classList.toggle('in', reduce); });
      if (!reduce) m.forEach(function (x, k) { later(function () { x.classList.add('in'); }, 500 + k * 1700); });
    }
    if (i === 2) {
      scan.classList.remove('ad-review');
      scan.querySelector('.ad-sub1').classList.add('ad-scanning');
      if (!reduce) later(function () { scan.classList.add('ad-review'); }, 4200);
      else scan.classList.add('ad-review');
    }
  }

  function show(i) {
    clear(); cur = i;
    panes.forEach(function (p, k) { p.classList.toggle('on', k === i); });
    tabs.forEach(function (t, k) { t.classList.toggle('on', k === i); });
    enter(i);
    if (auto) later(function () { show((i + 1) % panes.length); }, hold[i]);
  }

  tabs.forEach(function (t) {
    t.addEventListener('click', function () { auto = false; show(parseInt(t.dataset.go, 10)); });
  });

  root.querySelectorAll('[data-lang]').forEach(function (b) {
    b.addEventListener('click', function () {
      var l = b.dataset.lang;
      root.querySelectorAll('[data-lang]').forEach(function (x) { x.classList.toggle('on', x === b); });
      root.querySelectorAll('[data-en]').forEach(function (el) { el.innerHTML = el.getAttribute('data-' + l); });
    });
  });

  show(0);
})();
</script>
