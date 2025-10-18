<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

try {
    // Vérifier si l'utilisateur existe déjà
    $existingUser = User::where('email', 'client@test.com')->first();
    
    if ($existingUser) {
        echo "Utilisateur déjà existant: " . $existingUser->email . " (ID: " . $existingUser->id . ")\n";
        echo "Mot de passe: password123\n";
    } else {
        $user = User::create([
            'name' => 'Client Test',
            'email' => 'client@test.com',
            'password' => bcrypt('password123'),
            'role' => 'client'
        ]);
        
        echo "Utilisateur client créé avec succès!\n";
        echo "Email: " . $user->email . "\n";
        echo "Mot de passe: password123\n";
        echo "ID: " . $user->id . "\n";
    }
    
} catch(Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}