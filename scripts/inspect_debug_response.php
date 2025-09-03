<?php
$url = 'http://127.0.0.1:8000/debug/request-change/8/proof/devdebug';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);
$body = curl_exec($ch);
if ($body === false) { echo 'CURL ERROR: '.curl_error($ch)."\n"; exit(1); }
$len = strlen($body);
echo "Body length: $len\n";
echo "First 64 bytes hex: " . bin2hex(substr($body,0,64)) . "\n";
// Check jpeg magic
$magic = substr($body,0,4);
printf("Magic bytes ASCII/hex: %s (%s)\n", $magic, bin2hex($magic));
curl_close($ch);
