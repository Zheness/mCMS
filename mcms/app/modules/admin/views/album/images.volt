<h1 class="page-header">Gestion des images d'un album</h1>
{{ flashSession.output() }}
<ul class="breadcrumb">
    <li><a href="{{ url("") }}">Accueil</a></li>
    <li><a href="{{ url("album") }}">Albums</a></li>
    <li class="active">{{ album.title }}</li>
    <li class="active">Gestion des images</li>
</ul>
<ul class="nav nav-tabs margin-bottom-10">
    <li><a href="{{ url("album/edit/" ~ album.id) }}"><span class="fa fa-edit"></span> Modifier</a></li>
    <li class="active"><a href="{{ url("album/images/" ~ album.id) }}"><span class="fa fa-image"></span> Images
            ({{ album.Images.count() }})</a></li>
    <li><a href="{{ url("album/comments/" ~ album.id) }}"><span class="fa fa-comments"></span> Commentaires
            ({{ album.Comments.count() }})</a></li>
    <li><a href="{{ url("album/delete/" ~ album.id) }}" class="text-danger"><span class="fa fa-trash"></span>
            Supprimer</a></li>
</ul>
<p class="clearfix"><a href="{{ album.getUrl() }}" class="btn btn-primary pull-right"><span
            class="fa fa-external-link"></span> Ouvrir l'album</a></p>
<div class="row">
    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-body">
                <h2>Images disponibles</h2>
                <div class="row" id="image_thumbnails">
                </div>
                <div class="text-center">
                    <ul class="pagination">
                        <li>
                            <a href="#" id="imageThumnailsPrevious">
                                &laquo;
                            </a>
                        </li>
                        <li class="disabled">
                            <a href="#" onclick="return false;">
                                <span id="currentImagesOffset">1</span> / <span id="maxPagesImages">1</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" id="imageThumnailsNext">
                                &raquo;
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-body">
                <h2>Images dans l'album</h2>
                <div class="row" id="albumImages">
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var ADMIN_ALBUM_AJAX_URL = "{{ url("albumAjax") }}";
    var ADMIN_IMAGE_AJAX_URL = "{{ url("imageAjax") }}";
    var ADMIN_ALBUM_ID = "{{ album.id }}";
</script>
