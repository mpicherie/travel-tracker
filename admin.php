<?php
require 'config.php';
require 'functions.php';

// Authentification HTTP simple
$AUTH_USER = 'admin';
$AUTH_PASS = 'admin123';

if (!isset($_SERVER['PHP_AUTH_USER']) ||
    $_SERVER['PHP_AUTH_USER'] !== $AUTH_USER ||
    $_SERVER['PHP_AUTH_PW'] !== $AUTH_PASS) {
    header('WWW-Authenticate: Basic realm="SVI Admin"');
    header('HTTP/1.0 401 Unauthorized');
    exit('Accès refusé');
}

// Récupérer tous les trajets
$trajets = $pdo->query("SELECT * FROM trajets ORDER BY date_enregistrement DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Suivi des trajets</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <h2>🧭 Interface d'administration - Suivi des trajets</h2>

    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Transport</th>
                <th>Départ</th>
                <th>Arrivée</th>
                <th>État</th>
                <th>Retard</th>
                <th>Suivi</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($trajets as $t): ?>
            <tr>
                <td><?= clean($t['prenom']) . ' ' . clean($t['nom']) ?></td>
                <td><?= clean($t['moyen_transport']) . ' ' . clean($t['transport_id']) ?> (<?= clean($t['compagnie']) ?>)</td>
                <td><?= clean($t['lieu_depart']) ?> (<?= $t['date_depart'] ?>)</td>
                <td><?= clean($t['lieu_arrivee']) ?> (<?= $t['date_arrivee'] ?>)</td>
                <td><strong><?= clean($t['etat']) ?></strong></td>
                <td><pre><?= clean($t['retard_info']) ?></pre></td>
                <td><a href="suivi.php?token=<?= $t['suivi_token'] ?>" target="_blank">Voir</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
