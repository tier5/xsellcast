<?php

namespace App\Storage\Media;

use App\Storage\Media\MediaTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class MediaPresenter
 *
 * @package namespace App\Storage\Media;
 */
class MediaPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new MediaTransformer();
    }
}
