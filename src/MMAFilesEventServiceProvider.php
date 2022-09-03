<?php

namespace MMAE\Files;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use MMAE\Files\Events\ModelHasFileDeleted;
use MMAE\Files\Events\ModelHasFilesCreating;
use MMAE\Files\Events\ModelHasFilesSaved;
use MMAE\Files\Events\ModelHasFilesUpdating;
use MMAE\Files\Listeners\ModelHasFileDeletedListener;
use MMAE\Files\Listeners\ModelHasFilesCreatingListener;
use MMAE\Files\Listeners\ModelHasFilesSavedListener;
use MMAE\Files\Listeners\ModelHasFilesUpdatingListener;

class MMAFilesEventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        ModelHasFileDeleted::class => [
            ModelHasFileDeletedListener::class,
        ],
        ModelHasFilesCreating::class =>[
            ModelHasFilesCreatingListener::class,
        ],
        ModelHasFilesSaved::class => [
            ModelHasFilesSavedListener::class,
        ],
        ModelHasFilesUpdating::class =>[
            ModelHasFilesUpdatingListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
