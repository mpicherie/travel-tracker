<?php
require 'config.php';
require 'functions.php';

date_default_timezone_set('Europe/Paris');

echo "🕒 Vérification des retards en cours...\n";

// Récupère les trajets concernés (avion ou train)
$stmt = $pdo->query("SELECT * FROM trajets WHERE moyen_transport IN ('avion', 'train')");
$trajets = $stmt->fetchAll();

foreach ($trajets as $t) {
    $id = $t['id'];
    $type = $t['moyen_transport'];
    $transport_id = $t['transport_id'];

    if (!$transport_id) continue;

    echo "🔎 $type #$transport_id... ";

    $new_status = '';
    $retard_info = '';
    
    // Requête API selon le type
    if ($type === 'avion') {
        $flight = getFlightDetails($transport_id);

        if ($flight) {
            $new_status = match($flight['status']) {
                'delayed' => 'en retard',
                'active', 'scheduled' => 'prévu',
                'cancelled' => 'annulé',
                'landed' => 'arrivé',
                default => $t['etat']
            };

            $retard_info = "MàJ API AvionStack à " . date('H:i') . " — statut : " . $flight['status'] .
                "\nDépart : " . ($flight['from'] ?? '') .
                "\nArrivée : " . ($flight['to'] ?? '') .
                "\nRetard : " . ($flight['delay'] ?? 0) . " min";
        } else {
            echo "❌ API échouée ou vol introuvable.\n";
            continue;
        }

    } else if ($type === 'train') {
        // Ici, tu peux appeler une API de train selon la compagnie (à intégrer plus tard)
        echo "ℹ️  API train non implémentée.\n";
        continue;
    }

    // Mise à jour SQL
    $update = $pdo->prepare("UPDATE trajets SET etat = :etat, retard_info = :info WHERE id = :id");
    $update->execute([
        'etat' => $new_status,
        'info' => $retard_info,
        'id' => $id
    ]);

    echo "✅ statut mis à jour : $new_status\n";
}

echo "✅ Vérification terminée.\n";
