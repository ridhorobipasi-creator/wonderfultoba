<?php

/**
 * Wonderful Toba - Production Readiness Verification Script
 * Run: php verify.php
 */

echo "\n🔍 Wonderful Toba - Production Readiness Check\n";
echo str_repeat("=", 60) . "\n\n";

$checks = [];
$passed = 0;
$failed = 0;

// Check 1: PHP Version
echo "1. Checking PHP Version... ";
if (version_compare(PHP_VERSION, '8.3.0', '>=')) {
    echo "✅ PHP " . PHP_VERSION . "\n";
    $passed++;
} else {
    echo "❌ PHP " . PHP_VERSION . " (Required: >= 8.3)\n";
    $failed++;
}

// Check 2: Required Extensions
echo "2. Checking PHP Extensions... ";
$required = ['pdo', 'mbstring', 'openssl', 'json', 'tokenizer', 'xml'];
$missing = [];
foreach ($required as $ext) {
    if (!extension_loaded($ext)) {
        $missing[] = $ext;
    }
}
if (empty($missing)) {
    echo "✅ All required extensions loaded\n";
    $passed++;
} else {
    echo "❌ Missing: " . implode(', ', $missing) . "\n";
    $failed++;
}

// Check 3: .env file
echo "3. Checking .env file... ";
if (file_exists(__DIR__ . '/.env')) {
    echo "✅ .env exists\n";
    $passed++;
} else {
    echo "❌ .env not found\n";
    $failed++;
}

// Check 4: Database file
echo "4. Checking database... ";
if (file_exists(__DIR__ . '/database/database.sqlite')) {
    echo "✅ database.sqlite exists\n";
    $passed++;
} else {
    echo "❌ database.sqlite not found\n";
    $failed++;
}

// Check 5: Storage permissions
echo "5. Checking storage permissions... ";
if (is_writable(__DIR__ . '/storage')) {
    echo "✅ storage/ is writable\n";
    $passed++;
} else {
    echo "❌ storage/ is not writable\n";
    $failed++;
}

// Check 6: Bootstrap cache permissions
echo "6. Checking bootstrap/cache permissions... ";
if (is_writable(__DIR__ . '/bootstrap/cache')) {
    echo "✅ bootstrap/cache/ is writable\n";
    $passed++;
} else {
    echo "❌ bootstrap/cache/ is not writable\n";
    $failed++;
}

// Check 7: Vite manifest
echo "7. Checking Vite build... ";
if (file_exists(__DIR__ . '/public/build/manifest.json')) {
    echo "✅ Vite assets built\n";
    $passed++;
} else {
    echo "❌ Vite assets not built (run: npm run build)\n";
    $failed++;
}

// Check 8: Vendor directory
echo "8. Checking Composer dependencies... ";
if (is_dir(__DIR__ . '/vendor')) {
    echo "✅ vendor/ exists\n";
    $passed++;
} else {
    echo "❌ vendor/ not found (run: composer install)\n";
    $failed++;
}

// Check 9: Key in .env
echo "9. Checking APP_KEY... ";
if (file_exists(__DIR__ . '/.env')) {
    $env = file_get_contents(__DIR__ . '/.env');
    if (preg_match('/APP_KEY=base64:.+/', $env)) {
        echo "✅ APP_KEY is set\n";
        $passed++;
    } else {
        echo "❌ APP_KEY not set (run: php artisan key:generate)\n";
        $failed++;
    }
} else {
    echo "❌ Cannot check (no .env file)\n";
    $failed++;
}

// Check 10: Models exist
echo "10. Checking Models... ";
$models = ['Package', 'Car', 'Blog', 'City', 'Booking', 'User'];
$missingModels = [];
foreach ($models as $model) {
    if (!file_exists(__DIR__ . "/app/Models/{$model}.php")) {
        $missingModels[] = $model;
    }
}
if (empty($missingModels)) {
    echo "✅ All core models exist\n";
    $passed++;
} else {
    echo "❌ Missing models: " . implode(', ', $missingModels) . "\n";
    $failed++;
}

// Summary
echo "\n" . str_repeat("=", 60) . "\n";
echo "Summary: {$passed} passed, {$failed} failed\n";

if ($failed === 0) {
    echo "\n🎉 All checks passed! Application is ready.\n";
    echo "\nTo start the server:\n";
    echo "  php artisan serve\n\n";
    echo "Then visit: http://127.0.0.1:8000\n\n";
    exit(0);
} else {
    echo "\n⚠️  Some checks failed. Please fix the issues above.\n\n";
    exit(1);
}
