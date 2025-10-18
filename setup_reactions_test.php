<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Group;
use App\Models\Post;
use App\Models\PostReaction;

echo "=== TEST DU SYSTÈME DE RÉACTIONS ===\n";

// Créer un groupe de test si nécessaire
$group = Group::firstOrCreate([
    'name' => 'Groupe Test Réactions'
], [
    'theme' => 'Test',
    'description' => 'Groupe pour tester les réactions',
    'creator_id' => 1
]);

echo "Groupe créé/trouvé: {$group->name} (ID: {$group->id})\n";

// Créer quelques posts de test
$posts = [
    'Premier post pour tester les réactions ! 👋',
    'Qu\'est-ce que vous pensez de ce nouveau système de réactions ? 🤔',
    'Les réactions fonctionnent parfaitement ! 🎉'
];

foreach ($posts as $content) {
    $post = Post::firstOrCreate([
        'content' => $content,
        'group_id' => $group->id
    ], [
        'user_id' => 1
    ]);
    
    echo "Post créé: " . substr($content, 0, 50) . "... (ID: {$post->id})\n";
}

// Ajouter quelques réactions de test
$testReactions = [
    ['post_id' => 1, 'user_id' => 1, 'reaction_type' => 'like'],
    ['post_id' => 1, 'user_id' => 2, 'reaction_type' => 'love'],
    ['post_id' => 2, 'user_id' => 1, 'reaction_type' => 'wow'],
    ['post_id' => 2, 'user_id' => 3, 'reaction_type' => 'laugh'],
];

foreach ($testReactions as $reactionData) {
    if (Post::find($reactionData['post_id']) && \App\Models\User::find($reactionData['user_id'])) {
        PostReaction::firstOrCreate($reactionData);
        echo "Réaction {$reactionData['reaction_type']} ajoutée au post {$reactionData['post_id']}\n";
    }
}

echo "\n✅ Système de test configuré !\n";
echo "🌐 Allez sur: http://127.0.0.1:8000/groups/{$group->id}/wall\n";
echo "📝 Connectez-vous et testez les réactions !\n";