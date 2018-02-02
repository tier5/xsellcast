<?php

namespace App\Storage\Brand;

use App\Storage\Brand\BrandTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class BrandPresenter
 *
 * @package namespace App\Storage\Brand;
 */
class BrandPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new BrandTransformer();
    }
}
