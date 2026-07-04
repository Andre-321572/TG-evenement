<?php
header('Content-Type: text/plain');
echo "Images dir contents:\n";
$files = glob(__DIR__ . '/images/*');
if ($files === false) {
    echo "Failed to read directory.\n";
} else {
    foreach ($files as $file) {
        echo basename($file) . " - " . filesize($file) . " bytes\n";
    }
}
echo "\nDocument root: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "Current file: " . __FILE__ . "\n";
