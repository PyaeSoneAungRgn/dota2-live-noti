<?php

namespace App\Console\Commands;

use App\Jobs\SyncFixture;
use Illuminate\Console\Command;

class RunCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:code';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run test code';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        SyncFixture::dispatchSync();
    }
}
