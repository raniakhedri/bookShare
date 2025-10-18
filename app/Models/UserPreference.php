<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'preference_score',
        'preference_type',
        'learning_source',
        'confidence_level',
        'last_updated'
    ];

    protected $casts = [
        'preference_score' => 'decimal:4',
        'confidence_level' => 'decimal:2',
        'last_updated' => 'datetime'
    ];

    // Types de préférences
    const PREFERENCE_TYPES = [
        'GENRE' => 'genre',           // Préférence de genre
        'AUTHOR' => 'author',         // Préférence d'auteur
        'LANGUAGE' => 'language',     // Préférence de langue
        'LENGTH' => 'length',         // Préférence de longueur
        'DIFFICULTY' => 'difficulty', // Niveau de difficulté
        'THEME' => 'theme',          // Thèmes préférés
        'FORMAT' => 'format',        // Format préféré (PDF, EPUB, etc.)
        'PUBLICATION_DATE' => 'publication_date' // Époque préférée
    ];

    // Sources d'apprentissage des préférences
    const LEARNING_SOURCES = [
        'EXPLICIT' => 'explicit',     // Déclaré par l'utilisateur
        'IMPLICIT' => 'implicit',     // Déduit du comportement
        'COLLABORATIVE' => 'collaborative', // Basé sur utilisateurs similaires
        'CONTENT' => 'content',       // Basé sur le contenu des livres
        'HYBRID' => 'hybrid'          // Combinaison de plusieurs sources
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec la catégorie
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Mettre à jour ou créer une préférence
     */
    public static function updatePreference($userId, $categoryId, $score, $type = 'IMPLICIT', $source = 'IMPLICIT', $confidence = 0.5)
    {
        $preference = static::firstOrNew([
            'user_id' => $userId,
            'category_id' => $categoryId,
            'preference_type' => $type
        ]);

        // Mise à jour progressive du score (moyenne pondérée)
        if ($preference->exists) {
            $oldScore = $preference->preference_score;
            $oldConfidence = $preference->confidence_level;
            
            // Calcul de la nouvelle moyenne pondérée
            $totalWeight = $oldConfidence + $confidence;
            $newScore = (($oldScore * $oldConfidence) + ($score * $confidence)) / $totalWeight;
            $newConfidence = min(1.0, $totalWeight / 2); // Augmente la confiance graduellement
            
            $preference->preference_score = $newScore;
            $preference->confidence_level = $newConfidence;
        } else {
            $preference->preference_score = $score;
            $preference->confidence_level = $confidence;
        }

        $preference->learning_source = $source;
        $preference->last_updated = now();
        $preference->save();

        return $preference;
    }

    /**
     * Obtenir les préférences d'un utilisateur
     */
    public static function getUserPreferences($userId, $type = null)
    {
        $query = static::where('user_id', $userId)
            ->where('confidence_level', '>', 0.1) // Seuil minimum de confiance
            ->orderBy('preference_score', 'desc');

        if ($type) {
            $query->where('preference_type', $type);
        }

        return $query->with('category')->get();
    }

    /**
     * Calculer la similarité entre deux utilisateurs
     */
    public static function calculateUserSimilarity($userId1, $userId2)
    {
        $user1Prefs = static::getUserPreferences($userId1);
        $user2Prefs = static::getUserPreferences($userId2);

        if ($user1Prefs->isEmpty() || $user2Prefs->isEmpty()) {
            return 0.0;
        }

        // Créer des vecteurs de préférences
        $categories = $user1Prefs->pluck('category_id')->merge($user2Prefs->pluck('category_id'))->unique();
        
        $vector1 = [];
        $vector2 = [];

        foreach ($categories as $categoryId) {
            $pref1 = $user1Prefs->where('category_id', $categoryId)->first();
            $pref2 = $user2Prefs->where('category_id', $categoryId)->first();

            $vector1[] = $pref1 ? $pref1->preference_score : 0;
            $vector2[] = $pref2 ? $pref2->preference_score : 0;
        }

        // Calcul de la similarité cosinus
        return static::cosineSimilarity($vector1, $vector2);
    }

    /**
     * Calcul de la similarité cosinus entre deux vecteurs
     */
    private static function cosineSimilarity($vector1, $vector2)
    {
        if (count($vector1) !== count($vector2)) {
            return 0.0;
        }

        $dotProduct = 0;
        $magnitude1 = 0;
        $magnitude2 = 0;

        for ($i = 0; $i < count($vector1); $i++) {
            $dotProduct += $vector1[$i] * $vector2[$i];
            $magnitude1 += $vector1[$i] * $vector1[$i];
            $magnitude2 += $vector2[$i] * $vector2[$i];
        }

        $magnitude1 = sqrt($magnitude1);
        $magnitude2 = sqrt($magnitude2);

        if ($magnitude1 == 0 || $magnitude2 == 0) {
            return 0.0;
        }

        return $dotProduct / ($magnitude1 * $magnitude2);
    }

    /**
     * Trouver les utilisateurs similaires
     */
    public static function findSimilarUsers($userId, $limit = 10, $minSimilarity = 0.3)
    {
        $allUsers = User::whereNotIn('id', [$userId])
            ->whereHas('preferences')
            ->pluck('id');

        $similarities = [];

        foreach ($allUsers as $otherUserId) {
            $similarity = static::calculateUserSimilarity($userId, $otherUserId);
            
            if ($similarity >= $minSimilarity) {
                $similarities[$otherUserId] = $similarity;
            }
        }

        // Trier par similarité décroissante
        arsort($similarities);

        return array_slice($similarities, 0, $limit, true);
    }
}