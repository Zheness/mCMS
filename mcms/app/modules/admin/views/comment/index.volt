<h1 class="page-header">Liste des commentaires</h1>
{{ flashSession.output() }}
<ul class="breadcrumb">
    <li><a href="{{ url("") }}">Accueil</a></li>
    <li class="active">Commentaires</li>
</ul>
<table id="comment_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th>#</th>
        <th>Auteur</th>
        <th>Commentaire</th>
        <th>Localisation</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<script>
    var ADMIN_COMMENT_AJAX_URL = "{{ url("commentAjax") }}";
</script>
