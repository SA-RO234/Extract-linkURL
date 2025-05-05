<?php
include '../includes/config.php';
include '../includes/functions.php';
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $url = isset($_POST['url']) ? filter_var(trim($_POST['url']), FILTER_VALIDATE_URL):false;

    if (!$url) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid URL']);
        exit;
    }

    $html = file_get_contents($url);
    if ($html === false) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to fetch content']);
        exit;
    }

    $result  = extractData($html);
    saveData($result);
    echo json_encode(['status' => 'success', 'data' => $result]);
    exit;
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}
