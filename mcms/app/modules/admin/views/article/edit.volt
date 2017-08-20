<h1 class="page-header">Modification d'un article</h1>
{{ flashSession.output() }}
<ul class="breadcrumb">
    <li><a href="{{ url("") }}">Accueil</a></li>
    <li><a href="{{ url("article") }}">Articles</a></li>
    <li class="active">{{ article.title }}</li>
    <li class="active">Modifier</li>
</ul>
<ul class="nav nav-tabs margin-bottom-10">
    <li class="active">
        <a href="{{ url("article/edit/" ~ article.id) }}"><span class="fa fa-edit"></span> Modifier</a>
    </li>
    <li><a href="{{ url("article/comments/" ~ article.id) }}"><span class="fa fa-comments"></span> Commentaires
            ({{ article.Comments.count() }})</a></li>
    <li><a href="{{ url("article/delete/" ~ article.id) }}" class="text-danger"><span class="fa fa-trash"></span>
            Supprimer</a></li>
</ul>
<p class="clearfix"><a href="{{ article.getUrl() }}" class="btn btn-primary pull-right"><span
            class="fa fa-external-link"></span> Ouvrir l'article</a></p>
<dl class="dl-horizontal">
    <dt>Création</dt>
    <dd>{{ article.dateCreatedToFr() }} - {{ article.getAdminLinkCreator() }}</dd>
    <dt>Modification</dt>
    <dd>{{ article.dateUpdatedToFr() }} - {{ article.getAdminLinkLastEditor() }}</dd>
</dl>
<div class="row">
    <div class="col-lg-9">
        <form method="post" action="{{ url("article/edit/" ~ article.id) }}">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="title" class="control-label">Titre de l'article</label>
                        {{ form.render("title", ["class": "form-control"]) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="slug" class="control-label">Url de l'article <span
                                class="text-muted">- Facultatif</span></label>
                        {{ form.render("slug", ["class": "form-control"]) }}
                        <p class="help-block">Laissez vide ce champ pour générer automatiquement l'url à partir du
                            titre.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="content" class="control-label">Contenu</label>
                        {{ form.render("content", ["class": "form-control tinymceEditableFull", "rows": "10"]) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="datePublication" class="control-label">Date de publication</label>
                        {{ form.render("datePublication", ["class": "form-control bootstrap-date-picker"]) }}
                        <p class="help-block">Indiquez la date à laquelle l'article doit être affiché sur le site
                            (jj/mm/aaaa).<br>
                            Attention, la date de publication détermine aussi l'url de l'article.</p>
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
                        <label for="isPrivate">Page privée</label>
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
