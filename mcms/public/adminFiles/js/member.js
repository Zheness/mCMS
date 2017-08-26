$(function () {
    if ($('#member_list').length) {
        $('#member_list').DataTable({
            "language": datatable_fr,
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "searchDelay": 400,
            "ajax": ADMIN_MEMBER_AJAX_URL + "/list",
            "columns": [
                {"name": "id"},
                {"name": "image", "orderable": false, "searchable": false},
                {"name": "fullname"},
                {"name": "creation"},
                {"name": "edition"},
                {"name": "status", "searchable": false},
                {"name": "role", "searchable": false},
                {"name": "actions", "orderable": false, "searchable": false}
            ]
        });
    }
});
