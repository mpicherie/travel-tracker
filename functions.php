<?php
/**
 * Nettoie une chaîne pour éviter les failles XSS
 */
function clean($val) {
    return htmlspecialchars(trim($val), ENT_QUOTES, 'UTF-8');
}

/**
 * Génère un token de suivi unique
 */
function generateToken($length = 32) {
    return bin2hex(random_bytes($length / 2));
}

/**
 * Récupère les infos de retard pour un vol via l’API AviationStack
 */
function getFlightDelayInfo($flight_number) {
    $api_key = 'd1b2e1cdf243b1ebab47b119a3bd95dc'; // 🔐 Remplir ta clé API ici (https://aviationstack.com/)
    if (empty($api_key)) return null;

    $url = "http://api.aviationstack.com/v1/flights?access_key=$api_key&flight_iata=" . urlencode($flight_number);

    $response = @file_get_contents($url);
    if (!$response) return null;

    $data = json_decode($response, true);
    if (!isset($data['data'][0])) return null;

    $flight = $data['data'][0];
    $scheduled = $flight['departure']['scheduled'] ?? 'Inconnu';
    $actual = $flight['departure']['actual'] ?? 'Inconnu';
    $delay = $flight['departure']['delay'] ?? 0;

    return "Départ prévu : $scheduled\nDépart réel : $actual\nRetard : {$delay} minutes";
}

/**
 * Récupère les infos de retard d’un train (exemples fictifs)
 */
function getTrainDelayInfo($train_number, $company) {
    // Tu peux remplacer cette logique avec un appel à une vraie API selon la compagnie
    switch (strtolower($company)) {
        case 'sncf':
            return "Retard inconnu - API SNCF non connectée.";
        case 'ns':
            return "Retard inconnu - API NS non connectée.";
        case 'db':
            return "Retard inconnu - API DB non connectée.";
        case 'irail':
            return "Retard inconnu - API iRail (Belgique) non intégrée.";
        default:
            return "Compagnie non reconnue.";
    }
}
?>
