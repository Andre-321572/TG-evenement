<?php
header('Content-Type: text/plain');
echo "Deploy works!\n";
echo "Files in public/images/:\n";
foreach (glob(__DIR__ . '/images/*') as $file) {
    echo basename($file) . " - " . filesize($file) . " bytes\n";
}
