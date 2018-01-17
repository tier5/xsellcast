<?php

namespace App\Storage\OfferTag;

use League\Fractal\TransformerAbstract;
use App\Storage\OfferTag\OfferTag;

/**
 * Class OfferTagTransformer
 * @package namespace App\Storage\OfferTag;
 */
class OfferTagTransformer extends TransformerAbstract
{

    /**
     * Transform the \OfferTag entity
     * @param \OfferTag $model
     *
     * @return array
     */
    public function transform(OfferTag $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
