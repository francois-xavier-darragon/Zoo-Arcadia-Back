# 1.Prérequis

Node.js
Php 8.2
Serveur web (Apache ou Nginx)
MySQL
MongoDB

# 2.Installation

1.Installez MySQL
2.Installez MongoDB
3.Clonez le dépôt du projet

# 3.Installation les dépendances

composer install
npm install

# 4.Configurez les variables d'environnement

Copiez le fichier .env en .env.local et modifiez les variables d'environnement selon la configuration locale,
notamment les informations de connexion à MySQL et MongoDB.
cp .env .env.local

# 5.Initialisation de la base de données mysql avec la commande personnalisée

php bin/console app:manage:database
[1] create
php bin/console app:manage:database imports
[3] imports

# 6.Initialisation de la base de données mongodb avec la commande personnalisée

php bin/console doctrine:mongodb:schema:create

# 7.Tester la connections mongodb

php bin/console app:test-mongodb

# 8.importer les données mongodb

php bin/console app:manage:mongodb
[1] animal_views
choisir le dernier fichier

# 9.Compilez les assets

npm run build

# 10.Déploiement en production

1.Configurez le serveur web
Configurez le serveur web (Apache ou Nginx) pour pointer vers le répertoire public/ de votre projet Symfony.

2.Configurez les variables d'environnement de production
Configurez les variables d'environnement spécifiques à la production dans le fichier .env.local.php ou via des variables d'environnement système.

3.Installez les dépendances de production
composer install --optimize-autoloader

4.Initialisez les bases de données en production
Exécutez les commandes d'initialisation de la base de données MySQL et MongoDB (étapes 5, 6 et 8 de la section Installation) en production.

5.Videz le cache de production
php bin/console cache:clear

6.Régénérez les hydrators Doctrine MongoDB
php bin/console doctrine:mongodb:generate:hydrators

7.Configurez les permissions des répertoires
Assurez-vous que les répertoires var/ et public/uploads/ ont les permissions d'écriture appropriées.
