<ol class="breadcrumb">
    <li><a href="{{ url('') }}">Accueil</a></li>
    <li class="active">Profil</li>
    <li class="active">Modifier mon image de profil</li>
</ol>
<ul class="nav nav-tabs margin-bottom-10">
    <li><a href="{{ url("member/edit") }}"><span class="fa fa-user"></span> Informations</a></li>
    <li class="active"><a href="{{ url("member/profilePicture") }}"><span class="fa fa-picture-o"></span> Image de
            profil</a></li>
    <li><a href="{{ url("member/password") }}"><span class="fa fa-key"></span> Mot de passe</a></li>
    <li>
        <a href="{{ url("member/unsubscribe") }}" class="text-danger">
            <span class="fa fa-trash"></span> Désinscription
        </a>
    </li>
</ul>
{% if member.profilePicture %}
    <div class="text-center">
        <img src="{{ member.ProfilePicture.getUrl() }}" class="img-thumbnail thumbnail-image">
    </div>
{% endif %}
<form method="post" action="{{ url("member/profilePicture") }}" enctype="multipart/form-data">
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="file" class="control-label">
                    Fichier
                </label>
                {{ form.render("file") }}
            </div>
        </div>
    </div>
    <div class="form-group">
        <input type="hidden" name="{{ csrfKey }}" value="{{ csrf }}">
        <button type="submit" class="btn btn-primary">Envoyer l'image</button>
    </div>
</form>
