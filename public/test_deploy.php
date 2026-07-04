<?php
header('Content-Type: text/plain');
echo "Deploy works!\n";

$apkPath = __DIR__ . '/downloads/tgevent.apk';
echo "Check APK path: " . $apkPath . "\n";
echo "Exists: " . (file_exists($apkPath) ? 'YES' : 'NO') . "\n";
if (file_exists($apkPath)) {
    echo "Size: " . filesize($apkPath) . " bytes\n";
}

echo "\nFiles in public/images/:\n";
foreach (glob(__DIR__ . '/images/*') as $file) {
    echo basename($file) . " - " . filesize($file) . " bytes\n";
}

