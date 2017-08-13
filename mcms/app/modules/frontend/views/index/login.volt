<ol class="breadcrumb">
    <li><a href="{{ url('') }}">Accueil</a></li>
    <li class="active">Connexion à l'espace membre</li>
</ol>
<form class="form-horizontal" method="post" action="{{ url("index/login") }}">
    <div class="form-group">
        <label for="email" class="col-sm-2 control-label">Identifiant</label>
        <div class="col-sm-4">
            {{ form.render("email", ["class": "form-control"]) }}
        </div>
    </div>
    <div class="form-group">
        <label for="password" class="col-sm-2 control-label">Mot de passe</label>
        <div class="col-sm-4">
            {{ form.render("password", ["class": "form-control"]) }}
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <input type="hidden" name="{{ csrfKey }}" value="{{ csrf }}">
            <button type="submit" class="btn btn-default">Connexion</button>
            <a href="{{ url('member/passwordLost') }}">Mot de passe perdu ?</a>
        </div>
    </div>
</form>
