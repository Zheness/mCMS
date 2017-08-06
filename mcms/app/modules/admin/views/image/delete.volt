<h1 class="page-header">Suppression d'une image</h1>
{{ flashSession.output() }}
<ul class="breadcrumb">
    <li><a href="{{ url("") }}">Accueil</a></li>
    <li><a href="{{ url("image") }}">Images</a></li>
    <li class="active">{{ image.title != '' ? image.title : '#' ~ image.id }}</li>
    <li class="active">Supprimer</li>
</ul>
<ul class="nav nav-tabs margin-bottom-10">
    <li><a href="{{ url("image/edit/" ~ image.id) }}"><span class="fa fa-edit"></span> Modifier</a></li>
    <li class="active">
        <a href="{{ url("image/delete/" ~ image.id) }}" class="text-danger">
            <span class="fa fa-trash"></span> Supprimer
        </a>
    </li>
</ul>
<div class="row">
    <div class="col-sm-12">
        <p>Attention, souhaitez-vous réelement supprimer l'image
            <b>{{ image.title != '' ? image.title : '#' ~ image.id }}</b> ?</p>
        <p>Toute suppression est définitive.</p>
    </div>
    <div class="col-lg-7">
        <form method="post" action="{{ url("image/delete/" ~ image.id) }}">
            <div class="form-group">
                {{ form.render("action") }}
                <input type="hidden" name="{{ csrfKey }}" value="{{ csrf }}">
                <button type="submit" class="btn btn-danger">Supprimer</button>
                <a href="{{ url("image") }}" class="btn btn-default">Retour à la liste</a>
            </div>
        </form>
    </div>
</div>
