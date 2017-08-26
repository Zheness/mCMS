<h1 class="page-header">Option
    <small>Statut par défaut des membres</small>
</h1>
{{ flashSession.output() }}
<ul class="breadcrumb">
    <li><a href="{{ url("") }}">Accueil</a></li>
    <li><a href="{{ url("option") }}">Options</a></li>
    <li class="active">Statut par défaut des membres</li>
</ul>
<div class="row">
    <div class="col-lg-9">
        <form method="post" action="{{ url("option/memberStatus") }}">
            <div class="form-group">
                <label for="status" class="control-label">Statut</label>
                <div class="row">
                    <div class="col-md-4">
                        {{ form.render("status", ["class": "form-control"]) }}
                    </div>
                </div>
            </div>

            <div class="form-group">
                <input type="hidden" name="{{ csrfKey }}" value="{{ csrf }}">
                <button type="submit" class="btn btn-default">Modifier</button>
            </div>
        </form>
    </div>
</div>
