<ol class="breadcrumb">
    <li><a href="{{ url('') }}">Accueil</a></li>
    <li class="active">Réinitialiser un mot de passe</li>
</ol>
<form method="post" action="{{ url("member/resetPassword/" ~ member.token) }}">
    <div class="form-group">
        <label for="password" class="control-label">Nouveau mot de passe</label>
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
        <button type="submit" class="btn btn-primary">
            Changer le mot de passe
        </button>
    </div>
</form>
