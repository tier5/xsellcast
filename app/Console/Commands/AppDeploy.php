<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Storage\Ontraport\OntraportHttpd;
use App\Storage\Customer\CustomerRepository;

class AppDeploy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:deploy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Application Deployment';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(CustomerRepository $customer)
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

        $output = shell_exec('php artisan migrate');
        echo "$output";

        $output = shell_exec('php artisan api:generate --routePrefix="api/*"');
        echo "$output";

        $output = shell_exec('php artisan laroute:generate');
        echo "$output";

        $output = shell_exec('gulp');
        echo "$output";

        echo 'Deployment completed!' . PHP_EOL;
    }
}
