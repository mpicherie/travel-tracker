<?php
require 'config.php';
require 'functions.php';

$token = $_GET['token'] ?? '';

if (!$token) {
    die("⛔ Token de suivi manquant.");
}

$stmt = $pdo->prepare("SELECT * FROM trajets WHERE suivi_token = :token");
$stmt->execute(['token' => $token]);
$trajet = $stmt->fetch();

if (!$trajet) {
    die("⛔ Trajet introuvable.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Suivi du trajet</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <h2>📍 Suivi du trajet de <?= htmlspecialchars($trajet['prenom'] . " " . $trajet['nom']) ?></h2>

  <p><strong>Départ :</strong> <?= htmlspecialchars($trajet['lieu_depart']) ?> — le <?= htmlspecialchars($trajet['date_depart']) ?></p>
  <p><strong>Arrivée :</strong> <?= htmlspecialchars($trajet['lieu_arrivee']) ?> — le <?= htmlspecialchars($trajet['date_arrivee']) ?></p>
  <p><strong>Transport :</strong> <?= htmlspecialchars($trajet['moyen_transport']) ?> <?= $trajet['transport_id'] ? '(' . htmlspecialchars($trajet['transport_id']) . ')' : '' ?></p>
  <p><strong>Compagnie :</strong> <?= htmlspecialchars($trajet['compagnie']) ?></p>
  <p><strong>Statut :</strong> <?= strtoupper($trajet['etat']) ?></p>

  <?php if (!empty($trajet['retard_info'])): ?>
    <pre><strong>Infos retard / suivi temps réel :</strong>
<?= htmlspecialchars($trajet['retard_info']) ?>
    </pre>
  <?php endif; ?>
</body>
</html>
