<?php
require 'config.php';
require 'functions.php';

// Nettoyage et récupération des données du formulaire
$data = [
    'nom'             => clean($_POST['nom'] ?? ''),
    'prenom'          => clean($_POST['prenom'] ?? ''),
    'email'           => clean($_POST['email'] ?? ''),
    'lieu_depart'     => clean($_POST['lieu_depart'] ?? ''),
    'lieu_arrivee'    => clean($_POST['lieu_arrivee'] ?? ''),
    'date_depart'     => $_POST['date_depart'] ?? '',
    'date_arrivee'    => $_POST['date_arrivee'] ?? '',
    'moyen_transport' => $_POST['moyen_transport'] ?? '',
    'transport_id'    => clean($_POST['transport_id'] ?? ''),
    'compagnie'       => clean($_POST['compagnie'] ?? ''),
    'suivi_token'     => generateToken()
];

// Validation minimale
if (!$data['email'] || !$data['nom'] || !$data['prenom']) {
    die("❌ Données incomplètes.");
}

// Enregistrement en base de données
$stmt = $pdo->prepare("
    INSERT INTO trajets (
        nom, prenom, email, lieu_depart, lieu_arrivee,
        date_depart, date_arrivee, moyen_transport,
        transport_id, compagnie, suivi_token
    ) VALUES (
        :nom, :prenom, :email, :lieu_depart, :lieu_arrivee,
        :date_depart, :date_arrivee, :moyen_transport,
        :transport_id, :compagnie, :suivi_token
    )
");

$stmt->execute($data);

// Génération du lien de suivi
$lien = "http://192.168.0.210/suivi.php?token={$data['suivi_token']}";

// Envoi d’un e-mail de confirmation au volontaire
$message = <<<MSG
Bonjour {$data['prenom']},

Merci d’avoir renseigné ton trajet. Tu peux le suivre à tout moment via le lien suivant :

$lien

Cordialement,
L’équipe SVI
MSG;

mail($data['email'], "📍 Suivi de ton trajet volontaire", $message);

// Message de confirmation
echo "<p>✅ Ton trajet a bien été enregistré.</p>";
echo "<p>📍 Lien de suivi : <a href='$lien'>$lien</a></p>";
echo "<p>✉️ Un e-mail t’a été envoyé avec ce lien.</p>";
?>
