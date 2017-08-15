<h1 class="page-header">Liste des messages</h1>
{{ flashSession.output() }}
<ul class="breadcrumb">
    <li><a href="{{ url("") }}">Accueil</a></li>
    <li class="active">Messages</li>
</ul>
<table id="message_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th>#</th>
        <th>Sujet</th>
        <th>Création</th>
        <th>Modification</th>
        <th>Lu</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<script>
    var ADMIN_MESSAGE_AJAX_URL = "{{ url("messageAjax") }}";
</script>
