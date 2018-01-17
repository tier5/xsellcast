<?php namespace App\Storage\Messenger;

use App\Storage\Messenger\ThreadMessagesTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class ThreadMessagesPresenter
 * 
 * Thread with list of messages
 *
 * @package namespace App\Storage\Messenger;
 */
class ThreadMessagesPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new ThreadMessagesTransformer();
    }
}
