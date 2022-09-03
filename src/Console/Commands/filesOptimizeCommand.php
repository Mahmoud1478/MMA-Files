<?php

namespace MMA\Files\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use MMA\Files\Enum\AttributeEnum;
use MMA\Files\Enum\FolderEnum;
use MMA\Files\Enum\PropertyEnum;
use MMA\Files\Model\File;
use MMA\Files\Service\FileSystem;
use MMA\Files\Service\Path;

class filesOptimizeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'files:sync';

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
        $media = DB::table('files')->select(['id','folder','name','model_type'])->get()->groupBy('model_type');
        $folders = [
            'main' => Path::mainFolder(),
            'media' => prop(FolderEnum::Media),
            'thumb' => prop(FolderEnum::Thumb),
        ];
        $mainPath = public_path($folders['main']);
        $mediaIds = [];
        foreach(config(prop(PropertyEnum::ConfigName).'.'.prop(PropertyEnum::Models)) as $key => $value){
            $modelIds = [];
            $model = gettype($key) == 'integer' ? $value :$key;
            $fileAttr =$model::{prop(AttributeEnum::File)}();
            $modeFolder = $mainPath.DIRECTORY_SEPARATOR.$model::{prop(FolderEnum::Model)}();
            $scannedModelFolder = FileSystem::scan($modeFolder) ;
            $modelFiles = $model::pluck('id',$fileAttr)->toArray();

            // file in model attr but not found int folder
            foreach (array_diff(array_keys($modelFiles),$scannedModelFolder) as $file ){
                FileSystem::deleteFiles($modeFolder.DIRECTORY_SEPARATOR.$folders['thumb'].DIRECTORY_SEPARATOR.$file);
                $modelIds[] = $modelFiles[$file];
            };

            foreach (array_chunk($modelIds,5000) as $ids){
                $model::whereIn('id',$ids)->update([$fileAttr => null]);
            }
            // file  in folder but not found in model

            foreach (array_diff($scannedModelFolder,array_keys($modelFiles),['.', '..' , $folders['media'], $folders['thumb']]) as $file ){
                if (! is_dir($modeFolder.DIRECTORY_SEPARATOR.$file)){
                    FileSystem::deleteFiles($modeFolder.DIRECTORY_SEPARATOR.$file);
                    FileSystem::deleteFiles($modeFolder.DIRECTORY_SEPARATOR.$folders['thumb'].DIRECTORY_SEPARATOR.$file);
                }
            }

            /*
             * media section
             */

            $mediaFolder = $modeFolder.DIRECTORY_SEPARATOR.$folders['media'];
            $DBMedia = $media[$model]->groupBy('folder')->toArray();
            $mediaScan = FileSystem::scan($mediaFolder);

            foreach(array_diff($mediaScan,array_keys($DBMedia),['.', '..']) as $folder){
                if (is_dir($mediaFolder.DIRECTORY_SEPARATOR.$folder)){
                    FileSystem::rmdir($mediaFolder.DIRECTORY_SEPARATOR.$folder);
                }
            };

            /**
             * folder  files table but not found in media Folder
             */
            foreach (array_diff(array_keys($DBMedia),$mediaScan) as $folder){
                if(in_array($folder,array_keys($DBMedia))){
                    $mediaIds [] =  $DBMedia[$folder][0]->id;
                };
            }
        }
        foreach (array_chunk($mediaIds,5000) as $ids){
            File::whereIn('id',$ids)->delete();
        }
        return 0;
    }

}
