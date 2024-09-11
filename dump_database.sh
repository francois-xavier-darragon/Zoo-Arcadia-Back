#!/bin/bash

# Lire les informations de connexion à partir du fichier .env
if [ -f .env.local ]; then
    source .env.local
elif [ -f .env ]; then
    source .env
else
    echo "Fichier .env ou .env.local non trouvé"
    exit 1
fi

# Extraire les informations de DATABASE_URL
DB_USER=$(echo $DATABASE_URL | sed -n 's/.*mysql:\/\/\([^:]*\):.*/\1/p')
DB_PASS=$(echo $DATABASE_URL | sed -n 's/.*mysql:\/\/[^:]*:\([^@]*\).*/\1/p')
DB_NAME=$(echo $DATABASE_URL | sed -n 's/.*\/\([^?]*\).*/\1/p')

# Demander le nom du fichier de sortie
read -p "Entrez le nom du fichier SQL (sans extension) : " FILE_NAME

# Définir le répertoire d'export
EXPORT_DIR="./sql"

# Créer le répertoire s'il n'existe pas
mkdir -p "$EXPORT_DIR"

# Obtenir la date du jour
TODAY=$(date +"%m-%d-%Y")

# Construire le nom de fichier complet
FULL_FILE_NAME="${FILE_NAME}-($TODAY).sql"
FULL_PATH="${EXPORT_DIR}/${FULL_FILE_NAME}"

# Vérifier si le fichier existe déjà et ajouter un numéro si nécessaire
COUNTER=1
while [ -f "$FULL_PATH" ]
do
    FULL_FILE_NAME="${FILE_NAME}-($TODAY)($COUNTER).sql"
    FULL_PATH="${EXPORT_DIR}/${FULL_FILE_NAME}"
    COUNTER=$((COUNTER+1))
done

# Exécuter la commande mysqldump
MYSQL_PWD="$DB_PASS" mysqldump -u "$DB_USER" "$DB_NAME" --skip-comments --skip-extended-insert > "$FULL_PATH"

# Vérifier si la commande s'est bien exécutée
if [ $? -eq 0 ]; then
    echo "Les données ont été exportées avec succès dans le fichier $FULL_PATH"
else
    echo "Une erreur s'est produite lors de l'exportation de la base de données."
fi