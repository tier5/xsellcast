<?php namespace App\Storage\Messenger;

use App\Storage\Messenger\MessageAjaxThreadSentTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class MessageAjaxThreadSentPresenter
 *
 * @package namespace App\Storage\Messenger;
 */
class MessageAjaxThreadSentPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new MessageAjaxThreadSentTransformer();
    }
}
