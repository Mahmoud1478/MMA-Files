<?php

namespace Mahmoud\Files\Console\Commands;

use Illuminate\Console\Command;
use Mahmoud\Files\Enum\FolderEnum;
use Mahmoud\Files\Enum\PropertyEnum;
use Mahmoud\Files\Service\FileSystem;
use Mahmoud\Files\Service\Path;
use Mahmoud\Files\Service\Property;
use Mahmoud\Files\Service\Shaper;

class filesInitCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'files:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $main = Path::mainFolder();
        $mainPath = public_path($main);

        if (!file_exists($mainPath)) {
            FileSystem::mkdir($mainPath);
        }
        $this->info(Shaper::folderCreatedMsg($main));
        foreach (config(prop(PropertyEnum::ConfigName).'.'.prop(PropertyEnum::Models)) as $key => $value){
            $model = gettype($key) == 'integer' ? $value :$key;
            $item = $model::{prop(FolderEnum::Model)}();
            $baseFolderPath = $main.DIRECTORY_SEPARATOR.$item;
            $folder = $mainPath.DIRECTORY_SEPARATOR.$item;
            if (!file_exists($folder)) {
                FileSystem::mkdir($folder);
            }
            $this->info(Shaper::folderCreatedMsg($baseFolderPath));
            if (Property::get($model,prop(PropertyEnum::FileThumbActivate),false) && Property::get($model,prop(PropertyEnum::FileIsImage),false)){
                $thumb = $folder.DIRECTORY_SEPARATOR.prop(FolderEnum::Thumb);
                if (!file_exists($thumb)){
                    FileSystem::mkdir($thumb);
                }
                $this->info(Shaper::folderCreatedMsg($baseFolderPath.DIRECTORY_SEPARATOR.prop(FolderEnum::Thumb)));
            }
            if (Property::get($model,prop(PropertyEnum::MediaActivate),false)){
                $media = $folder.DIRECTORY_SEPARATOR.prop(FolderEnum::Media);
                if (!file_exists($media)){
                    FileSystem::mkdir($media);
                }
                $this->info(Shaper::folderCreatedMsg($baseFolderPath.DIRECTORY_SEPARATOR.prop(FolderEnum::Media)));
            }
        }
        return 0;
    }
}
