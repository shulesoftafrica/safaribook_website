<!DOCTYPE html>
<html lang="{{ $lang }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=540, initial-scale=1">
<title>Safaribook demo video stage</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@600;700&family=Source+Serif+4:wght@600;700&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box}
html,body{margin:0;width:540px;height:960px;overflow:hidden;background:#0B2347;font-family:Inter,sans-serif}
#stage{position:relative;width:540px;height:960px;background:radial-gradient(circle at 85% 8%,rgba(201,162,78,.28),transparent 45%),linear-gradient(160deg,#0B2347,#143A70);color:#fff;overflow:hidden}
.brand{position:absolute;top:26px;left:0;right:0;display:flex;justify-content:center;align-items:center;gap:10px;font-weight:700;font-size:20px;letter-spacing:.2px}
.brand svg{width:30px;height:30px}
#cap{position:absolute;top:84px;left:28px;right:28px;text-align:center;height:128px;transition:opacity .4s}
#cap h1{font:700 33px/1.15 "Playfair Display",Georgia,serif;margin:0 0 8px;color:#fff}
#cap p{margin:0;font-size:17px;color:#d7e0ee;line-height:1.35}
#cap.hide{opacity:0}
#phoneWrap{position:absolute;left:50%;top:214px;transform:translateX(-50%) scale(1.12);transform-origin:top center;width:320px}
.ad-ctrl,.ad-note{display:none!important}
.foot{position:absolute;bottom:22px;left:0;right:0;text-align:center;font-size:17px;font-weight:600;color:#C9A24E;letter-spacing:.4px}
.card{position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;padding:40px;background:linear-gradient(160deg,#0B2347,#143A70);opacity:0;visibility:hidden;transition:opacity .5s,visibility .5s;z-index:20}
.card.on{opacity:1;visibility:visible}
.card .logo{width:96px;height:96px;margin-bottom:26px}
.card h2{font:700 46px/1.15 "Playfair Display",Georgia,serif;margin:0 0 16px}
.card h2 em{font-style:normal;color:#C9A24E}
.card p{font-size:21px;color:#d7e0ee;margin:0;line-height:1.4}
.card .cta{margin-top:34px;background:#C9A24E;color:#1b1405;font-weight:700;font-size:22px;border-radius:999px;padding:16px 34px}
.card .url{margin-top:22px;font-size:26px;font-weight:700;color:#fff;letter-spacing:.3px}
#tap{position:absolute;width:46px;height:46px;margin:-23px 0 0 -23px;border-radius:50%;background:rgba(201,162,78,.55);border:3px solid #C9A24E;opacity:0;pointer-events:none;z-index:15}
#tap.go{animation:tap .7s ease-out}
@keyframes tap{0%{opacity:0;transform:scale(.4)}30%{opacity:1;transform:scale(1)}100%{opacity:0;transform:scale(1.5)}}
.ad-sub2 .ad-b.p.pressed{transform:scale(.94);background:#143A70}
#saved{position:absolute;left:14px;right:14px;bottom:78px;background:#1C6B3F;color:#fff;border-radius:12px;padding:10px 12px;font-size:11.5px;font-weight:600;text-align:center;opacity:0;transform:translateY(10px);transition:.35s;z-index:6}
#saved.on{opacity:1;transform:none}
</style>
</head>
<body>
<script>
// Recording mode (#record): replace timers and CSS animation clocks with a manual clock, so each video frame is
// rendered at an exact time no matter how long the screenshot takes.
if (location.hash === '#record') {
  (function () {
    var now = 0, seq = 0, timers = [], real = { st: window.setTimeout, ct: window.clearTimeout };
    window.setTimeout = function (fn, ms) { var id = ++seq; timers.push({ id: id, at: now + (ms || 0), fn: fn }); return id; };
    window.clearTimeout = function (id) { timers = timers.filter(function (t) { return t.id !== id; }); };
    var starts = new WeakMap();
    window.__advance = function (dt) {
      var end = now + dt, fired = null;
      for (;;) {
        timers.sort(function (a, b) { return a.at - b.at || a.id - b.id; });
        if (!timers.length || timers[0].at > end) break;
        var t = timers.shift(); now = Math.max(now, t.at); fired = now; t.fn();
      }
      now = end;
      document.getAnimations().forEach(function (a) {
        if (!starts.has(a)) { starts.set(a, fired === null ? now : fired); a.pause(); }
        a.currentTime = now - starts.get(a);
      });
    };
  })();
}
</script>
<div id="stage">
  <div class="brand"><svg viewBox="0 0 64 64"><rect width="64" height="64" rx="14" fill="#fff"/><path d="M18 42 32 16l14 26" fill="none" stroke="#C9A24E" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/><path d="M23 42h18" stroke="#C9A24E" stroke-width="6" stroke-linecap="round"/></svg>Safaribook</div>
  <div id="cap" class="hide"><h1></h1><p></p></div>
  <div id="phoneWrap">@include('site.partials.app-demo')</div>
  <div class="foot">safaribook.africa</div>
  <i id="tap"></i>

  <div class="card on" id="title">
    <svg class="logo" viewBox="0 0 64 64"><rect width="64" height="64" rx="14" fill="#fff"/><path d="M18 42 32 16l14 26" fill="none" stroke="#C9A24E" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/><path d="M23 42h18" stroke="#C9A24E" stroke-width="6" stroke-linecap="round"/></svg>
    <h2 id="t1"></h2><p id="t2"></p>
  </div>
  <div class="card" id="end">
    <h2 id="e1"></h2><p id="e2"></p><div class="cta" id="e3"></div><div class="url">safaribook.africa</div>
  </div>
</div>

<script>
(function () {
  var LANG = @json($lang);
  var T = {
    en: {
      t1: 'Meet your <em>Virtual Accountant</em>', t2: 'The Mhasibu app by Safaribook',
      c: [
        ['Your day at a glance', 'Money in and money out, in one number'],
        ['Snap a receipt', 'Point the camera. No typing.'],
        ['Check what it read', 'Nothing is saved until you say yes'],
        ['One tap, it is in your books', 'Saved and sorted for you'],
        ['Just ask', 'Who owes me? What did I spend?'],
        ['Know where you stand', 'Your money health, reports and plain advice']
      ],
      saved: 'Saved. The receipt is in your books.',
      e1: 'Your books, <em>kept for you</em>', e2: 'Register your business in two minutes', e3: 'Register your business'
    },
    sw: {
      t1: 'Kutana na <em>Msaidizi wako wa Mhasibu</em>', t2: 'Programu ya Mhasibu kutoka Safaribook',
      c: [
        ['Siku yako kwa muhtasari', 'Mapato na matumizi ya leo katika namba moja'],
        ['Piga picha ya risiti', 'Elekeza kamera. Huhitaji kuandika.'],
        ['Hakiki alichosoma', 'Hakuna kinachohifadhiwa hadi useme ndiyo'],
        ['Mguso mmoja, imeingia kwenye vitabu', 'Imehifadhiwa na kupangwa kwa ajili yako'],
        ['Uliza tu', 'Nani ananidai? Nimetumia nini?'],
        ['Jua hali yako', 'Afya ya fedha zako, ripoti na ushauri rahisi']
      ],
      saved: 'Imehifadhiwa. Risiti iko kwenye vitabu vyako.',
      e1: 'Vitabu vyako, <em>tunavitunza</em>', e2: 'Sajili biashara yako kwa dakika mbili', e3: 'Sajili biashara yako'
    }
  }[LANG];

  var $ = function (s) { return document.querySelector(s); };
  var stage = $('#stage'), cap = $('#cap');
  $('#t1').innerHTML = T.t1; $('#t2').textContent = T.t2;
  $('#e1').innerHTML = T.e1; $('#e2').textContent = T.e2; $('#e3').textContent = T.e3;
  $('#saved') || (function () { var d = document.createElement('div'); d.id = 'saved'; d.textContent = T.saved; document.querySelector('.ad-screen').appendChild(d); })();

  if (LANG === 'sw') document.querySelector('[data-lang="sw"]').click();

  function setCap(i) {
    cap.classList.add('hide');
    setTimeout(function () {
      cap.querySelector('h1').textContent = T.c[i][0];
      cap.querySelector('p').textContent = T.c[i][1];
      cap.classList.remove('hide');
    }, 350);
  }
  function tapAt(el) {
    var r = el.getBoundingClientRect(), s = stage.getBoundingClientRect(), t = $('#tap');
    t.style.left = (r.left + r.width / 2 - s.left) + 'px';
    t.style.top = (r.top + r.height / 2 - s.top) + 'px';
    t.classList.remove('go'); void t.offsetWidth; t.classList.add('go');
  }
  var tabs = document.querySelectorAll('.ad-tab');
  function goTab(i, delay) { tapAt(tabs[i]); setTimeout(function () { tabs[i].click(); }, delay || 350); }

  var at = function (ms, fn) { setTimeout(fn, ms); };

  window.__start = function () {
  at(100, function () { tabs[0].click(); });                 // stop the demo's own autoplay
  at(3200, function () { $('#title').classList.remove('on'); setCap(0); });
  at(8400, function () { setCap(1); goTab(2); });            // scan: camera, flips to review after 4.2s
  at(13000, function () { setCap(2); });
  at(16400, function () {
    var b = document.querySelector('.ad-sub2 .ad-b.p'); tapAt(b);
    at(350, function () { b.classList.add('pressed'); });
    at(900, function () { $('#saved').classList.add('on'); setCap(3); });
  });
  at(20200, function () { $('#saved').classList.remove('on'); setCap(4); goTab(1); });
  at(30200, function () { setCap(5); goTab(3); });
  at(36200, function () { $("#end").classList.add("on"); });
  };
  window.__done = 40500;
})();
</script>
</body>
</html>
