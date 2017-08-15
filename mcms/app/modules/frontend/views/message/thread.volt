<ol class="breadcrumb">
    <li><a href="{{ url('') }}">Accueil</a></li>
    <li class="active">Contact</li>
    <li class="active">Conversation: {{ thread.subject }}</li>
</ol>
<h2>Conversation <small>{{ thread.subject }}</small></h2>
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
