<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {% if page_info['title'] is defined %}
        <title>{{ page_info['title'] }}</title>
    {% else %}
        <title>{{ config.site.name }} - Administration</title>
    {% endif %}

    <link href="{{ static_url("vendor/components/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet">
    <link href="{{ static_url("vendor/onokumus/metismenu/dist/metisMenu.min.css") }}" rel="stylesheet">
    <link href="{{ static_url("vendor/components/font-awesome/css/font-awesome.min.css") }}" rel="stylesheet">
    <link href="{{ static_url("vendor/iron-summit-media/startbootstrap-sb-admin-2/dist/css/sb-admin-2.css") }}" rel="stylesheet">
    <link href="{{ static_url("css/style.css") }}" rel="stylesheet">
    {{ assets.outputCss() }}

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body>

{% if session.has('member') %}
    <div id="wrapper">
        {% include "layouts/navbar.volt" %}
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        {{ content() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
{% else %}
    <div class="container">
        {{ content() }}
    </div>
{% endif %}

<script src="{{ static_url("vendor/components/jquery/jquery.min.js") }}"></script>
<script src="{{ static_url("vendor/components/bootstrap/js/bootstrap.min.js") }}"></script>
<script src="{{ static_url("vendor/onokumus/metismenu/dist/metisMenu.min.js") }}"></script>
<script src="{{ static_url("vendor/iron-summit-media/startbootstrap-sb-admin-2/dist/js/sb-admin-2.js") }}"></script>
<script src="{{ static_url("js/bootstrap.js") }}"></script>
{{ assets.outputJs() }}

</body>
</html>
