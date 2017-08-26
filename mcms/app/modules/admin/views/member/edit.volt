<h1 class="page-header">Modification d'un membre</h1>
{{ flashSession.output() }}
<ul class="breadcrumb">
    <li><a href="{{ url("") }}">Accueil</a></li>
    <li><a href="{{ url("member") }}">Membres</a></li>
    <li class="active">{{ member.getFullname() }}</li>
    <li class="active">Modifier</li>
</ul>
<ul class="nav nav-tabs margin-bottom-10">
    <li class="active">
        <a href="{{ url("member/edit/" ~ member.id) }}"><span class="fa fa-edit"></span> Modifier</a>
    </li>
    <li>
        <a href="{{ url("member/profilePicture/" ~ member.id) }}"><span class="fa fa-picture-o"></span> Image de profil</a>
    </li>
    <li>
        <a href="{{ url("member/invite/" ~ member.id) }}"><span class="fa fa-envelope-o"></span> Inviter à devenir
            administrateur</a>
    </li>
    <li><a href="{{ url("member/comments/" ~ member.id) }}"><span class="fa fa-comments"></span> Commentaires
            ({{ member.CommentsCreated.count() }})</a></li>
    <li><a href="{{ url("member/delete/" ~ member.id) }}" class="text-danger"><span class="fa fa-trash"></span>
            Supprimer</a></li>
</ul>
<dl class="dl-horizontal">
    <dt>Création</dt>
    <dd>{{ member.dateCreatedToFr() }} - {{ member.getAdminLinkCreator() }}</dd>
    <dt>Modification</dt>
    <dd>{{ member.dateUpdatedToFr() }} - {{ member.getAdminLinkLastEditor() }}</dd>
</dl>
<div class="row">
    <div class="col-lg-9">
        <form method="post" action="{{ url("member/edit/" ~ member.id) }}">
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
                <button type="submit" class="btn btn-primary">Modifier</button>
            </div>
        </form>
    </div>
</div>
