<h1 class="page-header">Suppression d'un album</h1>
{{ flashSession.output() }}
<ul class="breadcrumb">
    <li><a href="{{ url("") }}">Accueil</a></li>
    <li><a href="{{ url("album") }}">Albums</a></li>
    <li class="active">{{ album.title }}</li>
    <li class="active">Supprimer</li>
</ul>
<ul class="nav nav-tabs margin-bottom-10">
    <li><a href="{{ url("album/edit/" ~ album.id) }}"><span class="fa fa-edit"></span> Modifier</a></li>
    <li><a href="{{ url("album/images/" ~ album.id) }}"><span class="fa fa-image"></span> Images
            ({{ album.Images.count() }})</a></li>
    <li><a href="{{ url("album/comments/" ~ album.id) }}"><span class="fa fa-comments"></span> Commentaires
            ({{ album.Comments.count() }})</a></li>
    <li class="active">
        <a href="{{ url("album/delete/" ~ album.id) }}" class="text-danger">
            <span class="fa fa-trash"></span> Supprimer
        </a>
    </li>
</ul>
<div class="row">
    <div class="col-sm-12">
        <p>Attention, souhaitez-vous réelement supprimer l'album <b>{{ album.title }}</b> ?</p>
        <p>Les {{ album.Images.count() }} images rattachées ne seront pas supprimées.</p>
        <p>Toute suppression est définitive.</p>
    </div>
    <div class="col-lg-7">
        <form method="post" action="{{ url("album/delete/" ~ album.id) }}">
            <div class="form-group">
                {{ form.render("action") }}
                <input type="hidden" name="{{ csrfKey }}" value="{{ csrf }}">
                <button type="submit" class="btn btn-danger">Supprimer</button>
                <a href="{{ url("album") }}" class="btn btn-default">Retour à la liste</a>
            </div>
        </form>
    </div>
</div>
