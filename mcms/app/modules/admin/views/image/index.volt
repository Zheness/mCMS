<h1 class="page-header">Liste des images</h1>
{{ flashSession.output() }}
<ul class="breadcrumb">
    <li><a href="{{ url("") }}">Accueil</a></li>
    <li class="active">Images</li>
</ul>
<p class="clearfix">
    <a href="{{ url("image/add") }}" class="btn btn-primary pull-right">
        <span class="fa fa-plus"></span>
        Ajouter une image
    </a>
</p>
<table id="image_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th>#</th>
        <th>Aperçu</th>
        <th>Titre</th>
        <th>Création</th>
        <th>Modification</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<script>
    var ADMIN_IMAGE_AJAX_URL = "{{ url("imageAjax") }}";
</script>
