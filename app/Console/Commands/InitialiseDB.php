<?php

namespace App\Console\Commands;

use App\Domain\Persistence\Seeders\DatabaseSeeder;
use Illuminate\Console\Command;

class InitialiseDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:initialise-db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialise the database and adds the startup data';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->call('migrate', ['--path' => 'App/Domain/Persistence/Migrations']);
        $this->call('db:seed', ['--class' => DatabaseSeeder::class]);
    }
}
