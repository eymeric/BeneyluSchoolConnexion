# OAuth Client Beneylu

## Pré-requis

* php 5.4+


## Installation

Il faut cloner le dépot git :


    git clone git@git.pixel-cookers.com:beneylu-school/bns-oauth-client-example.git

puis aller dans le dossier `bns-oauth-client-example`

### Récupération des des dépendances via composer https://getcomposer.org/


Installez composer s'il n'est pas présent sur la machine https://getcomposer.org/download/ :

    curl -sS https://getcomposer.org/installer | php

Puis lancer l'installation des dépendances

    php composer.phar install

### Configuration serveur web

Créez un vhost apache ou nginx vers le dossier web exemple :

    <VirtualHost *:80>
        ServerName oauth-test.dev

        DocumentRoot "/bns/bns-oauth-client-example/web"
        <Directory "/bns/bns-oauth-client-example/web">
            #Apache 2.2
            <IfModule !mod_authz_core.c>
                Order allow,deny
                Allow from All
            </IfModule>

            #Apache 2.4
            <IfModule mod_authz_core.c>
                Require all granted
            </IfModule>
        </Directory>
    </VirtualHost>


### Configuration du script d'exemple

Il faut modifier les valiables `$oauth_` dans le fichier `web/index.php` avec les inforamtions transmises par l'équipe Beneylu.


## Test

Se rendre sur la page http://oauth-test.dev/ une demande de connexion oauth doit se faire puis afficher les informations de l'utilisateur connecté.

Si vous rencontrez des erreurs Curl / HTTPS vous trouverez la réponse à votre question ici : http://www.zen-cart.com/showthread.php?213892-Curl-error-(60)-SSL-Certificate-problem-Unable-to-get-local-issuer-certificate
