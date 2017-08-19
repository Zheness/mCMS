<h1 class="page-header">Liste des pages spéciales</h1>
{{ flashSession.output() }}
<ul class="breadcrumb">
    <li><a href="{{ url("") }}">Accueil</a></li>
    <li class="active">Pages spéciales</li>
</ul>
<table class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th>#</th>
        <th>Type</th>
        <th>Titre</th>
        <th>Modification</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    {% for page in pages %}
        <tr>
            <td>{{ page.id }}</td>
            <td>{{ page.internTitle }}</td>
            <td>{{ page.title }}</td>
            <td>
                {{ page.dateUpdatedToFr() }}
                <br/>
                {{ page.getAdminLinkLastEditor() }}
            </td>
            <td>
                <div class="btn-group btn-group-sm btn-group-right">
                    <a href="{{ url("specialPage/edit/" ~ page.id) }}" class="btn btn-default">
                        <span class="fa fa-pencil"></span> Modifier
                    </a>
                </div>
            </td>
        </tr>
    {% endfor %}
    </tbody>
</table>
