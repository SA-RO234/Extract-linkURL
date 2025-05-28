<?php
include '../includes/config.php';
include '../includes/functions.php';
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Support both form-data and JSON input
    $input = file_get_contents('php://input');
    $data = [];
    if (!empty($input) && isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
        $data = json_decode($input, true);
    }
    $url = isset($_POST['url']) ? filter_var(trim($_POST['url']), FILTER_VALIDATE_URL) : false;
    if (!$url && isset($data['url'])) {
        $url = filter_var(trim($data['url']), FILTER_VALIDATE_URL);
    }

    if (!$url) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid URL']);
        exit;
    }

    // Check if the URL is a direct image file
    if (preg_match('/\.(jpg|jpeg|png|webp)(\?.*)?$/i', $url)) {
        $result = [
            'emails' => [],
            'images' => [$url],
            'phones' => []
        ];
        saveData($result);
        echo json_encode(['status' => 'success', 'data' => $result]);
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
