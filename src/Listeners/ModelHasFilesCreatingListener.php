<?php

namespace MMA\Files\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use MMA\Files\Enum\AttributeEnum;
use MMA\Files\Events\ModelHasFilesCreating;
use MMA\Files\Service\Upload;

class ModelHasFilesCreatingListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param ModelHasFilesCreating $event
     * @return void
     */
    public function handle(ModelHasFilesCreating $event) :void
    {
        $attr = $event->model->{prop(AttributeEnum::File)}();
        if ($event->model->{$attr}) {
            $event->model->{$attr} = Upload::file($event->model::class,$event->model->{$attr});
        }
    }
}
