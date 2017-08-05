<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="{{ url() }}">{{ config.site.name }} - Administration</a>
    </div>

    <ul class="nav navbar-top-links navbar-right hidden-xs">
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-user fa-fw"></i> {{ session.get("user").getFullname() }} <i
                    class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
                <li>
                    <a href="{{ url("user/edit/" ~ session.get("user").id) }}"><i class="fa fa-gear fa-fw"></i>
                        Paramètres</a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="{{ url("index/logout") }}"><i class="fa fa-sign-out fa-fw"></i> Déconnexion</a>
                </li>
            </ul>
        </li>
    </ul>

    {% include "layouts/sidebar.volt" %}
</nav>
