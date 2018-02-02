<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Storage\SalesRep\SalesRepRepository;
use App\Storage\Ontraport\OntraportHttpd;

class OntraportSalesRepSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:ontraport-salesrep';

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

    protected $salesrep;

    public function __construct(SalesRepRepository $salesrep)
    {
        parent::__construct();
        $this->salesrep = $salesrep;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $ont = new OntraportHttpd();

        $salesreps = $this->salesrep->skipPresenter()->all();

        foreach($salesreps as $salesrep)
        {
            $this->salesrep->updateOne($salesrep, []);
        }

        echo "Sync done!" . PHP_EOL;
    }
}
