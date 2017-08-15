<h1 class="page-header">Lire une conversation</h1>
{{ flashSession.output() }}
<ul class="breadcrumb">
    <li><a href="{{ url("") }}">Accueil</a></li>
    <li><a href="{{ url("message") }}">Messages</a></li>
    <li class="active">{{ thread.subject }}</li>
    <li class="active">Lire</li>
</ul>
<ul class="nav nav-tabs margin-bottom-10">
    <li class="active">
        <a href="{{ url("message/thread/" ~ thread.token) }}"><span class="fa fa-edit"></span> Lire</a>
    </li>
    <li><a href="{{ url("message/delete/" ~ thread.token) }}" class="text-danger"><span class="fa fa-trash"></span>
            Supprimer</a></li>
</ul>
<div class="row">
    <div class="col-lg-9">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    {{ thread.firstname }} {{ thread.lastname }}
                    <span class="text-muted">- {{ thread.dateCreatedToFr() }}</span>
                </h3>
            </div>
            <div class="panel-body">
                <div class="nl2br">{{ thread.content }}</div>
            </div>
        </div>
        {% for message in thread.Messages %}
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        {{ message.firstname }} {{ message.lastname }}
                        <span class="text-muted">- {{ message.dateCreatedToFr() }}</span>
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="nl2br">{{ message.content }}</div>
                </div>
            </div>
        {% endfor %}
        <h3>Répondre à la conversation</h3>
        <form method="post" action="{{ url("message/thread/" ~ thread.token) }}">
            <div class="form-group">
                <label for="email" class="control-label">Message</label>
                <div class="row">
                    <div class="col-md-9">
                        {{ form.render("content", ["class": "form-control", "rows": "10"]) }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <input type="hidden" name="{{ csrfKey }}" value="{{ csrf }}">
                <button type="submit" class="btn btn-primary">Envoyer le message</button>
            </div>
        </form>
    </div>
</div>
