<?php

namespace App\Storage\OfferTag;

use App\Storage\OfferTag\OfferTagTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class OfferTagPresenter
 *
 * @package namespace App\Storage\OfferTag;
 */
class OfferTagPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new OfferTagTransformer();
    }
}
