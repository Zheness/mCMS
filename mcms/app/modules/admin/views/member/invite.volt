<h1 class="page-header">Envoyer un mail d'invitation</h1>
{{ flashSession.output() }}
<ul class="breadcrumb">
    <li><a href="{{ url("") }}">Accueil</a></li>
    <li><a href="{{ url("member") }}">Membres</a></li>
    <li class="active">{{ member.getFullname() }}</li>
    <li class="active">Inviter à devenir administrateur</li>
</ul>
<ul class="nav nav-tabs margin-bottom-10">
    <li>
        <a href="{{ url("member/edit/" ~ member.id) }}"><span class="fa fa-edit"></span> Modifier</a>
    </li>
    <li>
        <a href="{{ url("member/profilePicture/" ~ member.id) }}"><span class="fa fa-picture-o"></span> Image de profil</a>
    </li>
    <li class="active">
        <a href="{{ url("member/invite/" ~ member.id) }}"><span class="fa fa-envelope-o"></span> Inviter à devenir
            administrateur</a>
    </li>
    <li><a href="{{ url("member/comments/" ~ member.id) }}"><span class="fa fa-comments"></span> Commentaires
            ({{ member.CommentsCreated.count() }})</a></li>
    <li><a href="{{ url("member/delete/" ~ member.id) }}" class="text-danger"><span class="fa fa-trash"></span>
            Supprimer</a></li>
</ul>
<p>En invitant un membre, son rôle passera a <b>Adminsitrateur</b> et son statut à <b>Actif</b> et un email lui sera envoyé pour l'informer de ce
    changement.</p>
<div class="row">
    <div class="col-lg-9">
        <form method="post" action="{{ url("member/invite/" ~ member.id) }}">
            <div class="form-group">
                {{ form.render("action") }}
                <input type="hidden" name="{{ csrfKey }}" value="{{ csrf }}">
                <button type="submit" class="btn btn-primary">Imviter</button>
            </div>
        </form>
    </div>
</div>
