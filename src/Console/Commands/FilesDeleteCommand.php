<?php

namespace MMAE\Files\Console\Commands;

use Illuminate\Console\Command;
use MMAE\Files\Service\FileSystem;
use MMAE\Files\Service\Path;
use MMAE\Files\Service\Shaper;

class FilesDeleteCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'files:fresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'delete the entire files folder and create new empty folders';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $main = Path::mainFolder();
        $mainPath = public_path($main);
        FileSystem::rmdir($mainPath);
        $this->warn(Shaper::folderDeletedMsg($main));
        $this->call('files:init');
        return 0;
    }
}
