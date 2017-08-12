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
