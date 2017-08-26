<ol class="breadcrumb">
    <li><a href="{{ url('') }}">Accueil</a></li>
    <li><a href="{{ url('album') }}">Albums</a></li>
    <li class="active">{{ album.title }}</li>
</ol>
<article>
    <h1>
        {{ album.title }}
        {% if album.isPrivate %}
            <small><sup class="fa fa-lock" data-toggle="tooltip" data-placement="top" title="Album privé"></sup></small>
        {% endif %}
    </h1>
    <p class="text-muted">
        Rédigé par {{ album.createdByMember.getFullname() }} le {{ album.dateCreatedToFr() }}.
    </p>
    {% if album.updatedBy != null %}
        <p class="text-muted">
            Dernière modification par {{ album.updatedByMember.getFullname() }} le {{ album.dateUpdatedToFr() }}.
        </p>
    {% endif %}

    <div>
        {{ album.content }}
    </div>
    <div>
        <div class="row">
            {% for albumImage in album.Images %}
                {% set image = albumImage.Image %}
                <div class="col-md-3">
                    <a href="#" class="thumbnail" data-toggle="modal" data-target="#modalImage{{ image.id }}">
                        <img src="{{ image.getThumbnailUrl() }}" alt="Image - {{ image.title }}">
                    </a>
                </div>
            {% endfor %}
        </div>
    </div>

</article>
{% for albumImage in album.Images %}
    {% set image = albumImage.Image %}
    <div class="modal fade" id="modalImage{{ image.id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">{{ image.title }}</h4>
                </div>
                <div class="modal-body">
                    <img src="{{ image.getUrl() }}" alt="Image - {{ image.title }}">
                    <div class="nl2br">{{ image.description }}</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
{% endfor %}
<hr>
<div id="comments">
    <h1>Commentaires</h1>
    {% if commentsOpen %}
        <form action="{{ url('album/read/' ~ album.slug) }}" method="post">
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
        {% if album.Comments.count() == 0 %}
            <p><i>Aucun commentaire pour le moment. Ajoutez le premier !</i></p>
        {% endif %}
        {% for comment in album.Comments %}
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
