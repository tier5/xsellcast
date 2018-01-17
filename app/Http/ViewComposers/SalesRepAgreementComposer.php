<?php namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Auth;

class SalesRepAgreementComposer
{
    public function __construct()
    {

    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $usr = Auth::user();
        $salesRep = $usr->salesRep()->first();
        $bodyClass = '';
        $salesrep_not_agree = false;

        if($salesRep)
        {
            $agreed = $usr->getMeta('salesrep_agreement');

            //Make is agreement meta is not null but false/true
            if(is_bool($agreed) && !$agreed)
            {
                $bodyClass = 'salesrep-not-agreed ';
                $salesrep_not_agree = true;
            }
        }

        $view->with('salesrep_class', $bodyClass);
        $view->with('salesrep_not_agree', $salesrep_not_agree);
    }
}