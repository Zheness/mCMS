<ol class="breadcrumb">
    <li><a href="{{ url('') }}">Accueil</a></li>
    <li><a href="{{ url('page') }}">Pages</a></li>
    <li class="active">{{ page.title }}</li>
</ol>
<article>
    <h1>
        {{ page.title }}
    </h1>
    <p class="text-muted">
        Rédigé par {{ page.createdByMember.getFullname() }} le {{ page.dateCreatedToFr() }}.
    </p>
    {% if page.updatedBy != null %}
        <p class="text-muted">
            Dernière modification par {{ page.updatedByMember.getFullname() }} le {{ page.dateUpdatedToFr() }}.
        </p>
    {% endif %}

    <div>
        {{ page.content }}
    </div>

</article>
