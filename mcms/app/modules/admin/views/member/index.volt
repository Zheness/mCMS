<h1 class="page-header">Liste des membres</h1>
{{ flashSession.output() }}
<ul class="breadcrumb">
    <li><a href="{{ url("") }}">Accueil</a></li>
    <li class="active">Membres</li>
</ul>
<p class="clearfix">
    <a href="{{ url("member/add") }}" class="btn btn-primary pull-right">
        <span class="fa fa-plus"></span>
        Ajouter un membre
    </a>
</p>
<table id="member_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th>#</th>
        <th></th>
        <th>Nom</th>
        <th>Création</th>
        <th>Modification</th>
        <th>Statut</th>
        <th>Rôle</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<script>
    var ADMIN_MEMBER_AJAX_URL = "{{ url("memberAjax") }}";
</script>
