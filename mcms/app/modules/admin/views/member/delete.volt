<h1 class="page-header">Suppression d'un membre</h1>
{{ flashSession.output() }}
<ul class="breadcrumb">
    <li><a href="{{ url("") }}">Accueil</a></li>
    <li><a href="{{ url("member") }}">Membres</a></li>
    <li class="active">{{ member.getFullname() }}</li>
    <li class="active">Supprimer</li>
</ul>
<ul class="nav nav-tabs margin-bottom-10">
    <li>
        <a href="{{ url("member/edit/" ~ member.id) }}"><span class="fa fa-edit"></span> Modifier</a>
    </li>
    <li>
        <a href="{{ url("member/profilePicture/" ~ member.id) }}"><span class="fa fa-picture-o"></span> Image de profil</a>
    </li>
    <li>
        <a href="{{ url("member/invite/" ~ member.id) }}"><span class="fa fa-envelope-o"></span> Inviter à devenir
            administrateur</a>
    </li>
    <li><a href="{{ url("member/comments/" ~ member.id) }}"><span class="fa fa-comments"></span> Commentaires
            ({{ member.CommentsCreated.count() }})</a></li>
    <li class="active"><a href="{{ url("member/delete/" ~ member.id) }}" class="text-danger"><span
                class="fa fa-trash"></span> Supprimer</a></li>
</ul>
<div class="row">
    <div class="col-sm-12">
        <p>Attention, souhaitez-vous réelement supprimer le membre <b>{{ member.getFullname() }}</b> ?</p>
        {% if member.PagesCreated.count() %}
            <p>Les pages suivant seront <b>conservées</b> et rattachées à l'administrateur principal {{ root.generateAdminMemberLink() }}.</p>
            <ul>
                {% for page in member.PagesCreated %}
                    <li>
                        <a href="{{ url('page/edit/' ~ page.id) }}">{{ page.title }}</a>
                    </li>
                {% endfor %}
            </ul>
        {% endif %}
        {% if member.AlbumsCreated.count() %}
            <p>Les albums suivant seront <b>conservés</b> et rattachés à l'administrateur principal {{ root.generateAdminMemberLink() }}.</p>
            <ul>
                {% for album in member.AlbumsCreated %}
                    <li>
                        <a href="{{ url('album/edit/' ~ album.id) }}">{{ album.title }}</a>
                    </li>
                {% endfor %}
            </ul>
        {% endif %}
        {% if member.ArticlesCreated.count() %}
            <p>Les articles suivant seront <b>conservés</b> et rattachés à l'administrateur principal {{ root.generateAdminMemberLink() }}.</p>
            <ul>
                {% for article in member.ArticlesCreated %}
                    <li>
                        <a href="{{ url('article/edit/' ~ article.id) }}">{{ article.title }}</a>
                    </li>
                {% endfor %}
            </ul>
        {% endif %}
        {% if member.ImagesCreated.count() %}
            <p>{{ member.ImagesCreated.count() }} images seront <b>conservées</b> et rattachées à l'administrateur principal {{ root.generateAdminMemberLink() }}.</p>
        {% endif %}
        {% if member.MembersCreated.count() %}
            <p>{{ member.MembersCreated.count() }} membres seront <b>conservés</b> et rattachés à l'administrateur principal {{ root.generateAdminMemberLink() }}.</p>
        {% endif %}
        {% if member.MessagesCreated.count() %}
            <p>{{ member.MessagesCreated.count() }} messages de conversations seront <b>conservés</b> et rattachés à l'administrateur principal {{ root.generateAdminMemberLink() }}.</p>
        {% endif %}
        {% if member.CommentsCreated.count() %}
            <p>{{ member.CommentsCreated.count() }} commentaires seront <b>supprimés</b>.</p>
        {% endif %}
        <p>Toute suppression est définitive.</p>
    </div>
    <div class="col-lg-9">
        <form method="post" action="{{ url("member/delete/" ~ member.id) }}">
            <div class="form-group">
                {{ form.render("action") }}
                <input type="hidden" name="{{ csrfKey }}" value="{{ csrf }}">
                <button type="submit" class="btn btn-danger">Supprimer</button>
                <a href="{{ url("member") }}" class="btn btn-default">Retour à la liste</a>
            </div>
        </form>
    </div>
</div>
