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
    if ($('#image_thumbnails').length) {
        let offset = 0;
        let maxPages = 0;
        let image_thumbnails = $("#image_thumbnails");

        $("#imageThumnailsPrevious").click(function (e) {
            e.preventDefault();
            if (offset == 0) {
                return false;
            }
            offset--;
            loadImageThumbnails();
        });
        $("#imageThumnailsNext").click(function (e) {
            e.preventDefault();
            if (offset == (maxPages - 1)) {
                return false;
            }
            offset++;
            loadImageThumbnails();
        });
        $(document).on("click", ".addImageToAlbum", function() {
            $.post(ADMIN_ALBUM_AJAX_URL + "/addImage/" + ADMIN_ALBUM_ID + "/" + $(this).attr('data-id'), function (data) {
                loadImageThumbnails();
                loadAlbumImageThumbnails();
            });
        });
        $(document).on("click", ".removeImageFromAlbum", function() {
            $.post(ADMIN_ALBUM_AJAX_URL + "/removeImage/" + ADMIN_ALBUM_ID + "/" + $(this).attr('data-id'), function (data) {
                loadImageThumbnails();
                loadAlbumImageThumbnails();
            });
        });
        function loadImageThumbnails() {
            $.get(ADMIN_IMAGE_AJAX_URL + "/thumbnails?limit=9&offset=" + offset, function (data) {

                displayImageList(data.data);

                $("#currentImagesOffset").text(data.offset + 1);
                maxPages = Math.ceil(data.recordsTotal / data.limit);
                $("#maxPagesImages").text(maxPages);
            });
        }
        function loadAlbumImageThumbnails() {
            $.get(ADMIN_ALBUM_AJAX_URL + "/thumbnails/" + ADMIN_ALBUM_ID, function (data) {

                displayAlbumImageList(data);
            });
        }

        function displayImageList(data) {
            $("#image_thumbnails").html('');
            for (let i = 0; i < data.length; ++i) {
                let image = data[i];
                let div = $('<div class="col-md-4"> \
                                <div class="thumbnail"> \
                                    <img src="/img/upload/' + image['filename'] + '" alt="Image - ' + image['title'] + '"> \
                                    <div class="caption"> \
                                        <button class="btn btn-block btn-primary addImageToAlbum" type="button" data-id="' + image['id'] + '"> \
                                            Ajouter à l\'album \
                                        </button> \
                                    </div> \
                                </div> \
                            </div>');
                $("#image_thumbnails").append(div);
            }
        }
        function displayAlbumImageList(data) {
            $("#albumImages").html('');
            for (let i = 0; i < data.length; ++i) {
                let image = data[i];
                let div = $('<div class="col-md-4"> \
                                <div class="thumbnail"> \
                                    <img src="/img/upload/' + image['filename'] + '" alt="Image - ' + image['title'] + '"> \
                                    <div class="caption"> \
                                        <button class="btn btn-block btn-danger removeImageFromAlbum" type="button" data-id="' + image['id'] + '"> \
                                            Retirer de l\'album \
                                        </button> \
                                    </div> \
                                </div> \
                            </div>');
                $("#albumImages").append(div);
            }
        }

        loadAlbumImageThumbnails();
        loadImageThumbnails();
    }
});
