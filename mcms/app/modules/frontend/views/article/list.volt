<ol class="breadcrumb">
    <li><a href="{{ url('') }}">Accueil</a></li>
    <li class="active">Articles</li>
    <li class="active">Liste par année</li>
</ol>
<h2>Articles pour l'année {{ year }}</h2>
<nav>
    <ul class="pager">
        {% if previousYearArticles %}
            <li class="previous">
                <a href="{{ url('article/list/' ~ (year - 1)) }}">
                    <span aria-hidden="true">&larr;</span> Année {{ year - 1 }}
                </a>
            </li>
        {% endif %}
        {% if (year + 1) <= date('Y') %}
            <li class="next">
                <a href="{{ url('article/list/' ~ (year + 1)) }}">
                    Année {{ year + 1 }} <span aria-hidden="true">&rarr;</span>
                </a>
            </li>
        {% endif %}
    </ul>
</nav>
<div class="row">
    {% for item in articles %}
        <div class="col-md-4">
            <div class="jumbotron">
                <h3>{{ item['name'] }}</h3>
                <h1>{{ item['count'] }}</h1>
                <p>
                    <a class="btn btn-primary" href="{{ url('article/list/' ~ item['year'] ~ '/' ~ item['month']) }}">
                        Voir les articles
                    </a>
                </p>
            </div>
        </div>
    {% endfor %}
</div>
