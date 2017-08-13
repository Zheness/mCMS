<h1 class="page-header">Liste des albums</h1>
{{ flashSession.output() }}
<ul class="breadcrumb">
    <li><a href="{{ url("") }}">Accueil</a></li>
    <li class="active">Albums</li>
</ul>
<p class="clearfix">
    <a href="{{ url("album/add") }}" class="btn btn-primary pull-right">
        <span class="fa fa-plus"></span>
        Ajouter un album
    </a>
</p>
<table id="album_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th>#</th>
        <th>Titre</th>
        <th>Création</th>
        <th>Modification</th>
        <th>Images</th>
        <th>Commentaires ouverts</th>
        <th>Album privé</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<script>
    var ADMIN_ALBUM_AJAX_URL = "{{ url("albumAjax") }}";
</script>
