<?php

namespace MMA\Files;

use Illuminate\Support\ServiceProvider;
use MMA\Files\Console\Commands\FilesDeleteCommand;
use MMA\Files\Console\Commands\filesInitCommand;
use MMA\Files\Console\Commands\filesOptimizeCommand;
use MMA\Files\MMAFilesEventServiceProvider;

class MMAFilesServerProvider extends ServiceProvider
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
        $this->app->register(MMAFilesEventServiceProvider::class);
    }

}
