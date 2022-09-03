<?php

namespace MMA\Files\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use MMA\Files\Enum\AttributeEnum;
use MMA\Files\Enum\PropertyEnum;
use MMA\Files\Events\ModelHasFilesSaved;
use MMA\Files\Service\Property;
use MMA\Files\Service\Upload;

class ModelHasFilesSavedListener
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
     * @param ModelHasFilesSaved $event
     * @return void
     */
    public function handle(ModelHasFilesSaved $event): void
    {
        $media =  request($event->model->{prop(AttributeEnum::RequestField)}());
        if ($media && Property::get($event->model::class,prop(PropertyEnum::MediaActivate),false)) {
           Upload::media($event->model,$media);
        }
    }
}
