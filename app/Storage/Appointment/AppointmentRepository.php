<?php

namespace App\Storage\Appointment;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface AppointmentRepository
 * @package namespace App\Storage\Appointment;
 */
interface AppointmentRepository extends RepositoryInterface
{
    public function getByDealer($dealer_id);
}
