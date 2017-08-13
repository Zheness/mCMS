<ol class="breadcrumb">
    <li><a href="{{ url('') }}">Accueil</a></li>
    <li class="active">Albums</li>
</ol>
{% for album in albums %}
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                {% if album.isPrivate %}
                    <span class="fa fa-lock" data-toggle="tooltip" data-placement="top" title="Album privé"></span>
                {% endif %}
                <a href="{{ album.getUrl() }}">{{ album.title }}</a>
            </h3>
        </div>
        <div class="panel-body">
            {% if album.Images.count() >= 1 %}
                <div class="thumbnail">
                    <img src="/img/upload/{{ album.Images[0].Image.filename }}" alt="Image - {{ album.Images[0].Image.title }}">
                </div>
            {% endif %}
            {{ album.truncateContent() }}
        </div>
        <div class="panel-footer">
            <p class="text-right no-margin">
                <a href="{{ album.getUrl() }}" class="btn btn-primary">Voir l'album</a>
                {% if album.commentsOpen %}
                    <a href="{{ album.getUrl() }}#comments" class="btn btn-default">
                        {{ album.Comments.count() }} {{ album.Comments.count() == 1 ? 'commentaire' : 'commentaires' }}
                    </a>
                {% else %}
                    <a href="{{ album.getUrl() }}#comments" class="btn btn-default disabled">
                        Commentaires fermés
                    </a>
                {% endif %}
            </p>
        </div>
    </div>
{% endfor %}
