async function copyText(text) {
	await navigator.clipboard.writeText(text);
	showToast();
}

function showToast() {
	var toast = document.querySelector(".toast");

	toast.classList.add("active");

	setTimeout(hideToast, 3000);
}

function hideToast() {
	var toast = document.querySelector(".toast");
	toast.classList.remove("active");
}

function sleep(ms) {
	var start = new Date().getTime(),
		expire = start + ms;
	while (new Date().getTime() < expire) {}
	return;
}
