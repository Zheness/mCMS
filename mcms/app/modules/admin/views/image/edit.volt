<h1 class="page-header">Modification d'une image</h1>
{{ flashSession.output() }}
<ul class="breadcrumb">
    <li><a href="{{ url("") }}">Accueil</a></li>
    <li><a href="{{ url("image") }}">Images</a></li>
    <li class="active">{{ image.title != '' ? image.title : '#' ~ image.id }}</li>
    <li class="active">Modifier</li>
</ul>
<ul class="nav nav-tabs margin-bottom-10">
    <li class="active"><a href="{{ url("image/edit/" ~ image.id) }}"><span class="fa fa-edit"></span> Modifier</a></li>
    <li><a href="{{ url("image/delete/" ~ image.id) }}" class="text-danger"><span class="fa fa-trash"></span>
            Supprimer</a></li>
</ul>
<dl class="dl-horizontal">
    <dt>Création</dt>
    <dd>{{ image.dateCreatedToFr() }} - {{ image.getAdminLinkCreator() }}</dd>
    <dt>Modification</dt>
    <dd>{{ image.dateUpdatedToFr() }} - {{ image.getAdminLinkLastEditor() }}</dd>
</dl>
<div class="text-center">
    <img src="/img/upload/{{ image.filename }}" class="img-thumbnail thumbnail-image">
</div>
<div class="row">
    <div class="col-lg-9">
        <form method="post" action="{{ url("image/edit/" ~ image.id) }}">
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
                <button type="submit" class="btn btn-default">Modifier</button>
            </div>
        </form>
    </div>
</div>
