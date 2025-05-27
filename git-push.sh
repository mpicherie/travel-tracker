#!/bin/bash

# ────────────────────────────────────────────────
# Script pour Git + restart Apache
# Usage : ./git-push.sh "Message de commit"
# ────────────────────────────────────────────────

# Vérifie qu’un message a été donné
if [ -z "$1" ]; then
  echo "❌ Tu dois entrer un message de commit."
  echo "➡️  Exemple : ./git-push.sh \"Mise à jour formulaire\""
  exit 1
fi

# Étapes Git
echo "📦 Ajout des fichiers modifiés..."
git add .

echo "📝 Commit avec message : \"$1\""
git commit -m "$1" || exit 1

echo "🚀 Envoi vers GitHub..."
git push || exit 1

# Redémarrage Apache
echo "🔄 Redémarrage du service Apache..."
sudo systemctl restart apache2

# Résultat
echo "✅ Commit et déploiement terminés !"
