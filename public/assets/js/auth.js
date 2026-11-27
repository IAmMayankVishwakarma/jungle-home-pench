/* Patara — auth.js (null-safe; load only on auth pages) */
(function () {
  'use strict';
  var $ = function (s, c) { return (c || document).querySelector(s); };
  var $$ = function (s, c) { return Array.prototype.slice.call((c || document).querySelectorAll(s)); };

  $$('.au-pw-btn').forEach(function (b) {
    b.addEventListener('click', function () {
      var i = $('input', b.parentNode), show = i.type === 'password';
      i.type = show ? 'text' : 'password';
      b.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
      b.setAttribute('aria-pressed', show);
    });
  });

  var pw = $('#password'), meter = $('.au-meter'), mt = $('.au-meter-t');
  if (pw && meter) {
    var labels = ['', 'Weak', 'Fair', 'Good', 'Strong'];
    pw.addEventListener('input', function () {
      var v = pw.value, s = 0;
      if (v.length >= 8) s++;
      if (/[a-z]/.test(v) && /[A-Z]/.test(v)) s++;
      if (/\d/.test(v)) s++;
      if (/[^A-Za-z0-9]/.test(v)) s++;
      if (!v) s = 0;
      meter.setAttribute('data-s', s); if (mt) mt.textContent = labels[s];
    });
  }

  // Client-side validation UI only; Laravel server-side validation must still run.
  $$('form.au-validate').forEach(function (f) {
    var fields = $$('.au-field', f);
    var check = function (fd) {
      var i = $('input', fd); if (!i) return true;
      var ok = i.checkValidity(), msg = $('.au-err', fd);
      if (i.id === 'password_confirmation') {
        var p = $('#password', f);
        if (p && i.value !== p.value) { ok = false; if (msg) msg.textContent = 'Passwords do not match.'; }
      } else if (msg && !ok) { msg.textContent = i.validationMessage; }
      fd.classList.toggle('is-invalid', !ok);
      var srv = $('.is-server', fd); if (srv && ok) srv.style.display = 'none';
      return ok;
    };
    fields.forEach(function (fd) {
      var i = $('input', fd); if (!i) return;
      i.addEventListener('blur', function () { if (i.value) check(fd); });
      i.addEventListener('input', function () { if (fd.classList.contains('is-invalid')) check(fd); });
    });
    var t = $('#terms', f), te = $('.au-terms-err', f);
    if (t && te) t.addEventListener('change', function () { te.style.display = 'none'; });
    f.addEventListener('submit', function (e) {
      var ok = true, first = null;
      fields.forEach(function (fd) { if (!check(fd)) { ok = false; first = first || $('input', fd); } });
      if (t && !t.checked) { ok = false; first = first || t; if (te) te.style.display = 'block'; }
      if (!ok) { e.preventDefault(); if (first) first.focus(); }
      else if (f.getAttribute('action') === '#') { e.preventDefault(); alert('Demo only — set the form action to your Laravel route.'); }
    });
  });
})();
