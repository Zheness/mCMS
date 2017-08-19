<h1 class="page-header">Option
    <small>Administrateur principal</small>
</h1>
{{ flashSession.output() }}
<ul class="breadcrumb">
    <li><a href="{{ url("") }}">Accueil</a></li>
    <li><a href="{{ url("option") }}">Options</a></li>
    <li class="active">Administrateur principal</li>
</ul>
<div class="row">
    <div class="col-lg-9">
        <form method="post" action="{{ url("option/root") }}">
            <div class="form-group">
                <label for="width" class="control-label">Id du membre</label>
                <div class="row">
                    <div class="col-md-2">
                        {{ form.render("id", ["class": "form-control", "min": "1"]) }}
                    </div>
                </div>
                <p class="help-block">Recherchez le membre dans la <a href="{{ url('member') }}">liste des membres</a>
                    et copiez son <b>ID</b> (première colonne #) dans la case ci-dessus pour nommer le membre
                    administrateur principal.</p>
            </div>

            {% if root == session.get('member').id %}
                <div class="alert alert-danger">
                    <h2 class="margin-top-10">Attention !</h2>
                    <p>En changeant cette valeur, vous ne serez plus considéré comme administrateur principal !</p>
                </div>
            {% endif %}

            <div class="form-group">
                <input type="hidden" name="{{ csrfKey }}" value="{{ csrf }}">
                <button type="submit" class="btn btn-default">Modifier</button>
            </div>
        </form>
    </div>
</div>
