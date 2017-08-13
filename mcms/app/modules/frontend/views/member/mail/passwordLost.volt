<p>Bonjour {{ firstname }},</p>
<p>Vous avez demandé un lien de réinitialisation de mot de passe pour le site <a href="{{ config.site.url }}">{{ config.site.name }}</a>.</p>
<p>Cliquez sur le lien suivant pour modifier votre mot de passe: <a href="{{ link }}">{{ link }}</a></p>
{% include "layouts/mail/signature.volt" %}
