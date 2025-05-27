<?php
require 'config.php';
require 'functions.php';

// Récupération du token depuis l'URL
$token = $_GET['token'] ?? '';

if (!$token) {
    die("❌ Aucun token fourni.");
}

// Récupération du trajet associé
$stmt = $pdo->prepare("SELECT * FROM trajets WHERE suivi_token = ?");
$stmt->execute([$token]);
$trajet = $stmt->fetch();

if (!$trajet) {
    die("❌ Trajet introuvable.");
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
  <h2>📍 Suivi du trajet de <?= clean($trajet['prenom']) . ' ' . clean($trajet['nom']) ?></h2>

  <p><strong>Lieu de départ :</strong> <?= clean($trajet['lieu_depart']) ?> (<?= $trajet['date_depart'] ?>)</p>
  <p><strong>Lieu d’arrivée :</strong> <?= clean($trajet['lieu_arrivee']) ?> (<?= $trajet['date_arrivee'] ?>)</p>
  <p><strong>Transport :</strong> <?= clean($trajet['moyen_transport']) ?> <?= clean($trajet['transport_id']) ?> (<?= clean($trajet['compagnie']) ?>)</p>
  <p><strong>État actuel :</strong> <span style="color: <?= $trajet['etat'] === 'en retard' ? 'red' : 'green' ?>;">
    <?= clean($trajet['etat']) ?>
  </span></p>

  <?php if (!empty($trajet['retard_info'])): ?>
    <p><strong>Détails sur le retard :</strong><br><pre><?= clean($trajet['retard_info']) ?></pre></p>
  <?php else: ?>
    <p><em>Aucune information de retard disponible pour le moment.</em></p>
  <?php endif; ?>
</body>
</html>
