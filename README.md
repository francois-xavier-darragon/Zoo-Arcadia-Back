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
heroku create "nom-d-application"
heroku git:remote -a "nom-d-application"
heroku addons:create jawsdb:kitefin --app arcadia-ecf
# Verification JAWSDB_URL
heroku config:get JAWSDB_URL --app "nom-d-application"
heroku addons:create ormongo --app "nom-de-votre-application"
# Verification ORMONGO_URL
heroku config:get ORMONGO_URL --app "nom-d-application"
heroku config:get ORMONGO_RS_URL --app "nom-d-application"

heroku config:set DATABASE_DRIVER=pdo_mysql --app "nom-d-application"
heroku buildpacks:add --index 1 heroku/nodejs --app "nom-d-application"
heroku buildpacks:add --index 2 heroku/php --app "nom-d-application"
heroku config:set APP_ENV=prod --app "nom-d-application"
git push heroku main

une fois le projet compiler est déployé sur heroku 
heroku run php bin/console app:manage:database choix 2
heroku run php bin/console app:manage:database choix 3 avec à la question : arcadia.sql 


(Optionnel)
heroku domains:add www.votredomaine.com