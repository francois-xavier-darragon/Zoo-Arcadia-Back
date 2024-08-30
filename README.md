# Zoo-Arcadia-Back
Changer de branch et basculé sur develope

Avoir d'installé Node.js

lancer : 
composer install

npm install
npm run build

bin/console app:manage-database create
bin/console app:manage-database update

# Deploiment sur heroku
avoir un compte heroku
se connecté  : heroku login
heroku config:get JAWSDB_URL --app "nom-d-application"
heroku config:get JAWSDB_URL
heroku config:set APP_ENV=prod
heroku config:set DATABASE_DRIVER=pdo_mysql
heroku buildpacks:add --index 1 heroku/nodejs "nom-d-application"
heroku buildpacks:add --index 2 heroku/php "nom-d-application"

une fois le projet compiler est dployé sur heroku 
heroku run php bin/console app:manage:database choix 2
heroku run php bin/console app:manage:database choix 3 avec à la question : arcadia.sql 


(Optionnel)
heroku domains:add www.votredomaine.com