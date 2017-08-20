<ol class="breadcrumb">
    <li><a href="{{ url('') }}">Accueil</a></li>
    <li class="active">Articles</li>
</ol>
{% for article in articles %}
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                {% if article.isPrivate %}
                    <span class="fa fa-lock" data-toggle="tooltip" data-placement="top" title="Article privé"></span>
                {% endif %}
                <a href="{{ article.getUrl() }}">{{ article.title }}</a>
            </h3>
        </div>
        <div class="panel-body">
            {{ article.truncateContent() }}
        </div>
        <div class="panel-footer">
            <p class="text-right no-margin">
                <a href="{{ article.getUrl() }}" class="btn btn-primary">Lire l'article</a>
                {% if article.commentsOpen %}
                    <a href="{{ article.getUrl() }}#comments" class="btn btn-default">
                        {{ article.Comments.count() }} {{ article.Comments.count() == 1 ? 'commentaire' : 'commentaires' }}
                    </a>
                {% else %}
                    <a href="{{ article.getUrl() }}#comments" class="btn btn-default disabled">
                        Commentaires fermés
                    </a>
                {% endif %}
            </p>
        </div>
    </div>
{% endfor %}
