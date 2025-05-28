<?php
function extractData($content)
{
    return [
        'emails' => array_unique(extractEmails($content)),
        'images' => array_unique(extractImages($content)),
        'phones' => array_unique(extractPhones($content))
    ];
}
function extractEmails($content)
{
    preg_match_all("/[a-z0-9.\-_]+@[a-z0-9\-]+\.[a-z]{2,6}/i", $content, $matches);
    return $matches[0];
}


function extractPhones($content)
{
    $pattern = '/\b(0|\+855)[\s.-]*([1-9][0-9])[\s.-]*((?:\d[\s.-]*){6,8})\b/';
    preg_match_all($pattern, $content, $matches);
    $phones = array_map(function ($match) {
        return preg_replace('/[\s.-]/', '', $match);
    }, $matches[0]);
    return $phones;
}


function extractImages($content)
{
    $imageUrls = [];
    preg_match_all('/<img[^>]+src=["\']([^"\'>]+)["\']/i', $content, $imgMatches);
    if (!empty($imgMatches[1])) {
        $imageUrls = array_merge($imageUrls, $imgMatches[1]);
    }

    preg_match_all('/background-image\s*:\s*url\(["\']?([^"\')]+)["\']?\)/i', $content, $bgMatches);
    if (!empty($bgMatches[1])) {
        $imageUrls = array_merge($imageUrls, $bgMatches[1]);
    }

    preg_match_all('/<source[^>]+srcset=["\']([^"\'>]+)["\']/i', $content, $sourceMatches);
    if (!empty($sourceMatches[1])) {
        $imageUrls = array_merge($imageUrls, $sourceMatches[1]);
    }

    $imageUrls = array_filter($imageUrls, function ($url) {
        // Allow image URLs with query parameters after the extension (e.g., ?format=webp)
        return preg_match('/^https:\/\/.+\.(jpg|jpeg|png|webp)(\?.*)?$/i', $url);
    });

    return array_unique($imageUrls);
}

function saveData($data)
{
    function appendUnique($file, $lines)
    {
        $existing = file_exists($file) ? file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];
        $existing = array_map('trim', $existing);
        $toAdd = array_diff($lines, $existing);
        if (!empty($toAdd)) {
            file_put_contents($file, implode(PHP_EOL, $toAdd) . PHP_EOL, FILE_APPEND);
        }
    }
    appendUnique(STORAGE_PATH . 'emaildata.txt', $data['emails']);
    appendUnique(STORAGE_PATH . 'imagedata.txt', $data['images']);
    appendUnique(STORAGE_PATH . 'phonedata.txt', $data['phones']);
}
