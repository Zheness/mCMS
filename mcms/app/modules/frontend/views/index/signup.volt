<form method="post" action="{{ url("index/signup") }}">
    <div class="row">
        <div class="col-md-7">
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
            <div class="col-md-7">
                {{ form.render("email", ["class": "form-control"]) }}
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="password" class="control-label">Mot de passe</label>
        <div class="row">
            <div class="col-md-4">
                {{ form.render("password", ["class": "form-control"]) }}
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="passwordConfirm" class="control-label">Confirmation du mot de passe</label>
        <div class="row">
            <div class="col-md-4">
                {{ form.render("passwordConfirm", ["class": "form-control"]) }}
            </div>
        </div>
    </div>
    <div class="form-group">
        <input type="hidden" name="{{ csrfKey }}" value="{{ csrf }}">
        <button type="submit" class="btn btn-primary">Connexion</button>
    </div>
</form>
