<?php
header('Content-Type: text/plain');

echo "=== Laravel Storage Link Fixer ===\n\n";

$laravelRoot = realpath(__DIR__ . '/..');
$target = $laravelRoot . '/storage/app/public';
$shortcut = __DIR__ . '/storage';

echo "Laravel Root: " . $laravelRoot . "\n";
echo "Storage Target: " . $target . "\n";
echo "Symlink Path: " . $shortcut . "\n\n";

if (!file_exists($target)) {
    echo "ERROR: Target directory does not exist! Please check if your storage/app/public exists.\n";
    exit;
}

if (file_exists($shortcut) || is_link($shortcut)) {
    echo "Found existing storage file/link at public/storage.\n";
    if (is_link($shortcut)) {
        echo "It is a symlink. Deleting broken/old link...\n";
        if (unlink($shortcut)) {
            echo "Successfully deleted old symlink.\n";
        } else {
            echo "ERROR: Failed to delete old symlink!\n";
        }
    } else if (is_dir($shortcut)) {
        echo "ERROR: public/storage is a physical directory, not a link! Please rename or delete it manually first.\n";
        exit;
    } else {
        echo "Deleting old file...\n";
        unlink($shortcut);
    }
}

echo "Creating symlink...\n";
if (symlink($target, $shortcut)) {
    echo "SUCCESS: Symlink created successfully!\n";
} else {
    echo "ERROR: Failed to create symlink! Please check permissions.\n";
}

// Let's also check if public_html is a sibling of the Laravel root (common in cPanel subdirectory setup)
$siblingPublicHtml = realpath($laravelRoot . '/../public_html');
if ($siblingPublicHtml && $siblingPublicHtml !== $laravelRoot) {
    echo "\nDetected sibling public_html folder at: " . $siblingPublicHtml . "\n";
    $siblingShortcut = $siblingPublicHtml . '/storage';
    echo "Sibling Symlink Path: " . $siblingShortcut . "\n";
    
    if (file_exists($siblingShortcut) || is_link($siblingShortcut)) {
        if (is_link($siblingShortcut)) {
            echo "Deleting old symlink in public_html...\n";
            unlink($siblingShortcut);
        }
    }
    
    if (symlink($target, $siblingShortcut)) {
        echo "SUCCESS: Sibling symlink in public_html created successfully!\n";
    } else {
        echo "WARNING: Failed to create sibling symlink in public_html. (This is normal if public_html is not used or not writable)\n";
    }
}
