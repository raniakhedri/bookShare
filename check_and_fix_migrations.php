<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Get all pending migrations
$pendingMigrations = DB::table('migrations')
    ->pluck('migration')
    ->toArray();

// List of migrations we need to check
$migrationsToCheck = [
    ['file' => '2025_09_23_142914_create_book_journal_table', 'table' => 'book_journal'],
    ['file' => '2025_10_11_164439_create_reviews_table', 'table' => 'reviews'],
    ['file' => '2025_10_11_174110_create_review_interactions_table', 'table' => 'review_interactions'],
    ['file' => '2025_10_11_175500_create_favorites_table', 'table' => 'favorites'],
    ['file' => '2025_10_11_194049_create_reading_positions_table', 'table' => 'reading_positions'],
    ['file' => '2025_10_11_204514_create_user_interactions_table', 'table' => 'user_interactions'],
    ['file' => '2025_10_11_210639_create_user_preferences_table', 'table' => 'user_preferences'],
    ['file' => '2025_10_12_132641_create_group_member_badges_table', 'table' => 'group_member_badges'],
    ['file' => '2025_10_12_140225_create_group_events_table', 'table' => 'group_events'],
    ['file' => '2025_10_12_140324_create_event_participants_table', 'table' => 'event_participants'],
    ['file' => '2025_10_13_143600_create_reading_challenges_table', 'table' => 'reading_challenges'],
    ['file' => '2025_10_13_143715_create_challenge_participants_table', 'table' => 'challenge_participants'],
    ['file' => '2025_10_13_145139_create_interactive_sessions_table', 'table' => 'interactive_sessions'],
    ['file' => '2025_10_13_150916_create_quizzes_table', 'table' => 'quizzes'],
    ['file' => '2025_10_13_151125_create_participant_quizzes_table', 'table' => 'participant_quizzes'],
    ['file' => '2025_10_13_154227_create_ai_search_interactions_table', 'table' => 'ai_search_interactions'],
    ['file' => '2025_10_13_161757_create_quiz_responses', 'table' => 'quiz_responses'],
];

echo "Checking database tables...\n\n";

$batch = DB::table('migrations')->max('batch') + 1;

foreach ($migrationsToCheck as $migration) {
    $migrationFile = $migration['file'];
    $tableName = $migration['table'];
    
    // Check if table exists
    $tableExists = Schema::hasTable($tableName);
    
    // Check if migration is recorded
    $migrationRecorded = in_array($migrationFile, $pendingMigrations);
    
    echo "Table: {$tableName}\n";
    echo "  - Exists in DB: " . ($tableExists ? 'YES' : 'NO') . "\n";
    echo "  - Migration recorded: " . ($migrationRecorded ? 'YES' : 'NO') . "\n";
    
    if ($tableExists && !$migrationRecorded) {
        // Table exists but migration not recorded - mark it as complete
        DB::table('migrations')->insert([
            'migration' => $migrationFile,
            'batch' => $batch
        ]);
        echo "  - ACTION: Marked migration as complete\n";
    } elseif (!$tableExists && $migrationRecorded) {
        // Migration recorded but table doesn't exist - remove the record
        DB::table('migrations')->where('migration', $migrationFile)->delete();
        echo "  - ACTION: Removed incorrect migration record\n";
    } elseif (!$tableExists && !$migrationRecorded) {
        echo "  - ACTION: Ready to migrate\n";
    } else {
        echo "  - ACTION: Already in sync\n";
    }
    echo "\n";
}

echo "\nDone! Now run: php artisan migrate\n";
