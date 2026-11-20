@extends('site.layout')

@section('title', 'Verify your number — Safaribook')

@section('css')
.otp-wrap{min-height:60vh;display:grid;place-items:center;padding:50px 0 10px}
.otp-card{background:#fff;border:1px solid var(--line);border-radius:20px;box-shadow:var(--shadow);padding:40px 34px;max-width:470px;width:100%;text-align:center}
.otp-card h1{font-size:1.7rem}
.otp-card p{color:var(--muted)}
.digits{display:flex;gap:9px;justify-content:center;margin:24px 0}
.digits input{width:48px;height:58px;text-align:center;font:700 1.5rem Inter,sans-serif;border:2px solid var(--line);border-radius:10px;color:var(--navy)}
.digits input:focus{outline:none;border-color:var(--navy2);box-shadow:0 0 0 3px rgba(20,58,112,.12)}
@media(max-width:400px){.digits input{width:40px;height:50px;font-size:1.2rem}.otp-card{padding:30px 18px}}
.msg{display:none;border-radius:10px;padding:12px 14px;margin-bottom:16px;font-size:.9rem;text-align:left}
.msg.show{display:block}
.msg.e{background:#fff5f5;border:1px solid #f1c6c0;color:#a5281b}
.msg.s{background:#f0faf4;border:1px solid #c3e6d0;color:#1c6b43}
.alt{margin-top:18px;font-size:.9rem;color:var(--muted)}
.alt a{color:var(--navy2);font-weight:600;cursor:pointer}
.alt a.off{color:#9aa6b8;pointer-events:none;text-decoration:none}
@endsection

@section('content')
<div class="wrap otp-wrap">
  <div class="otp-card">
    <h1>Check your phone</h1>
    <p>Enter the 6-digit code we sent to <b>{{ $phone }}</b>@if($email) and <b>{{ preg_replace('/(^.).*(@.*$)/', '$1***$2', $email) }}</b>@endif. It is valid for 15 minutes.</p>

    <div class="msg" id="msg"></div>

    <form id="otpForm" autocomplete="off">
      @csrf
      <div class="digits" id="digits">
        @for($i = 0; $i < 6; $i++)
          <input type="text" inputmode="numeric" maxlength="1" pattern="\d*" aria-label="Digit {{ $i + 1 }}" @if($i === 0) autocomplete="one-time-code" autofocus @endif>
        @endfor
      </div>
      <button class="btn btn-navy" id="verifyBtn" type="submit" style="width:100%" disabled>Verify and continue</button>
    </form>

    <div class="alt">
      Didn't get it? <a id="resend" class="off">Resend code</a><span id="count"></span>
      @if($email)<br><a id="resendEmail">Send to my email instead</a>@endif
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
(function () {
  var inputs = Array.prototype.slice.call(document.querySelectorAll('#digits input'));
  var btn = document.getElementById('verifyBtn');
  var msg = document.getElementById('msg');
  var token = document.querySelector('meta[name="csrf-token"]').content;

  function show(text, ok) { msg.textContent = text; msg.className = 'msg show ' + (ok ? 's' : 'e'); }
  function code() { return inputs.map(function (i) { return i.value; }).join(''); }
  function sync() { btn.disabled = code().length !== 6; }

  inputs.forEach(function (el, i) {
    el.addEventListener('input', function () {
      el.value = el.value.replace(/\D/g, '').slice(-1);
      if (el.value && inputs[i + 1]) inputs[i + 1].focus();
      sync();
    });
    el.addEventListener('keydown', function (e) { if (e.key === 'Backspace' && !el.value && inputs[i - 1]) inputs[i - 1].focus(); });
    el.addEventListener('paste', function (e) {
      var t = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, 6);
      if (!t) return;
      e.preventDefault();
      t.split('').forEach(function (d, k) { if (inputs[k]) inputs[k].value = d; });
      (inputs[Math.min(t.length, 5)]).focus(); sync();
    });
  });

  function post(url, body) {
    return fetch(url, {
      method: 'POST',
      headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
      body: JSON.stringify(body || {})
    }).then(function (r) {
      return r.json().catch(function () {
        return { status: 'error', message: r.status === 429 ? 'Too many attempts. Please wait a minute.' : (r.status === 419 ? 'Your session expired. Please refresh the page.' : 'Something went wrong. Please try again.') };
      });
    });
  }

  document.getElementById('otpForm').addEventListener('submit', function (e) {
    e.preventDefault();
    if (code().length !== 6) return;
    btn.disabled = true; btn.textContent = 'Verifying…';
    post('/verify', { otp: code() }).then(function (res) {
      if (res.status === 'success') { show(res.message, true); setTimeout(function () { window.location.href = res.redirect; }, 1200); return; }
      show(res.message || 'Could not verify. Please try again.', false);
      btn.textContent = 'Verify and continue'; sync();
    }).catch(function () { show('Network problem. Please try again.', false); btn.textContent = 'Verify and continue'; sync(); });
  });

  var resend = document.getElementById('resend'), count = document.getElementById('count'), left = 0, timer;
  function cooldown(s) {
    left = s; resend.classList.add('off');
    clearInterval(timer);
    timer = setInterval(function () {
      left--; count.textContent = left > 0 ? ' in ' + left + 's' : '';
      if (left <= 0) { clearInterval(timer); resend.classList.remove('off'); }
    }, 1000);
    count.textContent = ' in ' + s + 's';
  }
  function doResend(channel) {
    post('/verify/resend', { channel: channel }).then(function (res) { show(res.message, res.status === 'success'); });
    cooldown(45);
  }
  resend.addEventListener('click', function () { if (!resend.classList.contains('off')) doResend('both'); });
  var em = document.getElementById('resendEmail');
  if (em) em.addEventListener('click', function () { doResend('email'); });
  cooldown(45);
})();
</script>
@endsection
