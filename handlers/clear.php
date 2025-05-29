<?php
header("Content-Type: application/json");
$files = [
    __DIR__ . '/../storage/imagedata.txt',
    __DIR__ . '/../storage/emaildata.txt',
    __DIR__ . '/../storage/phonedata.txt'
];
foreach ($files as $file) {
    if (file_exists($file)) file_put_contents($file, '');
}
echo json_encode(['status' => 'success']);
