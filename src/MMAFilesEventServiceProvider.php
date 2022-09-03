<?php

namespace MMA\Files;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use MMA\Files\Events\ModelHasFileDeleted;
use MMA\Files\Events\ModelHasFilesCreating;
use MMA\Files\Events\ModelHasFilesSaved;
use MMA\Files\Events\ModelHasFilesUpdating;
use MMA\Files\Listeners\ModelHasFileDeletedListener;
use MMA\Files\Listeners\ModelHasFilesCreatingListener;
use MMA\Files\Listeners\ModelHasFilesSavedListener;
use MMA\Files\Listeners\ModelHasFilesUpdatingListener;

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
