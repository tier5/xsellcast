<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Storage\Ontraport\OntraportHttpd;
use App\Storage\Customer\CustomerRepository;

class OntraportCustomerSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:ontraport-customer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(CustomerRepository $customer)
    {
        parent::__construct();

        $this->customer = $customer;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $ont = new OntraportHttpd();

        $customers = $this->customer->skipPresenter()->all();

        foreach($customers as $customer)
        {
            $this->customer->updateOne($customer, []);
        }

        echo "Sync done!" . PHP_EOL;
    }
}
