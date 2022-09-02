<?php

namespace Mahmoud147\Files;

use Illuminate\Support\ServiceProvider;
use Mahmoud\Files\Console\Commands\FilesDeleteCommand;
use Mahmoud147\Files\Console\Commands\filesInitCommand;
use Mahmoud147\Files\Console\Commands\filesOptimizeCommand;

class MahmoudFilesServerProvider extends ServiceProvider
{


    public function boot(){

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

    }

    public function register()
    {
        $this->publishes([
            __DIR__."/../config/files.php" => config_path('files.php')
        ],'files-config');
        $this->commands([
            filesInitCommand::class,
            filesOptimizeCommand::class,
            FilesDeleteCommand::class,
        ]);
    }

}
