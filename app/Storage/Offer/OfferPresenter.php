<?php

namespace App\Storage\Offer;

use App\Storage\Offer\OfferTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class OfferPresenter
 *
 * @package namespace App\Storage\Offer;
 */
class OfferPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new OfferTransformer();
    }
}
