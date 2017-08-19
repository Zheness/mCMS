<h1 class="page-header">Option
    <small>Miniatures d'images</small>
</h1>
{{ flashSession.output() }}
<ul class="breadcrumb">
    <li><a href="{{ url("") }}">Accueil</a></li>
    <li><a href="{{ url("option") }}">Options</a></li>
    <li class="active">Miniatures d'images</li>
</ul>
<div class="row">
    <div class="col-lg-9">
        <form method="post" action="{{ url("option/thumbnails") }}">
            <div class="form-group">
                <label for="width" class="control-label">Largeur</label>
                <div class="row">
                    <div class="col-md-2">
                        {{ form.render("width", ["class": "form-control", "min": "1", "max": "800"]) }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="height" class="control-label">Hauteur</label>
                <div class="row">
                    <div class="col-md-2">
                        {{ form.render("height", ["class": "form-control", "min": "1", "max": "800"]) }}
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
