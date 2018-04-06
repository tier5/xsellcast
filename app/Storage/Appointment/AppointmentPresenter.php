<?php

namespace App\Storage\Appointment;

use App\Storage\Appointment\AppointmentTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class AppointmentPresenter
 *
 * @package namespace App\Storage\Appointment;
 */
class AppointmentPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new AppointmentTransformer();
    }
}
