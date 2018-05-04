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
                'id'                => (int) $model->id,
                'wpid'              => $model->wpid,
                'title'             => $model->title,
                'contents'          => $model->contents,
                'wp_offer_link'     => $model->wp_offer_link,
                'media_link'        => $model->media_link,
                'status_human'      => $model->humanStatus(),
                'status'            => $model->status,
                'updated_at_human'  => $model->updated_at->format('l \a\t h:i:s A'),
                'created_at'        => $model->created_at,
                'updated_at'        => $model->updated_at,

                /* place your other model properties here */

                'brand'             => $brand,
                'thumbnail'         => ($media ? $media->getSize('150x100') : null ),
                'author_type_human' => config('lbt.offer.author_type.' . $model->author_type . '.label'),
                'badge'             => config('lbt.offer.author_type.' . $model->author_type . '.badge'),
        ];
    }
}
