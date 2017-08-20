<h1 class="page-header">Suppression d'un article</h1>
{{ flashSession.output() }}
<ul class="breadcrumb">
    <li><a href="{{ url("") }}">Accueil</a></li>
    <li><a href="{{ url("article") }}">Articles</a></li>
    <li class="active">{{ article.title }}</li>
    <li class="active">Supprimer</li>
</ul>
<ul class="nav nav-tabs margin-bottom-10">
    <li>
        <a href="{{ url("article/edit/" ~ article.id) }}"><span class="fa fa-edit"></span> Modifier</a>
    </li>
    <li><a href="{{ url("article/comments/" ~ article.id) }}"><span class="fa fa-comments"></span> Commentaires
            ({{ article.Comments.count() }})</a></li>
    <li class="active">
        <a href="{{ url("article/delete/" ~ article.id) }}" class="text-danger">
            <span class="fa fa-trash"></span> Supprimer
        </a>
    </li>
</ul>
<div class="row">
    <div class="col-sm-12">
        <p>Attention, souhaitez-vous réelement supprimer l'article <b>{{ article.title }}</b> ?</p>
        <p>Toute suppression est définitive.</p>
    </div>
    <div class="col-lg-7">
        <form method="post" action="{{ url("article/delete/" ~ article.id) }}">
            <div class="form-group">
                {{ form.render("action") }}
                <input type="hidden" name="{{ csrfKey }}" value="{{ csrf }}">
                <button type="submit" class="btn btn-danger">Supprimer</button>
                <a href="{{ url("article") }}" class="btn btn-default">Retour à la liste</a>
            </div>
        </form>
    </div>
</div>
