const adjustViewport = () => {
	const triggerWidth = 375;
	const viewport = document.querySelector('meta[name="viewport"]');
	const value = window.outerWidth < triggerWidth
		? `width=${triggerWidth}, target-densitydpi=device-dpi`
		: 'width=device-width, initial-scale=1';
	viewport.setAttribute('content', value);
}
const debouncedFunction = debounce(adjustViewport) // debounce関数は、Debounceの項で解説した関数です
window.addEventListener('resize', debouncedFunction, false);