<?php

namespace App\Storage\Customer;

use App\Storage\Customer\CustomerCompleteTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class CustomerCompletePresenter
 *
 * @package namespace App\Storage\Customer;
 */
class CustomerCompletePresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new CustomerCompleteTransformer();
    }
}
