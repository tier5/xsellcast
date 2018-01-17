<?php

namespace App\Storage\Permission;

use App\Storage\Permission\PermissionTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class PermissionPresenter
 *
 * @package namespace App\Storage\Permission;
 */
class PermissionPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new PermissionTransformer();
    }
}
