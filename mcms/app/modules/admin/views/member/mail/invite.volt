<p>Bonjour {{ firstname }},</p>
<p>Un administrateur vient de vous ajouter en tant qu'administrateur sur le site <a href="{{ config.site.url }}">{{ config.site.name }}</a>.</p>
<p>Vous pouvez dès à présent vous connecter à l'espace d'administration disponible à l'addresse: <a href="{{ linkAdmin }}">{{ linkAdmin }}</a></p>
<p>S'il s'agit de votre première connexion, n'oubliez pas de changer votre mot de passe en cliquant sur ce lien: <a href="{{ linkResetPassword }}">{{ linkResetPassword }}</a></p>
{% include "layouts/mail/signature.volt" %}
