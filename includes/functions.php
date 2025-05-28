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
    // Match <img src>, <source srcset>, and background images (accept any URL)
    preg_match_all('/<img[^>]+src=["\']([^"\'>]+)["\']|<source[^>]+srcset=["\']([^"\'>]+)["\']|background-image\s*:\s*url\(["\']?([^"\')]+)["\']?\)/i', $content, $matches);
    $allUrls = array_merge($matches[1] ?? [], $matches[2] ?? [], $matches[3] ?? []);
    foreach ($allUrls as $url) {
        if ($url && (
            preg_match('/\.(jpg|jpeg|png|webp|gif|svg)(\?.*)?$/i', $url) ||
            preg_match('/([?&](format|type|ext)=(jpg|jpeg|png|webp|gif|svg))/i', $url)
        )) {
            $imageUrls[$url] = true;
        }
    }
    // Also match direct image URLs in plain text (absolute and relative)
    preg_match_all('/((https?:\/\/|\/)[^\s"\'<>]+(?:(?:\.(jpg|jpeg|png|webp|gif|svg))|(?:[?&](?:format|type|ext)=(?:jpg|jpeg|png|webp|gif|svg)))(\?[^\s"\'<>]*)?)/i', $content, $plainMatches);
    foreach ($plainMatches[1] ?? [] as $url) {
        if ($url) {
            $imageUrls[$url] = true;
        }
    }
    return array_keys($imageUrls);
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
