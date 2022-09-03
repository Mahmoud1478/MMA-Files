<?php

namespace MMA\Files\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use MMA\Files\Enum\AttributeEnum;
use MMA\Files\Enum\FolderEnum;
use MMA\Files\Enum\PropertyEnum;
use MMA\Files\Events\ModelHasFilesUpdating;
use MMA\Files\Service\FileSystem;
use MMA\Files\Service\Path;
use MMA\Files\Service\Property;
use MMA\Files\Service\Upload;

class ModelHasFilesUpdatingListener
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
     * @param ModelHasFilesUpdating $event
     * @return void
     */
    public function handle(ModelHasFilesUpdating $event): void
    {
        $attr = $event->model->{prop(AttributeEnum::File)}();
        if ($event->model->attributes[$attr] !== $event->model->original[$attr]) {
            Filesystem::deleteFiles(Path::getFileDiskPath($event->model::class,$event->model->original[$attr]));
            if(Property::get($event->model::class,prop(PropertyEnum::FileThumbActivate),false))
            {Filesystem::deleteFiles(Path::getFileDiskPath($event->model::class,prop(FolderEnum::Thumb).DIRECTORY_SEPARATOR.$event->model->original[$attr]));}
            $event->model->{$attr} = Upload::file($event->model::class,$event->model->{$attr});
        }
    }
}
