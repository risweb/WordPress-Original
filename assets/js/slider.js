$(function () {
	// ファーストビュー
	$('.slider').slick({
		slidesToShow: 1,
		arrows: false,
		fade: true,
		speed: 1500,
		autoplay: true,
		autoplaySpeed: 5000,
		responsive: [
			{
				breakpoint: 1250,
				settings: {
					// variableWidth: true,
				},
			},
		],
	});
});