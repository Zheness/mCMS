<h1 class="page-header">Modification d'une page spéciale</h1>
{{ flashSession.output() }}
<ul class="breadcrumb">
    <li><a href="{{ url("") }}">Accueil</a></li>
    <li><a href="{{ url("specialPage") }}">Pages spéciales</a></li>
    <li class="active">{{ page.title }}</li>
    <li class="active">Modifier</li>
</ul>
<dl class="dl-horizontal">
    <dt>Modification</dt>
    <dd>{{ page.dateUpdatedToFr() }} - {{ page.getAdminLinkLastEditor() }}</dd>
</dl>
<div class="row">
    <div class="col-lg-9">
        <form method="post" action="{{ url("specialPage/edit/" ~ page.id) }}">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="title" class="control-label">Titre de la page</label>
                        {{ form.render("title", ["class": "form-control"]) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="content" class="control-label">Contenu</label>
                        {{ form.render("content", ["class": "form-control tinymceEditableFull", "rows": "10"]) }}
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
