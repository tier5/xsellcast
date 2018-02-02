<?php

namespace App\Storage\DealersCategory;

use App\Storage\DealersCategory\DealersCategoryTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class DealersCategoryPresenter
 *
 * @package namespace App\Storage\DealersCategory;
 */
class DealersCategoryPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new DealersCategoryTransformer();
    }
}
