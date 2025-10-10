// Front page hero DOM enhancements without editing Elementor content
(function () {
  function ready(fn) {
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', fn);
    } else {
      fn();
    }
  }

  ready(function () {
    try {
      // Only act on front page
      if (!document.body.classList.contains('home')) return;

      // Find hero section
      var hero = document.querySelector('section.home-hero') ||
                 document.querySelector('section.elementor-section.home-hero') ||
                 document.querySelector('section.elementor-top-section.home-hero');
      if (!hero) return;

      // Ensure class for CSS overrides
      hero.classList.add('hero-section');

      // Avoid duplicate injections
      if (hero.querySelector('.hero-trust-badges')) return;

      // Locate CTA buttons within hero
      var ctas = hero.querySelectorAll('a.elementor-button');
      var anchor = ctas && ctas.length ? ctas[ctas.length - 1] : null;

      var trustBadgesHTML = [
        '<div class="hero-trust-badges">',
        '  <div class="trust-badge"><span class="check-icon">✓</span><span class="badge-text">No signup required</span></div>',
        '  <div class="trust-badge"><span class="check-icon">✓</span><span class="badge-text">Bank-level encryption</span></div>',
        '  <div class="trust-badge"><span class="check-icon">✓</span><span class="badge-text">Files deleted after 1 hour</span></div>',
        '  <div class="trust-badge"><span class="check-icon">✓</span><span class="badge-text">50,000+ docs processed weekly</span></div>',
        '</div>'
      ].join('');

      var toolsRowHTML = [
        '<div class="hero-tools-row">',
        '  <div class="hero-tool">',
        '    <div class="hero-tool-icon">🗜️</div>',
        '    <h3>Compress PDF</h3>',
        '    <p>Reduce file size without losing quality.</p>',
        '  </div>',
        '  <div class="hero-tool">',
        '    <div class="hero-tool-icon">➕</div>',
        '    <h3>Merge PDF</h3>',
        '    <p>Combine multiple PDFs into one.</p>',
        '  </div>',
        '  <div class="hero-tool">',
        '    <div class="hero-tool-icon">🔁</div>',
        '    <h3>Convert PDF</h3>',
        '    <p>Change format instantly.</p>',
        '  </div>',
        '</div>'
      ].join('');

      var injectTarget = anchor || hero;
      if (!injectTarget) return;

      // Insert badges and tools
      injectTarget.insertAdjacentHTML('afterend', toolsRowHTML);
      injectTarget.insertAdjacentHTML('afterend', trustBadgesHTML);
    } catch (e) {
      // no-op
    }
  });
})();
