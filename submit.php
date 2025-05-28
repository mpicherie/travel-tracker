<?php
require 'config.php';
require 'functions.php';

// Activation des erreurs (à désactiver en production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (isset($_POST['selected_vol'])) {
    $vol = json_decode(base64_decode($_POST['selected_vol']), true);
    $_POST['lieu_depart'] = $vol['from'];
    $_POST['lieu_arrivee'] = $vol['to'];
    $_POST['date_depart'] = $vol['from_time'];
    $_POST['date_arrivee'] = $vol['to_time'];
    $_POST['transport_id'] = $vol['flight_iata'];
    $_POST['compagnie'] = $vol['compagnie'];
}


// Sécurité : nettoyage des données
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
    'etat'            => 'prévu',
    'retard_info'     => '',
    'suivi_token'     => generateToken(32),
];

// Auto-remplissage via API pour les vols
if ($data['moyen_transport'] === 'avion' && !empty($data['transport_id'])) {
    $flight = getFlightDetails($data['transport_id']);
    if ($flight) {
        $data['lieu_depart']  = $flight['from'];
        $data['lieu_arrivee'] = $flight['to'];
        $data['retard_info']  = "Départ prévu : " . $flight['from_time'] .
            "\nArrivée prévue : " . $flight['to_time'] .
            "\nRetard estimé : " . $flight['delay'] . " min" .
            "\nStatut : " . $flight['status'];
        $data['etat'] = ($flight['status'] === 'delayed') ? 'en retard' : 'prévu';
    }
}

// Préparation requête
$sql = "INSERT INTO trajets 
(nom, prenom, email, lieu_depart, lieu_arrivee, date_depart, date_arrivee, moyen_transport, transport_id, compagnie, etat, retard_info, suivi_token)
VALUES (:nom, :prenom, :email, :lieu_depart, :lieu_arrivee, :date_depart, :date_arrivee, :moyen_transport, :transport_id, :compagnie, :etat, :retard_info, :suivi_token)";

$stmt = $pdo->prepare($sql);
$stmt->execute($data);

// Affichage lien de suivi
$suivi_url = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/suivi.php?token=" . $data['suivi_token'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Trajet enregistré</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <h2>✅ Ton trajet a bien été enregistré !</h2>
  <p>Tu peux suivre ta progression ici :</p>
  <p><a href="<?= $suivi_url ?>"><?= $suivi_url ?></a></p>
</body>
</html>
