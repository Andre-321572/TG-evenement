<?php
header('Content-Type: text/plain');

$baseDir = dirname(__DIR__);
$bootstrapCacheDir = $baseDir . '/bootstrap/cache';

echo "Laravel Base Dir: $baseDir\n";
echo "Bootstrap Cache Dir: $bootstrapCacheDir\n";

if (file_exists($bootstrapCacheDir)) {
    echo "Files in bootstrap/cache:\n";
    print_r(scandir($bootstrapCacheDir));
    
    // Clear route cache if it exists
    $routeCacheFile = $bootstrapCacheDir . '/routes-v7.php';
    if (file_exists($routeCacheFile)) {
        echo "Removing routes-v7.php...\n";
        if (unlink($routeCacheFile)) {
            echo "Successfully removed routes-v7.php!\n";
        } else {
            echo "Failed to remove routes-v7.php.\n";
        }
    } else {
        echo "routes-v7.php does not exist.\n";
    }

    // Clear config cache if it exists
    $configCacheFile = $bootstrapCacheDir . '/config.php';
    if (file_exists($configCacheFile)) {
        echo "Removing config.php cache...\n";
        if (unlink($configCacheFile)) {
            echo "Successfully removed config.php cache!\n";
        } else {
            echo "Failed to remove config.php cache.\n";
        }
    } else {
        echo "config.php cache does not exist.\n";
    }
} else {
    echo "bootstrap/cache directory not found.\n";
}

$subdomainImagesDir = $baseDir . '/../tgevent.digitalforges.org/images';
echo "\nChecking subdomain images directory: $subdomainImagesDir\n";
if (file_exists($subdomainImagesDir)) {
    echo "Files in subdomain images:\n";
    print_r(scandir($subdomainImagesDir));
} else {
    echo "Subdomain images directory not found.\n";
}
