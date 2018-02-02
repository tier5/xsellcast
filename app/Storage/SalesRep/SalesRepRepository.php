<?php

namespace App\Storage\SalesRep;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface SalesRepRepository
 * @package namespace App\Storage\SalesRep;
 */
interface SalesRepRepository extends RepositoryInterface
{
    public function getByDealer($dealer_id);

    public function getByCustomer($customer_id);
}
