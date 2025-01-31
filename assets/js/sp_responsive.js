!(function () {
	function switchViewport() {
		const viewport = document.querySelector('meta[name="viewport"]');
		if (!viewport) return; // metaタグがない場合は処理しない

		const value =
			window.innerWidth > 360
				? 'width=device-width,initial-scale=1'
				: 'width=360';

		if (viewport.getAttribute('content') !== value) {
			viewport.setAttribute('content', value);
		}
	}

	// 初回実行
	document.addEventListener('DOMContentLoaded', switchViewport);
	window.addEventListener('resize', switchViewport, false);
	window.addEventListener('orientationchange', switchViewport, false);
})();
