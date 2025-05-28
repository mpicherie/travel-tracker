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

function getFlightDetails($flightNumber) {
    $apiKey = 'd1b2e1cdf243b1ebab47b119a3bd95dc'; // Remplace par ta vraie clé
    $url = "http://api.aviationstack.com/v1/flights?access_key=$apiKey&flight_iata=" . urlencode($flightNumber);

    $response = file_get_contents($url);
    if (!$response) {
        file_put_contents('log_api.json', json_encode(['error' => 'Pas de réponse API']));
        return null;
    }

    // Log brut
    file_put_contents('log_api.json', $response);

    $data = json_decode($response, true);
    if (!isset($data['data']) || empty($data['data'])) {
        file_put_contents('log_api.json', json_encode(['error' => 'Aucun vol trouvé', 'flight' => $flightNumber, 'brut' => $data]));
        return null;
    }

    // Cherche un vol du jour
    $today = date('Y-m-d');
    foreach ($data['data'] as $vol) {
        $scheduled = $vol['departure']['scheduled'] ?? '';
        if (strpos($scheduled, $today) !== false) {
            return [
                'from'       => $vol['departure']['airport'] ?? '',
                'to'         => $vol['arrival']['airport'] ?? '',
                'from_time'  => $vol['departure']['scheduled'] ?? '',
                'to_time'    => $vol['arrival']['scheduled'] ?? '',
                'delay'      => $vol['departure']['delay'] ?? 0,
                'status'     => $vol['flight_status'] ?? 'unknown'
            ];
        }
    }

    // Fallback : premier vol retourné
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

function getFlightsOfDay($flightNumber, $date) {
    $apiKey = 'd1b2e1cdf243b1ebab47b119a3bd95dc';
    $url = "http://api.aviationstack.com/v1/flights?access_key=$apiKey&flight_iata=" . urlencode($flightNumber);

    $response = file_get_contents($url);
    if (!$response) return [];

    $data = json_decode($response, true);
    if (!isset($data['data']) || empty($data['data'])) return [];

    $vols = [];

    foreach ($data['data'] as $v) {
        if (!isset($v['departure']['scheduled'])) continue;

        // Filtrer par date exacte (ex : 2025-06-01)
        if (strpos($v['departure']['scheduled'], $date) !== 0) continue;

        $vols[] = [
            'from'       => $v['departure']['airport'] ?? '',
            'to'         => $v['arrival']['airport'] ?? '',
            'from_time'  => $v['departure']['scheduled'] ?? '',
            'to_time'    => $v['arrival']['scheduled'] ?? '',
            'flight_iata'=> $v['flight']['iata'] ?? '',
            'compagnie'  => $v['airline']['name'] ?? '',
            'status'     => $v['flight_status'] ?? 'unknown'
        ];
    }

    return $vols;
}
