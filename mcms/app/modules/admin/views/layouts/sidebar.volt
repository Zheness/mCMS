<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            <!--<li>
                <a href="{{ url() }}"><i class="fa fa-dashboard fa-fw"></i> Accueil</a>
            </li>-->
            <li>
                <a href="{{ url('message') }}">
                    <i class="fa fa-envelope-o fa-fw"></i>
                    Messagerie
                    {% if menu_unreadMessages != 0 %}
                        <span class="badge progress-bar-danger">{{ menu_unreadMessages }}</span>
                    {% endif %}
                </a>
            </li>
            <li>
                <a href="#"><i class="fa fa-file-o fa-fw"></i> Pages<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{ url("page") }}"><i class="fa fa-list"></i> Liste des pages</a>
                    </li>
                    <li>
                        <a href="{{ url("page/add") }}"><i class="fa fa-plus"></i> Nouvelle page</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-file-image-o fa-fw"></i> Images<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{ url("image") }}"><i class="fa fa-list"></i> Liste des images</a>
                    </li>
                    <li>
                        <a href="{{ url("image/add") }}"><i class="fa fa-plus"></i> Nouvelle image</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-camera fa-fw"></i> Albums<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{ url("album") }}"><i class="fa fa-list"></i> Liste des albums</a>
                    </li>
                    <li>
                        <a href="{{ url("album/add") }}"><i class="fa fa-plus"></i> Nouvel album</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-file-text-o fa-fw"></i> Articles<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{ url("article") }}"><i class="fa fa-list"></i> Liste des articles</a>
                    </li>
                    <li>
                        <a href="{{ url("article/add") }}"><i class="fa fa-plus"></i> Nouvel article</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-users fa-fw"></i> Utilisateurs<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{ url("member") }}"><i class="fa fa-list"></i> Liste des membres</a>
                    </li>
                    <li>
                        <a href="{{ url("member/add") }}"><i class="fa fa-plus"></i> Nouveau membre</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{ url("specialPage") }}"><i class="fa fa-files-o fa-fw"></i> Pages spéciales</a>
            </li>
            <li>
                <a href="{{ url('comment') }}">
                    <i class="fa fa-comments fa-fw"></i>
                    Commentaires
                </a>
            </li>
            <li>
                <a href="{{ url('option') }}">
                    <i class="fa fa-cogs fa-fw"></i>
                    Options
                </a>
            </li>
            <li class="visible-xs">
                <a href="#"><i class="fa fa-user fa-fw"></i> {{ session.get("member").getFullname() }}<span
                        class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                    </li>
                    <li>
                        <a href="{{ url("index/logout") }}"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>
