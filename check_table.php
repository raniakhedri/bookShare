<?php
require 'bootstrap/app.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$result = \DB::select("SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'favorites'");
if (!empty($result)) {
    echo "✓ Favorites table EXISTS\n";
    $tableInfo = \DB::select("DESC favorites");
    echo "Table structure:\n";
    foreach ($tableInfo as $column) {
        echo "  - " . $column->Field . " (" . $column->Type . ")\n";
    }
} else {
    echo "✗ Favorites table DOES NOT exist\n";
}
