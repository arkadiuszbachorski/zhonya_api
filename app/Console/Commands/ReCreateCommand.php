<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ReCreateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 're:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Re-creates whole app: fresh migration, seed and passport';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->call('migrate:fresh');
        $this->call('db:seed');
        $this->call('passport:install');
    }
}
