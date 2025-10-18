<?php

namespace App\Listeners;

use App\Events\GroupActivityEvent;
use App\Models\GroupMemberBadge;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class EvaluateBadgesListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(GroupActivityEvent $event): void
    {
        // Évaluer les badges après une activité significative
        if (in_array($event->activityType, ['post_created', 'comment_created', 'reaction_added'])) {
            try {
                // Évaluer les badges pour l'utilisateur qui a effectué l'activité
                GroupMemberBadge::evaluateAndAwardBadges($event->groupId, $event->userId);
                
                // Optionnel: évaluer périodiquement tous les membres (moins fréquent)
                if (rand(1, 10) === 1) { // 10% de chance
                    $this->evaluateRandomMembers($event->groupId);
                }
            } catch (\Exception $e) {
                // Log l'erreur mais ne pas faire échouer la tâche
                \Log::error('Erreur lors de l\'évaluation des badges: ' . $e->getMessage());
            }
        }
    }

    /**
     * Évaluer les badges pour quelques membres aléatoirement
     */
    private function evaluateRandomMembers($groupId)
    {
        $group = \App\Models\Group::find($groupId);
        if (!$group) return;

        // Prendre 3 membres aléatoirement pour une évaluation
        $randomMembers = $group->users()
                              ->wherePivot('status', 'accepted')
                              ->inRandomOrder()
                              ->limit(3)
                              ->get();

        foreach ($randomMembers as $member) {
            GroupMemberBadge::evaluateAndAwardBadges($groupId, $member->id);
        }
    }
}
