#!/bin/bash

# Read login information from .env file
if [ -f .env.local ]; then
    source .env.local
elif [ -f .env ]; then
    source .env
else
    echo "Fichier .env ou .env.local non trouvé"
    exit 1
fi

# Extract information from DATABASE_URL
DB_USER=$(echo $DATABASE_URL | sed -n 's/.*mysql:\/\/\([^:]*\):.*/\1/p')
DB_PASS=$(echo $DATABASE_URL | sed -n 's/.*mysql:\/\/[^:]*:\([^@]*\).*/\1/p')
DB_NAME=$(echo $DATABASE_URL | sed -n 's/.*\/\([^?]*\).*/\1/p')

# Ask for the name of the output file
read -p "Entrez le nom du fichier SQL (sans extension) : " FILE_NAME

# Set export directory
EXPORT_DIR="./sql"

# Create the directory if it does not exist
mkdir -p "$EXPORT_DIR"

# Get today's date
TODAY=$(date +"%m-%d-%Y")

# Build full filename
FULL_FILE_NAME="${FILE_NAME}-($TODAY).sql"
FULL_PATH="${EXPORT_DIR}/${FULL_FILE_NAME}"

# Check if the file already exists and add a number if necessary
COUNTER=1
while [ -f "$FULL_PATH" ]
do
    FULL_FILE_NAME="${FILE_NAME}-($TODAY)($COUNTER).sql"
    FULL_PATH="${EXPORT_DIR}/${FULL_FILE_NAME}"
    COUNTER=$((COUNTER+1))
done

# Run the mysqldump command
MYSQL_PWD="$DB_PASS" mysqldump -u "$DB_USER" "$DB_NAME" --skip-comments --skip-extended-insert > "$FULL_PATH"

# Check if the command was executed correctly
if [ $? -eq 0 ]; then
    echo "Les données ont été exportées avec succès dans le fichier $FULL_PATH"
else
    echo "Une erreur s'est produite lors de l'exportation de la base de données."
fi