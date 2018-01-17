<?php

namespace App\Storage\UserAction;

use App\Storage\UserAction\UserActionTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class UserActionPresenter
 *
 * @package namespace App\Storage\UserAction;
 */
class UserActionPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new UserActionTransformer();
    }
}
