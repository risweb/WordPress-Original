$(function () {

	// ページ内スクロール
	var headerHeight = 140;
	$('[href^="#"]').click(function () {
		var href = $(this).attr("href");
		var target = $(href == "#" || href == "" ? 'html' : href);
		var position = target.offset().top - headerHeight;
		$("html, body").animate({ scrollTop: position }, 200, "swing");
		return false;
	});

	//header追従
	$(window).scroll(function () {
		if (!$('.js-nav-area').hasClass('_active')) {
			if ($(window).scrollTop() > 300) {
				$('.header').addClass('_fixed');
			} else {
				$('.header').removeClass('_fixed');
			}
		}
	});

	//リンクをクリックでメニューを閉じる
	$('.global-navigation__link').click(function () {
		$('.js-nav-area').removeClass('_active');
		$('.humberger').removeClass('_active');
	});

	// ハンバーガーメニュー以外をクリックで閉じる
	$('.contents').click(function () {
		$('.js-nav-area').removeClass('_active');
		$('.humberger').removeClass('_active');
	});

	// QAアコーディオン
	$('.qa__head').click(function () {
		$(this).toggleClass('_active');
		$(this).next('.qa__body').slideToggle(200);
	});

});
