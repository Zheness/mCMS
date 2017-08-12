<div>
    <h3>Dernières pages</h3>
    <ul>
        {% for page in menu_latestPages %}
            <li>
                <a href="{{ page.getUrl() }}">
                    {{ page.title }}
                </a>
            </li>
        {% endfor %}
    </ul>
</div>
