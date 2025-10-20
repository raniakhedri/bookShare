<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$migrations = [
    '2025_10_11_174110_create_review_interactions_table',
    '2025_10_11_175500_create_favorites_table',
    '2025_10_11_194049_create_reading_positions_table',
    '2025_10_11_210639_create_user_preferences_table',
    '2025_10_12_132641_create_group_member_badges_table',
    '2025_10_12_140225_create_group_events_table',
    '2025_10_12_140324_create_event_participants_table',
    '2025_10_13_143600_create_reading_challenges_table',
    '2025_10_13_143715_create_challenge_participants_table',
    '2025_10_13_145139_create_interactive_sessions_table',
    '2025_10_13_150916_create_quizzes_table',
    '2025_10_13_151125_create_participant_quizzes_table',
    '2025_10_13_154227_create_ai_search_interactions_table',
    '2025_10_13_161757_create_quiz_responses',
];

$batch = 20;

foreach ($migrations as $migration) {
    try {
        DB::table('migrations')->insert([
            'migration' => $migration,
            'batch' => $batch
        ]);
        echo "✓ Marked as complete: {$migration}\n";
    } catch (\Exception $e) {
        echo "✗ Already marked or error: {$migration}\n";
    }
}

echo "\nDone! Now run: php artisan migrate\n";
