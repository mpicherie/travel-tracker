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
function getFlightDetails($flight_number) {
    $api_key = 'd1b2e1cdf243b1ebab47b119a3bd95dc'; // ← Mets ta clé API AviationStack ici
    if (!$api_key) return null;

    $url = "http://api.aviationstack.com/v1/flights?access_key=$api_key&flight_iata=" . urlencode($flight_number);
    $response = @file_get_contents($url);
    if (!$response) return null;

    $data = json_decode($response, true);
    if (!isset($data['data'][0])) return null;

    $flight = $data['data'][0];

    return [
        'from'       => $flight['departure']['airport'] ?? '',
        'to'         => $flight['arrival']['airport'] ?? '',
        'from_code'  => $flight['departure']['iata'] ?? '',
        'to_code'    => $flight['arrival']['iata'] ?? '',
        'from_time'  => $flight['departure']['scheduled'] ?? '',
        'to_time'    => $flight['arrival']['scheduled'] ?? '',
        'delay'      => $flight['departure']['delay'] ?? 0,
        'airline'    => $flight['airline']['name'] ?? '',
        'status'     => $flight['flight_status'] ?? '',
        'raw'        => $flight // pour logs ou analyse complète
    ];
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
