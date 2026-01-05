<?php
// Test direct image access
$uri = '/assets/images/unnamed.jpg';
$file = __DIR__ . $uri;

echo "URI: $uri\n";
echo "File path: $file\n";
echo "File exists: " . (is_file($file) ? 'YES' : 'NO') . "\n";
echo "Is readable: " . (is_readable($file) ? 'YES' : 'NO') . "\n";

if (is_file($file)) {
    echo "File size: " . filesize($file) . " bytes\n";
}
