<?php namespace App\Storage\Customer;

use App\Storage\Customer\CustomerAutoCompleteTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class CustomerAutoCompletePresenter
 *
 * @package namespace App\Storage\Customer;
 */
class CustomerAutoCompletePresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new CustomerAutoCompleteTransformer();
    }
}
