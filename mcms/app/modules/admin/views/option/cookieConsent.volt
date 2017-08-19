<h1 class="page-header">Option
    <small>Bandeau des cookies</small>
</h1>
{{ flashSession.output() }}
<ul class="breadcrumb">
    <li><a href="{{ url("") }}">Accueil</a></li>
    <li><a href="{{ url("option") }}">Options</a></li>
    <li class="active">Bandeau des cookies</li>
</ul>
<div class="row">
    <div class="col-lg-9">
        <form method="post" action="{{ url("option/cookieConsent") }}">
            <div class="form-group">
                <label for="text" class="control-label">Texte</label>
                <div class="row">
                    <div class="col-sm-8">
                        {{ form.render("text", ["class": "form-control"]) }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="textButton" class="control-label">Texte du bouton</label>
                <div class="row">
                    <div class="col-sm-4">
                        {{ form.render("textButton", ["class": "form-control"]) }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="textLink" class="control-label">Texte du lien</label>
                <div class="row">
                    <div class="col-sm-4">
                        {{ form.render("textLink", ["class": "form-control"]) }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="backgroundColor" class="control-label">Couleur de fond</label>
                <div class="row">
                    <div class="col-sm-4">
                        {{ form.render("backgroundColor", ["class": "form-control bootstrap-color-picker"]) }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="backgroundColorButton" class="control-label">Couleur du bouton</label>
                <div class="row">
                    <div class="col-sm-4">
                        {{ form.render("backgroundColorButton", ["class": "form-control bootstrap-color-picker"]) }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="textColor" class="control-label">Couleur du texte</label>
                <div class="row">
                    <div class="col-sm-4">
                        {{ form.render("textColor", ["class": "form-control bootstrap-color-picker"]) }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="textColorButton" class="control-label">Couleur du texte du bouton</label>
                <div class="row">
                    <div class="col-sm-4">
                        {{ form.render("textColorButton", ["class": "form-control bootstrap-color-picker"]) }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="textColorLink" class="control-label">Couleur du lien</label>
                <div class="row">
                    <div class="col-sm-4">
                        {{ form.render("textColorLink", ["class": "form-control bootstrap-color-picker"]) }}
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
