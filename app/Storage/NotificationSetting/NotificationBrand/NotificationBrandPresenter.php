<?php

namespace App\Storage\NotificationSetting\NotificationBrand;

use App\Storage\NotificationSetting\NotificationBrand\NotificationBrandTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class BrandPresenter
 *
 * @package namespace App\Storage\NotificationSetting;
 */
class NotificationBrandPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new NotificationBrandTransformer();
    }
}
