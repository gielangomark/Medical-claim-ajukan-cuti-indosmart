<?php
$url = 'http://127.0.0.1:8000/request-change/8/proof';

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, false);
// follow redirects
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

$response = curl_exec($ch);
if ($response === false) {
    echo "CURL ERROR: " . curl_error($ch) . "\n";
    exit(1);
}
$info = curl_getinfo($ch);
$status = $info['http_code'];
$header_size = $info['header_size'];
$headers = substr($response, 0, $header_size);
$body = substr($response, $header_size);

echo "HTTP Status: $status\n";
echo "Headers:\n" . $headers . "\n";
echo "Body length: " . strlen($body) . " bytes\n";
echo "Body first 64 bytes (hex): " . bin2hex(substr($body, 0, 64)) . "\n";

curl_close($ch);
