<?php
require 'config.php';
require 'functions.php';

// Récupère les trajets à suivre (train ou avion uniquement)
$trajets = $pdo->query("SELECT * FROM trajets WHERE moyen_transport IN ('train', 'avion')")->fetchAll();

foreach ($trajets as $t) {
    $retard = null;

    // Appelle la bonne fonction selon le type de transport
    if ($t['moyen_transport'] === 'avion') {
        $retard = getFlightDelayInfo($t['transport_id']);
    } elseif ($t['moyen_transport'] === 'train') {
        $retard = getTrainDelayInfo($t['transport_id'], $t['compagnie']);
    }

    // Met à jour la BDD si un retard est trouvé
    if ($retard) {
        $stmt = $pdo->prepare("UPDATE trajets SET retard_info = ?, etat = ? WHERE id = ?");
        $etat = (str_contains(strtolower($retard), 'retard') && !str_contains($retard, '0')) ? 'en retard' : 'prévu';
        $stmt->execute([$retard, $etat, $t['id']]);
    }
}
