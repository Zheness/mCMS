# mCMS

## Qu'est-ce que mCMS ?

mCMS est une application web créée dans le but de simplifier le développement lors de la création de sites internet.

En effet, en tant que développeur, on se retrouve souvent à re-créer les mêmes éléments ou modules pour chacunes de nos applications.

mCMS inclus donc une base de modules déjà développés et configurés. Il suffit d'importer le projet, le configurer et l'utiliser !

Le projet inclus un site public, accessible à tous, et un site administratif, où seuls les membres avec un rôle d'administrateur peuvent accéder.

Les modules actuellement inclus sont:

- Pages
- Albums
- Articles
- Membres
- Messagerie
- Commentaires

## Installation

mCMS est construit avec le langage PHP et en utilisant le framework Phalcon. Pour installer le projet vous devez configurer un environnement similaire à celui-ci (configuration recommandée):

- Debian version 8
- Nginx version 1.10
- PHP version 7.0
- Mysql version 5.7
- Phalcon version 3.2

PHP doit également être installé avec le support des bibliothèques suivantes:

- mysql
- curl
- json
- imagick
- phalcon

Composer doit également être installé sur la machine.

Une fois que l'environnement est prêt, utilisez git pour cloner le dépôt.

## Configuration

Note: À partir d'ici, c'est à vous de configurer vos chemins, fichiers de configurations (php.ini, nginx.conf, virtual hosts, etc.) qui ne sont pas liés au projet mais dépendent de votre architecture.

Rendez-vous dans le dossier du projet mCMS et copiez le fichier `config/config.tpl.php` en `config/app_config.php`.  
Modifiez les valeurs dans ce dernier fichier pour configurer votre site (accès à la base de données, nom du site, addresse URL, etc.)

Installez l'outil phalcon-devtools disponible sur GitHub et utilisez le script pour installer la base de données grâce au dossier de migration de mCMS.

Une fois la base de données créée rendez vous dans le dossier `mcms` et lancez la commande `php ./run`. LE script mCMS va alors génerer un fichier de configuration `config/install.ini` que vous devez compléter avec les informations du premier utilisateur qui devient alors l'administrateur principal.

Quand le fichier est rempli, re-lancez le script `php ./run` pour compléter l'installation.

C'est tout ! Vous pouvez naviguer sur votre site internet à l'adresse que vous avez configurée.

## Utilisation et modification
L'administration se trouve toujours à l'adresse `<http://votre-adresse.fr>/admin/` vous pouvez vous y rendre et vous connecter avec l'email et le mot de passe que vous avez configuré dans le fichier `install.ini`.

Vous pouvez configurer beaucoup de choses en allant dans les options, et toutes les actions principales sont possible (ajout, édition, suppression).

mCMS doit être utilisée comme une base de code et non comme une application définitive. Vous êtes libre de modifier le code selon vos besoins et de modifier l'affichage de votre site à votre convenance.