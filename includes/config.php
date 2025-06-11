<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Use /var/data/ if it exists (Render persistent disk), otherwise use local storage
if (is_dir('/var/data/')) {
    define("STORAGE_PATH", '/var/data/');
} else {
    define("STORAGE_PATH", __DIR__ . "/../storage/");
}

//  Define Specific file storage path 
define('EMAIL_DATA_FILE', STORAGE_PATH . 'emaildata.txt');
define("PHONE_DATA_FILE", STORAGE_PATH . 'phonedata.txt');
define("IMAGE_DATA_FILE", STORAGE_PATH . 'imagedata.txt');
