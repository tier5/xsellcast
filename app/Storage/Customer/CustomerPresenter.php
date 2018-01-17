<?php

namespace App\Storage\Customer;

use App\Storage\Customer\CustomerTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class CustomerPresenter
 *
 * @package namespace App\Storage\Customer;
 */
class CustomerPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new CustomerTransformer();
    }
}
