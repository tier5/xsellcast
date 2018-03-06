<?php

namespace App\Storage\NotificationSetting\NotificationNews;

use App\Storage\NotificationSetting\NotificationNews\NotificationNewsTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class BrandPresenter
 *
 * @package namespace App\Storage\NotificationSetting;
 */
class NotificationNewsPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new NotificationNewsTransformer();
    }
}
