<?php
require 'functions.php';

$vols = [];
$flight_iata = $_POST['flight_iata'] ?? '';
$flight_date = $_POST['flight_date'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'rechercher') {
    if ($flight_iata && $flight_date) {
        $vols = getFlightsOfDay($flight_iata, $flight_date);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Travel Tracker</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <h2>🧳 Enregistrement de trajet volontaire</h2>

  <form method="POST">
    <label>Numéro de vol :</label>
    <input type="text" name="flight_iata" required value="<?= htmlspecialchars($flight_iata) ?>">

    <label>Date du vol :</label>
    <input type="date" name="flight_date" required value="<?= htmlspecialchars($flight_date) ?>">

    <input type="hidden" name="action" value="rechercher">
    <button type="submit">Rechercher ce vol</button>
  </form>

  <?php if (!empty($vols)): ?>
    <h3>✈️ Vols trouvés pour le <?= htmlspecialchars($flight_date) ?></h3>

    <form method="POST" action="submit.php">
      <input type="hidden" name="moyen_transport" value="avion">

      <label>Choisir le vol :</label>
      <select name="selected_vol" required>
        <?php foreach ($vols as $v): ?>
          <option value="<?= base64_encode(json_encode($v)) ?>">
            <?= $v['flight_iata'] ?> – <?= $v['from'] ?> → <?= $v['to'] ?>
            (<?= substr($v['from_time'], 11, 5) ?> → <?= substr($v['to_time'], 11, 5) ?>)
            [<?= ucfirst($v['status']) ?>]
          </option>
        <?php endforeach; ?>
      </select>

      <label>Nom :</label>
      <input type="text" name="nom" required>

      <label>Prénom :</label>
      <input type="text" name="prenom" required>

      <label>Email :</label>
      <input type="email" name="email" required>

      <button type="submit">Valider ce trajet</button>
    </form>
  <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
    <p>❌ Aucun vol trouvé pour ce numéro et cette date.</p>
  <?php endif; ?>
</body>
</html>
