<?php
$ch = curl_init('http://127.0.0.1:8000/debug/request-change/8/proof/devdebug');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
$r = curl_exec($ch);
if (! $r) { echo 'CURL ERROR: '.curl_error($ch); exit(1); }
$i = curl_getinfo($ch);
echo "HTTP Status: " . $i['http_code'] . "\n";
echo "Headers+body start:\n" . substr($r, 0, 300) . "\n";

curl_close($ch);
