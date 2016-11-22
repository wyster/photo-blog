<?php

namespace App\Console;

use App\Console\Commands\DeleteUploadedPhotosOlderThanWeek;
use App\Console\Commands\DeleteUnusedDirectoriesWithinPhotoStorage;
use App\Console\Commands\CreateRoles;
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
        DeleteUploadedPhotosOlderThanWeek::class,
        DeleteUnusedDirectoriesWithinPhotoStorage::class,
        CreateRoles::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('delete:uploaded_photos_older_than_week')
            ->dailyAt('00:00');

        $schedule->command('delete:unused_directories_within_photo_storage')
            ->dailyAt('00:10');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
