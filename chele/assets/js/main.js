/**
 * Chelé — front-end interactions.
 * Vanilla JS, no dependencies.
 */
(function () {
	'use strict';

	var doc = document;

	/* ---- Sticky header state ------------------------------------------ */
	var header = doc.querySelector('[data-header]');
	var lastY = 0;

	function onScroll() {
		var y = window.pageYOffset || doc.documentElement.scrollTop;
		if (header) {
			header.classList.toggle('is-scrolled', y > 20);
		}
		lastY = y;
	}
	window.addEventListener('scroll', onScroll, { passive: true });
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
			if (open) {
				overlay.hidden = false;
				requestAnimationFrame(function () { overlay.classList.add('is-active'); });
			} else {
				overlay.classList.remove('is-active');
				setTimeout(function () { overlay.hidden = true; }, 450);
			}
		}
		doc.body.style.overflow = open ? 'hidden' : '';
	}

	if (navToggle) {
		navToggle.addEventListener('click', function () {
			setNav(nav.classList.contains('is-open') === false);
		});
	}
	if (overlay) {
		overlay.addEventListener('click', function () { setNav(false); });
	}
	doc.addEventListener('keyup', function (e) {
		if (e.key === 'Escape') { setNav(false); }
	});
	// Close the drawer after tapping a link.
	if (nav) {
		nav.addEventListener('click', function (e) {
			if (e.target.closest('a') && window.matchMedia('(max-width: 900px)').matches) {
				setNav(false);
			}
		});
	}

	/* ---- Search panel -------------------------------------------------- */
	var searchToggle = doc.querySelector('[data-search-toggle]');
	var searchPanel = doc.querySelector('[data-search-panel]');
	if (searchToggle && searchPanel) {
		searchToggle.addEventListener('click', function () {
			var isHidden = searchPanel.hasAttribute('hidden');
			if (isHidden) {
				searchPanel.removeAttribute('hidden');
				var field = searchPanel.querySelector('input[type="search"]');
				if (field) { setTimeout(function () { field.focus(); }, 50); }
			} else {
				searchPanel.setAttribute('hidden', '');
			}
		});
	}

	/* ---- Scroll reveal ------------------------------------------------- */
	var revealEls = [].slice.call(doc.querySelectorAll('.reveal'));

	if (!('IntersectionObserver' in window) || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
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
})();
