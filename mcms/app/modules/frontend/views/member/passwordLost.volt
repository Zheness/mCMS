<ol class="breadcrumb">
    <li><a href="{{ url('') }}">Accueil</a></li>
    <li class="active">Mot de passe perdu</li>
</ol>
<form method="post" action="{{ url("member/passwordLost") }}">
    <div class="form-group">
        <label for="email" class="control-label">Email</label>
        <div class="row">
            <div class="col-md-7">
                {{ form.render("email", ["class": "form-control"]) }}
            </div>
        </div>
    </div>
    <div class="form-group">
        <input type="hidden" name="{{ csrfKey }}" value="{{ csrf }}">
        <button type="submit" class="btn btn-primary">
            Envoyer la demande de réinitialisation du mot de passe
        </button>
    </div>
</form>
