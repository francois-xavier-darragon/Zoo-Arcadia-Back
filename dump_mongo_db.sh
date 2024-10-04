#!/bin/bash

# Loading environment variables
if [ -f .env.local ]; then
    source .env.local
elif [ -f .env ]; then
    source .env
else
    echo "Fichier .env ou .env.local non trouvé"
    exit 1
fi

# Extracting login information from MONGODB_URL
DB_HOST=$(echo $MONGODB_URL | sed -n 's/.*mongodb:\/\/\([^:]*\):.*/\1/p')
DB_PORT=$(echo $MONGODB_URL | sed -n 's/.*:\([0-9]*\).*/\1/p')

# Use MONGODB_DB from environment file for database name
DB_NAME=$MONGODB_DB

# Checking the necessary variables
if [ -z "$DB_HOST" ] || [ -z "$DB_PORT" ] || [ -z "$DB_NAME" ]; then
    echo "Erreur : l'une des variables de connexion est vide."
    echo "DB_HOST: $DB_HOST"
    echo "DB_PORT: $DB_PORT"
    echo "DB_NAME: $DB_NAME"
    exit 1
fi

# Set export directory
EXPORT_DIR="./nosql"

# Create the directory if it does not exist
mkdir -p "$EXPORT_DIR"

# Get today's date
TODAY=$(date +"%m-%d-%Y")

# Function to list databases
list_databases() {
    mongosh --host $DB_HOST --port $DB_PORT --eval "db.adminCommand('listDatabases').databases.forEach(db => print(db.name))" --quiet
}

# List available databases
echo "Bases de données disponibles :"
mapfile -t databases < <(list_databases)

# Show databases with numbers
for i in "${!databases[@]}"; do
    echo "$((i+1)). ${databases[$i]}"
done

# Ask the user to choose a database
echo "Entrez le numéro de la base de données à sauvegarder (ou appuyez sur Entrée pour utiliser $DB_NAME) :"
read choice

# Select database
if [ -n "$choice" ] && [ "$choice" -le "${#databases[@]}" ]; then
    selected_db=${databases[$((choice-1))]}
else
    selected_db=$DB_NAME
fi

# Remove quotes and spaces from database name
selected_db=$(echo "$selected_db" | sed "s/'//g" | sed 's/"//g' | sed 's/ /_/g')

echo "Base de données sélectionnée : $selected_db"

# Set backup path with database name and date
BACKUP_PATH="$EXPORT_DIR/${selected_db}_${TODAY}"

# Run dump
echo "Démarrage du dump de la base de données $selected_db"
if mongodump --host $DB_HOST --port $DB_PORT --db="$selected_db" --out="$BACKUP_PATH"; then
    echo "Dump réussi dans $BACKUP_PATH"

    # Move files from subdirectory to main directory
    mv "$BACKUP_PATH/$selected_db"/* "$BACKUP_PATH/"
    rmdir "$BACKUP_PATH/$selected_db"

    # Check the contents of the backup directory
    echo "Contenu du répertoire de sauvegarde :"
    ls -R "$BACKUP_PATH"
else
    echo "Erreur lors du dump de la base de données"
    exit 1
fi

echo "Processus de sauvegarde terminé"