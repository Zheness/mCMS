<h1 class="page-header">Liste des pages</h1>
{{ flashSession.output() }}
<ul class="breadcrumb">
    <li><a href="{{ url("") }}">Accueil</a></li>
    <li class="active">Pages</li>
</ul>
<p class="clearfix">
    <a href="{{ url("page/add") }}" class="btn btn-primary pull-right">
        <span class="fa fa-plus"></span>
        Ajouter une page
    </a>
</p>
<table id="page_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th>#</th>
        <th>Titre</th>
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
    var ADMIN_PAGE_AJAX_URL = "{{ url("pageAjax") }}";
</script>
