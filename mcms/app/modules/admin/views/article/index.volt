<h1 class="page-header">Liste des articles</h1>
{{ flashSession.output() }}
<ul class="breadcrumb">
    <li><a href="{{ url("") }}">Accueil</a></li>
    <li class="active">Articles</li>
</ul>
<p class="clearfix">
    <a href="{{ url("page/article") }}" class="btn btn-primary pull-right">
        <span class="fa fa-plus"></span>
        Ajouter un article
    </a>
</p>
<table id="article_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th>#</th>
        <th>Titre</th>
        <th>Date de publication</th>
        <th>Création</th>
        <th>Modification</th>
        <th>Commentaires ouverts</th>
        <th>Page privée</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<script>
    var ADMIN_ARTICLE_AJAX_URL = "{{ url("articleAjax") }}";
</script>
