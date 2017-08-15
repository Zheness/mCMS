$(function () {
    if ($('#message_list').length) {
        $('#message_list').DataTable({
            "language": datatable_fr,
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "searchDelay": 400,
            "ajax": ADMIN_MESSAGE_AJAX_URL + "/list",
            "columns": [
                {"name": "id"},
                {"name": "subject"},
                {"name": "creation"},
                {"name": "edition"},
                {"name": "read", "searchable": false},
                {"name": "actions", "orderable": false, "searchable": false}
            ]
        });
    }
});
