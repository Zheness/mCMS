<ol class="breadcrumb">
    <li><a href="{{ url('') }}">Accueil</a></li>
    <li class="active">Pages</li>
</ol>
{% for page in pages %}
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                <a href="{{ page.getUrl() }}">{{ page.title }}</a>
            </h3>
        </div>
        <div class="panel-body">
            {{ page.truncateContent() }}
        </div>
        <div class="panel-footer">
            <p class="text-right no-margin">
                <a href="{{ page.getUrl() }}" class="btn btn-primary">Voir la page</a>
                {% if page.commentsOpen %}
                    <a href="{{ page.getUrl() }}#comments" class="btn btn-default">
                        {{ page.Comments.count() }} {{ page.Comments.count() == 1 ? 'commentaire' : 'commentaires' }}
                    </a>
                {% else %}
                    <a href="{{ page.getUrl() }}#comments" class="btn btn-default disabled">
                        Commentaires fermés
                    </a>
                {% endif %}
            </p>
        </div>
    </div>
{% endfor %}
