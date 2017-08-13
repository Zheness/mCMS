<h1 class="page-header">Ajouter un nouvel album</h1>
{{ flashSession.output() }}
<ul class="breadcrumb">
    <li><a href="{{ url("") }}">Accueil</a></li>
    <li><a href="{{ url("album") }}">Albums</a></li>
    <li class="active">Ajouter un nouvel album</li>
</ul>
<div class="row">
    <div class="col-lg-9">
        <form method="post" action="{{ url("album/add") }}">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="title" class="control-label">Titre de l'album</label>
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
                        <p class="help-block">
                            Laissez vide ce champ pour générer automatiquement l'url à partir du titre.
                        </p>
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
                <button type="submit" class="btn btn-default">Ajouter</button>
            </div>
        </form>
    </div>
</div>
