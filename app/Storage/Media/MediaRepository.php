<?php

namespace App\Storage\Media;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface MediaRepository
 * @package namespace App\Storage\Media;
 */
interface MediaRepository extends RepositoryInterface
{
    public function uploadImg($tmp_file = null);
}
