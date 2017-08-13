<ol class="breadcrumb">
    <li><a href="{{ url('') }}">Accueil</a></li>
    <li class="active">Profil</li>
    <li class="active">Modifier mes informations</li>
</ol>
<ul class="nav nav-tabs margin-bottom-10">
    <li class="active"><a href="{{ url("member/edit") }}">Informations</a></li>
    <li><a href="{{ url("member/password") }}">Mot de passe</a></li>
    <li>
        <a href="{{ url("member/unsubscribe") }}" class="text-danger">
            <span class="fa fa-trash"></span> Désinscription
        </a>
    </li>
</ul>
<form method="post" action="{{ url("member/edit") }}">
    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="firstname" class="control-label">Prénom</label>
                        <div class="row">
                            <div class="col-md-12">
                                {{ form.render("firstname", ["class": "form-control"]) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="lastname" class="control-label">Nom</label>
                        <div class="row">
                            <div class="col-md-12">
                                {{ form.render("lastname", ["class": "form-control"]) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="email" class="control-label">Email</label>
        <div class="row">
            <div class="col-md-8">
                {{ form.render("email", ["class": "form-control"]) }}
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="username" class="control-label">
            Pseudonyme <span class="text-muted">- Facultatif</span>
        </label>
        <div class="row">
            <div class="col-md-4">
                {{ form.render("username", ["class": "form-control"]) }}
            </div>
        </div>
    </div>
    <div class="form-group">
        <input type="hidden" name="{{ csrfKey }}" value="{{ csrf }}">
        <button type="submit" class="btn btn-primary">Modifier</button>
    </div>
</form>
