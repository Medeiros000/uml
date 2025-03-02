$(document).ready(function () {
	console.log("ready");

	$(".tab").click(function () {
		const expandableRows = $(this).find(".expandable");
		const isExpanded = expandableRows.is(":visible");
		const expandableMore = $(this).find(".more");
		const isExpandedMore = expandableMore.is(":visible");

		if (isExpanded) {
			expandableRows.hide();
		} else {
			expandableRows.show();
		}

		if (isExpandedMore) {
			expandableMore.hide();
		} else {
			expandableMore.show();
		}
		console.log("clicked");
	});
});

function send_form(page) {
	$("#post").html(`
        <form id="form" method="post" action="./">
            <input type="hidden" name="page" value="${page}">
        </form>
    `);
	$("#form").submit();
}
