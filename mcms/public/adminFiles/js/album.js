$(function () {
    if ($('#album_list').length) {
        $('#album_list').DataTable({
            "language": datatable_fr,
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "searchDelay": 400,
            "ajax": ADMIN_ALBUM_AJAX_URL + "/list",
            "columns": [
                {"name": "id"},
                {"name": "title"},
                {"name": "creation"},
                {"name": "edition"},
                {"name": "images", "searchable": false},
                {"name": "comments", "searchable": false},
                {"name": "private", "searchable": false},
                {"name": "actions", "orderable": false, "searchable": false}
            ]
        });
    }
});
