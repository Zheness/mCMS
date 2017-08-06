$(function () {
    if ($('#page_list').length) {
        $('#page_list').DataTable({
            "language": datatable_fr,
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "searchDelay": 400,
            "ajax": ADMIN_PAGE_AJAX_URL + "/list",
            "columns": [
                {"name": "id"},
                {"name": "title"},
                {"name": "creation"},
                {"name": "edition"},
                {"name": "comments", "searchable": false},
                {"name": "private", "searchable": false},
                {"name": "actions", "orderable": false, "searchable": false}
            ]
        });
    }
});
