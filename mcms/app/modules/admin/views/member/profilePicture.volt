<h1 class="page-header">Modification de l'image de profil d'un membre</h1>
{{ flashSession.output() }}
<ul class="breadcrumb">
    <li><a href="{{ url("") }}">Accueil</a></li>
    <li><a href="{{ url("member") }}">Membres</a></li>
    <li class="active">{{ member.getFullname() }}</li>
    <li class="active">Modifier l'image de profil</li>
</ul>
<ul class="nav nav-tabs margin-bottom-10">
    <li>
        <a href="{{ url("member/edit/" ~ member.id) }}"><span class="fa fa-edit"></span> Modifier</a>
    </li>
    <li class="active">
        <a href="{{ url("member/profilePicture/" ~ member.id) }}"><span class="fa fa-picture-o"></span> Image de profil</a>
    </li>
    <li><a href="{{ url("member/comments/" ~ member.id) }}"><span class="fa fa-comments"></span> Commentaires
            ({{ member.CommentsCreated.count() }})</a></li>
    <li><a href="{{ url("member/delete/" ~ member.id) }}" class="text-danger"><span class="fa fa-trash"></span>
            Supprimer</a></li>
</ul>
{% if member.profilePicture %}
    <div class="text-center">
        <img src="{{ member.ProfilePicture.getUrl() }}" class="img-thumbnail thumbnail-image">
    </div>
{% endif %}
<div class="row">
    <div class="col-lg-9">
        <form method="post" action="{{ url("member/profilePicture/" ~ member.id) }}" enctype="multipart/form-data">
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
                <button type="submit" class="btn btn-primary" name="edit">Envoyer l'image</button>
                <button type="submit" class="btn btn-danger" name="remove">Enlever l'image</button>
            </div>
        </form>
    </div>
</div>
