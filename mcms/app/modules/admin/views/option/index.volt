<h1 class="page-header">Liste des options</h1>
{{ flashSession.output() }}
<ul class="breadcrumb">
    <li><a href="{{ url("") }}">Accueil</a></li>
    <li class="active">Options</li>
</ul>
<table class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th colspan="2">Option</th>
        <th>Valeur</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td colspan="2">Administrateur principal</td>
        <td>
            {{ options['root'] }}
        </td>
        <td>
            <a href="{{ url('option/root') }}" class="btn btn-default">
                <span class="fa fa-edit"></span> Modifier
            </a>
        </td>
    </tr>
    <tr>
        <td colspan="2">Site en maintenance</td>
        <td>
            {% if options['maintenance_enabled'] %}
                <span class="text-success"><i class="fa fa-check-circle"></i> Oui</span>
            {% else %}
                <span class="text-danger"><i class="fa fa-times-circle"></i> Non</span>
            {% endif %}
        </td>
        <td>
            <a href="{{ url('option/maintenance') }}" class="btn btn-default">
                <span class="fa fa-edit"></span> Modifier
            </a>
        </td>
    </tr>
    <tr>
        <td colspan="2">Notification affichée</td>
        <td>
            {% if options['notification_enabled'] %}
                <span class="text-success"><i class="fa fa-check-circle"></i> Oui</span>
            {% else %}
                <span class="text-danger"><i class="fa fa-times-circle"></i> Non</span>
            {% endif %}
        </td>
        <td>
            <a href="{{ url('option/notification') }}" class="btn btn-default">
                <span class="fa fa-edit"></span> Modifier
            </a>
        </td>
    </tr>
    <tr>
        <td colspan="2">Inscription autorisée</td>
        <td>
            {% if options['registration_allowed'] %}
                <span class="text-success"><i class="fa fa-check-circle"></i> Oui</span>
            {% else %}
                <span class="text-danger"><i class="fa fa-times-circle"></i> Non</span>
            {% endif %}
        </td>
        <td>
            <a href="{{ url('option/registration') }}" class="btn btn-default">
                <span class="fa fa-edit"></span> Modifier
            </a>
        </td>
    </tr>
    <tr>
        <td colspan="2">Commentaires</td>
        <td></td>
        <td>
            <a href="{{ url('option/comments') }}" class="btn btn-default">
                <span class="fa fa-edit"></span> Modifier
            </a>
        </td>
    </tr>
    <tr>
        <td></td>
        <td>Commentaires actifs</td>
        <td>
            {% if options['comments_allowed'] %}
                <span class="text-success"><i class="fa fa-check-circle"></i> Oui</span>
            {% else %}
                <span class="text-danger"><i class="fa fa-times-circle"></i> Non</span>
            {% endif %}
        </td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td>Commentaires autorisés sur les pages</td>
        <td>
            {% if options['comments_pages_allowed'] %}
                <span class="text-success"><i class="fa fa-check-circle"></i> Oui</span>
            {% else %}
                <span class="text-danger"><i class="fa fa-times-circle"></i> Non</span>
            {% endif %}
        </td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td>Commentaires autorisés sur les albums</td>
        <td>
            {% if options['comments_albums_allowed'] %}
                <span class="text-success"><i class="fa fa-check-circle"></i> Oui</span>
            {% else %}
                <span class="text-danger"><i class="fa fa-times-circle"></i> Non</span>
            {% endif %}
        </td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td>Commentaires autorisés sur les articles</td>
        <td>
            {% if options['comments_articles_allowed'] %}
                <span class="text-success"><i class="fa fa-check-circle"></i> Oui</span>
            {% else %}
                <span class="text-danger"><i class="fa fa-times-circle"></i> Non</span>
            {% endif %}
        </td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td>Nombre maximum de commentaires par jour pour les utilisateurs non connectés</td>
        <td>
            {{ options['comments_maximum_per_day'] }}
        </td>
        <td></td>
    </tr>
    <tr>
        <td colspan="2">Taille par défaut des miniatures d'images</td>
        <td>
            {{ options['thumbnail_dimensions'] }}
        </td>
        <td>
            <a href="{{ url('option/thumbnails') }}" class="btn btn-default">
                <span class="fa fa-edit"></span> Modifier
            </a>
        </td>
    </tr>
    <tr>
        <td colspan="2">Bandeau des cookies</td>
        <td></td>
        <td>
            <a href="{{ url('option/cookieConsent') }}" class="btn btn-default">
                <span class="fa fa-edit"></span> Modifier
            </a>
        </td>
    </tr>
    <tr>
        <td colspan="2">Google reCaptcha</td>
        <td></td>
        <td>
            <a href="{{ url('option/googleReCaptcha') }}" class="btn btn-default">
                <span class="fa fa-edit"></span> Modifier
            </a>
        </td>
    </tr>
    </tbody>
</table>
