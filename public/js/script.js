console.log('page');
function send_form(page) {
    $('#post').html(`
        <form id="form" method="post" action="./">
            <input type="hidden" name="page" value="${page}">
        </form>
    `);
    $('#form').submit();
    console.log(page);
}