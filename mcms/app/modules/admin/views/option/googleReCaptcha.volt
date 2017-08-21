<h1 class="page-header">Option
    <small>Google reCaptcha</small>
</h1>
{{ flashSession.output() }}
<ul class="breadcrumb">
    <li><a href="{{ url("") }}">Accueil</a></li>
    <li><a href="{{ url("option") }}">Options</a></li>
    <li class="active">Google reCaptcha</li>
</ul>
<div class="row">
    <div class="col-lg-9">
        <form method="post" action="{{ url("option/googleReCaptcha") }}">
            <div class="row">
                <div class="col-sm-5">
                    <div class="form-group">
                        <label for="sitekey" class="control-label">Sitekey</label>
                        {{ form.render("sitekey", ["class": "form-control"]) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-5">
                    <div class="form-group">
                        <label for="secret" class="control-label">Secret</label>
                        {{ form.render("secret", ["class": "form-control"]) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="TriSea-technologies-Switch">
                        {{ form.render("registrationEnabled") }}
                        <label for="registrationEnabled" class="label-primary switch-label"></label>
                        <label for="registrationEnabled">Actif pour l'inscription</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="TriSea-technologies-Switch">
                        {{ form.render("commentsEnabled") }}
                        <label for="commentsEnabled" class="label-primary switch-label"></label>
                        <label for="commentsEnabled">Actif pour les commentaires</label>
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
