<?php

namespace App\Storage\Offer;

use League\Fractal\TransformerAbstract;
use App\Storage\Offer\Offer;

/**
 * Class OfferTransformer
 * @package namespace App\Storage\Offer;
 */
class OfferTransformer extends TransformerAbstract
{
    /**
     * Transform the \Offer entity
     * @param \Offer $model
     *
     * @return array
     */
    public function transform(Offer $model)
    {
        $brand = $model->brands()->first();
        $media = $model->getThumbnail();

        return [
            'id'               => (int) $model->id,
            /* place your other model properties here */
            'contents'          => $model->contents,
            'title'             => $model->title,
            'created_at'        => $model->created_at,
            'updated_at'        => $model->updated_at,
            'brand'             => $brand,
            'thumbnail'         => ($media ? $media->getSize('150x100') : null ),
            'updated_at_human'  => $model->updated_at->format('l \a\t h:i:s A'),
            'status_human'      => $model->humanStatus(),
            'status'            => $model->status,
            'author_type_human' => config('lbt.offer.author_type.' . $model->author_type . '.label'),
            'badge'             => config('lbt.offer.author_type.' . $model->author_type . '.badge'),
            'wpid'              => $model->wpid,
        ];
    }
}
