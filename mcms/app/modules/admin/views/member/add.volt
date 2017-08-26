<h1 class="page-header">Ajouter un nouveau membre</h1>
{{ flashSession.output() }}
<ul class="breadcrumb">
    <li><a href="{{ url("") }}">Accueil</a></li>
    <li><a href="{{ url("member") }}">Membres</a></li>
    <li class="active">Ajouter un nouveau membre</li>
</ul>
<div class="row">
    <div class="col-lg-9">
        <form method="post" action="{{ url("member/add") }}">
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
                <label for="role" class="control-label">
                    Rôle
                </label>
                <div class="row">
                    <div class="col-md-3">
                        {{ form.render("role", ["class": "form-control"]) }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="status" class="control-label">
                    Statut
                </label>
                <div class="row">
                    <div class="col-md-3">
                        {{ form.render("status", ["class": "form-control"]) }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="password" class="control-label">
                    Mot de passe
                    <span class="text-muted">- Facultatif</span>
                </label>
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
                <button type="submit" class="btn btn-primary">Ajouter</button>
            </div>
        </form>
    </div>
</div>
