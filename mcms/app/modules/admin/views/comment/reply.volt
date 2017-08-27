<h1 class="page-header">Répondre à commentaire</h1>
{{ flashSession.output() }}
<ul class="breadcrumb">
    <li><a href="{{ url("") }}">Accueil</a></li>
    <li><a href="{{ url("comment") }}">Commentaires</a></li>
    <li class="active">Répondre</li>
</ul>
<ul class="nav nav-tabs margin-bottom-10">
    <li class="active"><a href="{{ url("comment/reply/" ~ comment.id) }}">
            <span class="fa fa-mail-reply"></span> Répondre</a>
    </li>
    <li>
        <a href="{{ url("comment/delete/" ~ comment.id) }}" class="text-danger">
            <span class="fa fa-trash"></span> Supprimer
        </a>
    </li>
</ul>
<div class="row">
    <div class="col-sm-12">
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
                </p>
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
    </div>
    <div class="col-md-offset-1 col-md-11">
        <form action="{{ url('comment/reply/' ~ comment.id) }}" method="post">
            <div class="panel panel-info">
                <div class="panel-heading">
                    {{ session.get('member').getFullname() }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="content" class="control-label">Commentaire</label>
                                {{ form.render("content", ["class": "form-control", "rows": "5"]) }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="{{ csrfKey }}" value="{{ csrf }}">
                    </div>
                </div>
                <div class="panel-footer">
                    <p class="text-right no-margin">
                        <button type="submit" class="btn btn-primary">Répondre</button>
                    </p>
                </div>
            </div>
        </form>
    </div>
</div>
