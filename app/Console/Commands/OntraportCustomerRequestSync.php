<?php
/**
 * Deprecated Command since 1.0
 */
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Storage\Customer\CustomerRepository;
use App\Storage\UserAction\UserActionRepository;

class OntraportCustomerRequestSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:ontraport-request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync prospects request to Ontraport';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    protected $customer, $useraction;

    public function __construct(CustomerRepository $customer, UserActionRepository $useraction)
    {
        parent::__construct();
        $this->customer = $customer;
        $this->useraction = $useraction;
    }

    /**
     * Execute the console command.     *
     * @return mixed
     */
    public function handle()
    {
        $this->useraction->customersSyncToOntraport();

        echo "Sync done!" . PHP_EOL;
    }
}
