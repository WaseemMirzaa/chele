/**
 * Chelé — front-end interactions (vanilla JS, no dependencies).
 */
(function () {
	'use strict';

	var doc = document;
	var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	/* ---- Intro loader (once per session) ------------------------------ */
	var loader = doc.querySelector('[data-loader]');
	if (loader) {
		var seen = false;
		try { seen = sessionStorage.getItem('chele_seen') === '1'; } catch (e) {}
		if (seen || reduce) {
			loader.parentNode && loader.parentNode.removeChild(loader);
		} else {
			try { sessionStorage.setItem('chele_seen', '1'); } catch (e) {}
			window.setTimeout(function () {
				loader.classList.add('is-done');
				loader.parentNode && loader.parentNode.removeChild(loader);
			}, 2100);
		}
	}

	/* ---- Scroll progress + sticky header ------------------------------ */
	var header = doc.querySelector('[data-header]');
	var progress = doc.querySelector('[data-progress]');

	function onScroll() {
		var y = window.pageYOffset || doc.documentElement.scrollTop;
		if (header) { header.classList.toggle('is-scrolled', y > 24); }
		if (progress) {
			var h = doc.documentElement.scrollHeight - window.innerHeight;
			progress.style.width = (h > 0 ? (y / h) * 100 : 0) + '%';
		}
		ticking = false;
	}
	var ticking = false;
	window.addEventListener('scroll', function () {
		if (!ticking) { window.requestAnimationFrame(onScroll); ticking = true; }
	}, { passive: true });
	onScroll();

	/* ---- Mobile navigation -------------------------------------------- */
	var navToggle = doc.querySelector('[data-nav-toggle]');
	var nav = doc.querySelector('[data-nav]');
	var overlay = doc.querySelector('[data-nav-overlay]');

	function setNav(open) {
		if (!nav || !navToggle) { return; }
		nav.classList.toggle('is-open', open);
		navToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		if (overlay) {
			if (open) { overlay.hidden = false; requestAnimationFrame(function () { overlay.classList.add('is-active'); }); }
			else { overlay.classList.remove('is-active'); setTimeout(function () { overlay.hidden = true; }, 450); }
		}
		doc.body.style.overflow = open ? 'hidden' : '';
	}
	if (navToggle) { navToggle.addEventListener('click', function () { setNav(!nav.classList.contains('is-open')); }); }
	if (overlay) { overlay.addEventListener('click', function () { setNav(false); }); }
	doc.addEventListener('keyup', function (e) { if (e.key === 'Escape') { setNav(false); } });
	if (nav) {
		nav.addEventListener('click', function (e) {
			if (e.target.closest('a') && window.matchMedia('(max-width: 900px)').matches) { setNav(false); }
		});
	}

	/* ---- Search panel -------------------------------------------------- */
	var searchToggle = doc.querySelector('[data-search-toggle]');
	var searchPanel = doc.querySelector('[data-search-panel]');
	if (searchToggle && searchPanel) {
		searchToggle.addEventListener('click', function () {
			if (searchPanel.hasAttribute('hidden')) {
				searchPanel.removeAttribute('hidden');
				var f = searchPanel.querySelector('input[type="search"]');
				if (f) { setTimeout(function () { f.focus(); }, 50); }
			} else { searchPanel.setAttribute('hidden', ''); }
		});
	}

	/* ---- Scroll reveal (staggered) ------------------------------------ */
	var revealEls = [].slice.call(doc.querySelectorAll('.reveal'));
	if (!('IntersectionObserver' in window) || reduce) {
		revealEls.forEach(function (el) { el.classList.add('is-visible'); });
	} else {
		var io = new IntersectionObserver(function (entries, obs) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting) {
					var el = entry.target;
					var delay = parseInt(el.getAttribute('data-reveal-delay'), 10) || 0;
					el.style.transitionDelay = delay + 'ms';
					el.classList.add('is-visible');
					obs.unobserve(el);
				}
			});
		}, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });
		revealEls.forEach(function (el) { io.observe(el); });
	}

	/* ---- Animated counters -------------------------------------------- */
	var counters = [].slice.call(doc.querySelectorAll('[data-count]'));
	if (counters.length && 'IntersectionObserver' in window && !reduce) {
		var cio = new IntersectionObserver(function (entries, obs) {
			entries.forEach(function (entry) {
				if (!entry.isIntersecting) { return; }
				var el = entry.target;
				var target = parseFloat(el.getAttribute('data-count'));
				var suffix = el.getAttribute('data-suffix') || '';
				var dur = 1600, start = null;
				function step(ts) {
					if (!start) { start = ts; }
					var p = Math.min((ts - start) / dur, 1);
					var eased = 1 - Math.pow(1 - p, 3);
					el.textContent = Math.round(target * eased).toLocaleString() + suffix;
					if (p < 1) { requestAnimationFrame(step); }
				}
				requestAnimationFrame(step);
				obs.unobserve(el);
			});
		}, { threshold: 0.4 });
		counters.forEach(function (el) { cio.observe(el); });
	}

	/* ---- Parallax ------------------------------------------------------ */
	var parallaxEls = [].slice.call(doc.querySelectorAll('[data-parallax]'));
	if (parallaxEls.length && !reduce) {
		var pTicking = false;
		function parallax() {
			var vh = window.innerHeight;
			parallaxEls.forEach(function (el) {
				var rect = el.getBoundingClientRect();
				if (rect.bottom < -200 || rect.top > vh + 200) { return; }
				var speed = parseFloat(el.getAttribute('data-parallax')) || 0.12;
				var offset = (rect.top + rect.height / 2 - vh / 2) * speed * -1;
				el.style.transform = 'translate3d(0,' + offset.toFixed(1) + 'px,0)';
			});
			pTicking = false;
		}
		window.addEventListener('scroll', function () {
			if (!pTicking) { requestAnimationFrame(parallax); pTicking = true; }
		}, { passive: true });
		parallax();
	}

	/* ---- Magnetic buttons --------------------------------------------- */
	if (!reduce && window.matchMedia('(hover: hover)').matches) {
		[].slice.call(doc.querySelectorAll('[data-magnetic]')).forEach(function (el) {
			el.addEventListener('mousemove', function (e) {
				var r = el.getBoundingClientRect();
				var mx = e.clientX - r.left - r.width / 2;
				var my = e.clientY - r.top - r.height / 2;
				el.style.transform = 'translate(' + mx * 0.18 + 'px,' + my * 0.22 + 'px)';
			});
			el.addEventListener('mouseleave', function () { el.style.transform = ''; });
		});
	}

	/* ---- Wishlist + quick-add (demo affordances) ---------------------- */
	doc.addEventListener('click', function (e) {
		var wish = e.target.closest('[data-wish]');
		if (wish) {
			e.preventDefault();
			wish.classList.toggle('is-active');
			wish.setAttribute('aria-pressed', wish.classList.contains('is-active') ? 'true' : 'false');
		}
	});
})();
