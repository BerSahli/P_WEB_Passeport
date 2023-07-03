# passeport_ETML

## Marche à suivre pour la production
1.	S’assurer que le serveur répond aux exigences de Laravel (extensions PHP et autre)
2.	S’assurer que les répertoires `storage` et `bootstrap/cache` sont accessible en écriture par le serveur web.
    - Commande : `chmod -R 755 storage bootstrap/cache`
3.	Installer les dépendances composer
    - Commande : `composer install --no-dev --optimize-autoloader`
4.	Installer les dépendances npm
    - Commande : `npm install`
    - `npm run build --only=production`
5.	Si le projet provient du dépôt Git. Créer un fichier `.env` de base via le fichier `.env.example` et générer la clé d’application Laravel  
    - Commande : `php artisan key:generate`
6.	Configurer les informations de connexion dans le fichier `.env`
7.	Effectuer les migrations de la DB  
    - Commande : `php artisan migrate --force --seed`
8.	Optimiser les performances de l’app  
    - Commande : `composer dump-autoload --optimize`
    - `php artisan optimize`
    - `php artisan cache:clear`
    - `php artisan config:cache`
    - `php artisan route:cache`
    - `php artisan view:cache`
9.	Configurer le serveur pour pointer vers le répertoire `public`

## Exigence Laravel
* PHP >= 8.1
* Ctype PHP Extension
* cURL PHP Extension
* DOM PHP Extension
* Fileinfo PHP Extension
* Filter PHP Extension
* Hash PHP Extension
* Mbstring PHP Extension
* OpenSSL PHP Extension
* PCRE PHP Extension
* PDO PHP Extension
* Session PHP Extension
* Tokenizer PHP Extension
* XML PHP Extension

## Configuration .env
Les valeurs qui sont en `code` peuvent/doivent être changer selon les besoins.
Si APP_KEY est vide, suivre le point 5 de la marche à suivre
### Configuration de l’application
+ APP_ENV=production
+ APP_DEBUG=false
+ `APP_URL=URL_de_application_en_production`
### Configuration de la DB
+ `DB_CONNECTION=mysql`
+ `DB_HOST=localhost`
+ `DB_PORT=3306`
+ `DB_DATABASE=nom_de_la_DB`
+ `DB_USERNAME=nom_utilisateur`
+ `DB_PASSWORD=mot_de_passe`
### Configuration serveur SMTP
+ MAIL_MAILER=mailjet
+ `MAIL_FROM_ADDRESS=bertrand.sahli@eduvaud.ch`
+ `MAIL_FROM_NAME=nom`
+ `MAILJET_APIKEY=API_KEY`
+ `MAILJET_APISECRET=SECRET_KEY`

## Petit détail
Si nécessaire, il faut changer le mail de l'expéditeur dans `\app\Mail\EmailPassport.php` et `\app\Mail\EmailPassportConfirmation.php` ex: from: new Address('NouvelAdress@example.com', 'Nom de l'expéditeur');

Serveur SMTP : bertrand.sahli@eduvaud.ch
mdp : Etml$2023
API Key : ef20d84aba1bfee8813ec32814fcaf66
Secret Key : 4f88a8c5017b2b41b43a787bf30b4947