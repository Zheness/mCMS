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
