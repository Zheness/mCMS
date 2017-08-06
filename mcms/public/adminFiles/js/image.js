$(function () {
    if ($('#image_list').length) {
        $('#image_list').DataTable({
            "language": datatable_fr,
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "searchDelay": 400,
            "ajax": ADMIN_IMAGE_AJAX_URL + "/list",
            "columns": [
                {"name": "id"},
                {"name": "thumbnail", "orderable": false, "searchable": false},
                {"name": "title"},
                {"name": "creation"},
                {"name": "edition"},
                {"name": "actions", "orderable": false, "searchable": false}
            ]
        });
    }
});
