<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GroupEvent;
use App\Models\EventParticipant;
use App\Models\Group;
use App\Models\User;
use Carbon\Carbon;

class GroupEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer quelques groupes et utilisateurs pour les événements de démo
        $groups = Group::take(3)->get();
        $users = User::take(10)->get();

        if ($groups->isEmpty() || $users->isEmpty()) {
            $this->command->info('Pas assez de groupes ou d\'utilisateurs pour créer des événements de démonstration.');
            return;
        }

        $eventTypes = array_keys(GroupEvent::EVENT_TYPES);

        foreach ($groups as $group) {
            // Créer 2-4 événements par groupe
            $eventCount = rand(2, 4);
            
            for ($i = 0; $i < $eventCount; $i++) {
                $creator = $users->random();
                $eventType = collect($eventTypes)->random();
                $typeDetails = GroupEvent::EVENT_TYPES[$eventType];
                
                // Dates aléatoirement dans le futur ou le passé
                $isPast = rand(0, 1);
                if ($isPast) {
                    $startDate = Carbon::now()->subDays(rand(1, 60));
                    $status = collect(['completed', 'cancelled'])->random();
                } else {
                    $startDate = Carbon::now()->addDays(rand(1, 30));
                    $status = 'published';
                }
                
                $endDate = $startDate->copy()->addHours(rand(1, 4));
                
                $event = GroupEvent::create([
                    'group_id' => $group->id,
                    'creator_id' => $creator->id,
                    'title' => $this->generateEventTitle($eventType),
                    'description' => $this->generateEventDescription($eventType),
                    'type' => $eventType,
                    'location' => rand(0, 1) ? $this->generateLocation() : null,
                    'is_virtual' => rand(0, 1),
                    'meeting_link' => rand(0, 1) ? 'https://meet.google.com/abc-defg-hij' : null,
                    'start_datetime' => $startDate,
                    'end_datetime' => $endDate,
                    'max_participants' => rand(0, 1) ? rand(5, 20) : null,
                    'requires_approval' => rand(0, 1),
                    'status' => $status,
                    'resources' => $this->generateResources($eventType),
                    'requirements' => rand(0, 1) ? $this->generateRequirements($eventType) : null
                ]);

                // Ajouter des participants à l'événement
                $participantCount = min(rand(2, 8), $users->count() - 1); // S'assurer qu'on ne dépasse pas le nombre d'utilisateurs
                $participants = $users->random($participantCount);
                
                foreach ($participants as $participant) {
                    if ($participant->id === $creator->id) continue; // Le créateur ne s'inscrit pas
                    
                    $participantStatus = 'approved';
                    if ($event->requires_approval) {
                        $participantStatus = collect(['pending', 'approved', 'rejected'])->random();
                    }
                    
                    if ($event->status === 'completed') {
                        $participantStatus = collect(['attended', 'absent'])->random();
                    }

                    EventParticipant::create([
                        'event_id' => $event->id,
                        'user_id' => $participant->id,
                        'status' => $participantStatus,
                        'registered_at' => $startDate->copy()->subDays(rand(1, 10)),
                        'approved_at' => in_array($participantStatus, ['approved', 'attended', 'absent']) ? 
                                       $startDate->copy()->subDays(rand(1, 5)) : null,
                        'registration_message' => rand(0, 1) ? 'Je suis très intéressé par cet événement !' : null,
                        'additional_info' => rand(0, 1) ? ['dietary_requirements' => 'Végétarien'] : null
                    ]);
                }
            }
        }

        $this->command->info('Événements de démonstration créés avec succès !');
    }

    private function generateEventTitle($type)
    {
        $titles = [
            'meeting' => [
                'Rencontre mensuelle du groupe',
                'Café littéraire',
                'Rencontre des passionnés',
                'Échange autour des lectures'
            ],
            'reading_club' => [
                'Club de lecture : "1984"',
                'Discussion : Roman du mois',
                'Analyse littéraire collective',
                'Lecture partagée'
            ],
            'challenge' => [
                'Défi lecture 30 jours',
                'Challenge critique littéraire',
                'Concours de nouvelles',
                'Marathon de lecture'
            ],
            'discussion' => [
                'Débat : Littérature contemporaine',
                'Discussion : Avenir du livre',
                'Échange sur les genres littéraires',
                'Table ronde littéraire'
            ],
            'workshop' => [
                'Atelier d\'écriture créative',
                'Formation critique littéraire',
                'Atelier analyse de texte',
                'Workshop storytelling'
            ],
            'social' => [
                'Soirée conviviale du groupe',
                'Apéritif littéraire',
                'Fête de fin d\'année',
                'Barbecue des lecteurs'
            ],
            'other' => [
                'Événement spécial',
                'Activité du groupe',
                'Rassemblement',
                'Sortie culturelle'
            ]
        ];

        return collect($titles[$type] ?? $titles['other'])->random();
    }

    private function generateEventDescription($type)
    {
        $descriptions = [
            'meeting' => 'Rejoignez-nous pour notre rencontre mensuelle où nous échangerons sur nos lectures récentes et partagerons nos découvertes littéraires.',
            'reading_club' => 'Venez discuter du livre sélectionné ce mois-ci. Nous analyserons les thèmes, les personnages et partagerons nos impressions.',
            'challenge' => 'Participez à notre défi de lecture ! Objectifs, récompenses et motivation collective au programme.',
            'discussion' => 'Une discussion ouverte sur un sujet littéraire passionnant. Tous les points de vue sont bienvenus !',
            'workshop' => 'Atelier pratique pour développer vos compétences. Matériel fourni, venez avec votre motivation !',
            'social' => 'Moment de détente et de convivialité entre membres du groupe. L\'occasion de se rencontrer dans un cadre décontracté.',
            'other' => 'Un événement spécial organisé pour notre communauté de lecteurs passionnés.'
        ];

        return $descriptions[$type] ?? $descriptions['other'];
    }

    private function generateLocation()
    {
        $locations = [
            'Bibliothèque municipale',
            'Café des Lettres, 15 rue de la Paix',
            'Librairie du Centre, Place de la République',
            'Maison des associations, Salle B',
            'Parc des Écrivains',
            'Centre culturel, Auditorium',
            'Université - Amphithéâtre A'
        ];

        return collect($locations)->random();
    }

    private function generateResources($type)
    {
        $resources = [
            'meeting' => ['Café', 'Gâteaux', 'Bloc-notes'],
            'reading_club' => ['Livre du mois', 'Guide de discussion', 'Marque-pages'],
            'challenge' => ['Carnet de suivi', 'Autocollants de progression', 'Liste de livres'],
            'discussion' => ['Support de présentation', 'Articles de référence'],
            'workshop' => ['Cahier d\'exercices', 'Stylos', 'Support de cours'],
            'social' => ['Boissons', 'Amuse-bouches', 'Musique d\'ambiance'],
            'other' => ['À définir selon l\'événement']
        ];

        return $resources[$type] ?? $resources['other'];
    }

    private function generateRequirements($type)
    {
        $requirements = [
            'meeting' => 'Aucun prérequis, ouvert à tous les membres.',
            'reading_club' => 'Avoir lu le livre sélectionné pour participer pleinement aux discussions.',
            'challenge' => 'Motivation et engagement à participer régulièrement au défi.',
            'discussion' => 'Intérêt pour le sujet proposé et envie d\'échanger.',
            'workshop' => 'Aucune expérience préalable requise, débutants bienvenus.',
            'social' => 'Bonne humeur et envie de passer un moment convivial !',
            'other' => 'Détails communiqués lors de l\'inscription.'
        ];

        return $requirements[$type] ?? $requirements['other'];
    }
}