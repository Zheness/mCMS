<ol class="breadcrumb">
    <li><a href="{{ url('') }}">Accueil</a></li>
    <li class="active">Profil</li>
    <li class="active">Désinscription</li>
</ol>
<ul class="nav nav-tabs margin-bottom-10">
    <li><a href="{{ url("member/edit") }}"><span class="fa fa-user"></span> Informations</a></li>
    <li><a href="{{ url("member/profilePicture") }}"><span class="fa fa-picture-o"></span> Image de profil</a></li>
    <li><a href="{{ url("member/password") }}"><span class="fa fa-key"></span> Mot de passe</a></li>
    <li class="active">
        <a href="{{ url("member/unsubscribe") }}" class="text-danger">
            <span class="fa fa-trash"></span> Désinscription
        </a>
    </li>
</ul>
<p>Attention, souhaitez-vous réelement supprimer votre compte ?</p>
{% if member.PagesCreated.count() %}
    <p>Les pages suivant seront <b>conservées</b> et rattachées à l'administrateur
        principal {{ root.generateAdminMemberLink() }}.</p>
    <ul>
        {% for page in member.PagesCreated %}
            <li>
                <a href="{{ url('page/edit/' ~ page.id) }}">{{ page.title }}</a>
            </li>
        {% endfor %}
    </ul>
{% endif %}
    {% if member.AlbumsCreated.count() %}
        <p>Les albums suivant seront <b>conservés</b> et rattachés à l'administrateur
            principal {{ root.generateAdminMemberLink() }}.</p>
        <ul>
            {% for album in member.AlbumsCreated %}
                <li>
                    <a href="{{ url('album/edit/' ~ album.id) }}">{{ album.title }}</a>
                </li>
            {% endfor %}
        </ul>
    {% endif %}
    {% if member.ArticlesCreated.count() %}
        <p>Les articles suivant seront <b>conservés</b> et rattachés à l'administrateur
            principal {{ root.generateAdminMemberLink() }}.</p>
        <ul>
            {% for article in member.ArticlesCreated %}
                <li>
                    <a href="{{ url('article/edit/' ~ article.id) }}">{{ article.title }}</a>
                </li>
            {% endfor %}
        </ul>
    {% endif %}
    {% if member.ImagesCreated.count() %}
        <p>{{ member.ImagesCreated.count() }} images seront <b>conservées</b> et rattachées à l'administrateur
            principal {{ root.generateAdminMemberLink() }}.</p>
    {% endif %}
    {% if member.MembersCreated.count() %}
        <p>{{ member.MembersCreated.count() }} membres seront <b>conservés</b> et rattachés à l'administrateur
            principal {{ root.generateAdminMemberLink() }}.</p>
    {% endif %}
    {% if member.MessagesCreated.count() %}
        <p>{{ member.MessagesCreated.count() }} messages de conversations seront <b>conservés</b> et rattachés à
            l'administrateur principal {{ root.generateAdminMemberLink() }}.</p>
    {% endif %}
    {% if member.CommentsCreated.count() %}
        <p>{{ member.CommentsCreated.count() }} commentaires seront <b>supprimés</b>.</p>
    {% endif %}
<p>Toute suppression est définitive.</p>
<form method="post" action="{{ url("member/unsubscribe") }}">
    <div class="form-group">
        {{ form.render("action") }}
        <input type="hidden" name="{{ csrfKey }}" value="{{ csrf }}">
        <button type="submit" class="btn btn-danger">Supprimer mon compte</button>
    </div>
</form>
