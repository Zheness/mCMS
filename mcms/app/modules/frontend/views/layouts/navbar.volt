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
                    <a href="{{ url('message') }}">Contact</a>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="{{ activeMenu == 'signin' ? 'active' : '' }}">
                    <a href="{{ url('index/login') }}">Connexion</a>
                </li>
                <li class="{{ activeMenu == 'signup' ? 'active' : '' }}">
                    <a href="{{ url('index/signup') }}">Inscription</a>
                </li>
            </ul>
        </div>
    </div>
</nav>