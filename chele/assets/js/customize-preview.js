/**
 * Chelé — Customizer live preview.
 */
(function ($) {
	'use strict';

	if (typeof wp === 'undefined' || !wp.customize) { return; }

	// Site title / description.
	wp.customize('blogname', function (value) {
		value.bind(function (to) { $('.wm-text').text(to ? to.toLowerCase() : 'chelé'); });
	});

	// Hero text.
	var heroMap = {
		chele_hero_eyebrow: '.hero-eyebrow',
		chele_hero_title: '.hero-sub-line',
		chele_hero_subtitle: '.hero-text',
		chele_hero_cta_text: '.hero-actions .btn--primary',
		chele_hero_cta2_text: '.hero-actions .btn--ghost'
	};
	Object.keys(heroMap).forEach(function (key) {
		wp.customize(key, function (value) {
			value.bind(function (to) {
				var $el = $(heroMap[key]);
				if (key === 'chele_hero_cta_text') {
					$el.contents().filter(function () { return this.nodeType === 3; }).first().replaceWith(to + ' ');
				} else {
					$el.text(to);
				}
			});
		});
	});

	// Footer motto.
	wp.customize('chele_footer_motto', function (value) {
		value.bind(function (to) { $('.footer-motto-text').text(to); });
	});

	// Live palette.
	var colorMap = {
		chele_color_cream: '--cream',
		chele_color_plum: '--plum',
		chele_color_rose: '--rose',
		chele_color_gold: '--gold',
		chele_color_ink: '--ink'
	};
	Object.keys(colorMap).forEach(function (key) {
		wp.customize(key, function (value) {
			value.bind(function (to) {
				if (to) { document.documentElement.style.setProperty(colorMap[key], to); }
			});
		});
	});
})(jQuery);
