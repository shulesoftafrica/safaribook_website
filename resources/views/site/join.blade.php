@extends('site.layout')

@section('title', 'Register your business — Safaribook')
@section('description', 'Register your business on Safaribook and get your own Virtual Accountant.')

@section('head')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.19/build/css/intlTelInput.css">
@endsection

@section('css')
.page{padding:40px 0 10px}
.form-card{background:#fff;border:1px solid var(--line);border-radius:20px;box-shadow:var(--shadow);padding:34px;max-width:760px;margin:0 auto}
@media(max-width:600px){.form-card{padding:22px 18px}}
.form-card h1{font-size:clamp(1.6rem,4vw,2.2rem);margin-bottom:6px}
.sub{color:var(--muted);margin-bottom:24px}
fieldset{border:0;padding:0;margin:0 0 26px}
legend{font-weight:700;color:var(--navy);font-size:1.05rem;padding:0;margin-bottom:12px;border-bottom:2px solid var(--gold-soft);width:100%;padding-bottom:6px}
.row{display:grid;grid-template-columns:1fr 1fr;gap:16px}
@media(max-width:600px){.row{grid-template-columns:1fr}}
.field{margin-bottom:14px}
label{display:block;font-weight:600;font-size:.9rem;margin-bottom:5px}
label .req{color:var(--err)}
input[type=text],input[type=email],input[type=tel],select{width:100%;padding:11px 13px;border:1.5px solid var(--line);border-radius:10px;font:inherit;background:#fff;color:var(--ink)}
input:focus,select:focus{outline:none;border-color:var(--navy2);box-shadow:0 0 0 3px rgba(20,58,112,.12)}
.hint{display:block;color:var(--muted);font-size:.8rem;margin-top:4px}
.field.bad input,.field.bad select{border-color:var(--err)}
.err{display:none;color:var(--err);font-size:.8rem;margin-top:4px}
.field.bad .err{display:block}
.iti{width:100%}
.addr{display:flex;align-items:center;border:1.5px solid var(--line);border-radius:10px;overflow:hidden;background:#fff}
.addr:focus-within{border-color:var(--navy2);box-shadow:0 0 0 3px rgba(20,58,112,.12)}
.addr input{border:0;border-radius:0;box-shadow:none!important}
.addr span{padding:0 13px;background:var(--bg);color:var(--muted);font-size:.9rem;align-self:stretch;display:flex;align-items:center;border-left:1.5px solid var(--line);white-space:nowrap}
.checks{display:flex;flex-wrap:wrap;gap:10px 24px}
.chk{display:flex;gap:8px;align-items:flex-start;font-weight:500;font-size:.93rem;cursor:pointer}
.chk input{margin-top:4px;accent-color:var(--navy)}
.alert{display:none;border-radius:10px;padding:13px 16px;margin-bottom:18px;font-size:.92rem;background:#fff5f5;border:1px solid #f1c6c0;color:#a5281b}
.alert.show{display:block}
.hp{position:absolute!important;left:-9999px!important;opacity:0;height:0;width:0}
.fine{font-size:.82rem;color:var(--muted);text-align:center;margin-top:14px}
@endsection

@section('content')
<div class="page"><div class="wrap">
  <div class="form-card">
    <h1>Register your business</h1>
    <p class="sub">Tell us about your business. It takes about two minutes. We'll verify your phone with a one-time code, then you finish setup on your own Safaribook page.</p>

    <div class="alert" id="alert" role="alert"></div>

    <form id="joinForm" novalidate>
      @csrf
      <input type="text" name="website_hp" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true">
      <input type="hidden" name="form_rendered_at" value="{{ time() }}">

      <fieldset>
        <legend>Your business</legend>
        <div class="field" id="f-business_name">
          <label for="business_name">Business name <span class="req">*</span></label>
          <input type="text" id="business_name" name="business_name" maxlength="150" autocomplete="organization" placeholder="e.g. Neema Traders Ltd">
          <span class="err">Please enter your business name.</span>
        </div>
        <div class="row">
          <div class="field" id="f-business_type">
            <label for="business_type">Type of business <span class="req">*</span></label>
            <select id="business_type" name="business_type">
              <option value="">Select…</option>
              @foreach($businessTypes as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
              @endforeach
            </select>
            <span class="err">Please choose your business type.</span>
          </div>
          <div class="field" id="f-primary_currency">
            <label for="primary_currency">Business currency <span class="req">*</span></label>
            <select id="primary_currency" name="primary_currency">
              @foreach($currencies as $cur)
                <option value="{{ $cur }}">{{ $cur }}</option>
              @endforeach
            </select>
            <span class="err">Please choose a currency.</span>
          </div>
        </div>
        <div class="row">
          <div class="field" id="f-country">
            <label for="country">Country <span class="req">*</span></label>
            <select id="country" name="country">
              <option value="">Select…</option>
              @foreach($countries as $c)
                <option value="{{ $c->id }}">{{ trim($c->country) }}</option>
              @endforeach
            </select>
            <span class="err">Please choose your country.</span>
          </div>
          <div class="field" id="f-location">
            <label for="location">City / area <span class="req">*</span></label>
            <input type="text" id="location" name="location" maxlength="300" autocomplete="address-level2" placeholder="e.g. Kariakoo, Dar es Salaam">
            <span class="err">Please tell us where you are based.</span>
          </div>
        </div>
        <div class="field">
          <label for="monthly_revenue">Roughly how big is the business?</label>
          <select id="monthly_revenue" name="monthly_revenue">
            @foreach($revenueRanges as $value => $label)
              <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
          </select>
        </div>
        <div class="checks">
          <label class="chk"><input type="checkbox" name="has_employees" value="1"> I have employees to pay</label>
          <label class="chk"><input type="checkbox" name="needs_inventory" value="1"> I keep stock / inventory</label>
        </div>
      </fieldset>

      <fieldset>
        <legend>Who we should contact</legend>
        <div class="row">
          <div class="field" id="f-contact_name">
            <label for="contact_name">Your full name <span class="req">*</span></label>
            <input type="text" id="contact_name" name="contact_name" maxlength="150" autocomplete="name">
            <span class="err">Please enter your name.</span>
          </div>
          <div class="field">
            <label for="contact_title">Your role</label>
            <input type="text" id="contact_title" name="contact_title" maxlength="100" placeholder="e.g. Owner, Manager">
          </div>
        </div>
        <div class="row">
          <div class="field" id="f-phone">
            <label for="phone">WhatsApp / phone number <span class="req">*</span></label>
            <input type="tel" id="phone" name="phone" autocomplete="tel">
            <span class="hint">We send your verification code here.</span>
            <span class="err">Please enter a valid phone number.</span>
          </div>
          <div class="field" id="f-email">
            <label for="email">Email <span class="req">*</span></label>
            <input type="email" id="email" name="email" maxlength="150" autocomplete="email">
            <span class="err">Please enter a valid email address.</span>
          </div>
        </div>
      </fieldset>

      <fieldset>
        <legend>Your Safaribook address</legend>
        <div class="field" id="f-username">
          <label for="username">Choose a web address <span class="hint" style="display:inline">(optional)</span></label>
          <div class="addr"><input type="text" id="username" name="username" maxlength="30" placeholder="yourbusiness" autocapitalize="none" autocomplete="off"><span>.safaribook.africa</span></div>
          <span class="hint">Lowercase letters and numbers only. Leave blank and we'll create one from your business name.</span>
          <span class="err">Use lowercase letters and numbers only.</span>
        </div>
      </fieldset>

      <div class="field" id="f-terms_accepted">
        <label class="chk"><input type="checkbox" id="terms_accepted" name="terms_accepted" value="1"> <span>I agree to the <a href="https://shulesoft.africa/terms" target="_blank" rel="noopener">Terms</a> and <a href="https://shulesoft.africa/privacy" target="_blank" rel="noopener">Privacy Policy</a>.</span></label>
        <span class="err">Please accept the Terms and Privacy Policy to continue.</span>
      </div>

      <button type="submit" class="btn btn-navy" id="submitBtn" style="width:100%">Register and get my code</button>
      <p class="fine">Questions? WhatsApp <a href="https://wa.me/255689353642">+255 689 353 642</a></p>
    </form>
  </div>
</div></div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.19/build/js/intlTelInput.min.js"></script>
<script>
(function () {
  var form = document.getElementById('joinForm');
  var alertBox = document.getElementById('alert');
  var btn = document.getElementById('submitBtn');
  var phoneEl = document.getElementById('phone');
  var iti = window.intlTelInput(phoneEl, {
    utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.19/build/js/utils.js',
    separateDialCode: true,
    initialCountry: 'tz',
    preferredCountries: ['tz', 'ke', 'ug', 'rw', 'zm', 'gh', 'ng', 'za']
  });

  // suggest the currency for the chosen country (can still be changed)
  var cur = { tanzania: 'TZS', kenya: 'KES', uganda: 'UGX' };
  document.getElementById('country').addEventListener('change', function () {
    var t = this.options[this.selectedIndex].text.toLowerCase();
    Object.keys(cur).forEach(function (k) {
      if (t.indexOf(k) === 0) document.getElementById('primary_currency').value = cur[k];
    });
  });

  var u = document.getElementById('username');
  u.addEventListener('input', function () { this.value = this.value.toLowerCase().replace(/[^a-z0-9]/g, ''); });

  function bad(id, on) { var f = document.getElementById('f-' + id); if (f) f.classList.toggle('bad', !!on); return !on; }
  function showAlert(msg) { alertBox.textContent = msg; alertBox.classList.add('show'); alertBox.scrollIntoView({ behavior: 'smooth', block: 'center' }); }
  function v(id) { return (document.getElementById(id).value || '').trim(); }

  function validate() {
    var ok = true;
    ok = bad('business_name', v('business_name').length < 2) && ok;
    ok = bad('business_type', !v('business_type')) && ok;
    ok = bad('country', !v('country')) && ok;
    ok = bad('location', !v('location')) && ok;
    ok = bad('contact_name', v('contact_name').length < 2) && ok;
    ok = bad('phone', !(iti.isValidNumber())) && ok;
    ok = bad('email', !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v('email'))) && ok;
    ok = bad('terms_accepted', !document.getElementById('terms_accepted').checked) && ok;
    if (!ok) { var first = form.querySelector('.field.bad'); if (first) first.scrollIntoView({ behavior: 'smooth', block: 'center' }); }
    return ok;
  }

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    alertBox.classList.remove('show');
    if (!validate()) return;

    var data = new FormData(form);
    data.set('phone', iti.getNumber());
    btn.disabled = true; btn.textContent = 'Sending your code…';

    fetch('/join', {
      method: 'POST',
      headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
      body: data
    }).then(function (r) {
      var st = r.status;
      return r.json().catch(function () {
        if (st === 419) return { status: 'error', message: 'Your session expired. Please refresh the page and try again.' };
        if (st === 429) return { status: 'error', message: 'Too many attempts. Please wait a minute and try again.' };
        return { status: 'error', message: 'Something went wrong (HTTP ' + st + '). Please try again.' };
      });
    }).then(function (res) {
      if (res.status === 'success') { window.location.href = res.redirect; return; }
      var msg = res.message;
      if (!msg && res.errors) { msg = Object.values(res.errors).flat()[0]; }
      showAlert(msg || 'Please check the form and try again.');
      btn.disabled = false; btn.textContent = 'Register and get my code';
    }).catch(function () {
      showAlert('Network problem. Please check your connection and try again.');
      btn.disabled = false; btn.textContent = 'Register and get my code';
    });
  });
})();
</script>
@endsection
