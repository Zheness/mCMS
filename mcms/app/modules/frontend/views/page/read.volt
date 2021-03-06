<ol class="breadcrumb">
    <li><a href="{{ url('') }}">Accueil</a></li>
    <li><a href="{{ url('page') }}">Pages</a></li>
    <li class="active">{{ page.title }}</li>
</ol>
<article>
    <h1>
        {{ page.title }}
        {% if page.isPrivate %}
            <small><sup class="fa fa-lock" data-toggle="tooltip" data-placement="top" title="Page privée"></sup></small>
        {% endif %}
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
<hr>
<div id="comments">
    <h1>Commentaires</h1>
    {% if commentsOpen %}
        <form action="{{ url('page/read/' ~ page.slug) }}" method="post">
            <div class="form-group">
                <label for="username" class="control-label">Nom</label>
                <div class="row">
                    <div class="col-md-5">
                        {% if session.has('member') %}
                            {{ text_field('username', "class": "form-control", "disabled": "disabled", "value": session.get('member').getFullname()) }}
                        {% else %}
                            {{ formComment.render("username", ["class": "form-control"]) }}
                        {% endif %}
                    </div>
                </div>
                {% if session.has('member') is false %}
                    <p class="help-block">Entrez votre nom, celui-ci sera affiché au-dessus du commentaire.</p>
                {% endif %}
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <label for="content" class="control-label">Commentaire</label>
                        {{ formComment.render("content", ["class": "form-control", "rows": "5"]) }}
                    </div>
                </div>
            </div>
            {% if reCaptchaEnabled %}
                <div class="form-group">
                    <div class="g-recaptcha" data-sitekey="{{ reCaptchaKey }}"></div>
                </div>
            {% endif %}
            <div class="form-group">
                <input type="hidden" name="{{ csrfKey }}" value="{{ csrf }}">
                <button type="submit" class="btn btn-primary">Envoyer le commentaire</button>
            </div>
        </form>
        {% if page.Comments.count() == 0 %}
            <p><i>Aucun commentaire pour le moment. Ajoutez le premier !</i></p>
        {% endif %}
        {% for comment in page.Comments %}
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ comment.username }} - {{ comment.dateCreatedToFr() }}
                </div>
                <div class="panel-body nl2br">
                    {{ comment.content }}
                </div>
            </div>
            {% for subcomment in comment.Comments %}
                <div class="row">
                    <div class="col-md-offset-1 col-md-11">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                {{ subcomment.username }} - {{ subcomment.dateCreatedToFr() }}
                            </div>
                            <div class="panel-body nl2br">
                                {{ subcomment.content }}
                            </div>
                        </div>
                    </div>
                </div>
            {% endfor %}
        {% endfor %}
    {% else %}
        <p><i>Les commentaires sont désactivés sur cette page.</i></p>
    {% endif %}
</div>
