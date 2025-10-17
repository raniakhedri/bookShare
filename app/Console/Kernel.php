<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $hour = config('app.hour');
        $min = config('app.min');
        $scheduledInterval = $hour !== '' ? ( ($min !== '' && $min != 0) ?  $min .' */'. $hour .' * * *' : '0 */'. $hour .' * * *') : '*/'. $min .' * * * *';
        if(env('IS_DEMO')) {
            $schedule->command('migrate:fresh --seed')->cron($scheduledInterval);
        }

        // Génération automatique de défis de lecture
        // Tous les lundis à 9h - nouveaux défis pour commencer la semaine
        $schedule->command('challenges:generate')
                ->weeklyOn(1, '09:00')
                ->timezone('Europe/Paris')
                ->description('Génération automatique de défis de lecture IA');

        // Génération supplémentaire en milieu de mois pour les groupes très actifs  
        $schedule->command('challenges:generate --force')
                ->monthlyOn(15, '14:00')
                ->timezone('Europe/Paris')
                ->description('Génération supplémentaire de défis IA (mi-mois)');

        // Nettoyage des défis expirés tous les dimanches
        $schedule->call(function () {
            \App\Models\ReadingChallenge::where('status', 'active')
                ->where('end_date', '<', now())
                ->update(['status' => 'completed']);
        })
        ->weeklyOn(0, '23:00')
        ->description('Nettoyage automatique des défis expirés');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
