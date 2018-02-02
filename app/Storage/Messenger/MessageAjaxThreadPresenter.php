<?php

namespace App\Storage\Messenger;

use App\Storage\Messenger\MessageAjaxThreadTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class MessageAjaxThreadPresenter
 *
 * @package namespace App\Storage\Messenger;
 */
class MessageAjaxThreadPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new MessageAjaxThreadTransformer();
    }
}
