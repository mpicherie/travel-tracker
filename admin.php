<?php
require 'config.php';
require 'functions.php';

// Protection simple par identifiant/mot de passe
session_start();
$LOGIN = "admin";
$PASS = "admin123";

// Authentification
if (!isset($_SERVER['PHP_AUTH_USER']) || 
    $_SERVER['PHP_AUTH_USER'] !== $LOGIN || 
    $_SERVER['PHP_AUTH_PW'] !== $PASS) {
    header('WWW-Authenticate: Basic realm="Admin SVI"');
    header('HTTP/1.0 401 Unauthorized');
    echo "⛔ Accès refusé.";
    exit;
}

// Récupération des trajets
$stmt = $pdo->query("SELECT * FROM trajets ORDER BY date_enregistrement DESC");
$trajets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Admin – Suivi des trajets</title>
  <link rel="stylesheet" href="assets/style.css">
  <style>
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 1.5rem;
    }
    th, td {
      padding: 0.6rem;
      border: 1px solid #ccc;
      text-align: left;
    }
    th {
      background-color: #0077b6;
      color: white;
    }
    tr:nth-child(even) {
      background: #f2f2f2;
    }
    .retard {
      color: red;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <h2>🔐 Espace Admin – Suivi des volontaires</h2>

  <table>
    <tr>
      <th>Nom</th>
      <th>Email</th>
      <th>Départ</th>
      <th>Arrivée</th>
      <th>Transport</th>
      <th>Date</th>
      <th>Statut</th>
      <th>Suivi</th>
    </tr>

    <?php foreach ($trajets as $t): ?>
    <tr>
      <td><?= htmlspecialchars($t['prenom'] . ' ' . $t['nom']) ?></td>
      <td><?= htmlspecialchars($t['email']) ?></td>
      <td><?= htmlspecialchars($t['lieu_depart']) ?></td>
      <td><?= htmlspecialchars($t['lieu_arrivee']) ?></td>
      <td><?= htmlspecialchars($t['moyen_transport']) ?> <?= $t['transport_id'] ? '(' . htmlspecialchars($t['transport_id']) . ')' : '' ?></td>
      <td><?= htmlspecialchars($t['date_depart']) ?> → <?= htmlspecialchars($t['date_arrivee']) ?></td>
      <td class="<?= $t['etat'] === 'en retard' ? 'retard' : '' ?>">
        <?= htmlspecialchars($t['etat']) ?>
      </td>
      <td><a href="suivi.php?token=<?= $t['suivi_token'] ?>" target="_blank">🔗 Suivi</a></td>
    </tr>
    <?php endforeach; ?>
  </table>
</body>
</html>
