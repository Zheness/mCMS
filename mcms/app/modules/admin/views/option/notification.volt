<h1 class="page-header">Option
    <small>Notification</small>
</h1>
{{ flashSession.output() }}
<ul class="breadcrumb">
    <li><a href="{{ url("") }}">Accueil</a></li>
    <li><a href="{{ url("option") }}">Options</a></li>
    <li class="active">Notification</li>
</ul>
<div class="row">
    <div class="col-lg-9">
        <form method="post" action="{{ url("option/notification") }}">
            <div class="row">
                <div class="col-sm-12">
                    <div class="TriSea-technologies-Switch">
                        {{ form.render("enabled") }}
                        <label for="enabled" class="label-primary switch-label"></label>
                        <label for="enabled">Notification active</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-5">
                    <div class="form-group">
                        <label for="content" class="control-label">Type</label>
                        {{ form.render("type", ["class": "form-control"]) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="content" class="control-label">Message</label>
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
