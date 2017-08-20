<div>
    <h3>Dernières pages</h3>
    <ul>
        {% for page in menu_latestPages %}
            <li>
                {% if page.isPrivate %}
                    <span class="fa fa-lock" data-toggle="tooltip" data-placement="top" title="Page privée"></span>
                {% endif %}
                <a href="{{ page.getUrl() }}">
                    {{ page.title }}
                </a>
            </li>
        {% endfor %}
    </ul>
</div>
<div>
    <h3>Derniers albums</h3>
    <ul>
        {% for album in menu_latestAlbums %}
            <li>
                {% if album.isPrivate %}
                    <span class="fa fa-lock" data-toggle="tooltip" data-placement="top" title="Album privé"></span>
                {% endif %}
                <a href="{{ album.getUrl() }}">
                    {{ album.title }}
                </a>
            </li>
        {% endfor %}
    </ul>
</div>
<div>
    <h3>Derniers articles</h3>
    <ul>
        {% for article in menu_latestArticles %}
            <li>
                {% if article.isPrivate %}
                    <span class="fa fa-lock" data-toggle="tooltip" data-placement="top" title="Article privé"></span>
                {% endif %}
                <a href="{{ article.getUrl() }}">
                    {{ article.title }}
                </a>
            </li>
        {% endfor %}
    </ul>
    <p><a href="{{ url('article/list/' ~ date('Y')) }}">Voir les articles de cette année</a></p>
    <p><a href="{{ url('article/list/' ~ date('Y/m')) }}">Voir les articles de ce mois-ci</a></p>
</div>
