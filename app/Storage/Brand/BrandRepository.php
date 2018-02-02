<?php

namespace App\Storage\Brand;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface BrandRepository
 * @package namespace App\Storage\Brand;
 */
interface BrandRepository extends RepositoryInterface
{
    public function getByDealer($dealer_id);
}
