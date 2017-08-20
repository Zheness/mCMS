<ol class="breadcrumb">
    <li><a href="{{ url('') }}">Accueil</a></li>
    <li class="active">Profil</li>
    <li class="active">Modifier mon mot de passe</li>
</ol>
<ul class="nav nav-tabs margin-bottom-10">
    <li><a href="{{ url("member/edit") }}"><span class="fa fa-user"></span> Informations</a></li>
    <li><a href="{{ url("member/profilePicture") }}"><span class="fa fa-picture-o"></span> Image de profil</a></li>
    <li class="active"><a href="{{ url("member/password") }}"><span class="fa fa-key"></span> Mot de passe</a></li>
    <li>
        <a href="{{ url("member/unsubscribe") }}" class="text-danger">
            <span class="fa fa-trash"></span> Désinscription
        </a>
    </li>
</ul>
<form method="post" action="{{ url("member/password") }}">
    <div class="form-group">
        <label for="password" class="control-label">Nouveau mot de passe</label>
        <div class="row">
            <div class="col-md-4">
                {{ form.render("password", ["class": "form-control"]) }}
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="passwordConfirm" class="control-label">Confirmation du mot de passe</label>
        <div class="row">
            <div class="col-md-4">
                {{ form.render("passwordConfirm", ["class": "form-control"]) }}
            </div>
        </div>
    </div>
    <div class="form-group">
        <input type="hidden" name="{{ csrfKey }}" value="{{ csrf }}">
        <button type="submit" class="btn btn-primary">Modifier</button>
    </div>
</form>
