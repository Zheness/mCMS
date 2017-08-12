<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        {{ metaTitle is defined ? metaTitle ~ ' - ' : '' }}
        {{ config.site.name }}
    </title>
    <link rel="shortcut icon" href="{{ static_url('img/design/favicon.ico') }}" type="image/x-icon">

    <link href="{{ static_url("vendor/twbs/bootstrap/dist/css/bootstrap.min.css") }}" rel="stylesheet">
    <link href="{{ static_url("css/style.css") }}" rel="stylesheet">
    {{ assets.outputCss() }}

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<div class="container">
    {% include "layouts/navbar.volt" %}

    <div class="row">
        <div class="col-md-8">
            {{ content() }}
        </div>

        <div class="col-md-4">
            {% include "layouts/sidebar.volt" %}
        </div>
    </div>

</div>

<script src="{{ static_url("vendor/components/jquery/jquery.min.js") }}"></script>
<script src="{{ static_url("vendor/twbs/bootstrap/dist/js/bootstrap.min.js") }}"></script>
{{ assets.outputJs() }}

</body>
</html>
