<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GroupMemberBadge;
use App\Models\Group;
use App\Models\User;
use Carbon\Carbon;

class GroupBadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer quelques groupes et utilisateurs pour les badges de démo
        $groups = Group::take(3)->get();
        $users = User::take(10)->get();

        if ($groups->isEmpty() || $users->isEmpty()) {
            $this->command->info('Pas assez de groupes ou d\'utilisateurs pour créer des badges de démonstration.');
            return;
        }

        $badgeTypes = array_keys(GroupMemberBadge::BADGE_TYPES);

        foreach ($groups as $group) {
            // Attribuer des badges aléatoirement aux membres
            $groupUsers = $users->random(rand(3, 6));
            
            foreach ($groupUsers as $user) {
                // Chaque utilisateur peut avoir 1-3 badges
                $userBadgeCount = rand(1, 3);
                $userBadgeTypes = collect($badgeTypes)->random($userBadgeCount);
                
                foreach ($userBadgeTypes as $badgeType) {
                    $badgeInfo = GroupMemberBadge::BADGE_TYPES[$badgeType];
                    
                    // Éviter les doublons
                    $existingBadge = GroupMemberBadge::where([
                        'group_id' => $group->id,
                        'user_id' => $user->id,
                        'badge_type' => $badgeType
                    ])->first();
                    
                    if ($existingBadge) continue;

                    $expiresAt = null;
                    if ($badgeType === 'expert_month') {
                        $expiresAt = Carbon::now()->addMonth();
                    }

                    GroupMemberBadge::create([
                        'group_id' => $group->id,
                        'user_id' => $user->id,
                        'badge_type' => $badgeType,
                        'badge_name' => $badgeInfo['name'],
                        'badge_description' => $badgeInfo['description'],
                        'badge_icon' => $badgeInfo['icon'],
                        'badge_color' => $badgeInfo['color'],
                        'points_earned' => $badgeInfo['points'],
                        'earned_date' => Carbon::now()->subDays(rand(1, 30)),
                        'expires_at' => $expiresAt,
                        'criteria_met' => [
                            'demo_badge' => true,
                            'posts' => rand(10, 50),
                            'comments' => rand(20, 100),
                            'reactions' => rand(30, 200)
                        ]
                    ]);
                }
            }
        }

        $this->command->info('Badges de démonstration créés avec succès !');
    }
}
