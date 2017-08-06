<h1 class="page-header">Ajouter une nouvelle image</h1>
{{ flashSession.output() }}
<ul class="breadcrumb">
    <li><a href="{{ url("") }}">Accueil</a></li>
    <li><a href="{{ url("image") }}">Images</a></li>
    <li class="active">Ajouter une nouvelle image</li>
</ul>
<div class="row">
    <div class="col-lg-9">
        <form method="post" action="{{ url("image/add") }}" enctype="multipart/form-data">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="file" class="control-label">
                            Fichier
                        </label>
                        {{ form.render("file") }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="title" class="control-label">
                            Titre de l'image
                            <span class="text-muted">- Facultatif</span>
                        </label>
                        {{ form.render("title", ["class": "form-control"]) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="description" class="control-label">
                            Description
                            <span class="text-muted">- Facultatif</span>
                        </label>
                        {{ form.render("description", ["class": "form-control", "rows": "5"]) }}
                    </div>
                </div>
            </div>

            <div class="form-group">
                <input type="hidden" name="{{ csrfKey }}" value="{{ csrf }}">
                <button type="submit" class="btn btn-default">Ajouter</button>
            </div>
        </form>
    </div>
</div>
