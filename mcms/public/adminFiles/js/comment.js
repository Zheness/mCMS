$(function () {
    if ($('#comment_list').length) {
        $('#comment_list').DataTable({
            "language": datatable_fr,
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "searchDelay": 400,
            "ajax": ADMIN_COMMENT_AJAX_URL + "/list",
            "columns": [
                {"name": "id"},
                {"name": "author"},
                {"name": "comment", "orderable": false, "searchable": false},
                {"name": "location", "orderable": false, "searchable": false},
                {"name": "actions", "orderable": false, "searchable": false}
            ]
        });
    }
});
