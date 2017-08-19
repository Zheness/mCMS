<h1 class="page-header">Option
    <small>Commentaires</small>
</h1>
{{ flashSession.output() }}
<ul class="breadcrumb">
    <li><a href="{{ url("") }}">Accueil</a></li>
    <li><a href="{{ url("option") }}">Options</a></li>
    <li class="active">Commentaires</li>
</ul>
<div class="row">
    <div class="col-lg-9">
        <form method="post" action="{{ url("option/comments") }}">
            <div class="row">
                <div class="col-sm-12">
                    <div class="TriSea-technologies-Switch">
                        {{ form.render("enabled") }}
                        <label for="enabled" class="label-primary switch-label"></label>
                        <label for="enabled">Commentaires autorisés</label>
                    </div>
                    <p class="help-block">Les options suivantes n'auront aucun effet si les commentaires sont désactivés
                        globalement.</p>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="TriSea-technologies-Switch">
                        {{ form.render("pagesEnabled") }}
                        <label for="pagesEnabled" class="label-primary switch-label"></label>
                        <label for="pagesEnabled">Commentaires autorisés sur les pages</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="TriSea-technologies-Switch">
                        {{ form.render("albumsEnabled") }}
                        <label for="albumsEnabled" class="label-primary switch-label"></label>
                        <label for="albumsEnabled">Commentaires autorisés sur les albums</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="TriSea-technologies-Switch">
                        {{ form.render("articlesEnabled") }}
                        <label for="articlesEnabled" class="label-primary switch-label"></label>
                        <label for="articlesEnabled">Commentaires autorisés sur les articles</label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="maximumCommentsPerDay" class="control-label">Nombre de messages maximum par jour</label>
                <div class="row">
                    <div class="col-md-2">
                        {{ form.render("maximumCommentsPerDay", ["class": "form-control", "min": "-1", "max": "100"]) }}
                    </div>
                </div>
                <p class="help-block">Indiquez ici le nombre maximum de messages que peuvent poster les <b>utilisateurs
                        non-connectés</b> par jour.</p>
                <p class="help-block">
                    Valeurs spéciales:<br>
                    <b>0</b> => Les utilisateurs ne peuvent pas poster de commentaires.<br>
                    <b>-1</b> => Les utilisateurs peuvent poster des commentaires sans limite.
                </p>
            </div>

            <div class="form-group">
                <input type="hidden" name="{{ csrfKey }}" value="{{ csrf }}">
                <button type="submit" class="btn btn-default">Modifier</button>
            </div>
        </form>
    </div>
</div>
