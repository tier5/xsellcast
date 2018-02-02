<?php

namespace App\Storage\Media;

use League\Fractal\TransformerAbstract;
use App\Storage\Media\Media;

/**
 * Class MediaTransformer
 * @package namespace App\Storage\Media;
 */
class MediaTransformer extends TransformerAbstract
{

    /**
     * Transform the \Media entity
     * @param \Media $model
     *
     * @return array
     */
    public function transform(Media $model)
    {
        return [
            'id'                    => (int) $model->id,
            'url'                   => url($model->path . '/' . $model->slug),
            'thumbnail'             => $model->getSize(150, 100),
            'slug'                  => $model->slug,
            'title'                 => $model->title,
            'created_at'            => $model->created_at,
            'updated_at'            => $model->updated_at,
            'created_at_standard'   => $model->created_at->format('m/d/Y'),
            'type'                  => $model->type,
            'extension'             => $model->extension
        ];
    }
}
