<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#offcanvas">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ url('') }}">{{ config.site.name }}</a>
        </div>

        <div class="collapse navbar-collapse" id="offcanvas">
            <ul class="nav navbar-nav">
                <li class="{{ activeMenu == 'homepage' ? 'active' : '' }}">
                    <a href="{{ url('') }}">Accueil</a>
                </li>
                <li class="{{ activeMenu == 'pages' ? 'active' : '' }}">
                    <a href="{{ url('page') }}">Pages</a>
                </li>
                <li class="{{ activeMenu == 'albums' ? 'active' : '' }}">
                    <a href="{{ url('album') }}">Albums</a>
                </li>
                <li class="{{ activeMenu == 'articles' ? 'active' : '' }}">
                    <a href="{{ url('article') }}">Articles</a>
                </li>
                <li class="{{ activeMenu == 'contact' ? 'active' : '' }}">
                    <a href="{{ url('message/new') }}">Contact</a>
                </li>
            </ul>
            {% if session.has('member') %}
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-user fa-fw"></i> {{ session.get("member").getFullname() }} <i
                                class="fa fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li>
                                <a href="{{ url("member/edit") }}">
                                    <i class="fa fa-edit fa-fw"></i>
                                    Informations
                                </a>
                            </li>
                            <li>
                                <a href="{{ url("member/profilePicture") }}">
                                    <i class="fa fa-picture-o fa-fw"></i>
                                    Image de profil
                                </a>
                            </li>
                            <li>
                                <a href="{{ url("member/password") }}">
                                    <i class="fa fa-key fa-fw"></i>
                                    Mot de passe
                                </a>
                            </li>
                            <li class="divider"></li>
                            {% if session.get('member').role == 'admin' %}
                                <li>
                                    <a href="{{ url("admin") }}" target="_blank">
                                        <i class="fa fa-external-link fa-fw"></i> Administration
                                    </a>
                                </li>
                            {% endif %}
                            <li>
                                <a href="{{ url("index/logout") }}"><i class="fa fa-sign-out fa-fw"></i> Déconnexion</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            {% else %}
                <ul class="nav navbar-nav navbar-right">
                    <li class="{{ activeMenu == 'login' ? 'active' : '' }}">
                        <a href="{{ url('index/login') }}">Connexion</a>
                    </li>
                    {% if registrationAllowed %}
                        <li class="{{ activeMenu == 'signup' ? 'active' : '' }}">
                            <a href="{{ url('index/signup') }}">Inscription</a>
                        </li>
                    {% endif %}
                </ul>
            {% endif %}
        </div>
    </div>
</nav>
