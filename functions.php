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
    $url = "http://api.aviationstack.com/v1/flights?access_key=$apiKey&flight_iata=" . urlencode($flightNumber);

    $response = file_get_contents($url);
    if (!$response) return null;

    $data = json_decode($response, true);
    if (!isset($data['data']) || empty($data['data'])) return null;

    // On cherche le vol le plus récent et fiable
    foreach ($data['data'] as $vol) {
        // Filtrer par date du jour si possible
        $date_depart = $vol['departure']['scheduled'];
        $today = date('Y-m-d');
        if (strpos($date_depart, $today) === false) continue;

        return [
            'from'       => $vol['departure']['airport'] ?? '',
            'to'         => $vol['arrival']['airport'] ?? '',
            'from_time'  => $vol['departure']['scheduled'] ?? '',
            'to_time'    => $vol['arrival']['scheduled'] ?? '',
            'delay'      => $vol['departure']['delay'] ?? 0,
            'status'     => $vol['flight_status'] ?? 'unknown'
        ];
    }

    // Fallback si aucun vol du jour trouvé
    $vol = $data['data'][0];

    return [
        'from'       => $vol['departure']['airport'] ?? '',
        'to'         => $vol['arrival']['airport'] ?? '',
        'from_time'  => $vol['departure']['scheduled'] ?? '',
        'to_time'    => $vol['arrival']['scheduled'] ?? '',
        'delay'      => $vol['departure']['delay'] ?? 0,
        'status'     => $vol['flight_status'] ?? 'unknown'
    ];
}
