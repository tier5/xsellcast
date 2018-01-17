<?php
/**
 * Deprecated Command since 1.0
 */
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Storage\Customer\CustomerRepository;
use App\Storage\Ontraport\OntraportHttpd;

class OntraportOfferCustomerSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:ontraport-offercustomer';

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

    protected $customer;

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
            $fields = array(
                'count' => $customer->offers()->count(),
                'customer_id' => $customer->id);

            $res = $ont->saveOrUpdateOfferCustomer($fields);

        }

        echo "Sync done!";
    }
}
