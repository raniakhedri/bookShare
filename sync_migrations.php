<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Synchronizing migration records with actual database tables...\n\n";

// Get all migration files
$migrationPath = __DIR__ . '/database/migrations';
$migrationFiles = glob($migrationPath . '/*.php');

// Get already recorded migrations
$recordedMigrations = DB::table('migrations')->pluck('migration')->toArray();

// Get the next batch number
$batch = DB::table('migrations')->max('batch') + 1;

$marked = 0;
$skipped = 0;

foreach ($migrationFiles as $file) {
    $filename = basename($file, '.php');
    
    // Skip if already recorded
    if (in_array($filename, $recordedMigrations)) {
        $skipped++;
        continue;
    }
    
    // Try to extract table name from the migration file
    $content = file_get_contents($file);
    
    // Look for Schema::create patterns
    if (preg_match("/Schema::create\('([^']+)'/", $content, $matches)) {
        $tableName = $matches[1];
        
        // Check if table exists
        if (Schema::hasTable($tableName)) {
            DB::table('migrations')->insert([
                'migration' => $filename,
                'batch' => $batch
            ]);
            echo "✓ Marked: {$filename} (table '{$tableName}' exists)\n";
            $marked++;
        } else {
            echo "⊘ Skip: {$filename} (table '{$tableName}' does not exist)\n";
        }
    } else {
        echo "? Skip: {$filename} (could not determine table name)\n";
    }
}

echo "\n";
echo "Summary:\n";
echo "- Already recorded: {$skipped}\n";
echo "- Newly marked: {$marked}\n";
echo "\nNow run: php artisan migrate\n";
