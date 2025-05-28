<?php
require 'functions.php';

header('Content-Type: application/json');

// Récupère le numéro de vol passé en paramètre GET
$flight = $_GET['flight'] ?? '';

if (!$flight) {
    echo json_encode(['error' => 'Numéro de vol manquant']);
    exit;
}

// Appelle la fonction définie dans functions.php
$details = getFlightDetails($flight);

if (!$details) {
    echo json_encode(['error' => 'Vol introuvable']);
    exit;
}

// Renvoie un résumé des infos du vol
echo json_encode([
    'from' => $details['from'],
    'to' => $details['to'],
    'from_time' => $details['from_time'] ?? null,
    'to_time' => $details['to_time'] ?? null,
    'delay' => $details['delay'] ?? 0,
    'status' => $details['status'] ?? 'inconnu'
]);
