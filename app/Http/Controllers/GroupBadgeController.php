<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupMemberBadge;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupBadgeController extends Controller
{
    /**
     * Afficher les badges d'un groupe
     */
    public function index(Group $group)
    {
        $leaderboard = $group->getLeaderboard();
        $badgeTypes = GroupMemberBadge::BADGE_TYPES;
        $recentBadges = $group->badges()
                             ->active()
                             ->with('user:id,name')
                             ->orderBy('earned_date', 'desc')
                             ->limit(10)
                             ->get();

        return view('frontoffice.group.badges', compact(
            'group', 
            'leaderboard', 
            'badgeTypes', 
            'recentBadges'
        ));
    }

    /**
     * Afficher les badges d'un membre spécifique
     */
    public function memberBadges(Group $group, User $user)
    {
        $badges = GroupMemberBadge::getMemberBadges($group->id, $user->id);
        $activity = GroupMemberBadge::calculateMemberActivity($group->id, $user->id);
        $monthlyActivity = GroupMemberBadge::calculateMemberActivity($group->id, $user->id, '1 month');

        return response()->json([
            'badges' => $badges,
            'activity' => $activity,
            'monthly_activity' => $monthlyActivity,
            'total_points' => $badges->sum('points_earned')
        ]);
    }

    /**
     * Évaluer et attribuer automatiquement les badges
     */
    public function evaluateBadges(Group $group, Request $request)
    {
        // Vérifier que l'utilisateur est admin du groupe
        if (!$this->isGroupAdmin($group)) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $userId = $request->input('user_id', Auth::id());
        $awardedBadges = GroupMemberBadge::evaluateAndAwardBadges($group->id, $userId);

        return response()->json([
            'success' => true,
            'awarded_badges' => $awardedBadges,
            'message' => count($awardedBadges) . ' nouveau(x) badge(s) attribué(s)'
        ]);
    }

    /**
     * Évaluer les badges pour tous les membres du groupe
     */
    public function evaluateAllMembers(Group $group)
    {
        // Vérifier que l'utilisateur est admin du groupe
        if (!$this->isGroupAdmin($group)) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $members = $group->users()->get();
        $totalAwardedBadges = 0;

        foreach ($members as $member) {
            $awardedBadges = GroupMemberBadge::evaluateAndAwardBadges($group->id, $member->id);
            $totalAwardedBadges += count($awardedBadges);
        }

        return response()->json([
            'success' => true,
            'total_awarded' => $totalAwardedBadges,
            'message' => "$totalAwardedBadges nouveau(x) badge(s) attribué(s) aux membres"
        ]);
    }

    /**
     * Obtenir le classement du groupe
     */
    public function leaderboard(Group $group)
    {
        $leaderboard = $group->getLeaderboard(20);
        
        return response()->json([
            'leaderboard' => $leaderboard,
            'user_position' => $this->getUserPosition($group->id, Auth::id())
        ]);
    }

    /**
     * Attribuer manuellement un badge (admin uniquement)
     */
    public function awardBadge(Group $group, Request $request)
    {
        // Vérifier que l'utilisateur est admin du groupe
        if (!$this->isGroupAdmin($group)) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'badge_type' => 'required|in:' . implode(',', array_keys(GroupMemberBadge::BADGE_TYPES)),
            'reason' => 'nullable|string|max:255'
        ]);

        $badgeInfo = GroupMemberBadge::BADGE_TYPES[$request->badge_type];
        
        // Vérifier si l'utilisateur a déjà ce badge
        $existingBadge = GroupMemberBadge::where([
            'group_id' => $group->id,
            'user_id' => $request->user_id,
            'badge_type' => $request->badge_type,
            'is_active' => true
        ])->first();

        if ($existingBadge && !$existingBadge->is_expired) {
            return response()->json(['error' => 'Badge already awarded'], 409);
        }

        $badge = GroupMemberBadge::create([
            'group_id' => $group->id,
            'user_id' => $request->user_id,
            'badge_type' => $request->badge_type,
            'badge_name' => $badgeInfo['name'],
            'badge_description' => $badgeInfo['description'] . ($request->reason ? ' - ' . $request->reason : ''),
            'badge_icon' => $badgeInfo['icon'],
            'badge_color' => $badgeInfo['color'],
            'points_earned' => $badgeInfo['points'],
            'earned_date' => now(),
            'criteria_met' => ['manual_award' => true, 'reason' => $request->reason]
        ]);

        return response()->json([
            'success' => true,
            'badge' => $badge,
            'message' => 'Badge attribué avec succès'
        ]);
    }

    /**
     * Révoquer un badge (admin uniquement)
     */
    public function revokeBadge(Group $group, GroupMemberBadge $badge)
    {
        // Vérifier que l'utilisateur est admin du groupe
        if (!$this->isGroupAdmin($group) || $badge->group_id !== $group->id) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $badge->update(['is_active' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Badge révoqué avec succès'
        ]);
    }

    /**
     * Vérifier si l'utilisateur est admin du groupe
     */
    private function isGroupAdmin(Group $group)
    {
        return $group->creator_id === Auth::id() || Auth::user()->role === 'admin';
    }

    /**
     * Obtenir la position d'un utilisateur dans le classement
     */
    private function getUserPosition($groupId, $userId)
    {
        $leaderboard = GroupMemberBadge::getGroupLeaderboard($groupId, 100);
        
        foreach ($leaderboard as $index => $entry) {
            if ($entry->user_id === $userId) {
                return $index + 1;
            }
        }

        return null;
    }
}
