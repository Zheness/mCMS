<h1 class="page-header">Modification d'un album</h1>
{{ flashSession.output() }}
<ul class="breadcrumb">
    <li><a href="{{ url("") }}">Accueil</a></li>
    <li><a href="{{ url("album") }}">Albums</a></li>
    <li class="active">{{ album.title }}</li>
    <li class="active">Modifier</li>
</ul>
<ul class="nav nav-tabs margin-bottom-10">
    <li class="active"><a href="{{ url("album/edit/" ~ album.id) }}"><span class="fa fa-edit"></span> Modifier</a></li>
    <li><a href="{{ url("album/images/" ~ album.id) }}"><span class="fa fa-image"></span> Images
            ({{ album.Images.count() }})</a></li>
    <li><a href="{{ url("album/comments/" ~ album.id) }}"><span class="fa fa-comments"></span> Commentaires
            ({{ album.Comments.count() }})</a></li>
    <li><a href="{{ url("album/delete/" ~ album.id) }}" class="text-danger"><span class="fa fa-trash"></span>
            Supprimer</a></li>
</ul>
<p class="clearfix"><a href="{{ album.getUrl() }}" class="btn btn-primary pull-right"><span
            class="fa fa-external-link"></span> Ouvrir l'album</a></p>
<dl class="dl-horizontal">
    <dt>Création</dt>
    <dd>{{ album.dateCreatedToFr() }} - {{ album.getAdminLinkCreator() }}</dd>
    <dt>Modification</dt>
    <dd>{{ album.dateUpdatedToFr() }} - {{ album.getAdminLinkLastEditor() }}</dd>
</dl>
<div class="row">
    <div class="col-lg-9">
        <form method="post" action="{{ url("album/edit/" ~ album.id) }}">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="title" class="control-label">Titre de la page</label>
                        {{ form.render("title", ["class": "form-control"]) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="slug" class="control-label">Url de l'album <span
                                class="text-muted">- Facultatif</span></label>
                        {{ form.render("slug", ["class": "form-control"]) }}
                        <p class="help-block">Laissez vide ce champ pour générer automatiquement l'url à partir du
                            titre.</p>
                        <p class="help-block"><span class="fa fa-exclamation-triangle"></span> En modifiant l'url, les
                            liens présents dans vos autres pages seront invalides !</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="content" class="control-label">
                            Contenu <span class="text-muted">- Facultatif</span>
                        </label>
                        {{ form.render("content", ["class": "form-control tinymceEditableFull", "rows": "10"]) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="TriSea-technologies-Switch">
                        {{ form.render("commentsOpen") }}
                        <label for="commentsOpen" class="label-primary switch-label"></label>
                        <label for="commentsOpen">Commentaires ouverts</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="TriSea-technologies-Switch">
                        {{ form.render("isPrivate") }}
                        <label for="isPrivate" class="label-primary switch-label"></label>
                        <label for="isPrivate">Album privé</label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <input type="hidden" name="{{ csrfKey }}" value="{{ csrf }}">
                <button type="submit" class="btn btn-default">Modifier</button>
            </div>
        </form>
    </div>
</div>
