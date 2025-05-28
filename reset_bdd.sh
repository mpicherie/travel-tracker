#!/bin/bash

# Configuration
DB_NAME="svi_trajets"
DB_USER="root"
DB_PASS="rootpass"
SQL_FILE="sql/schema.sql"

echo "⚠️ Suppression de la base de données '$DB_NAME'..."
mysql -u "$DB_USER" -p"$DB_PASS" -e "DROP DATABASE IF EXISTS $DB_NAME;"

echo "🧱 Création de la base '$DB_NAME'..."
mysql -u "$DB_USER" -p"$DB_PASS" -e "CREATE DATABASE $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"

echo "📥 Importation du schéma depuis '$SQL_FILE'..."
mysql -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" < "$SQL_FILE"

echo "✅ Base de données recréée avec succès."
