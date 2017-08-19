<ol class="breadcrumb">
    <li><a href="{{ url('') }}">Accueil</a></li>
    <li class="active">Contact</li>
</ol>
{{ content }}
<form method="post" action="{{ url("message/new") }}">
    <div class="row">
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="firstname" class="control-label">Prénom</label>
                        <div class="row">
                            <div class="col-md-12">
                                {{ form.render("firstname", ["class": "form-control"]) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="lastname" class="control-label">Nom</label>
                        <div class="row">
                            <div class="col-md-12">
                                {{ form.render("lastname", ["class": "form-control"]) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="email" class="control-label">Email</label>
        <div class="row">
            <div class="col-md-9">
                {{ form.render("email", ["class": "form-control"]) }}
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="email" class="control-label">Sujet</label>
        <div class="row">
            <div class="col-md-9">
                {{ form.render("subject", ["class": "form-control"]) }}
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="email" class="control-label">Message</label>
        <div class="row">
            <div class="col-md-9">
                {{ form.render("content", ["class": "form-control", "rows": "10"]) }}
            </div>
        </div>
    </div>
    <div class="form-group">
        <input type="hidden" name="{{ csrfKey }}" value="{{ csrf }}">
        <button type="submit" class="btn btn-primary">Envoyer le message</button>
    </div>
</form>
