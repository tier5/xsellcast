<?php namespace App\Storage\User;

use App\Storage\User\UserAutoCompleteTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class UserAutoCompletePresenter
 *
 * @package namespace App\Storage\User;
 */
class UserAutoCompletePresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new UserAutoCompleteTransformer();
    }
}
