<h1 class="page-header">Gestion des commentaires d'un membre</h1>
{{ flashSession.output() }}
<ul class="breadcrumb">
    <li><a href="{{ url("") }}">Accueil</a></li>
    <li><a href="{{ url("member") }}">Membres</a></li>
    <li class="active">{{ member.getFullname() }}</li>
    <li class="active">Modifier</li>
</ul>
<ul class="nav nav-tabs margin-bottom-10">
    <li>
        <a href="{{ url("member/edit/" ~ member.id) }}"><span class="fa fa-edit"></span> Modifier</a>
    </li>
    <li>
        <a href="{{ url("member/profilePicture/" ~ member.id) }}"><span class="fa fa-picture-o"></span> Image de profil</a>
    </li>
    <li>
        <a href="{{ url("member/invite/" ~ member.id) }}"><span class="fa fa-envelope-o"></span> Inviter à devenir
            administrateur</a>
    </li>
    <li class="active">
        <a href="{{ url("member/comments/" ~ member.id) }}"><span class="fa fa-comments"></span> Commentaires
            ({{ member.CommentsCreated.count() }})</a>
    </li>
    <li><a href="{{ url("member/delete/" ~ member.id) }}" class="text-danger"><span class="fa fa-trash"></span>
            Supprimer</a></li>
</ul>
<div class="row">
    <div class="col-lg-9">
        {% for comment in member.CommentsCreatedDesc %}
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            {{ comment.username }} - {{ comment.dateCreatedToFr() }} - {{ comment.getAdminLinkToElement() }}
                        </div>
                        <div class="panel-body nl2br">
                            {{ comment.content }}
                        </div>
                        <div class="panel-footer">
                            <p class="text-right no-margin">
                                <a href="{{ url('comment/delete/' ~ comment.id) }}" class="btn btn-danger">Supprimer</a>
                                <a href="{{ url('comment/reply/' ~ comment.id) }}" class="btn btn-primary">Répondre</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        {% endfor %}
    </div>
</div>
