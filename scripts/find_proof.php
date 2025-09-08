<?php
// Usage: php find_proof.php <substring>
$needle = $argv[1] ?? '';
$dir = realpath(__DIR__ . '/../storage/app/public/proofs/data-changes');
if (!is_dir($dir)) {
    fwrite(STDERR, "directory not found: $dir\n");
    exit(2);
}
$results = [];
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
foreach ($it as $file) {
    if (!$file->isFile()) continue;
    $name = $file->getFilename();
    if ($needle === '' || stripos($name, $needle) !== false) {
        $results[] = [
            'FullName' => $file->getPathname(),
            'Name' => $name,
            'LastWriteTime' => date('c', $file->getMTime()),
            'Length' => $file->getSize(),
        ];
    }
}
echo json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL;
