<?php

namespace App\Storage\NotificationSetting;

use App\Storage\NotificationSetting\NotificationSettingTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class BrandPresenter
 *
 * @package namespace App\Storage\NotificationSetting;
 */
class NotificationSettingPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new NotificationSettingTransformer();
    }
}
