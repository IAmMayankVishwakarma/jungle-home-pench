/* Patara Admin — admin.js (null-safe) */
(function () {
  'use strict';
  var $ = function (s, c) { return (c || document).querySelector(s); };
  var $$ = function (s, c) { return Array.prototype.slice.call((c || document).querySelectorAll(s)); };
  var body = document.body;

  // Sidebar: mobile drawer + desktop collapse (remembered)
  var st = $('#sideToggle'), sc = $('#sideCollapse'), ov = $('.ad-overlay');
  try { if (localStorage.getItem('ad-collapsed') === '1') body.classList.add('side-collapsed'); } catch (e) {}
  if (st) st.addEventListener('click', function () { body.classList.toggle('side-open'); });
  if (ov) ov.addEventListener('click', function () { body.classList.remove('side-open'); });
  if (sc) sc.addEventListener('click', function () {
    var c = body.classList.toggle('side-collapsed');
    try { localStorage.setItem('ad-collapsed', c ? '1' : '0'); } catch (e) {}
    window.dispatchEvent(new Event('resize'));
  });
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape') body.classList.remove('side-open'); });

  // Charts: <canvas data-chart='{"type":"line","data":{...}}'>
  if (window.Chart) {
    var pal = ['#b08d57', '#1b1b1b', '#d9c3a0', '#4f8a6b', '#e0a64a', '#a85a4f'];
    Chart.defaults.font.family = "'Outfit', sans-serif"; Chart.defaults.color = '#6b6461';
    $$('canvas[data-chart]').forEach(function (cv) {
      var cfg; try { cfg = JSON.parse(cv.getAttribute('data-chart')); } catch (e) { return; }
      var t = cfg.type, ctx = cv.getContext('2d');
      cfg.data.datasets.forEach(function (d, i) {
        var c = pal[i % pal.length];
        if (t === 'doughnut' || t === 'pie') { d.backgroundColor = pal; d.borderWidth = 2; d.borderColor = '#fff'; }
        else if (t === 'bar') { d.backgroundColor = c; d.borderRadius = 4; d.maxBarThickness = 28; }
        else {
          d.borderColor = c; d.borderWidth = 2.5; d.tension = .35; d.pointRadius = 3; d.pointBackgroundColor = '#fff'; d.pointBorderColor = c;
          if (i === 0) { var g = ctx.createLinearGradient(0, 0, 0, 300); g.addColorStop(0, 'rgba(176,141,87,.28)'); g.addColorStop(1, 'rgba(176,141,87,0)'); d.backgroundColor = g; d.fill = true; }
          if (d.dashed) { d.borderDash = [6, 5]; d.borderColor = '#bdb3ab'; d.pointRadius = 0; d.fill = false; }
        }
      });
      cfg.options = Object.assign({ responsive: true, maintainAspectRatio: false, plugins: { legend: { display: cfg.data.datasets.length > 1 || t === 'doughnut', position: 'bottom', labels: { usePointStyle: true, boxWidth: 8 } } } },
        (t === 'doughnut' || t === 'pie') ? { cutout: '68%' } : { scales: { x: { grid: { display: false } }, y: { grid: { color: '#f0e9e4' }, beginAtZero: true } } });
      new Chart(cv, cfg);
    });
  }

  // Select all
  $$('.js-all').forEach(function (a) { a.addEventListener('change', function () { $$('.js-row', a.closest('table')).forEach(function (r) { r.checked = a.checked; }); }); });

  // Table search + status filter (client-side demo — replace with server-side filtering)
  $$('.card-ad').forEach(function (card) {
    var s = $('.js-tsearch', card), f = $('.js-tstatus', card), tb = $('#dataTbl tbody', card); if (!tb) return;
    var run = function () {
      var q = s ? s.value.toLowerCase() : '', v = f ? f.value.toLowerCase() : '';
      $$('tr', tb).forEach(function (tr) { var t = tr.textContent.toLowerCase(); tr.style.display = (!q || t.indexOf(q) > -1) && (!v || t.indexOf(v) > -1) ? '' : 'none'; });
    };
    if (s) s.addEventListener('input', run); if (f) f.addEventListener('change', run);
  });

  // Delete confirm (UI only)
  $$('.js-del').forEach(function (b) { b.addEventListener('click', function () { if (confirm('Delete this record?')) { var tr = b.closest('tr'); if (tr) tr.remove(); } }); });

  // Form validation UI
  $$('.needs-validation').forEach(function (f) {
    f.addEventListener('submit', function (e) {
      if (!f.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
      else if (!f.getAttribute('action')) { e.preventDefault(); var m = f.closest('.modal'); if (m && window.bootstrap) { var i = bootstrap.Modal.getInstance(m); if (i) i.hide(); } f.reset(); f.classList.remove('was-validated'); return; }
      f.classList.add('was-validated');
    });
  });

  // Open "new booking" modal via #new
  if (location.hash === '#new' && window.bootstrap && $('#formModal')) new bootstrap.Modal($('#formModal')).show();
})();
