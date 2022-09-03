<?php

namespace MMAE\Files\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use MMAE\Files\Enum\AttributeEnum;
use MMAE\Files\Enum\PropertyEnum;
use MMAE\Files\Events\ModelHasFilesSaved;
use MMAE\Files\Service\Property;
use MMAE\Files\Service\Upload;

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
        if(
            request()->has($event->model->{prop(AttributeEnum::RequestField)}())
            &&
            Property::get($event->model::class,prop(PropertyEnum::MediaActivate),false)
        ){
            $media =  request($event->model->{prop(AttributeEnum::RequestField)}());
            Upload::media($event->model,$media);
        }
        
    }
}
