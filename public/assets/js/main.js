/* Patara — main.js (null-safe; safe to load on every page) */
(function () {
  'use strict';
  var $ = function (s, c) { return (c || document).querySelector(s); };
  var $$ = function (s, c) { return Array.prototype.slice.call((c || document).querySelectorAll(s)); };

  // Sticky header
  var header = $('.pt-header');
  if (header) {
    var onScroll = function () { header.classList.toggle('is-stuck', window.scrollY > 120); };
    window.addEventListener('scroll', onScroll, { passive: true }); onScroll();
  }

  // Mobile menu + submenus
  var toggler = $('.pt-toggler'), nav = $('.pt-nav');
  if (toggler && nav) {
    toggler.addEventListener('click', function () {
      var open = nav.classList.toggle('is-open');
      toggler.setAttribute('aria-expanded', open);
      document.body.style.overflow = open ? 'hidden' : '';
    });
    $$('.has-sub > .pt-nav__link', nav).forEach(function (a) {
      a.addEventListener('click', function (e) {
        if (window.innerWidth < 992 && (a.getAttribute('href') === '#' || e.target.closest('.ic'))) {
          e.preventDefault(); a.parentNode.classList.toggle('is-open');
        }
      });
    });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && nav.classList.contains('is-open')) toggler.click(); });
  }

  // Back to top
  $$('.pt-top').forEach(function (b) { b.addEventListener('click', function () { window.scrollTo({ top: 0, behavior: 'smooth' }); }); });

  // Hero slider (arrows cycle background slides when present)
  $$('.hero--home').forEach(function (h) {
    var s = $$('.hero__slide', h), i = 0; if (s.length < 2) return;
    var go = function (n) { s[i].classList.remove('is-active'); i = (n + s.length) % s.length; s[i].classList.add('is-active'); };
    var p = $('.hero__arrow--prev', h), n = $('.hero__arrow--next', h);
    if (p) p.addEventListener('click', function () { go(i - 1); });
    if (n) n.addEventListener('click', function () { go(i + 1); });
    setInterval(function () { go(i + 1); }, 7000);
  });

  // Generic carousel with dots
  $$('.pt-slider').forEach(function (sl) {
    var track = $('.pt-slider__track', sl), slides = $$('.pt-slider__slide', sl), dots = $('.pt-slider__dots', sl);
    if (!track || !slides.length) return;
    var idx = 0, per = parseInt(sl.getAttribute('data-per'), 10) || 1, timer;
    var vis = function () { return window.innerWidth < 768 ? 1 : per; };
    var pages = function () { return Math.max(1, Math.ceil(slides.length / vis())); };
    var render = function () {
      var v = vis(); sl.style.setProperty('--per', v);
      idx = Math.min(idx, pages() - 1);
      track.style.transform = 'translateX(' + (-idx * 100) + '%)';
      if (dots) {
        dots.innerHTML = '';
        if (pages() > 1) for (var i = 0; i < pages(); i++) (function (i) {
          var b = document.createElement('button'); b.type = 'button'; b.setAttribute('aria-label', 'Go to slide ' + (i + 1));
          if (i === idx) b.className = 'is-active';
          b.addEventListener('click', function () { idx = i; render(); }); dots.appendChild(b);
        })(i);
      }
    };
    window.addEventListener('resize', render); render();
    if (sl.classList.contains('rd-slider')) { timer = setInterval(function () { idx = (idx + 1) % pages(); render(); }, 5000); }
  });

  // Accordions
  $$('.pt-acc__btn').forEach(function (b) {
    b.addEventListener('click', function () {
      var item = b.closest('.pt-acc__item'), open = !item.classList.contains('is-open');
      $$('.pt-acc__item', item.parentNode).forEach(function (it) { it.classList.remove('is-open'); var bb = $('.pt-acc__btn', it); if (bb) bb.setAttribute('aria-expanded', 'false'); });
      item.classList.toggle('is-open', open); b.setAttribute('aria-expanded', open);
    });
  });

  // Date inputs (text placeholder -> native picker on focus)
  $$('.js-date').forEach(function (i) {
    i.addEventListener('focus', function () { i.type = 'date'; if (i.showPicker) { try { i.showPicker(); } catch (e) {} } });
    i.addEventListener('blur', function () { if (!i.value) i.type = 'text'; });
  });

  // Booking bar validation (UI only — wire to your Laravel route)
  $$('.booking__form').forEach(function (f) {
    f.addEventListener('submit', function (e) {
      e.preventDefault();
      var ci = f.elements.check_in, co = f.elements.check_out, ok = true;
      [ci, co].forEach(function (x) { x.classList.remove('is-invalid'); });
      if (!ci.value) { ci.classList.add('is-invalid'); ok = false; }
      if (!co.value || (ci.value && co.value <= ci.value)) { co.classList.add('is-invalid'); ok = false; }
      if (ok) f.submit && f.removeAttribute('novalidate');
      else (ci.value ? co : ci).focus();
    });
  });

  // Load more
  $$('.js-loadmore').forEach(function (b) {
    b.addEventListener('click', function () {
      var scope = $(b.getAttribute('data-target')); if (!scope) return;
      $$('.is-extra', scope).forEach(function (el) { el.classList.add('is-shown'); el.style.display = 'block'; });
      b.parentNode.removeChild(b);
    });
  });

  // Gallery lightbox
  var gm = $('#galModalImg');
  $$('.js-gal').forEach(function (a) { a.addEventListener('click', function () { if (gm) gm.src = a.getAttribute('href'); }); });

  // Video modal (lazy-load iframe)
  var vm = $('#videoModal');
  if (vm) {
    var fr = $('iframe', vm);
    vm.addEventListener('show.bs.modal', function () { fr.src = fr.getAttribute('data-src'); });
    vm.addEventListener('hidden.bs.modal', function () { fr.src = ''; });
  }

  // Room booking box
  var rb = $('#roomBook');
  if (rb) {
    var price = +rb.getAttribute('data-price') || 0, q = $('.qty', rb), tot = $('.js-total', rb), dates = $$('.js-date', rb);
    var calc = function () {
      var n = 1; if (dates[0].value && dates[1].value) n = Math.max(1, Math.round((new Date(dates[1].value) - new Date(dates[0].value)) / 864e5));
      tot.textContent = '$' + (price * n * (+q.value || 1)) + ' / ' + n + ' night' + (n > 1 ? 's' : '');
    };
    [q].concat(dates).forEach(function (el) { el.addEventListener('change', calc); });
    $('.js-clear', rb).addEventListener('click', function (e) { e.preventDefault(); rb.reset(); dates.forEach(function (d) { d.type = 'text'; d.value = ''; }); calc(); });
    rb.addEventListener('submit', function (e) { e.preventDefault(); dates.forEach(function (d) { d.classList.toggle('is-invalid', !d.value); }); });
  }

  // Forms: Bootstrap validation UI
  $$('.needs-validation').forEach(function (f) {
    f.addEventListener('submit', function (e) {
      if (!f.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
      else if (!f.getAttribute('action')) { e.preventDefault(); var ok = $('.form-ok', f); if (ok) ok.hidden = false; f.reset(); f.classList.remove('was-validated'); return; }
      f.classList.add('was-validated');
    });
  });

  // 404 search
  $$('.search404').forEach(function (f) { f.addEventListener('submit', function (e) { if (!$('input', f).value.trim()) e.preventDefault(); }); });

  // Countdown
  $$('.cd').forEach(function (c) {
    var end = new Date(c.getAttribute('data-date')).getTime(), p = function (n) { return ('0' + n).slice(-2); };
    var tick = function () {
      var d = Math.max(0, end - Date.now()), u = { d: Math.floor(d / 864e5), h: Math.floor(d % 864e5 / 36e5), m: Math.floor(d % 36e5 / 6e4), s: Math.floor(d % 6e4 / 1e3) };
      Object.keys(u).forEach(function (k) { var el = $('[data-u="' + k + '"]', c); if (el) el.textContent = p(u[k]); });
    };
    tick(); setInterval(tick, 1000);
  });
})();
