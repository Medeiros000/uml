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
	});
});

//  expand all rows
function expand_all() {
	if ($("#expand").text() == "expand all") {
		$(".expandable").show();
		$(".more").hide();
		$("#expand").text("collapse all");
	} else {
		$(".expandable").hide();
		$(".more").show();
		$("#expand").text("expand all");
	}
}

function send_form(page) {
	$("#post").html(`
        <form id="form" method="post" action="/">
            <input type="hidden" name="page" value="${page}">
        </form>
    `);
	$("#form").submit();
}
