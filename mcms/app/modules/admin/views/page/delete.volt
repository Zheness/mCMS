<h1 class="page-header">Suppression d'une page</h1>
{{ flashSession.output() }}
<ul class="breadcrumb">
    <li><a href="{{ url("") }}">Accueil</a></li>
    <li><a href="{{ url("page") }}">Pages</a></li>
    <li class="active">{{ page.title }}</li>
    <li class="active">Supprimer</li>
</ul>
<ul class="nav nav-tabs margin-bottom-10">
    <li><a href="{{ url("page/edit/" ~ page.id) }}"><span class="fa fa-edit"></span> Modifier</a></li>
    <li><a href="{{ url("page/comments/" ~ page.id) }}"><span class="fa fa-comments"></span> Commentaires
            ({{ page.Comments.count() }})</a></li>
    <li class="active">
        <a href="{{ url("page/delete/" ~ page.id) }}" class="text-danger">
            <span class="fa fa-trash"></span> Supprimer
        </a>
    </li>
</ul>
<div class="row">
    <div class="col-sm-12">
        <p>Attention, souhaitez-vous réelement supprimer la page <b>{{ page.title }}</b> ?</p>
        <p>Toute suppression est définitive.</p>
    </div>
    <div class="col-lg-7">
        <form method="post" action="{{ url("page/delete/" ~ page.id) }}">
            <div class="form-group">
                {{ form.render("action") }}
                <input type="hidden" name="{{ csrfKey }}" value="{{ csrf }}">
                <button type="submit" class="btn btn-danger">Supprimer</button>
                <a href="{{ url("page") }}" class="btn btn-default">Retour à la liste</a>
            </div>
        </form>
    </div>
</div>
