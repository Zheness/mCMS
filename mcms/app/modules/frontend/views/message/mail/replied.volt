<p>Bonjour {{ firstname }},</p>
<p>Un nouveau message a été posté sur la conversation <b>{{ thread_subject }}</b> sur le site <a href="{{ config.site.url }}">{{ config.site.name }}</a>.</p>
<p>Cliquez sur le lien suivant pour accéder aux messages et répondre à la conversation: <a href="{{ link }}">{{ link }}</a></p>
{% include "layouts/mail/signature.volt" %}
