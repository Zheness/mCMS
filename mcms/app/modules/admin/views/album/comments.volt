<h1 class="page-header">Gestion des commentaires d'un album</h1>
{{ flashSession.output() }}
<ul class="breadcrumb">
    <li><a href="{{ url("") }}">Accueil</a></li>
    <li><a href="{{ url("album") }}">Albums</a></li>
    <li class="active">{{ album.title }}</li>
    <li class="active">Gestion des commentaires</li>
</ul>
<ul class="nav nav-tabs margin-bottom-10">
    <li><a href="{{ url("album/edit/" ~ album.id) }}"><span class="fa fa-edit"></span> Modifier</a></li>
    <li><a href="{{ url("album/images/" ~ album.id) }}"><span class="fa fa-image"></span> Images
            ({{ album.Images.count() }})</a></li>
    <li class="active"><a href="{{ url("album/comments/" ~ album.id) }}"><span class="fa fa-comments"></span>
            Commentaires ({{ album.Comments.count() }})</a></li>
    <li><a href="{{ url("album/delete/" ~ album.id) }}" class="text-danger"><span class="fa fa-trash"></span>
            Supprimer</a></li>
</ul>
<p class="clearfix"><a href="{{ album.getUrl() }}" class="btn btn-primary pull-right"><span
            class="fa fa-external-link"></span> Ouvrir l'album</a></p>
<div class="row">
    <div class="col-lg-9">
        {% for comment in album.CommentsDesc %}
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            {{ comment.username }} - {{ comment.dateCreatedToFr() }}
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
            {% for subcomment in comment.Comments %}
                <div class="row">
                    <div class="col-md-offset-1 col-md-11">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                {{ subcomment.username }} - {{ subcomment.dateCreatedToFr() }}
                            </div>
                            <div class="panel-body nl2br">
                                {{ subcomment.content }}
                            </div>
                            <div class="panel-footer">
                                <p class="text-right no-margin">
                                    <a href="{{ url('comment/delete/' ~ subcomment.id) }}" class="btn btn-danger">Supprimer</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            {% endfor %}
        {% endfor %}
    </div>
</div>
