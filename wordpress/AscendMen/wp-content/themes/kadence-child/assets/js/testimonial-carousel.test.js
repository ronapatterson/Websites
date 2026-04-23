// Node built-in test runner. Run with:
//   node --test wp-content/themes/kadence-child/assets/js/testimonial-carousel.test.js
const { test } = require('node:test');
const assert   = require('node:assert');

// Require the module under test. Expect it to export an object with { createCarousel }.
const { createCarousel } = require('./testimonial-carousel.js');

test('advance() cycles forward and wraps', () => {
  const c = createCarousel({ slideCount: 3, intervalMs: 0, autoplay: false });
  assert.equal(c.index(), 0);
  c.advance();
  assert.equal(c.index(), 1);
  c.advance();
  assert.equal(c.index(), 2);
  c.advance();
  assert.equal(c.index(), 0);
});

test('retreat() cycles backward and wraps', () => {
  const c = createCarousel({ slideCount: 3, intervalMs: 0, autoplay: false });
  c.retreat();
  assert.equal(c.index(), 2);
  c.retreat();
  assert.equal(c.index(), 1);
});

test('goTo() clamps via modulo', () => {
  const c = createCarousel({ slideCount: 5, intervalMs: 0, autoplay: false });
  c.goTo(7);
  assert.equal(c.index(), 2);
  c.goTo(-1);
  assert.equal(c.index(), 4);
});

test('reducedMotion disables autoplay', () => {
  const c = createCarousel({ slideCount: 3, intervalMs: 6000, autoplay: true, reducedMotion: true });
  assert.equal(c.isPlaying(), false);
});

test('pause()/play() toggle state', () => {
  const c = createCarousel({ slideCount: 3, intervalMs: 6000, autoplay: true });
  assert.equal(c.isPlaying(), true);
  c.pause();
  assert.equal(c.isPlaying(), false);
  c.play();
  assert.equal(c.isPlaying(), true);
});
