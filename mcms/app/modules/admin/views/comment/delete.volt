<h1 class="page-header">Suppression d'un commentaire</h1>
{{ flashSession.output() }}
<ul class="breadcrumb">
    <li><a href="{{ url("") }}">Accueil</a></li>
    <li><a href="{{ url("comment") }}">Commentaires</a></li>
    <li class="active">Supprimer</li>
</ul>
<ul class="nav nav-tabs margin-bottom-10">
    <li><a href="{{ url("comment/reply/" ~ comment.id) }}"><span class="fa fa-mail-reply"></span> Répondre</a></li>
    <li class="active">
        <a href="{{ url("comment/delete/" ~ comment.id) }}" class="text-danger">
            <span class="fa fa-trash"></span> Supprimer
        </a>
    </li>
</ul>
<div class="row">
    <div class="col-sm-12">
        <p>Attention, souhaitez-vous réelement supprimer le commentaire posté par <b>{{ comment.username }}</b> ?</p>
        <p>Toute suppression est définitive.</p>
    </div>
    <div class="col-lg-7">
        <form method="post" action="{{ url("comment/delete/" ~ comment.id) }}">
            <div class="form-group">
                {{ form.render("action") }}
                <input type="hidden" name="{{ csrfKey }}" value="{{ csrf }}">
                <button type="submit" class="btn btn-danger">Supprimer</button>
                <a href="{{ url("comment") }}" class="btn btn-default">Retour à la liste</a>
            </div>
        </form>
    </div>
</div>
