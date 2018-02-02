<?php

namespace App\Storage\Messenger;

use App\Storage\Messenger\ThreadTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class ThreadPresenter
 *
 * @package namespace App\Storage\Messenger;
 */
class ThreadPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new ThreadTransformer();
    }
}
