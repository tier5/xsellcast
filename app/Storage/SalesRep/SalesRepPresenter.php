<?php

namespace App\Storage\SalesRep;

use App\Storage\SalesRep\SalesRepTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class SalesRepPresenter
 *
 * @package namespace App\Storage\SalesRep;
 */
class SalesRepPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new SalesRepTransformer();
    }
}
