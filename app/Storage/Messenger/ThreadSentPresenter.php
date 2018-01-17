<?php

namespace App\Storage\Messenger;

use App\Storage\Messenger\ThreadTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class ThreadSentPresenter
 *
 * @package namespace App\Storage\Messenger;
 */
class ThreadSentPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new ThreadTransformer(true);
    }
}
