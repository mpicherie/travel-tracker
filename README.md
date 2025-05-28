# 🌍 Travel Tracker – SVI

Module PHP/MySQL pour suivre les trajets de volontaires (avion/train/voiture) avec :
- Saisie via formulaire
- Lien de suivi unique
- Auto-remplissage via API AviationStack
- Interface admin
- Mise à jour automatique des retards

## Installation
1. Cloner le projet
2. Créer la BDD et importer `sql/schema.sql`
3. Configurer `config.php`
4. Ajouter au CRON `check_retards.php`

## Accès admin
- `/admin.php`
- identifiant : admin
- mot de passe : admin123
