<?php

namespace App\Storage\UserActivations;

use App\Storage\UserActivations\UserActivationsTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class UserActivationsPresenter
 *
 * @package namespace App\Storage\UserActivations;
 */
class UserActivationsPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new UserActivationsTransformer();
    }
}
