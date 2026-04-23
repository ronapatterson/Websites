(function (root, factory) {
  if (typeof module === 'object' && module.exports) {
    module.exports = factory();
  } else {
    root.AMTestimonialCarousel = factory();
  }
}(typeof self !== 'undefined' ? self : this, function () {

  function createCarousel(opts) {
    const slideCount = opts.slideCount | 0;
    const intervalMs = opts.intervalMs || 6000;
    const reducedMotion = !!opts.reducedMotion;
    let autoplay = !!opts.autoplay && !reducedMotion;
    let i = 0;
    let timer = null;

    function mod(n, m) { return ((n % m) + m) % m; }

    function goTo(n) {
      if (slideCount <= 0) return;
      i = mod(n, slideCount);
      if (opts.onChange) opts.onChange(i);
    }
    function advance()  { goTo(i + 1); }
    function retreat()  { goTo(i - 1); }

    function play() {
      if (reducedMotion) return;
      autoplay = true;
      if (timer) clearInterval(timer);
      timer = setInterval(advance, intervalMs);
      // Don't keep Node's event loop alive for test runners; no-op in browsers.
      if (timer && typeof timer.unref === 'function') timer.unref();
    }
    function pause() {
      autoplay = false;
      if (timer) { clearInterval(timer); timer = null; }
    }
    function destroy() {
      pause();
    }

    if (autoplay && typeof setInterval !== 'undefined' && intervalMs > 0) {
      play();
    }

    return {
      advance: advance,
      retreat: retreat,
      goTo: goTo,
      play: play,
      pause: pause,
      destroy: destroy,
      index: function () { return i; },
      isPlaying: function () { return autoplay; }
    };
  }

  function initFromDOM(root) {
    if (!root) return null;
    const slides = Array.prototype.slice.call(root.querySelectorAll('.am-carousel__slide'));
    const dots   = Array.prototype.slice.call(root.querySelectorAll('.am-carousel__dot'));
    const prev   = root.querySelector('.am-carousel__prev');
    const next   = root.querySelector('.am-carousel__next');

    const reducedMotion = (typeof matchMedia !== 'undefined')
      && matchMedia('(prefers-reduced-motion: reduce)').matches;

    function render(n) {
      slides.forEach(function (s, idx) {
        s.classList.toggle('is-active', idx === n);
        s.setAttribute('aria-hidden', idx === n ? 'false' : 'true');
      });
      dots.forEach(function (d, idx) {
        d.setAttribute('aria-current', idx === n ? 'true' : 'false');
      });
    }

    const c = createCarousel({
      slideCount: slides.length,
      intervalMs: 6000,
      autoplay: true,
      reducedMotion: reducedMotion,
      onChange: render
    });

    render(0);

    if (prev) prev.addEventListener('click', function(){ c.retreat(); });
    if (next) next.addEventListener('click', function(){ c.advance(); });
    dots.forEach(function (d, idx) {
      d.addEventListener('click', function(){ c.goTo(idx); });
    });

    root.addEventListener('mouseenter', function(){ c.pause(); });
    root.addEventListener('mouseleave', function(){ c.play();  });
    document.addEventListener('visibilitychange', function(){
      if (document.hidden) c.pause(); else c.play();
    });

    return c;
  }

  return { createCarousel: createCarousel, initFromDOM: initFromDOM };
}));
