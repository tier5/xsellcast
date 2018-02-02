<?php

namespace App\Storage\Dealer;

use App\Storage\Dealer\DealerTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class DealerPresenter
 *
 * @package namespace App\Storage\Dealer;
 */
class DealerPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new DealerTransformer();
    }
}
