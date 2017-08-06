<h1 class="page-header">Erreur 401: Authentification requise</h1>
{{ flashSession.output() }}
<p>La page que vous demandez nécessite une connexion.</p>
<p><a href="{{ url('index/login') }}">Cliquez ici</a> pour accéder au formulaire de connexion.</p>