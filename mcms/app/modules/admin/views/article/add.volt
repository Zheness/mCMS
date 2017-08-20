<h1 class="page-header">Ajouter un nouvel article</h1>
{{ flashSession.output() }}
<ul class="breadcrumb">
    <li><a href="{{ url("") }}">Accueil</a></li>
    <li><a href="{{ url("article") }}">Articles</a></li>
    <li class="active">Ajouter un nouvel article</li>
</ul>
<div class="row">
    <div class="col-lg-9">
        <form method="post" action="{{ url("article/add") }}">
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
                        <p class="help-block">Indiquez la date à laquelle l'article doit être affiché sur le site (jj/mm/aaaa).<br>
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
                        <label for="isPrivate">Article privé</label>
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
