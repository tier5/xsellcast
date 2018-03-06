<?php

namespace App\Storage\NotificationSetting\NotificationBrandAssociates;

use App\Storage\NotificationSetting\NotificationBrandAssociates\NotificationBrandAssociatesTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class BrandPresenter
 *
 * @package namespace App\Storage\NotificationSetting;
 */
class NotificationBrandAssociatesPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new NotificationBrandAssociatesTransformer();
    }
}
